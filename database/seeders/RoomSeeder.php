<?php

namespace Database\Seeders;

use App\Models\FloorPlan;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Ce seeder initialise les plans de salle du restaurant (FloorPlan).
     * Les salles de restaurant ne sont PAS des chambres d'hôtel (Room).
     */
    public function run(): void
    {
        $salles = [
            ['room_id' => null, 'layout' => [], 'updated_by' => null],
        ];

        // Rien à créer ici par défaut — les plans sont créés via l'interface admin.
        // Anciennement ce seeder créait des Room avec des noms de salles de restaurant,
        // ce qui était incorrect. Le modèle FloorPlan gère les plans de salle.
    }
}
