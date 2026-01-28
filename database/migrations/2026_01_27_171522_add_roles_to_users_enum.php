<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Ajoute les nouveaux rôles à l'ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('Super','Admin','Customer','Receptionist','Cashier','Manager') NULL");
        
        // Optionnel : Mettre 'Customer' comme valeur par défaut
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('Super','Admin','Customer','Receptionist','Cashier','Manager') DEFAULT 'Customer'");
    }

    public function down()
    {
        // Retour aux valeurs originales
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('Super','Admin','Customer') DEFAULT NULL");
    }
};