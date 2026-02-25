<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Ajouter les colonnes pour les heures
            $table->time('check_in_time')->nullable()->after('check_in');
            $table->time('check_out_time')->nullable()->after('check_out');
            
            // Modifier le statut pour accepter les nouveaux statuts
            $table->string('status')->default('reservation')->change();
        });

        // Optionnel: Ajouter un commentaire pour documentation
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status 
                      VARCHAR(255) NOT NULL DEFAULT 'reservation' 
                      COMMENT 'reservation, confirmed, active, completed, cancelled, no_show, pending_checkout'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropColumn(['check_in_time', 'check_out_time']);
            
            // Remettre le statut comme avant (si nécessaire)
            $table->string('status')->default('reservation')->change();
        });
    }
};