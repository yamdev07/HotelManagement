<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Room;
use App\Models\User;

class RoomPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Listing accessible à tous les staff + frontend
    }

    public function view(User $user, Room $room): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return UserRole::from($user->role)->canManageRooms();
    }

    public function update(User $user, Room $room): bool
    {
        return UserRole::from($user->role)->canManageRooms();
    }

    public function delete(User $user, Room $room): bool
    {
        return $user->role === UserRole::Super->value;
    }

    public function updateStatus(User $user): bool
    {
        return UserRole::from($user->role)->isStaff();
    }
}
