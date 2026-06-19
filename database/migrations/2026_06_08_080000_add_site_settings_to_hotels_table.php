<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Contenu CMS de la vitrine publique, éditable par hôtel.
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            if (! Schema::hasColumn('hotels', 'tagline')) {
                $table->string('tagline')->nullable()->after('secondary_color');
            }
            if (! Schema::hasColumn('hotels', 'description')) {
                $table->text('description')->nullable()->after('tagline');
            }
            if (! Schema::hasColumn('hotels', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('description');
            }
        });
    }

    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            foreach (['tagline', 'description', 'cover_image'] as $col) {
                if (Schema::hasColumn('hotels', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
