<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'payment_date')) {
                $table->timestamp('payment_date')->nullable()->after('reference');
            }
            if (! Schema::hasColumn('payments', 'notes')) {
                $table->text('notes')->nullable()->after('payment_date');
            }
            if (! Schema::hasColumn('payments', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable();
            }
            if (! Schema::hasColumn('payments', 'cancelled_by')) {
                $table->unsignedBigInteger('cancelled_by')->nullable();
            }
            if (! Schema::hasColumn('payments', 'cancel_reason')) {
                $table->string('cancel_reason')->nullable();
            }
            if (! Schema::hasColumn('payments', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable();
            }
            if (! Schema::hasColumn('payments', 'verified_at')) {
                $table->timestamp('verified_at')->nullable();
            }
            if (! Schema::hasColumn('payments', 'payment_gateway_response')) {
                $table->json('payment_gateway_response')->nullable();
            }
            if (! Schema::hasColumn('payments', 'currency')) {
                $table->string('currency', 3)->default('XOF');
            }
            if (! Schema::hasColumn('payments', 'exchange_rate')) {
                $table->decimal('exchange_rate', 10, 4)->default(1);
            }
            if (! Schema::hasColumn('payments', 'fees')) {
                $table->decimal('fees', 10, 2)->default(0);
            }
            if (! Schema::hasColumn('payments', 'tax')) {
                $table->decimal('tax', 10, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $cols = [
                'payment_date', 'notes', 'cancelled_at', 'cancelled_by',
                'cancel_reason', 'verified_by', 'verified_at',
                'payment_gateway_response', 'currency', 'exchange_rate',
                'fees', 'tax',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('payments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
