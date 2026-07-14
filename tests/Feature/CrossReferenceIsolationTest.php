<?php

namespace Tests\Feature;

use App\Http\Requests\StoreRoomRequest;
use App\Models\Hotel;
use App\Models\Type;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tests\TestCase;

class CrossReferenceIsolationTest extends TestCase
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

    public function test_hotel_cannot_reference_another_hotels_type(): void
    {
        $a = $this->hotel();
        $b = $this->hotel();

        // Type appartenant à l'hôtel A
        app(TenantManager::class)->setHotelId($a->id);
        $typeA = Type::create(['name' => 'Suite', 'capacity' => 2, 'information' => 'x']);

        // L'hôtel B tente de créer une chambre avec le type de A -> refusé
        app(TenantManager::class)->setHotelId($b->id);
        $rules = (new StoreRoomRequest)->rules();
        $validator = Validator::make(['type_id' => $typeA->id], ['type_id' => $rules['type_id']]);
        $this->assertTrue($validator->fails(), "Le type d'un autre hôtel ne doit pas être accepté");

        // Avec son propre type, ça passe
        app(TenantManager::class)->setHotelId($b->id);
        $typeB = Type::create(['name' => 'Suite', 'capacity' => 2, 'information' => 'x']);
        $rulesB = (new StoreRoomRequest)->rules();
        $okValidator = Validator::make(['type_id' => $typeB->id], ['type_id' => $rulesB['type_id']]);
        $this->assertFalse($okValidator->fails());
    }
}
