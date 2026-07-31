<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Synchronisation de calendriers OTA (Booking.com, Airbnb, ...).
 *  - room_calendar_feeds : URLs iCal externes à importer pour une chambre
 *  - room_blocks         : périodes d'indisponibilité importées de ces flux
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_calendar_feeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->string('source', 60);            // ex. "Booking.com", "Airbnb"
            $table->string('url', 1000);
            $table->timestamp('last_synced_at')->nullable();
            $table->string('last_error', 500)->nullable();
            $table->timestamps();

            $table->index(['room_id']);
        });

        Schema::create('room_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feed_id')->nullable()->constrained('room_calendar_feeds')->cascadeOnDelete();
            $table->string('source', 60)->nullable();
            $table->string('external_uid', 255)->nullable();
            $table->date('start_date');
            $table->date('end_date');            // exclusif (convention iCal/OTA)
            $table->string('summary', 255)->nullable();
            $table->timestamps();

            $table->index(['room_id', 'start_date', 'end_date']);
            $table->unique(['feed_id', 'external_uid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_blocks');
        Schema::dropIfExists('room_calendar_feeds');
    }
};
