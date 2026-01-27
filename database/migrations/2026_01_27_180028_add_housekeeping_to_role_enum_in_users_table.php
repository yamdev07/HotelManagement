<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // Dans la migration créée
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('Super','Admin','Customer','Receptionist','Cashier','Manager','Housekeeping')");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('Super','Admin','Customer','Receptionist','Cashier','Manager')");
    }
};
