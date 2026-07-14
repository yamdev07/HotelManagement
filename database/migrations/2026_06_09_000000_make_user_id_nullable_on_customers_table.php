<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Rend customers.user_id nullable : une fiche client peut exister sans compte
 * de connexion global (multi-tenant — le même client peut être présent dans
 * plusieurs hôtels sans dupliquer un compte à email globalement unique).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customers') && Schema::hasColumn('customers', 'user_id')) {
            // MODIFY conserve la clé étrangère existante, rend juste la colonne nullable.
            DB::statement('ALTER TABLE `customers` MODIFY `user_id` BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        // On ne re-force pas NOT NULL (des fiches sans compte pourraient exister).
    }
};
