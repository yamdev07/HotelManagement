<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Journal des emails du cycle d'essai déjà envoyés à chaque hôtel.
 * Garantit qu'une étape (welcome/tips/usage/ending) n'est envoyée qu'une fois.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('trial_email_logs')) {
            return;
        }

        Schema::create('trial_email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->string('stage', 40);
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();

            $table->unique(['hotel_id', 'stage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trial_email_logs');
    }
};
