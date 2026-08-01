<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Type;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * L'upload d'image de chambre valide le type ET la taille côté serveur
 * (durcissement : vraie image, JPG/PNG/WEBP, 4 Mo max).
 */
class RoomImageUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @return array{0:User,1:Room} */
    private function adminAndRoom(): array
    {
        $hotel = Hotel::create([
            'name' => 'Img Hotel', 'slug' => Str::slug('img '.Str::random(4)),
            'is_active' => true, 'onboarding_completed_at' => now(), 'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);
        $room = Room::create([
            'type_id' => $type->id, 'room_status_id' => RoomStatus::where('code', 'AVL')->value('id'),
            'number' => '901', 'capacity' => 2, 'price' => 30000, 'view' => '',
        ]);
        app(TenantManager::class)->forget();

        return [$admin, $room];
    }

    public function test_non_image_file_is_rejected(): void
    {
        [$admin, $room] = $this->adminAndRoom();

        $this->actingAs($admin)
            ->post('/room/'.$room->id.'/image/upload', ['image' => UploadedFile::fake()->create('note.txt', 20, 'text/plain')])
            ->assertSessionHasErrors('image');
    }

    public function test_oversized_image_is_rejected(): void
    {
        [$admin, $room] = $this->adminAndRoom();

        // 5 Mo > limite de 4 Mo
        $this->actingAs($admin)
            ->post('/room/'.$room->id.'/image/upload', ['image' => UploadedFile::fake()->image('big.jpg')->size(5120)])
            ->assertSessionHasErrors('image');
    }
}
