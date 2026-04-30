<?php

namespace App\Exceptions;

class TransactionException extends HotelException
{
    public static function cannotCancel(string $reason): self
    {
        return new self("Annulation impossible : {$reason}");
    }

    public static function cannotModify(string $reason): self
    {
        return new self("Modification impossible : {$reason}");
    }

    public static function cannotCheckIn(string $reason): self
    {
        return new self("Check-in impossible : {$reason}");
    }

    public static function cannotCheckOut(string $reason): self
    {
        return new self("Check-out impossible : {$reason}");
    }

    public static function roomUnavailable(): self
    {
        return new self('La chambre sélectionnée n\'est pas disponible pour ces dates.');
    }

    public static function alreadyFinal(string $status): self
    {
        return new self("La réservation est déjà dans l'état final : {$status}.");
    }

    public static function notFound(int $id): self
    {
        return new self("Réservation #{$id} introuvable.", 404);
    }

    public function httpStatusCode(): int
    {
        return $this->getCode() === 404 ? 404 : 422;
    }
}
