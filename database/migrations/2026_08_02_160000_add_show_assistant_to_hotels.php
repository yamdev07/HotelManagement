<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Interrupteur d'affichage de l'assistant IA sur la vitrine de l'hôtel.
 * (L'assistant n'apparaît que si ce réglage est actif ET qu'une clé Groq
 * est configurée côté serveur.)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            if (! Schema::hasColumn('hotels', 'show_assistant')) {
                $table->boolean('show_assistant')->default(true)->after('reviews_moderation');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            if (Schema::hasColumn('hotels', 'show_assistant')) {
                $table->dropColumn('show_assistant');
            }
        });
    }
};
