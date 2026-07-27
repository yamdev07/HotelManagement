<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Issue #144 : le journal d'activité montrait les activités de tous les hôtels.
 * On ajoute hotel_id au journal (rempli automatiquement à l'écriture via le
 * trait BelongsToHotel) et on rattache l'historique existant à l'hôtel de
 * l'utilisateur qui a causé chaque activité.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('activity_log') || Schema::hasColumn('activity_log', 'hotel_id')) {
            return;
        }

        Schema::table('activity_log', function (Blueprint $table) {
            $table->unsignedBigInteger('hotel_id')->nullable()->after('id')->index();
        });

        // Backfill : rattache chaque activité passée à l'hôtel de son auteur.
        if (Schema::hasColumn('users', 'hotel_id')) {
            try {
                DB::table('activity_log')
                    ->join('users', 'activity_log.causer_id', '=', 'users.id')
                    ->where('activity_log.causer_type', \App\Models\User::class)
                    ->whereNull('activity_log.hotel_id')
                    ->update(['activity_log.hotel_id' => DB::raw('users.hotel_id')]);
            } catch (\Throwable $e) {
                // Skip backfill on drivers that don't support raw column references in UPDATE
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('activity_log') && Schema::hasColumn('activity_log', 'hotel_id')) {
            Schema::table('activity_log', function (Blueprint $table) {
                $table->dropColumn('hotel_id');
            });
        }
    }
};
