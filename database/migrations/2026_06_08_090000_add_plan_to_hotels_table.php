<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Abonnement choisi par l'hôtel (palier + limite de chambres).
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            if (! Schema::hasColumn('hotels', 'plan')) {
                $table->string('plan')->default('starter')->after('subscription_ends_at');
            }
            if (! Schema::hasColumn('hotels', 'room_limit')) {
                $table->unsignedInteger('room_limit')->nullable()->after('plan');
            }
        });
    }

    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            foreach (['plan', 'room_limit'] as $col) {
                if (Schema::hasColumn('hotels', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
