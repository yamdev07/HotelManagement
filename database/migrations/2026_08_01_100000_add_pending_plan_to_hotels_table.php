<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Changement de formule programmé (downgrade en fin de cycle) :
 *  - pending_plan              : palier vers lequel basculer
 *  - pending_plan_effective_at : date d'effet (fin du cycle payé actuel)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            if (! Schema::hasColumn('hotels', 'pending_plan')) {
                $table->string('pending_plan', 40)->nullable()->after('plan');
            }
            if (! Schema::hasColumn('hotels', 'pending_plan_effective_at')) {
                $table->timestamp('pending_plan_effective_at')->nullable()->after('pending_plan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            foreach (['pending_plan', 'pending_plan_effective_at'] as $col) {
                if (Schema::hasColumn('hotels', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
