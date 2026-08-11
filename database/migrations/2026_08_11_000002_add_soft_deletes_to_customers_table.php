<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Suppression logique des clients : un client ayant des réservations ne
     * pouvait pas être supprimé (FK RESTRICT sur transactions.customer_id).
     * Avec SoftDeletes la ligne reste en base (historique intact) mais le client
     * disparaît des listes. Issue #202.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (! Schema::hasColumn('customers', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
