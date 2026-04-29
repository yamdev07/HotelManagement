<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    protected $fillable = [
        'name', 'type', 'seats',
        'x', 'y', 'w', 'h',
        'rotation', 'color', 'z_order',
    ];

    protected $casts = [
        'x'        => 'float',
        'y'        => 'float',
        'w'        => 'float',
        'h'        => 'float',
        'seats'    => 'integer',
        'rotation' => 'integer',
        'z_order'  => 'integer',
    ];

    /** Types d'éléments disponibles dans l'éditeur */
    const TYPES = [
        'round'   => ['label' => 'Table ronde',         'icon' => '●'],
        'square'  => ['label' => 'Table carrée',        'icon' => '■'],
        'rect'    => ['label' => 'Table rectangle',     'icon' => '▬'],
        'long'    => ['label' => 'Grande table',        'icon' => '▬'],
        'bar'     => ['label' => 'Bar / Comptoir',      'icon' => '━'],
        'chair'   => ['label' => 'Chaise',              'icon' => '●'],
        'plant'   => ['label' => 'Plante / Déco',       'icon' => '🌿'],
        'wall'    => ['label' => 'Cloison / Mur',       'icon' => '▮'],
    ];
}
