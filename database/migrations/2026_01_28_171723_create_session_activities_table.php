<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('session_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashier_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // Ex: payment_created, booking_created, checkout, checkin, etc.
            $table->string('entity_type'); // Ex: Payment, Booking, Transaction, Room, etc.
            $table->unsignedBigInteger('entity_id')->nullable(); // ID de l'entité
            $table->text('description'); // Description en texte clair
            $table->json('data')->nullable(); // Données supplémentaires au format JSON
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            // Index pour les performances
            $table->index(['cashier_session_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_activities');
    }
};
