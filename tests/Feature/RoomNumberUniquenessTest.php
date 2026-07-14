<?php

namespace Tests\Feature;

use App\Http\Requests\StoreRoomRequest;
use App\Models\Hotel;
use App\Models\Type;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tests\TestCase;

class RoomNumberUniquenessTest extends TestCase
{
    use RefreshDatabase;

    private function hotel(): Hotel
    {
        return Hotel::create([
            'name'      => 'Hotel '.Str::random(5),
            'slug'      => Str::slug('Hotel '.Str::random(6)),
            'is_active' => true,
        ]);
    }

    private function roomFor(int $hotelId, string $number): void
    {
        app(TenantManager::class)->setHotelId($hotelId);
        $type = Type::create(['name' => 'Std '.Str::random(4), 'capacity' => 2, 'information' => 'x']);
        $statusId = DB::table('room_statuses')->value('id');

        DB::table('rooms')->insert([
            'type_id'        => $type->id,
            'room_status_id' => $statusId,
            'number'         => $number,
            'capacity'       => 2,
            'price'          => 100,
            'view'           => '',
            'hotel_id'       => $hotelId,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    private function validateNumberFor(int $hotelId, string $number): \Illuminate\Contracts\Validation\Validator
    {
        app(TenantManager::class)->setHotelId($hotelId);

        return Validator::make(
            ['number' => $number],
            ['number' => (new StoreRoomRequest)->rules()['number']]
        );
    }

    public function test_two_hotels_can_have_the_same_room_number(): void
    {
        $a = $this->hotel();
        $b = $this->hotel();

        $this->roomFor($a->id, '101');

        // L'hôtel B peut créer SA chambre 101
        $this->assertFalse($this->validateNumberFor($b->id, '101')->fails());
    }

    public function test_same_hotel_cannot_duplicate_room_number(): void
    {
        $a = $this->hotel();
        $this->roomFor($a->id, '101');

        // Doublon dans le MÊME hôtel : refusé
        $this->assertTrue($this->validateNumberFor($a->id, '101')->fails());
    }
}
