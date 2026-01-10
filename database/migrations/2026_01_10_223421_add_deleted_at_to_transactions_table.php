<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Ajouter deleted_at si elle n'existe pas
            if (!Schema::hasColumn('transactions', 'deleted_at')) {
                $table->softDeletes();
            }
            
            // Ajouter aussi cancelled_at si elle n'existe pas
            if (!Schema::hasColumn('transactions', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('status');
            }
            
            // Ajouter cancelled_by si elle n'existe pas
            if (!Schema::hasColumn('transactions', 'cancelled_by')) {
                $table->foreignId('cancelled_by')->nullable()->constrained('users')->after('cancelled_at');
            }
            
            // Ajouter cancel_reason si elle n'existe pas
            if (!Schema::hasColumn('transactions', 'cancel_reason')) {
                $table->text('cancel_reason')->nullable()->after('cancelled_by');
            }
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['cancelled_at', 'cancelled_by', 'cancel_reason']);
        });
    }
};