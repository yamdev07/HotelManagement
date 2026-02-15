<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixPaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // 1. Corriger le type de la colonne amount
            $table->decimal('amount', 10, 2)->default(0)->change();

            // 2. Renommer notes en description si nécessaire
            if (Schema::hasColumn('payments', 'notes')) {
                $table->renameColumn('notes', 'description');
            }

            // 3. Ajouter les colonnes manquantes
            if (! Schema::hasColumn('payments', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('user_id');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }

            if (! Schema::hasColumn('payments', 'cashier_session_id')) {
                $table->unsignedBigInteger('cashier_session_id')->nullable()->after('transaction_id');
                $table->foreign('cashier_session_id')->references('id')->on('cashier_sessions')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Revenir au type original si nécessaire
            $table->decimal('amount', 65, 2)->change();

            if (Schema::hasColumn('payments', 'description')) {
                $table->renameColumn('description', 'notes');
            }

            if (Schema::hasColumn('payments', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }

            if (Schema::hasColumn('payments', 'cashier_session_id')) {
                $table->dropForeign(['cashier_session_id']);
                $table->dropColumn('cashier_session_id');
            }
        });
    }
}
