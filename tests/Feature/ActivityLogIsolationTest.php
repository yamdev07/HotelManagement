<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Hotel;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Issue #144 : le journal d'activite d'un hotelier affichait les activites
 * des autres hotels. Chaque activite est desormais rattachee a son hotel et
 * le journal est filtre sur l'hotel courant.
 */
class ActivityLogIsolationTest extends TestCase
{
    use RefreshDatabase;

    private function hotel(string $name): Hotel
    {
        return Hotel::create([
            'name' => $name,
            'slug' => Str::slug($name . ' ' . Str::random(4)),
            'is_active' => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at' => now()->addMonth(),
        ]);
    }

    private function logFor(int $hotelId, string $description): int
    {
        return DB::table('activity_log')->insertGetId([
            'log_name' => 'default',
            'description' => $description,
            'hotel_id' => $hotelId,
            'properties' => '{}',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_journal_page_does_not_leak_other_hotels(): void
    {
        $hotelA = $this->hotel('Hotel Page A');
        $hotelB = $this->hotel('Hotel Page B');
        $adminA = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotelA->id]);

        $this->logFor($hotelA->id, 'OPERATION_HOTEL_A');
        $bId = $this->logFor($hotelB->id, 'OPERATION_HOTEL_B');

        app(TenantManager::class)->forget();
        $res = $this->actingAs($adminA)->get(route('activity.index'));
        $res->assertOk();
        $res->assertSee('OPERATION_HOTEL_A');
        $res->assertDontSee('OPERATION_HOTEL_B');

        app(TenantManager::class)->forget();
        $this->actingAs($adminA)->get(route('activity.show', $bId))->assertNotFound();
    }

    public function test_activity_journal_is_scoped_to_current_hotel(): void
    {
        $hotelA = $this->hotel('Hotel A');
        $hotelB = $this->hotel('Hotel B');
        $userA = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotelA->id]);
        $userB = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotelB->id]);

        $tm = app(TenantManager::class);

        $tm->setHotelId($hotelA->id);
        activity()->causedBy($userA)->log('Action Hotel A');

        $tm->setHotelId($hotelB->id);
        activity()->causedBy($userB)->log('Action Hotel B');

        $tm->setHotelId($hotelA->id);
        $descA = Activity::pluck('description');
        $this->assertContains('Action Hotel A', $descA);
        $this->assertNotContains('Action Hotel B', $descA);

        $tm->setHotelId($hotelB->id);
        $descB = Activity::pluck('description');
        $this->assertContains('Action Hotel B', $descB);
        $this->assertNotContains('Action Hotel A', $descB);

        $tm->setHotelId(null);
        $tm->forget();
        $all = Activity::pluck('description');
        $this->assertContains('Action Hotel A', $all);
        $this->assertContains('Action Hotel B', $all);
    }
}
