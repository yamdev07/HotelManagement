<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'early_checkout')) {
                $table->boolean('early_checkout')->default(false);
            }
            if (! Schema::hasColumn('transactions', 'early_checkout_refund')) {
                $table->decimal('early_checkout_refund', 10, 2)->nullable();
            }
            if (! Schema::hasColumn('transactions', 'early_checkout_reason')) {
                $table->string('early_checkout_reason')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            foreach (['early_checkout', 'early_checkout_refund', 'early_checkout_reason'] as $col) {
                if (Schema::hasColumn('transactions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
