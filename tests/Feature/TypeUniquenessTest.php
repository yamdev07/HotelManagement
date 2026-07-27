<?php

namespace Tests\Feature;

use App\Http\Requests\StoreTypeRequest;
use App\Models\Hotel;
use App\Models\Type;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tests\TestCase;

class TypeUniquenessTest extends TestCase
{
    use RefreshDatabase;

    private function hotel(): Hotel
    {
        return Hotel::create([
            'name' => 'Hotel '.Str::random(5),
            'slug' => Str::slug('Hotel '.Str::random(6)),
            'is_active' => true,
        ]);
    }

    private function validateNameFor(int $hotelId, string $name): \Illuminate\Contracts\Validation\Validator
    {
        app(TenantManager::class)->setHotelId($hotelId);

        return Validator::make(['name' => $name, 'capacity' => 2], (new StoreTypeRequest)->rules());
    }

    public function test_two_hotels_can_have_the_same_type_name(): void
    {
        $a = $this->hotel();
        $b = $this->hotel();

        // L'hôtel A possède déjà un type "Standard"
        app(TenantManager::class)->setHotelId($a->id);
        Type::create(['name' => 'Standard', 'capacity' => 2, 'information' => 'x']);

        // L'hôtel B peut créer SON "Standard" sans conflit
        $this->assertFalse($this->validateNameFor($b->id, 'Standard')->fails());
    }

    public function test_same_hotel_cannot_duplicate_type_name(): void
    {
        $a = $this->hotel();

        app(TenantManager::class)->setHotelId($a->id);
        Type::create(['name' => 'Deluxe', 'capacity' => 2, 'information' => 'x']);

        // Doublon dans le MÊME hôtel : refusé
        $validator = $this->validateNameFor($a->id, 'Deluxe');
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }
}
