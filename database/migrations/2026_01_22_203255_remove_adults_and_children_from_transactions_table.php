<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Supprimer les colonnes adults et children
            $table->dropColumn(['adults', 'children']);
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Recréer les colonnes si on annule la migration
            $table->integer('adults')->default(1)->after('person_count');
            $table->integer('children')->default(0)->after('adults');
        });
    }
};