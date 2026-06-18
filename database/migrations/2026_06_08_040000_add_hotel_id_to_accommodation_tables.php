<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables d'hébergement scopées par hôtel.
     * room_statuses reste GLOBAL : ce sont des états de workflow câblés en dur
     * via les constantes Room::STATUS_* (ids 1 à 6).
     */
    private array $tables = ['rooms', 'types', 'facilities', 'images'];

    public function up()
    {
        foreach ($this->tables as $t) {
            if (Schema::hasTable($t) && ! Schema::hasColumn($t, 'hotel_id')) {
                Schema::table($t, function (Blueprint $table) {
                    $table->foreignId('hotel_id')->nullable()->after('id')
                        ->constrained('hotels')->nullOnDelete();
                });
            }
        }

        // Backfill vers l'hôtel par défaut
        $defaultHotelId = DB::table('hotels')->where('slug', 'hotel-par-defaut')->value('id');
        if ($defaultHotelId) {
            foreach ($this->tables as $t) {
                if (Schema::hasColumn($t, 'hotel_id')) {
                    DB::table($t)->whereNull('hotel_id')->update(['hotel_id' => $defaultHotelId]);
                }
            }
        }
    }

    public function down()
    {
        foreach ($this->tables as $t) {
            if (Schema::hasColumn($t, 'hotel_id')) {
                Schema::table($t, function (Blueprint $table) {
                    $table->dropConstrainedForeignId('hotel_id');
                });
            }
        }
    }
};
