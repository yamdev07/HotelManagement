<?php

namespace App\Exceptions;

class PaymentException extends HotelException
{
    public static function noActiveSession(): self
    {
        return new self('Aucune session de caisse active. Veuillez démarrer une session.');
    }

    public static function amountExceedsBalance(float $remaining): self
    {
        $formatted = number_format($remaining, 0, ',', ' ');
        return new self("Le montant dépasse le solde restant de {$formatted} CFA.");
    }

    public static function transactionAlreadyPaid(): self
    {
        return new self('Cette transaction est déjà entièrement payée.');
    }

    public static function cannotCancelCompletedPayment(): self
    {
        return new self('Un paiement complété ne peut être annulé que par un administrateur.');
    }

    public static function insufficientBalance(float $required, float $available): self
    {
        $req = number_format($required, 0, ',', ' ');
        $avail = number_format($available, 0, ',', ' ');
        return new self("Solde insuffisant. Requis : {$req} CFA, disponible : {$avail} CFA.");
    }

    public static function invalidPaymentMethod(string $method): self
    {
        return new self("Méthode de paiement invalide : {$method}.");
    }
}
