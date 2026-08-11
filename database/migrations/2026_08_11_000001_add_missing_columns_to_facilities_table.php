<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Le modèle Facility (fillable + boot()) référence des colonnes qui
     * n'existaient pas en base (category, is_active, sort_order...). Résultat :
     * toute création d'équipement plantait ("Unknown column 'sort_order'").
     * On ajoute les colonnes manquantes avec des valeurs par défaut sûres.
     */
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            if (! Schema::hasColumn('facilities', 'category')) {
                $table->string('category')->default('general')->after('detail');
            }
            if (! Schema::hasColumn('facilities', 'description')) {
                $table->text('description')->nullable()->after('category');
            }
            if (! Schema::hasColumn('facilities', 'additional_info')) {
                $table->json('additional_info')->nullable()->after('description');
            }
            if (! Schema::hasColumn('facilities', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('additional_info');
            }
            if (! Schema::hasColumn('facilities', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            foreach (['sort_order', 'is_active', 'additional_info', 'description', 'category'] as $col) {
                if (Schema::hasColumn('facilities', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
