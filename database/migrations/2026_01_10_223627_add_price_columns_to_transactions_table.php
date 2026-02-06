<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'total_price')) {
                $table->decimal('total_price', 10, 2)->nullable()->after('check_out');
            }

            if (! Schema::hasColumn('transactions', 'total_payment')) {
                $table->decimal('total_payment', 10, 2)->nullable()->after('total_price');
            }

            if (! Schema::hasColumn('transactions', 'person_count')) {
                $table->integer('person_count')->nullable()->after('total_payment');
            }
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['total_price', 'total_payment', 'person_count']);
        });
    }
};
