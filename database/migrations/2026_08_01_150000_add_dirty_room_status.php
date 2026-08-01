<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * L'enum App\Enums\RoomStatus définit Dirty = 6, et le code écrit ce statut
 * après chaque check-out / early-checkout (chambre à nettoyer). Or la table
 * room_statuses n'était peuplée que de 5 lignes (id 1..5) : la ligne id 6
 * « Dirty » manquait -> violation de clé étrangère au moment du départ,
 * empêchant le check-out. On l'ajoute (idempotent).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! DB::table('room_statuses')->where('id', 6)->exists()) {
            DB::table('room_statuses')->insert([
                'id' => 6,
                'name' => 'Dirty',
                'code' => 'DRT',
                'information' => 'Room needs cleaning after checkout',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('room_statuses')->where('id', 6)->where('code', 'DRT')->delete();
    }
};
