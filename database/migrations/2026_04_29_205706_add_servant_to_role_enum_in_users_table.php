<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('Super','Admin','Customer','Receptionist','Cashier','Manager','Housekeeping','Servant')");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('Super','Admin','Customer','Receptionist','Cashier','Manager','Housekeeping')");
    }
};
