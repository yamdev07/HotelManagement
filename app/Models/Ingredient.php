<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['name', 'unit', 'quantity_in_stock', 'min_stock', 'price_per_unit'];

    protected $casts = [
        'quantity_in_stock' => 'decimal:2',
        'min_stock'         => 'decimal:2',
        'price_per_unit'    => 'decimal:2',
    ];

    public function isLowStock(): bool
    {
        return $this->quantity_in_stock <= $this->min_stock;
    }

    public function stockStatus(): string
    {
        if ($this->quantity_in_stock <= 0) return 'out';
        if ($this->isLowStock()) return 'low';
        return 'ok';
    }
}
