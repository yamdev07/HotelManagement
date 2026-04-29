<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return UserRole::from($user->role)->canManageUsers();
    }

    public function view(User $user, User $target): bool
    {
        if ($user->id === $target->id) {
            return true;
        }

        return UserRole::from($user->role)->canManageUsers();
    }

    public function create(User $user): bool
    {
        return UserRole::from($user->role)->canManageUsers();
    }

    public function update(User $user, User $target): bool
    {
        return $user->canEditUser($target);
    }

    public function delete(User $user, User $target): bool
    {
        return $user->canDeleteUser($target);
    }

    public function resetPassword(User $user, User $target): bool
    {
        return in_array($user->role, [UserRole::Super->value, UserRole::Admin->value]);
    }

    public function assignRole(User $user, string $newRole): bool
    {
        // Seul Super peut créer des Supers
        if ($newRole === UserRole::Super->value) {
            return $user->role === UserRole::Super->value;
        }

        return in_array($user->role, [UserRole::Super->value, UserRole::Admin->value]);
    }
}
