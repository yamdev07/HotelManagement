<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Jeton d'accès au flux iCal d'une chambre (export vers Booking/Airbnb).
 * Non devinable : l'URL du calendrier ne révèle pas l'identité de l'hôtel.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (! Schema::hasColumn('rooms', 'ical_token')) {
                $table->string('ical_token', 40)->nullable()->unique()->after('view');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'ical_token')) {
                $table->dropColumn('ical_token');
            }
        });
    }
};
