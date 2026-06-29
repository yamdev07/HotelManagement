<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Suivi de l'onboarding (personnalisation initiale du site par l'hôtelier).
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            if (! Schema::hasColumn('hotels', 'onboarding_completed_at')) {
                $table->timestamp('onboarding_completed_at')->nullable();
            }
        });

        // Les hôtels déjà existants sont considérés comme déjà configurés
        if (Schema::hasColumn('hotels', 'onboarding_completed_at')) {
            DB::table('hotels')->whereNull('onboarding_completed_at')->update([
                'onboarding_completed_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            if (Schema::hasColumn('hotels', 'onboarding_completed_at')) {
                $table->dropColumn('onboarding_completed_at');
            }
        });
    }
};
