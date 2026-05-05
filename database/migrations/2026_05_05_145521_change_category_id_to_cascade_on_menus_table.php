<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // $table->dropForeign(['category_id']); // Déjà supprimé lors de la tentative précédente
            $table->foreignId('category_id')
                ->nullable() // Ajout de nullable pour éviter l'erreur de conversion
                ->change()
                ->constrained()
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreignId('category_id')
                ->change()
                ->constrained()
                ->onDelete('set null');
        });
    }
};
