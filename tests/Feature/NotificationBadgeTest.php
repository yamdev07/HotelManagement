<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Issue #196 : le badge rouge de notifications ne disparaissait pas après
 * consultation de l'onglet. Ouvrir la page doit tout marquer comme lu.
 */
class NotificationBadgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_visiting_notifications_marks_all_as_read(): void
    {
        $hotel = Hotel::create([
            'name'                    => 'Hotel Notif',
            'slug'                    => Str::slug('Hotel Notif '.Str::random(4)),
            'is_active'               => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at'    => now()->addMonth(),
        ]);
        $user = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        // Deux notifications non lues
        foreach (range(1, 2) as $i) {
            $user->notifications()->create([
                'id'   => (string) Str::uuid(),
                'type' => 'App\\Notifications\\Dummy',
                'data' => ['title' => 'Test '.$i, 'url' => '/'],
            ]);
        }

        $this->assertEquals(2, $user->unreadNotifications()->count());

        $this->actingAs($user)->get(route('notification.index'))->assertOk();

        $this->assertEquals(0, $user->fresh()->unreadNotifications()->count());
    }

    public function test_new_notifications_are_highlighted_and_page_has_search_and_filters(): void
    {
        // Issue #198 : distinguer les nouvelles des anciennes + recherche/filtres.
        $hotel = Hotel::create([
            'name'                    => 'Hotel Notif2',
            'slug'                    => Str::slug('Hotel Notif2 '.Str::random(4)),
            'is_active'               => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at'    => now()->addMonth(),
        ]);
        $user = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        $user->notifications()->create([
            'id'   => (string) Str::uuid(),
            'type' => 'App\\Notifications\\Dummy',
            'data' => ['message' => 'Réservation confirmée', 'url' => '/'],
        ]);

        $res = $this->actingAs($user)->get(route('notification.index'));
        $res->assertOk();
        // La notification (non lue à l'ouverture) est mise en avant comme "Nouveau"
        $res->assertSee('Réservation confirmée');
        $res->assertSee('Nouveau');
        $res->assertSee('data-state="new"', false);
        // Barre de recherche + filtres présents
        $res->assertSee('id="notifSearch"', false);
        $res->assertSee('data-filter="new"', false);

        // Le badge se vide quand même (issue #196)
        $this->assertEquals(0, $user->fresh()->unreadNotifications()->count());
    }
}
