<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHousekeepingColumnsToRoomsTable extends Migration
{
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Colonnes pour le nettoyage
            $table->boolean('needs_cleaning')->default(false)->after('room_status_id');
            $table->boolean('needs_inspection')->default(false)->after('needs_cleaning');

            // Timestamps pour le nettoyage
            $table->timestamp('last_cleaned_at')->nullable()->after('needs_inspection');
            $table->timestamp('cleaning_started_at')->nullable()->after('last_cleaned_at');
            $table->timestamp('cleaning_completed_at')->nullable()->after('cleaning_started_at');

            // Pour qui a effectué le nettoyage
            $table->foreignId('cleaned_by')->nullable()->after('cleaning_completed_at')
                ->constrained('users')->nullOnDelete();
            $table->foreignId('inspected_by')->nullable()->after('cleaned_by')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('inspected_at')->nullable()->after('inspected_by');

            // Pour les inspections
            $table->foreignId('inspection_requested_by')->nullable()->after('inspected_at')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('inspection_requested_at')->nullable()->after('inspection_requested_by');

            // Pour la maintenance
            $table->text('maintenance_reason')->nullable()->after('inspection_requested_at');
            $table->timestamp('maintenance_started_at')->nullable()->after('maintenance_reason');
            $table->timestamp('maintenance_ended_at')->nullable()->after('maintenance_started_at');
            $table->integer('estimated_maintenance_duration')->nullable()->after('maintenance_ended_at'); // en heures
            $table->foreignId('maintenance_requested_by')->nullable()->after('estimated_maintenance_duration')
                ->constrained('users')->nullOnDelete();
            $table->foreignId('maintenance_resolved_by')->nullable()->after('maintenance_requested_by')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn([
                'needs_cleaning',
                'needs_inspection',
                'last_cleaned_at',
                'cleaning_started_at',
                'cleaning_completed_at',
                'cleaned_by',
                'inspected_by',
                'inspected_at',
                'inspection_requested_by',
                'inspection_requested_at',
                'maintenance_reason',
                'maintenance_started_at',
                'maintenance_ended_at',
                'estimated_maintenance_duration',
                'maintenance_requested_by',
                'maintenance_resolved_by',
            ]);
        });
    }
}
