<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Ajouter room_type_id comme référence à types
            $table->foreignId('room_type_id')->nullable()->constrained('types')->after('room_id');
            
            // Champs pour l'attribution de chambre
            $table->boolean('is_assigned')->default(false)->after('room_type_id');
            $table->timestamp('assigned_at')->nullable()->after('is_assigned');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->after('assigned_at');
            
            // Index pour optimisation
            $table->index(['room_type_id', 'check_in', 'check_out']);
            $table->index(['is_assigned', 'check_in']);
            $table->index(['status', 'check_in']);
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['room_type_id']);
            $table->dropForeign(['assigned_by']);
            $table->dropIndex(['room_type_id_check_in_check_out']);
            $table->dropIndex(['is_assigned_check_in']);
            $table->dropIndex(['status_check_in']);
            $table->dropColumn(['room_type_id', 'is_assigned', 'assigned_at', 'assigned_by']);
        });
    }
};