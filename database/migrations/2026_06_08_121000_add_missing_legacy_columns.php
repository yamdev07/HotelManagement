<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Réconciliation du schéma : ajoute les colonnes présentes en base de dev mais
 * sans migration (caisse, sessions réceptionniste, suivi check-in/housekeeping
 * sur les transactions). Idempotent via hasColumn.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cashier_sessions')) {
            Schema::table('cashier_sessions', function (Blueprint $table) {
                $add = fn (string $c, callable $def) => Schema::hasColumn('cashier_sessions', $c) ?: $def();

                $add('receptionist_session_id', fn () => $table->unsignedBigInteger('receptionist_session_id')->nullable());
                foreach (['cash_in', 'cash_out', 'card_total', 'mobile_total', 'other_total', 'refunds_total'] as $c) {
                    $add($c, fn () => $table->decimal($c, 15, 2)->default(0)->nullable());
                }
                $add('audit_by', fn () => $table->unsignedBigInteger('audit_by')->nullable());
                $add('audit_date', fn () => $table->dateTime('audit_date')->nullable());
            });
        }

        if (Schema::hasTable('receptionist_sessions') && ! Schema::hasColumn('receptionist_sessions', 'cashier_session_id')) {
            Schema::table('receptionist_sessions', function (Blueprint $table) {
                $table->unsignedBigInteger('cashier_session_id')->nullable();
            });
        }

        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                $add = fn (string $c, callable $def) => Schema::hasColumn('transactions', $c) ?: $def();

                $add('actual_check_in', fn () => $table->dateTime('actual_check_in')->nullable());
                $add('actual_check_out', fn () => $table->dateTime('actual_check_out')->nullable());
                $add('assigned_room_id', fn () => $table->unsignedBigInteger('assigned_room_id')->nullable());
                $add('checkin_notes', fn () => $table->text('checkin_notes')->nullable());
                $add('checkout_notes', fn () => $table->text('checkout_notes')->nullable());
                $add('checkin_method', fn () => $table->string('checkin_method')->nullable());
                $add('checkin_time_minutes', fn () => $table->integer('checkin_time_minutes')->nullable());
                foreach (['early_checkin', 'late_checkin', 'room_ready', 'room_cleaned', 'room_inspected'] as $c) {
                    $add($c, fn () => $table->boolean($c)->default(false));
                }
                $add('room_ready_at', fn () => $table->dateTime('room_ready_at')->nullable());
                $add('room_cleaned_at', fn () => $table->dateTime('room_cleaned_at')->nullable());
                $add('room_inspected_at', fn () => $table->dateTime('room_inspected_at')->nullable());
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cashier_sessions')) {
            Schema::table('cashier_sessions', function (Blueprint $table) {
                foreach (['receptionist_session_id', 'cash_in', 'cash_out', 'card_total', 'mobile_total', 'other_total', 'refunds_total', 'audit_by', 'audit_date'] as $c) {
                    if (Schema::hasColumn('cashier_sessions', $c)) {
                        $table->dropColumn($c);
                    }
                }
            });
        }

        if (Schema::hasTable('receptionist_sessions') && Schema::hasColumn('receptionist_sessions', 'cashier_session_id')) {
            Schema::table('receptionist_sessions', fn (Blueprint $table) => $table->dropColumn('cashier_session_id'));
        }

        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                foreach (['actual_check_in', 'actual_check_out', 'assigned_room_id', 'checkin_notes', 'checkout_notes', 'checkin_method', 'checkin_time_minutes', 'early_checkin', 'late_checkin', 'room_ready', 'room_cleaned', 'room_inspected', 'room_ready_at', 'room_cleaned_at', 'room_inspected_at'] as $c) {
                    if (Schema::hasColumn('transactions', $c)) {
                        $table->dropColumn($c);
                    }
                }
            });
        }
    }
};
