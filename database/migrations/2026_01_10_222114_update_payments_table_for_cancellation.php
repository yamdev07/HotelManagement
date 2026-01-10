<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Renommer price en amount si nécessaire
            if (Schema::hasColumn('payments', 'price')) {
                $table->renameColumn('price', 'amount');
            }
            
            // Ajouter les nouveaux champs
            $table->string('payment_method')->nullable()->after('amount');
            $table->text('notes')->nullable()->after('payment_method');
            $table->string('reference')->nullable()->after('notes');
            $table->string('status')->default('pending')->change();
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('cancel_reason')->nullable();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Re-renommer amount en price
            if (Schema::hasColumn('payments', 'amount')) {
                $table->renameColumn('amount', 'price');
            }
            
            // Supprimer les champs
            $table->dropColumn(['payment_method', 'notes', 'reference', 'cancelled_at', 'cancelled_by', 'cancel_reason']);
            $table->dropSoftDeletes();
        });
    }
};