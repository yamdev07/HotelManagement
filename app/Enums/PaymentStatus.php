<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending           = 'pending';
    case Completed         = 'completed';
    case Cancelled         = 'cancelled';
    case Expired           = 'expired';
    case Failed            = 'failed';
    case Refunded          = 'refunded';
    case Processing        = 'processing';
    case PartiallyRefunded = 'partially_refunded';

    public function label(): string
    {
        return match($this) {
            self::Pending           => 'En attente',
            self::Completed         => 'Complété',
            self::Cancelled         => 'Annulé',
            self::Expired           => 'Expiré',
            self::Failed            => 'Échoué',
            self::Refunded          => 'Remboursé',
            self::Processing        => 'En cours',
            self::PartiallyRefunded => 'Partiellement remboursé',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending           => 'warning',
            self::Completed         => 'success',
            self::Cancelled         => 'danger',
            self::Expired           => 'secondary',
            self::Failed            => 'danger',
            self::Refunded          => 'info',
            self::Processing        => 'primary',
            self::PartiallyRefunded => 'warning',
        };
    }

    public function canBeCancelled(): bool
    {
        return in_array($this, [self::Pending, self::Completed]);
    }

    public function countsAsRevenue(): bool
    {
        return $this === self::Completed;
    }
}
