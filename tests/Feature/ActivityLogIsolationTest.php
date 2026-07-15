<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Hotel;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Issue #144 : le journal d'activité d'un hôtelier affichait les activités
 * des autres hôtels. Chaque activité est désormais rattachée à son hôtel et
 * le journal est filtré sur l'hôtel courant.
 */
class ActivityLogIsolationTest extends TestCase
{
    use RefreshDatabase;

    private function hotel(string $name): Hotel
    {
        return Hotel::create([
            'name'      => $name,
            'slug'      => Str::slug($name.' '.Str::random(4)),
            'is_active' => true,
        ]);
    }

    public function test_activity_journal_is_scoped_to_current_hotel(): void
    {
        $hotelA = $this->hotel('Hotel A');
        $hotelB = $this->hotel('Hotel B');
        $userA  = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotelA->id]);
        $userB  = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotelB->id]);

        $tm = app(TenantManager::class);

        $tm->setHotelId($hotelA->id);
        activity()->causedBy($userA)->log('Action Hotel A');

        $tm->setHotelId($hotelB->id);
        activity()->causedBy($userB)->log('Action Hotel B');

        // Vue côté hôtel A : ne voit que ses propres activités
        $tm->setHotelId($hotelA->id);
        $descA = Activity::pluck('description');
        $this->assertContains('Action Hotel A', $descA);
        $this->assertNotContains('Action Hotel B', $descA);

        // Vue côté hôtel B : symétrique
        $tm->setHotelId($hotelB->id);
        $descB = Activity::pluck('description');
        $this->assertContains('Action Hotel B', $descB);
        $this->assertNotContains('Action Hotel A', $descB);

        // Super-Admin plateforme (sans hôtel) : voit tout
        $tm->setHotelId(null);
        $tm->forget();
        $all = Activity::pluck('description');
        $this->assertContains('Action Hotel A', $all);
        $this->assertContains('Action Hotel B', $all);
    }
}
