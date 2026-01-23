<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'adults')) {
                $table->dropColumn('adults');
            }
            if (Schema::hasColumn('transactions', 'children')) {
                $table->dropColumn('children');
            }
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // remettre les colonnes si besoin
            if (!Schema::hasColumn('transactions', 'adults')) {
                $table->integer('adults')->default(1);
            }
            if (!Schema::hasColumn('transactions', 'children')) {
                $table->integer('children')->default(0);
            }
        });
    }
};
