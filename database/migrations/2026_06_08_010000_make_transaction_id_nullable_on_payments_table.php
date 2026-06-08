<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Rend payments.transaction_id nullable : les paiements d'ajustement
     * générés à la clôture de caisse (excédent / manquant) ne sont liés
     * à aucune transaction. Sans ça, fermer la caisse avec un écart échoue
     * avec « Column 'transaction_id' cannot be null ».
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign('payments_transaction_id_foreign');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('transaction_id')->nullable()->change();
            $table->foreign('transaction_id')->references('id')->on('transactions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign('payments_transaction_id_foreign');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('transaction_id')->nullable(false)->change();
            $table->foreign('transaction_id')->references('id')->on('transactions')->cascadeOnDelete();
        });
    }
};
