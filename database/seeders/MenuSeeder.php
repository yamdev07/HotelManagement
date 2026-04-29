<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('menus')->truncate();

        $menus = [
            // ── ENTRÉES CLASSIQUES ────────────────────────────────────────
            [
                'name'        => 'Salade César Royale',
                'category'    => 'entree',
                'price'       => 3500,
                'description' => 'Laitue romaine croquante, parmesan affiné, croûtons dorés, sauce César maison et anchois de Méditerranée.',
                'image_url'   => 'https://images.unsplash.com/photo-1551248429-40975aa4de74?w=600&q=80',
                'image_file'  => 'salade-cesar.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Carpaccio de Bœuf',
                'category'    => 'entree',
                'price'       => 4500,
                'description' => 'Fines tranches de bœuf Angus, roquette sauvage, copeaux de parmesan, huile d\'olive extra vierge et câpres.',
                'image_url'   => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&q=80',
                'image_file'  => 'carpaccio-boeuf.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Velouté de Carottes au Gingembre',
                'category'    => 'entree',
                'price'       => 2800,
                'description' => 'Crème onctueuse de carottes bio, gingembre frais, touche de crème fraîche et huile de coriandre.',
                'image_url'   => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=600&q=80',
                'image_file'  => 'veloute-carottes.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Gambas Flambées au Cognac',
                'category'    => 'entree',
                'price'       => 5500,
                'description' => 'Gambas royales flambées au cognac, beurre à l\'ail, persil plat et pain de campagne grillé.',
                'image_url'   => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80',
                'image_file'  => 'gambas-cognac.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Foie Gras Maison',
                'category'    => 'entree',
                'price'       => 6500,
                'description' => 'Terrine de foie gras de canard mi-cuit, confiture de figues, brioche toastée et fleur de sel.',
                'image_url'   => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&q=80',
                'image_file'  => 'foie-gras.jpg',
                'is_african'  => false,
            ],

            // ── ENTRÉES AFRICAINES ────────────────────────────────────────
            [
                'name'        => 'Accara — Beignets de Niébé',
                'category'    => 'entree',
                'price'       => 2000,
                'description' => 'Beignets croustillants de haricots niébé trempés et frits à l\'huile dorée, servis avec sauce piment doux et oignons marinés.',
                'image_url'   => 'https://i.pinimg.com/736x/15/d9/be/15d9be51a5b1128dd8f8ac2da9f27feb.jpg',
                'image_file'  => 'accara-beignets.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Samosa Africain Maison',
                'category'    => 'entree',
                'price'       => 2500,
                'description' => 'Chaussons feuilletés farcis de viande hachée épicée, oignons, persil et piment doux, frits jusqu\'à la perfection.',
                'image_url'   => 'https://i.pinimg.com/736x/02/d7/79/02d7791317b1a3f066ce3f459f06ee93.jpg',
                'image_file'  => 'samosa-africain.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Salade de Papaye Verte Épicée',
                'category'    => 'entree',
                'price'       => 2200,
                'description' => 'Papaye verte râpée, carottes, arachides grillées, sauce citron-piment, herbes fraîches et crevettes séchées.',
                'image_url'   => 'https://i.pinimg.com/736x/46/9c/fd/469cfdde5f78d80c7a9821b047d0ec68.jpg',
                'image_file'  => 'salade-papaye.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Boulettes d\'Igname Frites',
                'category'    => 'entree',
                'price'       => 1800,
                'description' => 'Igname pilée façonnée en boulettes dorées, frites à l\'huile, trempées dans une sauce tomate-piment maison.',
                'image_url'   => 'https://i.pinimg.com/736x/2a/1e/9a/2a1e9af06e677e089ac193130253f1fd.jpg',
                'image_file'  => 'boulettes-igname.jpg',
                'is_african'  => true,
            ],

            // ── PLATS CLASSIQUES ──────────────────────────────────────────
            [
                'name'        => 'Crevettes à la Créole',
                'category'    => 'plat',
                'price'       => 8500,
                'description' => 'Crevettes tigrées sautées dans une sauce créole parfumée, riz au lait de coco et plantain frit.',
                'image_url'   => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=600&q=80',
                'image_file'  => 'crevettes-creole.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Filet de Bar Grillé',
                'category'    => 'plat',
                'price'       => 9500,
                'description' => 'Bar de l\'Atlantique grillé à la plancha, légumes de saison, sauce vierge au basilic et citron confit.',
                'image_url'   => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=600&q=80',
                'image_file'  => 'bar-grille.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Côte de Bœuf Maturée 400g',
                'category'    => 'plat',
                'price'       => 15000,
                'description' => 'Côte de bœuf Black Angus maturée 28 jours, frites maison croustillantes, sauce béarnaise et salade verte.',
                'image_url'   => 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&q=80',
                'image_file'  => 'cote-boeuf.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Poulet Braisé Cactus Palace',
                'category'    => 'plat',
                'price'       => 5000,
                'description' => 'Poulet fermier braisé aux épices maison, plantain frit doré, salade fraîche et sauce piment doux.',
                'image_url'   => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?w=600&q=80',
                'image_file'  => 'poulet-braise.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Brochettes de Bœuf Marinées',
                'category'    => 'plat',
                'price'       => 7000,
                'description' => 'Brochettes de bœuf marinées aux herbes du jardin, grillées au charbon de bois, accompagnées de légumes grillés et de riz.',
                'image_url'   => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=600&q=80',
                'image_file'  => 'brochettes-boeuf.jpg',
                'is_african'  => false,
            ],

            // ── PLATS AFRICAINS ───────────────────────────────────────────
            [
                'name'        => 'Thiéboudienne Royal du Chef',
                'category'    => 'plat',
                'price'       => 6500,
                'description' => 'Riz parfumé au poisson frais, légumes du jardin mijotés, sauce tomate maison — fierté de notre cuisine sénégalaise.',
                'image_url'   => 'https://i.pinimg.com/736x/f9/c9/e9/f9c9e9522e4ac37f343f198d945ff974.jpg',
                'image_file'  => 'thieboudienne.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Poulet Yassa du Chef',
                'category'    => 'plat',
                'price'       => 5500,
                'description' => 'Poulet fermier mariné 24h, oignons caramélisés, citron vert, olives vertes et riz basmati parfumé.',
                'image_url'   => 'https://i.pinimg.com/736x/1e/c8/cd/1ec8cd33df0d6f46d53b9a7b3fb259aa.jpg',
                'image_file'  => 'poulet-yassa.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Mafé de Bœuf Traditionnel',
                'category'    => 'plat',
                'price'       => 6000,
                'description' => 'Bœuf braisé longuement en sauce d\'arachide épicée, légumes frais du marché et attiéké maison.',
                'image_url'   => 'https://i.pinimg.com/736x/fc/1f/c6/fc1fc64b2eaba90c909f13f043b297d6.jpg',
                'image_file'  => 'mafe-boeuf.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Ndolé du Cameroun',
                'category'    => 'plat',
                'price'       => 6500,
                'description' => 'Feuilles de ndolé mijotées aux crevettes fumées, arachides pilées, viande de bœuf et plantain bouilli.',
                'image_url'   => 'https://i.pinimg.com/736x/2f/ae/85/2fae85a1939a0550752d34d971a5307d.jpg',
                'image_file'  => 'ndole-cameroun.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Poulet DG Camerounais',
                'category'    => 'plat',
                'price'       => 7000,
                'description' => 'Poulet fermier sauté avec plantain mûr frit, carottes, poivrons, tomates fraîches et épices du terroir camerounais.',
                'image_url'   => 'https://i.pinimg.com/736x/0d/cc/05/0dcc05c70f586ca0f99c27673d5ab133.jpg',
                'image_file'  => 'poulet-dg.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Jollof Rice au Poulet',
                'category'    => 'plat',
                'price'       => 5500,
                'description' => 'Riz parfumé cuit dans une sauce tomate épicée à la nigériane, servi avec poulet grillé et salade fraîche.',
                'image_url'   => 'https://i.pinimg.com/736x/15/55/b6/1555b60f4b999fae2cafdae66d3497f1.jpg',
                'image_file'  => 'jollof-rice.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Tilapia Braisé Sauce Piment',
                'category'    => 'plat',
                'price'       => 6000,
                'description' => 'Tilapia frais grillé sur braises, nappé d\'une sauce piment-tomate relevée, servi avec attiéké et oignons frits.',
                'image_url'   => 'https://i.pinimg.com/736x/ae/e4/18/aee4187cc812456261dabf26b0ec1f23.jpg',
                'image_file'  => 'tilapia-braise.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Alloco Poulet Grillé',
                'category'    => 'plat',
                'price'       => 4500,
                'description' => 'Tranches de banane plantain mûre frites dorées, accompagnées de poulet grillé épicé et d\'une sauce piment fraîche.',
                'image_url'   => 'https://i.pinimg.com/736x/77/97/99/779799f8982bc6b0ddf5e42d1ffe29ac.jpg',
                'image_file'  => 'alloco-poulet.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Attiéké Poisson Braisé',
                'category'    => 'plat',
                'price'       => 5000,
                'description' => 'Couscous de manioc ivoirien (attiéké) servi avec poisson braisé, oignons marinés, tomates fraîches et sauce piment.',
                'image_url'   => 'https://i.pinimg.com/736x/e3/99/b2/e399b215a5ab887fe72e8060dc638be6.jpg',
                'image_file'  => 'attieke-poisson.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Capitaine à la Sauce Tomate',
                'category'    => 'plat',
                'price'       => 7500,
                'description' => 'Filet de capitaine du fleuve mijoté dans une sauce tomate-oignons aux épices sénégalaises, riz blanc parfumé.',
                'image_url'   => 'https://i.pinimg.com/736x/70/a4/ca/70a4caf53e8729ffdd08a41026eaf94a.jpg',
                'image_file'  => 'capitaine-sauce-tomate.jpg',
                'is_african'  => true,
            ],

            // ── DESSERTS CLASSIQUES ───────────────────────────────────────
            [
                'name'        => 'Fondant au Chocolat Noir',
                'category'    => 'dessert',
                'price'       => 3500,
                'description' => 'Cœur coulant au chocolat noir 70%, glace vanille bourbon de Madagascar, coulis de fruits rouges.',
                'image_url'   => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=600&q=80',
                'image_file'  => 'fondant-chocolat.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Crème Brûlée à la Vanille',
                'category'    => 'dessert',
                'price'       => 2500,
                'description' => 'Crème onctueuse à la vanille de Madagascar, caramel croustillant doré à la flamme, biscuit sablé.',
                'image_url'   => 'https://images.unsplash.com/photo-1470124182917-cc6e71b22ecc?w=600&q=80',
                'image_file'  => 'creme-brulee.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Tatin de Mangues Caramélisées',
                'category'    => 'dessert',
                'price'       => 3000,
                'description' => 'Tarte tatin aux mangues locales caramélisées, pâte feuilletée dorée et crème fraîche à la fleur d\'oranger.',
                'image_url'   => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&q=80',
                'image_file'  => 'tatin-mangue.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Plateau de Fruits Frais de Saison',
                'category'    => 'dessert',
                'price'       => 2000,
                'description' => 'Sélection de fruits frais de saison — mangue, papaye, ananas, fruit de la passion — coulis exotique.',
                'image_url'   => 'https://images.unsplash.com/photo-1464965911861-746a04b4bca6?w=600&q=80',
                'image_file'  => 'fruits-saison.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Tiramisu au Café Arabica',
                'category'    => 'dessert',
                'price'       => 3200,
                'description' => 'Tiramisu traditionnel au café arabica du Cameroun, mascarpone léger, cacao amer en poudre fine.',
                'image_url'   => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=600&q=80',
                'image_file'  => 'tiramisu.jpg',
                'is_african'  => false,
            ],

            // ── DESSERTS AFRICAINS ────────────────────────────────────────
            [
                'name'        => 'Thiakry Crémeux',
                'category'    => 'dessert',
                'price'       => 2000,
                'description' => 'Couscous de mil sénégalais mélangé à du lait caillé sucré, parfumé à la vanille et à la noix de muscade.',
                'image_url'   => 'https://i.pinimg.com/736x/4b/56/fd/4b56fd1d5325a4cbf1973600d3799124.jpg',
                'image_file'  => 'thiakry.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Gâteau de Manioc Noix de Coco',
                'category'    => 'dessert',
                'price'       => 2500,
                'description' => 'Cake moelleux à base de manioc râpé, noix de coco fraîche, sucre de canne et lait concentré, cuit à la vapeur.',
                'image_url'   => 'https://i.pinimg.com/736x/ab/b7/ca/abb7ca957e9489383cec66d866dff8af.jpg',
                'image_file'  => 'gateau-manioc-coco.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Beignets de Banane Plantain Sucrés',
                'category'    => 'dessert',
                'price'       => 1800,
                'description' => 'Rondelles de banane plantain mûre enrobées d\'une pâte légère et frites, saupoudrées de sucre glace et cannelle.',
                'image_url'   => 'https://i.pinimg.com/736x/82/fc/50/82fc50ed9caf25f724370d82e5ea25fd.jpg',
                'image_file'  => 'beignets-plantain.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Dégué Glacé',
                'category'    => 'dessert',
                'price'       => 2200,
                'description' => 'Couscous de mil fin mélangé à du yaourt nature sucré, servi glacé, parsemé de raisins secs et de poudre de noix de coco.',
                'image_url'   => 'https://i.pinimg.com/736x/a2/cf/92/a2cf920e59fcbd1a69d92a571a473650.jpg',
                'image_file'  => 'degue-glace.jpg',
                'is_african'  => true,
            ],

            // ── BOISSONS CLASSIQUES ───────────────────────────────────────
            [
                'name'        => 'Citronnade Gingembre Maison',
                'category'    => 'boisson',
                'price'       => 1200,
                'description' => 'Jus de gingembre frais pressé, citron vert, miel de fleurs locales, eau pétillante fraîche.',
                'image_url'   => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=600&q=80',
                'image_file'  => 'citronnade-gingembre.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Cocktail Tropique Sans Alcool',
                'category'    => 'boisson',
                'price'       => 2500,
                'description' => 'Mangue, fruit de la passion, ananas frais, sirop de canne, eau pétillante et zeste de citron vert.',
                'image_url'   => 'https://images.unsplash.com/photo-1551024709-8f23befc548e?w=600&q=80',
                'image_file'  => 'cocktail-tropique.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Café Arabica / Thé du Monde',
                'category'    => 'boisson',
                'price'       => 1000,
                'description' => 'Café arabica torréfié ou sélection de thés — vert Sencha, Earl Grey, Rooibos, Menthe fraîche.',
                'image_url'   => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600&q=80',
                'image_file'  => 'cafe-the.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Jus de Fruits Frais Pressés',
                'category'    => 'boisson',
                'price'       => 1800,
                'description' => 'Orange, mangue, ananas, citron vert ou pastèque — pressés à la commande, sans sucre ajouté.',
                'image_url'   => 'https://images.unsplash.com/photo-1525385133512-2f3bdd039054?w=600&q=80',
                'image_file'  => 'jus-fruits.jpg',
                'is_african'  => false,
            ],
            [
                'name'        => 'Eau Minérale Evian 75cl',
                'category'    => 'boisson',
                'price'       => 800,
                'description' => 'Eau minérale naturelle des Alpes françaises, plate ou pétillante, servie avec une tranche de citron.',
                'image_url'   => 'https://images.unsplash.com/photo-1548839140-29a749e1cf4d?w=600&q=80',
                'image_file'  => 'eau-minerale.jpg',
                'is_african'  => false,
            ],

            // ── BOISSONS AFRICAINES ───────────────────────────────────────
            [
                'name'        => 'Bissap Premium Maison',
                'category'    => 'boisson',
                'price'       => 1500,
                'description' => 'Infusion de fleurs d\'hibiscus fraîches, gingembre, sucre de canne, servie fraîche avec des feuilles de menthe.',
                'image_url'   => 'https://i.pinimg.com/736x/8c/e2/3b/8ce23bed7329ecec35198578f1f82c8b.jpg',
                'image_file'  => 'bissap.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Gnamakoudji — Eau de Gingembre',
                'category'    => 'boisson',
                'price'       => 1200,
                'description' => 'Boisson ivoirienne traditionnelle au gingembre frais infusé, citron vert, sucre de canne et pointe de menthe fraîche.',
                'image_url'   => 'https://i.pinimg.com/736x/8e/b0/a4/8eb0a46cd993bb6f5ce2c66e19298271.jpg',
                'image_file'  => 'gnamakoudji.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Jus de Bouye — Baobab',
                'category'    => 'boisson',
                'price'       => 1500,
                'description' => 'Boisson sénégalaise onctueuse à base de fruit de baobab, riche en vitamine C, légèrement sucrée et servie bien fraîche.',
                'image_url'   => 'https://i.pinimg.com/736x/54/e6/9c/54e69cd6e6c03062620b3caa1abdcc4c.jpg',
                'image_file'  => 'jus-bouye.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Kinkeliba Tisane Royale',
                'category'    => 'boisson',
                'price'       => 1000,
                'description' => 'Infusion chaude ou froide de feuilles de kinkeliba séchées, plante médicinale sénégalaise aux vertus digestives.',
                'image_url'   => 'https://i.pinimg.com/736x/21/e2/1d/21e21d143e02a98b6d2349fc4a63cabc.jpg',
                'image_file'  => 'kinkeliba.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Tamarin Frais Maison',
                'category'    => 'boisson',
                'price'       => 1300,
                'description' => 'Pulpe de tamarin fraîche dissoute dans l\'eau, sucrée au miel de fleurs, relevée d\'une pointe de piment doux.',
                'image_url'   => 'https://i.pinimg.com/736x/b5/72/f6/b572f6082cc2b62e936cf181c034fdce.jpg',
                'image_file'  => 'tamarin-frais.jpg',
                'is_african'  => true,
            ],
            [
                'name'        => 'Ditakh — Jus de Ditax',
                'category'    => 'boisson',
                'price'       => 1400,
                'description' => 'Jus traditionnel sénégalais extrait du fruit du ditakh, naturellement acidulé, sucré à la canne et servi sur glace.',
                'image_url'   => 'https://i.pinimg.com/736x/93/05/dd/9305dd913b1b5c4f5d0f488da05d764d.jpg',
                'image_file'  => 'ditakh.jpg',
                'is_african'  => true,
            ],
        ];

        $storageDir = storage_path('app/public/menus');
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0775, true);
        }

        $context = stream_context_create([
            'http' => [
                'method'          => 'GET',
                'header'          => implode("\r\n", [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36',
                    'Accept: image/webp,image/apng,image/*,*/*;q=0.8',
                    'Accept-Language: fr-FR,fr;q=0.9',
                    'Referer: https://www.pinterest.com/',
                ]) . "\r\n",
                'follow_location' => true,
                'timeout'         => 20,
            ],
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ]);

        foreach ($menus as $menu) {
            $localFile  = $storageDir . '/' . $menu['image_file'];
            $imagePath  = null;

            // Les URLs Pinterest (i.pinimg.com) sont stockées directement en base ;
            // les images Unsplash/Pexels sont téléchargées en local.
            if (str_starts_with($menu['image_url'], 'https://i.pinimg.com')) {
                $imagePath = $menu['image_url'];
                $this->command->getOutput()->writeln("  Pinterest : {$menu['image_file']} <info>URL directe</info>");
            } else {
                $this->command->getOutput()->write("  Téléchargement : {$menu['image_file']}... ");
                try {
                    $imageData = @file_get_contents($menu['image_url'], false, $context);
                    if ($imageData !== false && strlen($imageData) > 1000) {
                        file_put_contents($localFile, $imageData);
                        $imagePath = 'menus/' . $menu['image_file'];
                        $this->command->getOutput()->writeln('<info>OK</info>');
                    } else {
                        $this->command->getOutput()->writeln('<comment>Ignoré (taille trop petite)</comment>');
                        if (file_exists($localFile)) {
                            $imagePath = 'menus/' . $menu['image_file'];
                        }
                    }
                } catch (\Exception $e) {
                    $this->command->getOutput()->writeln('<error>Erreur</error>');
                    if (file_exists($localFile)) {
                        $imagePath = 'menus/' . $menu['image_file'];
                    }
                }
            }

            DB::table('menus')->insert([
                'name'        => $menu['name'],
                'category'    => $menu['category'],
                'price'       => $menu['price'],
                'description' => $menu['description'],
                'image'       => $imagePath,
                'is_african'  => $menu['is_african'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        $this->command->info('✓ ' . count($menus) . ' menus insérés avec succès.');
    }
}
