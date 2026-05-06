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
        $default = 'https://i.pinimg.com/736x/fc/7a/4a/fc7a4ad5e3299c1dac28baa60eef6111.jpg';
        
        if (!$this->image) return $default;
        
        if (str_starts_with($this->image, 'http')) return $this->image;
        
        $path = storage_path('app/public/' . $this->image);
        if (!file_exists($path)) return $default;

        return asset('storage/' . $this->image);
    }
}
