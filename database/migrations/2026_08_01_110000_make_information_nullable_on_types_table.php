<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * La description d'un type de chambre (`information`) est optionnelle côté
 * formulaire et validation, mais la colonne était NOT NULL -> créer un type
 * sans description échouait. On la rend nullable.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('types', function (Blueprint $table) {
            $table->longText('information')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('types', function (Blueprint $table) {
            $table->longText('information')->nullable(false)->change();
        });
    }
};
