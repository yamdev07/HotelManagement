<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Changes that are safe in one pass
        Schema::table('payments', function (Blueprint $table) {

            // amount type fix
            // NOTE: change() requires doctrine/dbal in many setups.
            // If CI fails here later, we can switch to raw SQL.
            $table->decimal('amount', 10, 2)->default(0)->change();

            // created_by (FK -> users)
            if (!Schema::hasColumn('payments', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('user_id');
            }

            // cashier_session_id column (FK later only if table exists)
            if (!Schema::hasColumn('payments', 'cashier_session_id')) {
                $table->unsignedBigInteger('cashier_session_id')->nullable()->after('transaction_id');
            }
        });

        // 2) Rename column in a separate schema call (safer)
        if (Schema::hasColumn('payments', 'notes') && !Schema::hasColumn('payments', 'description')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->renameColumn('notes', 'description');
            });
        }

        // 3) Add FKs only if referenced tables exist
        // created_by -> users
        if (Schema::hasTable('users') && Schema::hasColumn('payments', 'created_by')) {
            Schema::table('payments', function (Blueprint $table) {
                // Try/catch prevents failure if FK already exists
                try {
                    $table->foreign('created_by')
                        ->references('id')
                        ->on('users')
                        ->nullOnDelete();
                } catch (\Throwable $e) {}
            });
        }

        // cashier_session_id -> cashier_sessions (only if table exists)
        if (Schema::hasTable('cashier_sessions') && Schema::hasColumn('payments', 'cashier_session_id')) {
            Schema::table('payments', function (Blueprint $table) {
                try {
                    $table->foreign('cashier_session_id')
                        ->references('id')
                        ->on('cashier_sessions')
                        ->nullOnDelete();
                } catch (\Throwable $e) {}
            });
        }
    }

    public function down(): void
    {
        // Drop FKs safely first
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'created_by')) {
                try { $table->dropForeign(['created_by']); } catch (\Throwable $e) {}
            }
            if (Schema::hasColumn('payments', 'cashier_session_id')) {
                try { $table->dropForeign(['cashier_session_id']); } catch (\Throwable $e) {}
            }
        });

        // Rename back (separate)
        if (Schema::hasColumn('payments', 'description') && !Schema::hasColumn('payments', 'notes')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->renameColumn('description', 'notes');
            });
        }

        // Drop columns
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'created_by')) {
                $table->dropColumn('created_by');
            }
            if (Schema::hasColumn('payments', 'cashier_session_id')) {
                $table->dropColumn('cashier_session_id');
            }

            // back to previous type (if you really need it)
            $table->decimal('amount', 65, 2)->change();
        });
    }
};
