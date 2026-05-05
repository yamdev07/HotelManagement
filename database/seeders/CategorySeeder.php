<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Plat',
            'Entrée',
            'Dessert',
            'Boisson',
            'Surgelé',
            'Divers'
        ];
        // Note: slugs will be 'plat', 'entree', etc.

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat)],
                ['name' => $cat]
            );
        }
    }
}
