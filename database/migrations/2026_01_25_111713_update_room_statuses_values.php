<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour les valeurs des statuts pour correspondre au nouveau schéma
        DB::statement('
            UPDATE rooms 
            SET room_status_id = 
                CASE room_status_id
                    WHEN 1 THEN 1  -- Disponible reste 1
                    WHEN 2 THEN 4  -- Ancien Occupé (2) devient Occupé (4)
                    WHEN 3 THEN 2  -- Ancien Maintenance (3) devient Maintenance (2)
                    WHEN 4 THEN 3  -- Ancien Nettoyage (4) devient Nettoyage (3)
                    ELSE room_status_id
                END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir aux anciennes valeurs
        DB::statement('
            UPDATE rooms 
            SET room_status_id = 
                CASE room_status_id
                    WHEN 1 THEN 1  -- Disponible reste 1
                    WHEN 4 THEN 2  -- Occupé (4) redevient ancien Occupé (2)
                    WHEN 2 THEN 3  -- Maintenance (2) redevient ancien Maintenance (3)
                    WHEN 3 THEN 4  -- Nettoyage (3) redevient ancien Nettoyage (4)
                    ELSE room_status_id
                END
        ');
    }
};
