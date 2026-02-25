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
            // Vérifier et ajouter check_in_time si inexistant
            if (!Schema::hasColumn('transactions', 'check_in_time')) {
                $table->time('check_in_time')->nullable()->after('check_in');
            }
            
            // Vérifier et ajouter check_out_time si inexistant
            if (!Schema::hasColumn('transactions', 'check_out_time')) {
                $table->time('check_out_time')->nullable()->after('check_out');
            }
            
            // Vérifier et ajouter expected_checkout_time si inexistant
            if (!Schema::hasColumn('transactions', 'expected_checkout_time')) {
                $table->time('expected_checkout_time')->default('12:00:00')->after('check_out_time');
            }
        });

        // Modifier le commentaire du statut (optionnel)
        try {
            DB::statement("ALTER TABLE transactions MODIFY COLUMN status 
                          VARCHAR(255) NOT NULL DEFAULT 'reservation' 
                          COMMENT 'reservation, active, completed, cancelled, no_show, pending_checkout, reserved_waiting'");
        } catch (\Exception $e) {
            // Ignorer si la modification échoue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Ne supprimer que si les colonnes existent
            $columns = ['check_in_time', 'check_out_time', 'expected_checkout_time'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('transactions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};