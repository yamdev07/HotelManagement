<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case Reservation     = 'reservation';
    case Active          = 'active';
    case Completed       = 'completed';
    case Cancelled       = 'cancelled';
    case NoShow          = 'no_show';
    case PendingCheckout = 'pending_checkout';
    case ReservedWaiting = 'reserved_waiting';

    public function label(): string
    {
        return match($this) {
            self::Reservation     => 'Réservation',
            self::Active          => 'Dans l\'hôtel',
            self::Completed       => 'Séjour terminé',
            self::Cancelled       => 'Annulée',
            self::NoShow          => 'No Show',
            self::PendingCheckout => 'En attente de départ',
            self::ReservedWaiting => 'En attente de chambre',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Reservation     => 'warning',
            self::Active          => 'success',
            self::Completed       => 'info',
            self::Cancelled       => 'danger',
            self::NoShow          => 'secondary',
            self::PendingCheckout => 'primary',
            self::ReservedWaiting => 'dark',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Reservation     => 'fa-calendar-check',
            self::Active          => 'fa-bed',
            self::Completed       => 'fa-check-circle',
            self::Cancelled       => 'fa-times-circle',
            self::NoShow          => 'fa-user-slash',
            self::PendingCheckout => 'fa-clock',
            self::ReservedWaiting => 'fa-hourglass-half',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::Completed, self::Cancelled, self::NoShow]);
    }

    public function canBeCancelled(): bool
    {
        return $this === self::Reservation;
    }

    public function canBeCheckedIn(): bool
    {
        return $this === self::Reservation;
    }

    public function canBeCheckedOut(): bool
    {
        return $this === self::Active;
    }

    public function canBeRestoredTo(): bool
    {
        return in_array($this, [self::Cancelled, self::NoShow]);
    }

    /** @return string[] */
    public static function terminalValues(): array
    {
        return [self::Cancelled->value, self::NoShow->value, self::Completed->value];
    }

    /** Statuts qui bloquent la disponibilité d'une chambre */
    public static function blockingValues(): array
    {
        return [self::Reservation->value, self::Active->value, self::PendingCheckout->value, self::ReservedWaiting->value];
    }
}
