<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Thème par défaut de l'établissement (Apparence) : clair / sombre / système.
 * L'accent (primary_color) existe déjà. Chaque appareil peut surcharger via le
 * toggle de la sidebar (localStorage) ; cette valeur est le défaut de l'hôtel.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('hotels') && ! Schema::hasColumn('hotels', 'theme_mode')) {
            Schema::table('hotels', function (Blueprint $table) {
                $table->string('theme_mode', 10)->default('light')->after('secondary_color');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('hotels') && Schema::hasColumn('hotels', 'theme_mode')) {
            Schema::table('hotels', function (Blueprint $table) {
                $table->dropColumn('theme_mode');
            });
        }
    }
};
