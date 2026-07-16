<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Issue #173 : la photo ajoutée à la fiche client (flux réservation) n'était
 * jamais enregistrée · la table customers n'avait pas de colonne 'avatar'
 * (et le fillable la supprimait silencieusement). On ajoute la colonne.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customers') && ! Schema::hasColumn('customers', 'avatar')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('avatar')->nullable()->after('phone');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('customers') && Schema::hasColumn('customers', 'avatar')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('avatar');
            });
        }
    }
};
