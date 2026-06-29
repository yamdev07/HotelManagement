<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Affichage des sections de la vitrine publique, activable par hôtel.
     */
    private array $toggles = ['show_rooms', 'show_restaurant', 'show_services', 'show_contact'];

    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            foreach ($this->toggles as $col) {
                if (! Schema::hasColumn('hotels', $col)) {
                    $table->boolean($col)->default(true);
                }
            }
        });
    }

    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            foreach ($this->toggles as $col) {
                if (Schema::hasColumn('hotels', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
