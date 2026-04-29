<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->default('Table');       // "T1", "Table VIP", etc.
            $table->string('type', 30)->default('round');       // round | square | rect | long | bar | chair | plant | wall
            $table->unsignedTinyInteger('seats')->default(2);   // nombre de places
            $table->float('x')->default(10);                    // position left en % du canvas
            $table->float('y')->default(10);                    // position top en % du canvas
            $table->float('w')->default(8);                     // largeur en % du canvas
            $table->float('h')->default(8);                     // hauteur en % du canvas
            $table->smallInteger('rotation')->default(0);       // rotation en degrés
            $table->string('color', 20)->default('#7c5c3e');    // couleur hex
            $table->unsignedSmallInteger('z_order')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_tables');
    }
};
