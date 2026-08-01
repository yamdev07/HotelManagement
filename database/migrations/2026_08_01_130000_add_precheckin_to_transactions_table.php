<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pré-check-in en ligne : le voyageur remplit ses infos avant l'arrivée via un
 * lien/QR. Jeton non devinable + données soumises + date de complétion.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'checkin_token')) {
                $table->string('checkin_token', 40)->nullable()->unique()->after('notes');
            }
            if (! Schema::hasColumn('transactions', 'pre_checkin')) {
                $table->json('pre_checkin')->nullable()->after('checkin_token');
            }
            if (! Schema::hasColumn('transactions', 'pre_checkin_completed_at')) {
                $table->timestamp('pre_checkin_completed_at')->nullable()->after('pre_checkin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            foreach (['pre_checkin_completed_at', 'pre_checkin', 'checkin_token'] as $col) {
                if (Schema::hasColumn('transactions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
