<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Réconciliation du schéma : recrée les tables présentes en base de dev mais
 * qui n'avaient aucune migration (indispensable pour un déploiement sur base neuve).
 * Clés étrangères retirées (les colonnes/indexes suffisent au fonctionnement et
 * évitent les problèmes d'ordre de création). Idempotent via hasTable.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (! Schema::hasTable('cashier_transactions')) {
            DB::statement("
                CREATE TABLE `cashier_transactions` (
                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                  `hotel_id` bigint unsigned DEFAULT NULL,
                  `cashier_session_id` bigint unsigned NOT NULL,
                  `transaction_id` bigint unsigned DEFAULT NULL,
                  `payment_id` bigint unsigned DEFAULT NULL,
                  `type` enum('cash_payment','card_payment','mobile_payment','cash_refund','card_refund','mobile_refund','cash_deposit','cash_withdrawal','expense','correction','transfer') NOT NULL,
                  `amount` decimal(15,2) NOT NULL,
                  `description` varchar(500) NOT NULL,
                  `reference` varchar(100) DEFAULT NULL,
                  `notes` text DEFAULT NULL,
                  `created_by` bigint unsigned NOT NULL,
                  `verified_by` bigint unsigned DEFAULT NULL,
                  `verified_at` datetime DEFAULT NULL,
                  `status` enum('pending','completed','cancelled','disputed') DEFAULT 'completed',
                  `metadata` longtext DEFAULT NULL,
                  `created_at` timestamp NULL DEFAULT NULL,
                  `updated_at` timestamp NULL DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  KEY `idx_ct_hotel` (`hotel_id`),
                  KEY `idx_ct_session` (`cashier_session_id`),
                  KEY `idx_ct_type` (`type`),
                  KEY `idx_ct_created_by` (`created_by`),
                  KEY `idx_ct_reference` (`reference`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        }

        if (! Schema::hasTable('receptionist_commissions')) {
            DB::statement("
                CREATE TABLE `receptionist_commissions` (
                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                  `receptionist_id` bigint unsigned NOT NULL,
                  `period_start` date NOT NULL,
                  `period_end` date NOT NULL,
                  `total_sales` decimal(15,2) DEFAULT 0.00,
                  `commission_rate` decimal(5,2) DEFAULT 2.50,
                  `commission_amount` decimal(15,2) DEFAULT 0.00,
                  `bonus_amount` decimal(15,2) DEFAULT 0.00,
                  `penalty_amount` decimal(15,2) DEFAULT 0.00,
                  `net_commission` decimal(15,2) DEFAULT 0.00,
                  `status` enum('pending','calculated','approved','paid','cancelled') DEFAULT 'pending',
                  `payment_date` date DEFAULT NULL,
                  `payment_method` varchar(50) DEFAULT NULL,
                  `payment_reference` varchar(100) DEFAULT NULL,
                  `notes` text DEFAULT NULL,
                  `calculated_by` bigint unsigned DEFAULT NULL,
                  `approved_by` bigint unsigned DEFAULT NULL,
                  `paid_by` bigint unsigned DEFAULT NULL,
                  `created_at` timestamp NULL DEFAULT NULL,
                  `updated_at` timestamp NULL DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  KEY `idx_rc_receptionist` (`receptionist_id`),
                  KEY `idx_rc_period` (`period_start`,`period_end`),
                  KEY `idx_rc_status` (`status`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        }

        if (! Schema::hasTable('receptionist_profiles')) {
            DB::statement("
                CREATE TABLE `receptionist_profiles` (
                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                  `user_id` bigint unsigned NOT NULL,
                  `employee_id` varchar(50) DEFAULT NULL,
                  `hire_date` date DEFAULT NULL,
                  `department` varchar(100) DEFAULT 'Réception',
                  `position` varchar(100) DEFAULT 'Réceptionniste',
                  `shift_type` enum('morning','afternoon','night','flexible') DEFAULT 'morning',
                  `max_transaction_amount` decimal(15,2) DEFAULT 100000.00,
                  `can_give_discount` tinyint(1) DEFAULT 0,
                  `max_discount_percentage` decimal(5,2) DEFAULT 10.00,
                  `can_refund` tinyint(1) DEFAULT 0,
                  `can_check_in_out` tinyint(1) DEFAULT 1,
                  `can_manage_rooms` tinyint(1) DEFAULT 0,
                  `can_view_reports` tinyint(1) DEFAULT 0,
                  `monthly_target` decimal(15,2) DEFAULT 1000000.00,
                  `performance_bonus` decimal(15,2) DEFAULT 0.00,
                  `notes` text DEFAULT NULL,
                  `created_at` timestamp NULL DEFAULT NULL,
                  `updated_at` timestamp NULL DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uq_rp_user` (`user_id`),
                  UNIQUE KEY `uq_rp_employee` (`employee_id`),
                  KEY `idx_rp_department` (`department`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        }

        if (! Schema::hasTable('room_status_history')) {
            DB::statement("
                CREATE TABLE `room_status_history` (
                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                  `room_id` bigint unsigned NOT NULL,
                  `old_status_id` bigint unsigned DEFAULT NULL,
                  `new_status_id` bigint unsigned NOT NULL,
                  `changed_by` bigint unsigned DEFAULT NULL,
                  `reason` text DEFAULT NULL,
                  `changed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  KEY `idx_rsh_room` (`room_id`),
                  KEY `idx_rsh_new_status` (`new_status_id`),
                  KEY `idx_rsh_changed_by` (`changed_by`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        Schema::dropIfExists('cashier_transactions');
        Schema::dropIfExists('receptionist_commissions');
        Schema::dropIfExists('receptionist_profiles');
        Schema::dropIfExists('room_status_history');
    }
};
