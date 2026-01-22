<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            
            // Informations de base
            $table->decimal('amount', 12, 2);
            
            // Méthode de paiement
            $table->enum('payment_method', [
                'cash',
                'card', 
                'transfer', 
                'mobile_money',
                'fedapay',
                'check',
                'refund'
            ])->default('cash');
            
            $table->json('payment_method_details')->nullable();
            
            // Statut
            $table->enum('status', [
                'pending',
                'completed', 
                'cancelled', 
                'expired',
                'failed',
                'refunded'
            ])->default('completed');
            
            // Référence
            $table->string('reference')->unique();
            
            // Champs spécifiques selon la méthode
            $table->string('check_number')->nullable();
            $table->string('card_last_four', 4)->nullable();
            $table->string('card_type', 20)->nullable();
            $table->string('mobile_money_provider', 50)->nullable();
            $table->string('mobile_money_number', 20)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_number', 50)->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            
            // Annulation
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users');
            $table->text('cancel_reason')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index('reference');
            $table->index('payment_method');
            $table->index('status');
            $table->index(['transaction_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};