<?php

namespace App\Repositories\Implementation;

use App\Models\Room;
use App\Repositories\Interface\ReservationRepositoryInterface;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function getUnocuppiedroom($request, $occupiedRoomId)
{
    \Log::info('🔍 ========== REPOSITORY DEBUG 11-13 FEV ==========');
    \Log::info('📅 Période:', [
        'check_in' => $request->check_in,
        'check_out' => $request->check_out,
        'count_person' => $request->count_person
    ]);
    
    \Log::info('🚫 Chambres occupées (IDs):', $occupiedRoomId->toArray());
    
    // DEBUG SPÉCIFIQUE CHAMBRE 101
    $room101 = Room::where('number', '101')->first();
    if ($room101) {
        \Log::info('🔍 ANALYSE CHAMBRE 101:', [
            'id' => $room101->id,
            'number' => $room101->number,
            'capacity' => $room101->capacity,
            'room_status_id' => $room101->room_status_id,
            'dans_occupied_list' => $occupiedRoomId->contains($room101->id) ? 'OUI' : 'NON',
            'meets_capacity' => $room101->capacity >= $request->count_person ? 'OUI' : 'NON',
            'status_ok' => in_array($room101->room_status_id, [1, 3]) ? 'OUI' : 'NON'
        ]);
    }
    
    // Construire la requête manuellement pour voir
    $query = Room::with('type', 'roomStatus');
    
    // 1. Capacité
    $query->where('capacity', '>=', $request->count_person);
    \Log::info('✅ Étape 1 - Capacité >= ' . $request->count_person);
    
    // 2. Exclure occupées
    $query->whereNotIn('id', $occupiedRoomId);
    \Log::info('✅ Étape 2 - Exclure IDs: ' . json_encode($occupiedRoomId->toArray()));
    
    // 3. Statut
    $query->whereIn('room_status_id', [1, 3]);
    \Log::info('✅ Étape 3 - Statut IN [1, 3]');
    
    // Trier
    if (! empty($request->sort_name)) {
        $query->orderBy($request->sort_name, $request->sort_type);
        \Log::info('✅ Étape 4 - Tri: ' . $request->sort_name . ' ' . $request->sort_type);
    }
    $query->orderBy('capacity');
    
    // Afficher la requête SQL
    $sql = $query->toSql();
    $bindings = $query->getBindings();
    \Log::info('📝 Requête SQL:', ['sql' => $sql, 'bindings' => $bindings]);
    
    // Exécuter
    $rooms = $query->paginate(5);
    
    \Log::info('✅ Résultats:', [
        'total' => $rooms->total(),
        'count' => $rooms->count(),
        'chambres_trouvees' => $rooms->pluck('number')->toArray(),
        'chambre_101_trouvee' => $rooms->contains('number', '101') ? 'OUI' : 'NON'
    ]);
    
    // Afficher TOUTES les chambres pour comparaison
    $allRooms = Room::orderBy('number')->get();
    \Log::info('🏨 TOUTES LES CHAMBRES (' . $allRooms->count() . '):');
    foreach ($allRooms as $room) {
        \Log::info("   {$room->number}: Cap={$room->capacity}, Statut={$room->room_status_id}, Occupée=" . 
                  ($occupiedRoomId->contains($room->id) ? 'OUI' : 'NON'));
    }
    
    \Log::info('🔍 ========== FIN DEBUG ==========');
    
    return $rooms;
}
    public function countUnocuppiedroom($request, $occupiedRoomId)
    {
        return Room::with('type', 'roomStatus')
            ->where('capacity', '>=', $request->count_person)
            ->whereNotIn('id', $occupiedRoomId)
            ->orderBy('price')
            ->orderBy('capacity')
            ->count();
    }
}
