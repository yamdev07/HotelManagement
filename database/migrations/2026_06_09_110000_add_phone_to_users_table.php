<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Issue #145 : le profil permet de saisir un numéro de téléphone, mais la
 * table users n'avait pas de colonne 'phone' → la valeur n'était jamais
 * enregistrée. On l'ajoute.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone', 30)->nullable()->after('email');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('phone');
            });
        }
    }
};
