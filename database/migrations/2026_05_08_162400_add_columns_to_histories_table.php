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
        Schema::table('histories', function (Blueprint $table) {
            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('cascade')->after('id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->after('transaction_id');
            $table->string('action')->nullable()->after('user_id');
            $table->text('description')->nullable()->after('action');
            $table->json('old_values')->nullable()->after('description');
            $table->json('new_values')->nullable()->after('old_values');
            $table->text('notes')->nullable()->after('new_values');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['transaction_id', 'user_id', 'action', 'description', 'old_values', 'new_values', 'notes']);
        });
    }
};
