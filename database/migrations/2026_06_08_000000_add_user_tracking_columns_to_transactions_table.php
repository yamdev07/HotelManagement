<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ajoute le suivi des utilisateurs sur les transactions :
     * qui a créé la transaction, qui a fait le check-in et le check-out
     * (notamment pour le check-in direct).
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('status')
                    ->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('transactions', 'checked_in_by')) {
                $table->foreignId('checked_in_by')->nullable()->after('created_by')
                    ->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('transactions', 'checked_out_by')) {
                $table->foreignId('checked_out_by')->nullable()->after('checked_in_by')
                    ->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            foreach (['created_by', 'checked_in_by', 'checked_out_by'] as $column) {
                if (Schema::hasColumn('transactions', $column)) {
                    $table->dropConstrainedForeignId($column);
                }
            }
        });
    }
};
