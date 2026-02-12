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
        Schema::table('transactions', function (Blueprint $table) {
            // 1. Supprimez d'abord la contrainte étrangère existante
            $table->dropForeign(['room_id']);
            
            // 2. Modifiez la colonne pour la rendre nullable
            $table->foreignId('room_id')
                ->nullable()
                ->change();
            
            // 3. Recréez la contrainte étrangère avec onDelete('set null')
            $table->foreign('room_id')
                ->references('id')
                ->on('rooms')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // 1. Supprimez la contrainte étrangère
            $table->dropForeign(['room_id']);
            
            // 2. Remettez non nullable
            $table->foreignId('room_id')
                ->nullable(false)
                ->change();
            
            // 3. Recréez la contrainte d'origine
            $table->foreign('room_id')
                ->references('id')
                ->on('rooms')
                ->onDelete('cascade');
        });
    }
};