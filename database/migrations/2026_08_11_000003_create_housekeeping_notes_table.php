<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Notes du rapport quotidien de nettoyage (observations + suggestions).
     * Avant, les champs étaient de l'UI morte (pas de sauvegarde). Issue #222.
     * Une note par hôtel et par jour.
     */
    public function up(): void
    {
        Schema::create('housekeeping_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->nullable()->index();
            $table->date('report_date')->index();
            $table->text('observations')->nullable();
            $table->text('suggestions')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->timestamps();

            $table->unique(['hotel_id', 'report_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('housekeeping_notes');
    }
};
