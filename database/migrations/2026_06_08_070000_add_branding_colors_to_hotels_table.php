<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Couleurs de marque par hôtel (white-label).
     * Le logo et les infos de contact existent déjà sur la table hotels.
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            if (! Schema::hasColumn('hotels', 'primary_color')) {
                $table->string('primary_color', 9)->default('#4f46e5')->after('logo');
            }
            if (! Schema::hasColumn('hotels', 'secondary_color')) {
                $table->string('secondary_color', 9)->default('#0f172a')->after('primary_color');
            }
        });
    }

    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            foreach (['primary_color', 'secondary_color'] as $col) {
                if (Schema::hasColumn('hotels', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
