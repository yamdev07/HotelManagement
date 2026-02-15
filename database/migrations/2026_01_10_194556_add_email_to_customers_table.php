<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Ajoutez la colonne email (NON UNIQUE pour permettre les doublons)
            if (! Schema::hasColumn('customers', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
