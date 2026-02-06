<?php

// database/migrations/xxxx_add_room_type_fields.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoomTypeFields extends Migration
{
    public function up()
    {
        Schema::table('types', function (Blueprint $table) {
            // Champs ESSENTIELS
            $table->decimal('base_price', 10, 2)->nullable()->after('name');
            $table->integer('capacity')->default(1)->after('base_price');

            // Champs OPTIONNELS mais recommandés
            $table->json('amenities')->nullable()->after('capacity');
            $table->string('bed_type')->nullable()->after('amenities');
            $table->integer('bed_count')->default(1)->after('bed_type');
            $table->string('size')->nullable()->after('bed_count');

            // Pour tri et organisation
            $table->integer('sort_order')->default(0)->after('size');
            $table->boolean('is_active')->default(true)->after('sort_order');
        });
    }

    public function down()
    {
        Schema::table('types', function (Blueprint $table) {
            $table->dropColumn([
                'base_price',
                'capacity',
                'amenities',
                'bed_type',
                'bed_count',
                'size',
                'sort_order',
                'is_active',
            ]);
        });
    }
}
