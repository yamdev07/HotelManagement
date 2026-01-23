<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('transaction_id');
            $table->decimal('amount', 12, 2);

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
            $table->string('reference');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
