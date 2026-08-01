<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Champs de check-in (identité du voyageur) écrits par CheckInService mais
 * jamais créés en base -> ajout des colonnes manquantes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'special_requests')) {
                $table->text('special_requests')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('transactions', 'id_type')) {
                $table->string('id_type', 40)->nullable()->after('special_requests');
            }
            if (! Schema::hasColumn('transactions', 'id_number')) {
                $table->string('id_number', 60)->nullable()->after('id_type');
            }
            if (! Schema::hasColumn('transactions', 'nationality')) {
                $table->string('nationality', 80)->nullable()->after('id_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            foreach (['nationality', 'id_number', 'id_type', 'special_requests'] as $col) {
                if (Schema::hasColumn('transactions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
