<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use \App\Models\Concerns\BelongsToHotel;
    protected $fillable = ['name', 'slug'];

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
