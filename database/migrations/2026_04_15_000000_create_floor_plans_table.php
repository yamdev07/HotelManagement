<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        // Plans de salle (layout JSON par salle)
        Schema::create('floor_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->json('layout');           // tableau JSON des éléments positionnés
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
 
            $table->unique('room_id');        // un seul plan par salle
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('floor_plans');
    }
};
