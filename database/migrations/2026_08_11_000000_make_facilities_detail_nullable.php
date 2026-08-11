<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * La colonne `detail` était NOT NULL sans valeur par défaut : enregistrer un
     * équipement sans description déclenchait une erreur SQL. On la rend nullable.
     */
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->longText('detail')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->longText('detail')->nullable(false)->change();
        });
    }
};
