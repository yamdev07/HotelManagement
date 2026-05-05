<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'category_id', 'price', 'description', 'image', 'available_days', 'is_available'];

    protected $casts = [
        'is_available' => 'boolean',
        'available_days' => 'array',
        'category_id' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return '';
        if (str_starts_with($this->image, 'http')) return $this->image;
        return asset('storage/' . $this->image);
    }
}
