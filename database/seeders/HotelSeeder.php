<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    /**
     * Crée l'hôtel par défaut qui hébergera toutes les données existantes
     * lors de la migration vers le multi-tenant.
     */
    public function run()
    {
        Hotel::firstOrCreate(
            ['slug' => 'hotel-par-defaut'],
            [
                'name'        => config('app.name', 'MyHotel'),
                'currency'    => 'CFA',
                'timezone'    => config('app.timezone', 'Africa/Lagos'),
                'is_active'   => true,
            ]
        );
    }
}
