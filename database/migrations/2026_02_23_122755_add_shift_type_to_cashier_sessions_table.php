<?php
// database/migrations/2024_02_23_000000_add_shift_type_to_cashier_sessions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cashier_sessions', function (Blueprint $table) {
            // Ajouter la colonne shift_type (nullable pour les anciennes sessions)
            $table->string('shift_type')->nullable()->after('status');
            
            // Optionnel : Ajouter un index pour les recherches
            $table->index('shift_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cashier_sessions', function (Blueprint $table) {
            $table->dropColumn('shift_type');
        });
    }
};