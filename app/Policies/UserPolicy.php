<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->roleEnum->canManageUsers();
    }

    public function view(User $user, User $target): bool
    {
        return $user->id === $target->id || $user->roleEnum->canManageUsers();
    }

    public function create(User $user): bool
    {
        return $user->roleEnum->canManageUsers();
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
        return in_array($user->roleEnum, [UserRole::Super, UserRole::Admin]);
    }

    public function assignRole(User $user, string $newRole): bool
    {
        if ($newRole === UserRole::Super->value) {
            return $user->roleEnum === UserRole::Super;
        }

        return in_array($user->roleEnum, [UserRole::Super, UserRole::Admin]);
    }
}
