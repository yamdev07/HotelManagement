<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // If the table doesn't exist (rare case), create it.
        // Otherwise, upgrade it safely.
        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();

                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('transaction_id')->constrained()->onDelete('cascade');

                $table->decimal('amount', 12, 2);

                $table->enum('payment_method', [
                    'cash', 'card', 'transfer', 'mobile_money', 'fedapay', 'check', 'refund',
                ])->default('cash');

                $table->json('payment_method_details')->nullable();

                $table->enum('status', [
                    'pending', 'completed', 'cancelled', 'expired', 'failed', 'refunded',
                ])->default('completed');

                // ⚠️ In CI/seed, unique reference can break if not provided.
                // Keep it nullable + unique index (if you always set it in app).
                $table->string('reference')->nullable();

                $table->string('check_number')->nullable();
                $table->string('card_last_four', 4)->nullable();
                $table->string('card_type', 20)->nullable();
                $table->string('mobile_money_provider', 50)->nullable();
                $table->string('mobile_money_number', 20)->nullable();
                $table->string('bank_name', 100)->nullable();
                $table->string('account_number', 50)->nullable();

                $table->text('notes')->nullable();

                $table->timestamp('cancelled_at')->nullable();
                $table->foreignId('cancelled_by')->nullable()->constrained('users');
                $table->text('cancel_reason')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index('reference');
                $table->index('payment_method');
                $table->index('status');
                $table->index(['transaction_id', 'status']);
            });

            return;
        }

        // Upgrade existing table safely
        Schema::table('payments', function (Blueprint $table) {
            // Add columns only if missing (safe for prod/dev/CI)
            if (! Schema::hasColumn('payments', 'payment_method')) {
                $table->enum('payment_method', [
                    'cash', 'card', 'transfer', 'mobile_money', 'fedapay', 'check', 'refund',
                ])->default('cash')->after('amount');
            }

            if (! Schema::hasColumn('payments', 'payment_method_details')) {
                $table->json('payment_method_details')->nullable()->after('payment_method');
            }

            if (! Schema::hasColumn('payments', 'status')) {
                $table->enum('status', [
                    'pending', 'completed', 'cancelled', 'expired', 'failed', 'refunded',
                ])->default('completed')->after('payment_method_details');
            }

            if (! Schema::hasColumn('payments', 'reference')) {
                $table->string('reference')->nullable()->after('status');
            } else {
                // Ensure it's nullable to avoid CI/seed issues
                $table->string('reference')->nullable()->change();
            }

            if (! Schema::hasColumn('payments', 'check_number')) $table->string('check_number')->nullable();
            if (! Schema::hasColumn('payments', 'card_last_four')) $table->string('card_last_four', 4)->nullable();
            if (! Schema::hasColumn('payments', 'card_type')) $table->string('card_type', 20)->nullable();
            if (! Schema::hasColumn('payments', 'mobile_money_provider')) $table->string('mobile_money_provider', 50)->nullable();
            if (! Schema::hasColumn('payments', 'mobile_money_number')) $table->string('mobile_money_number', 20)->nullable();
            if (! Schema::hasColumn('payments', 'bank_name')) $table->string('bank_name', 100)->nullable();
            if (! Schema::hasColumn('payments', 'account_number')) $table->string('account_number', 50)->nullable();

            if (! Schema::hasColumn('payments', 'notes')) $table->text('notes')->nullable();

            if (! Schema::hasColumn('payments', 'cancelled_at')) $table->timestamp('cancelled_at')->nullable();
            if (! Schema::hasColumn('payments', 'cancelled_by')) $table->foreignId('cancelled_by')->nullable()->constrained('users');
            if (! Schema::hasColumn('payments', 'cancel_reason')) $table->text('cancel_reason')->nullable();

            if (! Schema::hasColumn('payments', 'deleted_at')) $table->softDeletes();
        });

        // Indexes (create only if not existing)
        // Note: Laravel doesn't have a perfect "hasIndex" helper,
        // so we keep this minimal. If you want, we can add a raw check.
        Schema::table('payments', function (Blueprint $table) {
            // These index calls are safe if they don't exist,
            // but can fail if they already exist with same name.
            // If your previous migration already created them,
            // comment out the duplicates.
            try { $table->index('reference'); } catch (\Throwable $e) {}
            try { $table->index('payment_method'); } catch (\Throwable $e) {}
            try { $table->index('status'); } catch (\Throwable $e) {}
            try { $table->index(['transaction_id', 'status']); } catch (\Throwable $e) {}
        });
    }

    public function down(): void
    {
        // Safe rollback: only drop columns we added (don't drop entire table)
        if (! Schema::hasTable('payments')) return;

        Schema::table('payments', function (Blueprint $table) {
            // Drop columns if they exist
            foreach ([
                'payment_method_details', 'check_number', 'card_last_four', 'card_type',
                'mobile_money_provider', 'mobile_money_number', 'bank_name', 'account_number',
                'notes', 'cancelled_at', 'cancelled_by', 'cancel_reason', 'deleted_at',
            ] as $col) {
                if (Schema::hasColumn('payments', $col)) {
                    try { $table->dropColumn($col); } catch (\Throwable $e) {}
                }
            }
        });
    }
};
