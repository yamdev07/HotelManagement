<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Désactivez temporairement les contraintes de clé étrangère
        Schema::disableForeignKeyConstraints();

        // Supprimez d'abord les contraintes de clé étrangère
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Supprimez toutes les données existantes (les types de chambres)
        DB::table('room_statuses')->truncate();

        // Réactivez les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Insérez les vrais statuts
        $statuses = [
            ['name' => 'Available', 'code' => 'AVL', 'information' => 'Room is available for booking'],
            ['name' => 'Occupied', 'code' => 'OCC', 'information' => 'Room is currently occupied'],
            ['name' => 'Maintenance', 'code' => 'MNT', 'information' => 'Room is under maintenance'],
            ['name' => 'Reserved', 'code' => 'RSV', 'information' => 'Room is reserved'],
            ['name' => 'Cleaning', 'code' => 'CLN', 'information' => 'Room is being cleaned'],
        ];

        foreach ($statuses as $status) {
            DB::table('room_statuses')->insert([
                'name' => $status['name'],
                'code' => $status['code'],
                'information' => $status['information'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Réactivez les contraintes
        Schema::enableForeignKeyConstraints();

        echo "Table room_statuses mise à jour avec les vrais statuts.\n";
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('room_statuses')->truncate();

        // Si vous voulez restaurer les anciens types (optionnel)
        $oldTypes = [
            ['name' => 'single room', 'code' => 'SR', 'information' => 'A room for one person with a single bed.'],
            ['name' => 'double room', 'code' => 'DR', 'information' => 'A room for two people with one double bed.'],
            ['name' => 'Twin Room', 'code' => 'TR', 'information' => 'A room for two people with two single beds.'],
            ['name' => 'Deluxe Room', 'code' => 'DXR', 'information' => 'A larger, luxurious room.'],
            ['name' => 'Suite', 'code' => 'ST', 'information' => 'A spacious suite with separate areas.'],
            ['name' => 'Family Room', 'code' => 'FR', 'information' => 'A room designed for families.'],
            ['name' => 'VIP Suite', 'code' => 'VIP', 'information' => 'The most luxurious accommodation.'],
        ];

        foreach ($oldTypes as $type) {
            DB::table('room_statuses')->insert([
                'name' => $type['name'],
                'code' => $type['code'],
                'information' => $type['information'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Schema::enableForeignKeyConstraints();
    }
};
