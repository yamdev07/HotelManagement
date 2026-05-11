<?php

namespace App\Enums;

enum UserRole: string
{
    case Super        = 'Super';
    case Admin        = 'Admin';
    case Receptionist = 'Receptionist';
    case Cashier      = 'Cashier';
    case Housekeeping = 'Housekeeping';
    case Servant      = 'Servant';
    case Cuisiner     = 'Cuisiner';
    case Customer     = 'Customer';

    public function label(): string
    {
        return match($this) {
            self::Super        => 'Super Admin',
            self::Admin        => 'Administrateur',
            self::Receptionist => 'Réceptionniste',
            self::Cashier      => 'Caissier',
            self::Housekeeping => 'Housekeeping',
            self::Servant      => 'Serveur',
            self::Cuisiner     => 'Cuisinier',
            self::Customer     => 'Client',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Super        => 'fas fa-crown',
            self::Admin        => 'fas fa-user-shield',
            self::Receptionist => 'fas fa-concierge-bell',
            self::Cashier      => 'fas fa-cash-register',
            self::Housekeeping => 'fas fa-broom',
            self::Servant      => 'fas fa-utensils',
            self::Cuisiner     => 'fas fa-fire-burner',
            self::Customer     => 'fas fa-user',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Super        => 'danger',
            self::Admin        => 'primary',
            self::Receptionist => 'info',
            self::Cashier      => 'success',
            self::Housekeeping => 'warning',
            self::Servant      => 'info',
            self::Cuisiner     => 'warning',
            self::Customer     => 'secondary',
        };
    }

    public function isStaff(): bool
    {
        return in_array($this, [
            self::Super,
            self::Admin,
            self::Receptionist,
            self::Cashier,
            self::Housekeeping,
            self::Servant,
            self::Cuisiner,
        ]);
    }

    public function canManageReservations(): bool
    {
        return in_array($this, [self::Super, self::Admin, self::Receptionist]);
    }

    public function canProcessPayments(): bool
    {
        return in_array($this, [
            self::Super,
            self::Admin,
            self::Receptionist,
            self::Cashier,
            self::Servant,
            self::Cuisiner,
        ]);
    }

    public function canManageRooms(): bool
    {
        return in_array($this, [self::Super, self::Admin]);
    }

    public function canManageUsers(): bool
    {
        return in_array($this, [self::Super, self::Admin]);
    }

    /** @return self[] */
    public static function staffRoles(): array
    {
        return [
            self::Super,
            self::Admin,
            self::Receptionist,
            self::Cashier,
            self::Housekeeping,
            self::Servant,
            self::Cuisiner,
        ];
    }

    /** @return string[] */
    public static function staffValues(): array
    {
        return array_map(fn(self $r) => $r->value, self::staffRoles());
    }
}
