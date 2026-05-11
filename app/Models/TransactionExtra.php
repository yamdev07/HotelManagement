<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'category',
        'description',
        'amount',
        'quantity',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'quantity' => 'integer',
    ];

    const CATEGORY_MINIBAR  = 'minibar';
    const CATEGORY_LAUNDRY  = 'laundry';
    const CATEGORY_SERVICE  = 'service';
    const CATEGORY_OTHER    = 'other';

    public static function getCategories(): array
    {
        return [
            self::CATEGORY_MINIBAR => 'Minibar / Boissons',
            self::CATEGORY_LAUNDRY => 'Lessive / Blanchisserie',
            self::CATEGORY_SERVICE => 'Service (spa, navette…)',
            self::CATEGORY_OTHER   => 'Autre',
        ];
    }

    public static function getCategoryIcons(): array
    {
        return [
            self::CATEGORY_MINIBAR => 'fa-wine-glass-alt',
            self::CATEGORY_LAUNDRY => 'fa-tshirt',
            self::CATEGORY_SERVICE => 'fa-concierge-bell',
            self::CATEGORY_OTHER   => 'fa-box',
        ];
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSubtotalAttribute(): float
    {
        return (float) $this->amount * $this->quantity;
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::getCategories()[$this->category] ?? ucfirst($this->category);
    }

    public function getCategoryIconAttribute(): string
    {
        return self::getCategoryIcons()[$this->category] ?? 'fa-box';
    }
}
