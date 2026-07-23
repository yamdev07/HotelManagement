<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Création d'un client pour une réservation (issue #146) :
 * adresse, métier et date de naissance sont facultatifs à l'accueil, mais la
 * table les avait en NOT NULL sans défaut → crash "Column cannot be null" en
 * MySQL strict. De plus le genre "Other" (proposé dans le formulaire) n'était
 * pas dans l'enum ('Male','Female'). On relâche ces contraintes.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('customers')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            // Champs facultatifs à l'accueil : rendus nullable.
            foreach (['address' => 'VARCHAR(255)', 'job' => 'VARCHAR(255)', 'birthdate' => 'DATE'] as $col => $type) {
                if (Schema::hasColumn('customers', $col)) {
                    try {
                        DB::statement("ALTER TABLE `customers` MODIFY `{$col}` {$type} NULL");
                    } catch (\Throwable $e) {
                        // ignore : déjà nullable ou moteur différent
                    }
                }
            }

            // Le formulaire propose "Other" : on l'ajoute à l'enum.
            if (Schema::hasColumn('customers', 'gender')) {
                try {
                    DB::statement("ALTER TABLE `customers` MODIFY `gender` ENUM('Male','Female','Other') NOT NULL DEFAULT 'Other'");
                } catch (\Throwable $e) {
                    // ignore
                }
            }
        }
    }

    public function down(): void
    {
        // Pas de retour arrière : on ne veut pas re-rendre ces colonnes obligatoires.
    }
};
