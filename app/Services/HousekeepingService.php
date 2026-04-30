<?php

namespace App\Services;

use App\Enums\RoomStatus;
use App\Models\Room;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class HousekeepingService
{
    public function autoMarkDirtyRooms(): int
    {
        try {
            $updated = Room::where('room_status_id', RoomStatus::Occupied->value)
                ->where(function ($q) {
                    $q->whereNull('last_cleaned_at')
                        ->orWhereDate('last_cleaned_at', '<', Carbon::today());
                })
                ->get();

            foreach ($updated as $room) {
                $room->update([
                    'room_status_id' => RoomStatus::Dirty->value,
                    'needs_cleaning' => 1,
                ]);
                Log::info("Auto-marked room {$room->number} as DIRTY");
            }

            return $updated->count();
        } catch (\Throwable $e) {
            Log::error('autoMarkDirtyRooms: '.$e->getMessage());
            return 0;
        }
    }

    public function isRoomOccupied(int $roomId): bool
    {
        return Transaction::where('room_id', $roomId)
            ->whereIn('status', ['active', 'checked_in'])
            ->where('check_in', '<=', now())
            ->where('check_out', '>=', now())
            ->exists();
    }

    public function startCleaning(Room $room, int $userId): void
    {
        DB::transaction(function () use ($room, $userId) {
            $data = ['room_status_id' => RoomStatus::Cleaning->value];

            if (Schema::hasColumn('rooms', 'cleaning_started_at')) {
                $data['cleaning_started_at'] = now();
            }
            if (Schema::hasColumn('rooms', 'cleaned_by')) {
                $data['cleaned_by'] = $userId;
            }

            $room->update($data);
        });
    }

    public function finishCleaning(Room $room, int $userId): RoomStatus
    {
        $newStatus = $this->isRoomOccupied($room->id) ? RoomStatus::Occupied : RoomStatus::Available;

        DB::transaction(function () use ($room, $userId, $newStatus) {
            $data = [
                'room_status_id' => $newStatus->value,
                'needs_cleaning' => 0,
            ];

            if (Schema::hasColumn('rooms', 'cleaning_completed_at')) {
                $data['cleaning_completed_at'] = now();
            }
            if (Schema::hasColumn('rooms', 'cleaned_by')) {
                $data['cleaned_by'] = $userId;
            }
            if (Schema::hasColumn('rooms', 'last_cleaned_at')) {
                $data['last_cleaned_at'] = now();
            }

            $room->update($data);
        });

        return $newStatus;
    }

    public function getRoomsByStatus(): array
    {
        $rooms = Room::with(['type', 'roomStatus'])->orderBy('number')->get();

        foreach ($rooms as $room) {
            $room->is_occupied = $this->isRoomOccupied($room->id);
        }

        return [
            'dirty'       => $rooms->where('room_status_id', RoomStatus::Dirty->value)->values(),
            'cleaning'    => $rooms->where('room_status_id', RoomStatus::Cleaning->value)->values(),
            'clean'       => $rooms->where('room_status_id', RoomStatus::Available->value)
                                ->filter(fn ($r) => ! $r->is_occupied)->values(),
            'occupied'    => $rooms->filter(fn ($r) => $r->is_occupied || $r->room_status_id == RoomStatus::Occupied->value)
                                ->unique('id')->values(),
            'maintenance' => $rooms->where('room_status_id', RoomStatus::Maintenance->value)->values(),
            'reserved'    => $rooms->where('room_status_id', RoomStatus::Reserved->value)->values(),
        ];
    }

    public function getStats(array $roomsByStatus): array
    {
        $allRooms = Room::count();
        return [
            'total_rooms'       => $allRooms,
            'dirty_rooms'       => $roomsByStatus['dirty']->count(),
            'cleaning_rooms'    => $roomsByStatus['cleaning']->count(),
            'clean_rooms'       => $roomsByStatus['clean']->count(),
            'occupied_rooms'    => $roomsByStatus['occupied']->count(),
            'maintenance_rooms' => $roomsByStatus['maintenance']->count(),
            'reserved_rooms'    => $roomsByStatus['reserved']->count(),
            'cleaned_today'     => Room::whereDate('last_cleaned_at', Carbon::today())->count(),
        ];
    }

    public function getTodayDepartures()
    {
        return Transaction::with(['room', 'customer'])
            ->whereIn('status', ['active', 'checked_in'])
            ->whereDate('check_out', Carbon::today())
            ->orderBy('check_out')
            ->get();
    }

    public function getTodayArrivals()
    {
        return Transaction::with(['room', 'customer'])
            ->whereIn('status', ['reservation', 'confirmed'])
            ->whereDate('check_in', Carbon::today())
            ->orderBy('check_in')
            ->get();
    }

    public function statusIdFromSlug(string $slug): ?int
    {
        $map = [
            'dirty'       => RoomStatus::Dirty->value,
            'cleaning'    => RoomStatus::Cleaning->value,
            'clean'       => RoomStatus::Available->value,
            'occupied'    => RoomStatus::Occupied->value,
            'maintenance' => RoomStatus::Maintenance->value,
            'reserved'    => RoomStatus::Reserved->value,
        ];

        return $map[$slug] ?? null;
    }
}
