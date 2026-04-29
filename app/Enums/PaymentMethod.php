<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash        = 'cash';
    case Card        = 'card';
    case Transfer    = 'transfer';
    case MobileMoney = 'mobile_money';
    case Fedapay     = 'fedapay';
    case Check       = 'check';
    case Refund      = 'refund';

    public function label(): string
    {
        return match($this) {
            self::Cash        => 'Espèces',
            self::Card        => 'Carte bancaire',
            self::Transfer    => 'Virement',
            self::MobileMoney => 'Mobile Money',
            self::Fedapay     => 'FedaPay',
            self::Check       => 'Chèque',
            self::Refund      => 'Remboursement',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Cash        => 'fas fa-money-bill-wave',
            self::Card        => 'fas fa-credit-card',
            self::Transfer    => 'fas fa-university',
            self::MobileMoney => 'fas fa-mobile-alt',
            self::Fedapay     => 'fas fa-globe',
            self::Check       => 'fas fa-money-check',
            self::Refund      => 'fas fa-undo',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Cash        => 'success',
            self::Card        => 'primary',
            self::Transfer    => 'info',
            self::MobileMoney => 'warning',
            self::Fedapay     => 'purple',
            self::Check       => 'secondary',
            self::Refund      => 'danger',
        };
    }

    public function requiresSessionBalance(): bool
    {
        return $this === self::Cash;
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        $result = [];
        foreach (self::cases() as $case) {
            if ($case !== self::Refund) {
                $result[$case->value] = $case->label();
            }
        }
        return $result;
    }
}
