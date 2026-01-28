// database/migrations/xxxx_create_receptionist_actions_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('receptionist_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('receptionist_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action_type'); // reservation, checkin, checkout, payment, etc.
            $table->string('action_subtype')->nullable(); // create, update, delete, cancel
            $table->morphs('actionable'); // Polymorphic relation
            $table->json('action_data')->nullable(); // Données de l'action
            $table->json('before_state')->nullable(); // État avant l'action
            $table->json('after_state')->nullable(); // État après l'action
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('performed_at')->useCurrent();
            
            $table->index(['user_id', 'action_type']);
            $table->index(['session_id', 'performed_at']);
            $table->index('actionable_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('receptionist_actions');
    }
};