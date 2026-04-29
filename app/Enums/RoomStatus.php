<?php

namespace App\Enums;

enum RoomStatus: int
{
    case Available   = 1;
    case Occupied    = 2;
    case Maintenance = 3;
    case Reserved    = 4;
    case Cleaning    = 5;
    case Dirty       = 6;

    public function label(): string
    {
        return match($this) {
            self::Available   => 'Disponible',
            self::Occupied    => 'Occupée',
            self::Maintenance => 'Maintenance',
            self::Reserved    => 'Réservée',
            self::Cleaning    => 'En nettoyage',
            self::Dirty       => 'À nettoyer',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Available   => 'success',
            self::Occupied    => 'danger',
            self::Maintenance => 'warning',
            self::Reserved    => 'info',
            self::Cleaning    => 'primary',
            self::Dirty       => 'secondary',
        };
    }

    public function isAvailableForBooking(): bool
    {
        return $this === self::Available;
    }

    public function isBlockingCheckIn(): bool
    {
        return in_array($this, [self::Occupied, self::Maintenance, self::Cleaning, self::Dirty]);
    }
}
