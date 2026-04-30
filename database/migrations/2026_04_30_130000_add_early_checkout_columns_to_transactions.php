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
                $table->boolean('early_checkout')->default(false)->after('late_checkout');
            }
            if (! Schema::hasColumn('transactions', 'early_checkout_refund')) {
                $table->decimal('early_checkout_refund', 10, 2)->nullable()->after('early_checkout');
            }
            if (! Schema::hasColumn('transactions', 'early_checkout_reason')) {
                $table->string('early_checkout_reason')->nullable()->after('early_checkout_refund');
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
