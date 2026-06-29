<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Contenu dynamique de la vitrine, éditable par l'hôtelier :
     * - services : liste personnalisée [{icon, title, description}]
     * - socials  : réseaux sociaux {facebook, instagram, whatsapp, website}
     * - about_title / about_text : bloc de présentation personnalisable
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            if (! Schema::hasColumn('hotels', 'services')) {
                $table->json('services')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'socials')) {
                $table->json('socials')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'about_title')) {
                $table->string('about_title')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'about_text')) {
                $table->text('about_text')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            foreach (['services', 'socials', 'about_title', 'about_text'] as $col) {
                if (Schema::hasColumn('hotels', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
