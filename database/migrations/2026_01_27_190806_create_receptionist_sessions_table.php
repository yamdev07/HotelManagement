// database/migrations/xxxx_create_receptionist_sessions_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('receptionist_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('session_code')->unique();
            $table->timestamp('login_time')->useCurrent();
            $table->timestamp('logout_time')->nullable();
            $table->string('login_ip')->nullable();
            $table->string('login_device')->nullable();
            $table->string('login_location')->nullable();
            $table->enum('session_status', ['active', 'closed', 'forced', 'timeout'])->default('active');
            $table->decimal('total_transactions_amount', 10, 2)->default(0);
            $table->integer('reservations_count')->default(0);
            $table->integer('checkins_count')->default(0);
            $table->integer('checkouts_count')->default(0);
            $table->integer('payments_count')->default(0);
            $table->integer('customer_creations')->default(0);
            $table->text('session_summary')->nullable();
            $table->decimal('cash_handled', 10, 2)->default(0);
            $table->decimal('card_handled', 10, 2)->default(0);
            $table->decimal('other_payments_handled', 10, 2)->default(0);
            $table->json('performance_metrics')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'session_status']);
            $table->index(['login_time', 'logout_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('receptionist_sessions');
    }
};
