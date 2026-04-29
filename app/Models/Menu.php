<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'category', 'price', 'description', 'image', 'is_african'];

    protected $casts = ['is_african' => 'boolean'];

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return '';
        if (str_starts_with($this->image, 'http')) return $this->image;
        return asset('storage/' . $this->image);
    }
}
