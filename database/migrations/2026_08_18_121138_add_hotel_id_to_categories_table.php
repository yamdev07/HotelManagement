<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'hotel_id')) {
                $table->foreignId('hotel_id')->nullable()->after('id')->constrained('hotels')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'hotel_id')) {
                $table->dropForeign(['hotel_id']);
                $table->dropColumn('hotel_id');
            }
        });
    }
};
