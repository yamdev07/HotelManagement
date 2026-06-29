<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Rattache chaque utilisateur (staff) à un hôtel. Nullable pour permettre
     * un Super-Admin plateforme sans hôtel. Les users existants sont rattachés
     * à l'hôtel par défaut.
     */
    public function up()
    {
        if (! Schema::hasColumn('users', 'hotel_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('hotel_id')->nullable()->after('id')
                    ->constrained('hotels')->nullOnDelete();
            });
        }

        // Backfill : rattacher les utilisateurs existants à l'hôtel par défaut
        $defaultHotelId = DB::table('hotels')->where('slug', 'hotel-par-defaut')->value('id');
        if ($defaultHotelId) {
            DB::table('users')->whereNull('hotel_id')->update(['hotel_id' => $defaultHotelId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'hotel_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('hotel_id');
            });
        }
    }
};
