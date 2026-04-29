<?php

namespace App\Exceptions;

class ReservationException extends HotelException
{
    public static function roomConflict(string $roomNumber, string $checkIn, string $checkOut): self
    {
        return new self("La chambre {$roomNumber} est déjà réservée du {$checkIn} au {$checkOut}.");
    }

    public static function pastCheckIn(): self
    {
        return new self('La date d\'arrivée ne peut pas être dans le passé.');
    }

    public static function checkOutBeforeCheckIn(): self
    {
        return new self('La date de départ doit être après la date d\'arrivée.');
    }

    public static function customerNotFound(int $customerId): self
    {
        return new self("Client #{$customerId} introuvable.", 404);
    }

    public static function tooEarlyForCheckIn(int $hours, int $minutes): self
    {
        return new self("Check-in possible à partir de 12h. Encore {$hours}h{$minutes}min.");
    }

    public static function wrongDayForCheckIn(string $expectedDate): self
    {
        return new self("L'arrivée ne peut être enregistrée que le {$expectedDate}.");
    }

    public static function wrongDayForCheckOut(string $expectedDate): self
    {
        return new self("Le départ ne peut être enregistré que le {$expectedDate}.");
    }

    public static function lateCheckoutGracePeriodExpired(): self
    {
        return new self('La largesse de 2h est dépassée (après 14h). Prolongez le séjour d\'une nuit.');
    }
}
