<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // ⚠️ Ne PAS recréer l'id
            // $table->id(); // supprimé

            // Relations
            if (!Schema::hasColumn('payments', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('payments', 'transaction_id')) {
                $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            }

            // Informations de base
            if (!Schema::hasColumn('payments', 'amount')) {
                $table->decimal('amount', 12, 2);
            }

            // Méthode de paiement
            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->enum('payment_method', [
                    'cash',
                    'card',
                    'transfer',
                    'mobile_money',
                    'fedapay',
                    'check',
                    'refund'
                ])->default('cash');
            }

            if (!Schema::hasColumn('payments', 'payment_method_details')) {
                $table->json('payment_method_details')->nullable();
            }

            // Statut
            if (!Schema::hasColumn('payments', 'status')) {
                $table->enum('status', [
                    'pending',
                    'completed',
                    'cancelled',
                    'expired',
                    'failed',
                    'refunded'
                ])->default('completed');
            }

            // Référence
            if (!Schema::hasColumn('payments', 'reference')) {
                $table->string('reference')->unique();
            }

            // Champs spécifiques selon la méthode
            if (!Schema::hasColumn('payments', 'check_number')) {
                $table->string('check_number')->nullable();
            }
            if (!Schema::hasColumn('payments', 'card_last_four')) {
                $table->string('card_last_four', 4)->nullable();
            }
            if (!Schema::hasColumn('payments', 'card_type')) {
                $table->string('card_type', 20)->nullable();
            }
            if (!Schema::hasColumn('payments', 'mobile_money_provider')) {
                $table->string('mobile_money_provider', 50)->nullable();
            }
            if (!Schema::hasColumn('payments', 'mobile_money_number')) {
                $table->string('mobile_money_number', 20)->nullable();
            }
            if (!Schema::hasColumn('payments', 'bank_name')) {
                $table->string('bank_name', 100)->nullable();
            }
            if (!Schema::hasColumn('payments', 'account_number')) {
                $table->string('account_number', 50)->nullable();
            }

            // Notes
            if (!Schema::hasColumn('payments', 'notes')) {
                $table->text('notes')->nullable();
            }

            // Annulation
            if (!Schema::hasColumn('payments', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable();
            }
            if (!Schema::hasColumn('payments', 'cancelled_by')) {
                $table->foreignId('cancelled_by')->nullable()->constrained('users');
            }
            if (!Schema::hasColumn('payments', 'cancel_reason')) {
                $table->text('cancel_reason')->nullable();
            }

            // Timestamps et soft deletes
            if (!Schema::hasColumn('payments', 'created_at')) {
                $table->timestamps();
            }
            if (!Schema::hasColumn('payments', 'deleted_at')) {
                $table->softDeletes();
            }

            // Index
            $table->index('reference');
            $table->index('payment_method');
            $table->index('status');
            $table->index(['transaction_id', 'status']);
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // ⚠️ Ne PAS supprimer la table entière
            // Vous pouvez laisser vide ou supprimer uniquement les colonnes ajoutées si nécessaire
        });
    }
};
