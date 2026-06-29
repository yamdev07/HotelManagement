<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables caisse + restaurant + plans de salle scopées par hôtel.
     */
    private array $tables = [
        'cashier_sessions',
        'cashier_transactions',
        'restaurant_orders',
        'restaurant_order_items',
        'restaurant_reservations',
        'menus',
        'categories',
        'floor_plans',
    ];

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

        $defaultHotelId = DB::table('hotels')->where('slug', 'hotel-par-defaut')->value('id');
        if ($defaultHotelId) {
            foreach ($this->tables as $t) {
                if (Schema::hasTable($t) && Schema::hasColumn($t, 'hotel_id')) {
                    DB::table($t)->whereNull('hotel_id')->update(['hotel_id' => $defaultHotelId]);
                }
            }
        }
    }

    public function down()
    {
        foreach ($this->tables as $t) {
            if (Schema::hasTable($t) && Schema::hasColumn($t, 'hotel_id')) {
                Schema::table($t, function (Blueprint $table) {
                    $table->dropConstrainedForeignId('hotel_id');
                });
            }
        }
    }
};
