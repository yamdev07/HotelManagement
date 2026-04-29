<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            [
                'name' => 'Poulet Yassa',
                'category' => 'plat',
                'price' => 8500,
                'description' => 'Poulet mariné au citron et oignons, servi avec du riz blanc.',
                'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&q=80&w=800',
                'is_african' => true,
            ],
            [
                'name' => 'Tiep Bou Dien',
                'category' => 'plat',
                'price' => 9000,
                'description' => 'Le fameux riz au poisson sénégalais avec ses légumes mignons.',
                'image' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&q=80&w=800',
                'is_african' => true,
            ],
            [
                'name' => 'Ndolé au Bœuf',
                'category' => 'plat',
                'price' => 10500,
                'description' => 'Plat traditionnel camerounais aux feuilles de ndolé, arachides et viande de bœuf.',
                'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&q=80&w=800',
                'is_african' => true,
            ],
            [
                'name' => 'Maafe Poulet',
                'category' => 'plat',
                'price' => 8000,
                'description' => 'Poulet mijoté dans une onctueuse sauce à la pâte d\'arachide.',
                'image' => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&q=80&w=800',
                'is_african' => true,
            ],
            [
                'name' => 'Alloco Poisson Braisé',
                'category' => 'plat',
                'price' => 12000,
                'description' => 'Carpe braisée servie avec des bananes plantains frites (alloco).',
                'image' => 'https://images.unsplash.com/photo-1534080564583-6be75777b70a?auto=format&fit=crop&q=80&w=800',
                'is_african' => true,
            ],
            [
                'name' => 'Burger Classique Burger',
                'category' => 'plat',
                'price' => 6500,
                'description' => 'Steak haché pur bœuf, cheddar, salade, tomate, oignons, accompagné de frites maison.',
                'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&q=80&w=800',
                'is_african' => false,
            ],
            [
                'name' => 'Pizza Margherita',
                'category' => 'plat',
                'price' => 7000,
                'description' => 'Sauce tomate, mozzarella fior di latte, basilic frais, huile d\'olive extra vierge.',
                'image' => 'https://images.unsplash.com/photo-1604382355076-af4b0eb60143?auto=format&fit=crop&q=80&w=800',
                'is_african' => false,
            ],
            [
                'name' => 'Steak Frites',
                'category' => 'plat',
                'price' => 15000,
                'description' => 'Entrecôte grillée de 250g, beurre maître d\'hôtel et frites croustillantes.',
                'image' => 'https://images.unsplash.com/photo-1600891964092-4316c288032e?auto=format&fit=crop&q=80&w=800',
                'is_african' => false,
            ],
            [
                'name' => 'Spaghetti Carbonara',
                'category' => 'plat',
                'price' => 8500,
                'description' => 'Véritable recette italienne avec pancetta, pecorino, jaune d\'œuf et poivre noir.',
                'image' => 'https://images.unsplash.com/photo-1612874742237-6526221588e3?auto=format&fit=crop&q=80&w=800',
                'is_african' => false,
            ],
            [
                'name' => 'Salade César',
                'category' => 'plat',
                'price' => 6000,
                'description' => 'Laitue romaine, croûtons, parmesan, poulet grillé et sauce César authentique.',
                'image' => 'https://images.unsplash.com/photo-1550304943-4f24f54ddde9?auto=format&fit=crop&q=80&w=800',
                'is_african' => false,
            ],
            [
                'name' => 'Tiramisu',
                'category' => 'dessert',
                'price' => 4500,
                'description' => 'Dessert onctueux au mascarpone et café.',
                'image' => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?auto=format&fit=crop&q=80&w=800',
                'is_african' => false,
            ],
            [
                'name' => 'Fondant au Chocolat',
                'category' => 'dessert',
                'price' => 5000,
                'description' => 'Cœur coulant au chocolat noir, accompagné d\'une boule de glace vanille.',
                'image' => 'https://images.unsplash.com/photo-1624353365286-3f8d62daad51?auto=format&fit=crop&q=80&w=800',
                'is_african' => false,
            ],
            [
                'name' => 'Mousse au Chocolat',
                'category' => 'dessert',
                'price' => 4000,
                'description' => 'Mousse très aérée au chocolat de couverture.',
                'image' => 'https://images.unsplash.com/photo-1511018556340-d16986a1c194?auto=format&fit=crop&q=80&w=800',
                'is_african' => false,
            ],
            [
                'name' => 'Dégué',
                'category' => 'dessert',
                'price' => 3000,
                'description' => 'Dessert lacté à base de yaourt et de semoule de mil.',
                'image' => 'https://images.unsplash.com/photo-1621583441131-c8c190794171?auto=format&fit=crop&q=80&w=800',
                'is_african' => true,
            ],
            [
                'name' => 'Crêpes au Sucre',
                'category' => 'dessert',
                'price' => 2500,
                'description' => 'Délicieuses crêpes fines, simplement saupoudrées de sucre.',
                'image' => 'https://images.unsplash.com/photo-1563636619-e9143da7973b?auto=format&fit=crop&q=80&w=800',
                'is_african' => false,
            ],
            [
                'name' => 'Jus de Bissap',
                'category' => 'boisson',
                'price' => 1500,
                'description' => 'Décoction rafraîchissante de fleurs d\'hibiscus, parfumée à la menthe.',
                'image' => 'https://images.unsplash.com/photo-1497534446932-c925b458314e?auto=format&fit=crop&q=80&w=800',
                'is_african' => true,
            ],
            [
                'name' => 'Jus de Gingembre',
                'category' => 'boisson',
                'price' => 1500,
                'description' => 'Cocktail revigorant au gingembre frais et citron.',
                'image' => 'https://images.unsplash.com/photo-1600271886742-f049cd451b66?auto=format&fit=crop&q=80&w=800',
                'is_african' => true,
            ],
            [
                'name' => 'Mojito Virgin',
                'category' => 'boisson',
                'price' => 3500,
                'description' => 'Cocktail sans alcool au citron vert, menthe fraîche et eau gazeuse.',
                'image' => 'https://images.unsplash.com/photo-1551538827-9c037cb4f32a?auto=format&fit=crop&q=80&w=800',
                'is_african' => false,
            ],
            [
                'name' => 'Coca-Cola',
                'category' => 'boisson',
                'price' => 1000,
                'description' => 'Bouteille en verre 33cl bien fraîche.',
                'image' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?auto=format&fit=crop&q=80&w=800',
                'is_african' => false,
            ],
            [
                'name' => 'Café Espresso',
                'category' => 'boisson',
                'price' => 1000,
                'description' => 'Un classique 100% Arabica pour une pause revigorante.',
                'image' => 'https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&q=80&w=800',
                'is_african' => false,
            ]
        ];

        foreach ($menus as $menuData) {
            Menu::updateOrCreate(
                ['name' => $menuData['name']],
                $menuData
            );
        }
    }
}
