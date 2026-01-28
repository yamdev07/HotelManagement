// database/migrations/xxxx_add_fields_to_users_table_for_receptionist.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_session_id')->nullable()->constrained('receptionist_sessions');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_logout_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->integer('total_sessions')->default(0);
            $table->integer('total_reservations')->default(0);
            $table->integer('total_checkins')->default(0);
            $table->integer('total_checkouts')->default(0);
            $table->decimal('total_transactions_value', 15, 2)->default(0);
            $table->json('performance_stats')->nullable();
            $table->boolean('is_active_session')->default(false);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_session_id']);
            $table->dropColumn([
                'current_session_id',
                'last_login_at',
                'last_logout_at',
                'last_login_ip',
                'total_sessions',
                'total_reservations',
                'total_checkins',
                'total_checkouts',
                'total_transactions_value',
                'performance_stats',
                'is_active_session'
            ]);
        });
    }
};