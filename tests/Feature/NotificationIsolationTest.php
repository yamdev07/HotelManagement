<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Issue #149 : un hôtelier voyait les notifications d'un autre hôtel.
 * Les notifications de réservation ne doivent viser QUE le personnel de l'hôtel concerné.
 */
class NotificationIsolationTest extends TestCase
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

    public function test_reservation_notifications_target_only_the_reservation_hotel_staff(): void
    {
        $hotelA = $this->hotel('Hotel A');
        $hotelB = $this->hotel('Hotel B');

        $staffA = User::factory()->create(['role' => 'Receptionist', 'hotel_id' => $hotelA->id]);
        $adminA = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotelA->id]);
        $staffB = User::factory()->create(['role' => 'Receptionist', 'hotel_id' => $hotelB->id]);
        $super  = User::factory()->create(['role' => 'Super', 'hotel_id' => null]);

        // Destinataires calculés comme dans les contrôleurs (staff de l'hôtel A)
        $recipients = User::staff()->where('hotel_id', $hotelA->id)->get();

        $this->assertTrue($recipients->contains('id', $staffA->id));
        $this->assertTrue($recipients->contains('id', $adminA->id));
        // Pas le staff d'un autre hôtel, ni le super-admin plateforme
        $this->assertFalse($recipients->contains('id', $staffB->id));
        $this->assertFalse($recipients->contains('id', $super->id));
    }
}
