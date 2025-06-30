<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class RentalProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer les catégories si elles n'existent pas
        $outilsCategory = Category::firstOrCreate([
            'name' => 'Outils de jardinage',
        ], [
            'slug' => 'outils-jardinage',
            'description' => 'Outils pour l\'entretien du jardin et des espaces verts',
            'is_active' => true,
            'type' => 'rental',
            'food_type' => 'non_food' // Produits non-alimentaires
        ]);

        $machinesCategory = Category::firstOrCreate([
            'name' => 'Machines & Équipements',
        ], [
            'slug' => 'machines-equipements',
            'description' => 'Machines et équipements pour particuliers',
            'is_active' => true,
            'type' => 'rental',
            'food_type' => 'non_food' // Produits non-alimentaires
        ]);

        // Produits de location - Outils de jardinage
        $outils = [
            [
                'name' => 'Tondeuse à gazon électrique',
                'description' => 'Tondeuse électrique légère et maniable, parfaite pour les jardins de taille moyenne. Largeur de coupe 38cm, bac de ramassage 40L.',
                'rental_price_per_day' => 15.00,
                'deposit_amount' => 80.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'rental_conditions' => 'Vérifier le niveau d\'huile avant utilisation. Nettoyer après usage.',
                'main_image' => 'rental/tondeuse-electrique.jpg'
            ],
            [
                'name' => 'Taille-haie électrique',
                'description' => 'Taille-haie électrique pour la taille de précision des haies et arbustes. Lame de 55cm, poignée rotative.',
                'rental_price_per_day' => 12.00,
                'deposit_amount' => 60.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'rental_conditions' => 'Utiliser avec des gants de protection. Nettoyer la lame après usage.',
                'main_image' => 'rental/taille-haie.jpg'
            ],
            [
                'name' => 'Souffleur de feuilles',
                'description' => 'Souffleur thermique pour nettoyer facilement les feuilles mortes et débris. Puissant et léger.',
                'rental_price_per_day' => 10.00,
                'deposit_amount' => 45.00,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'rental_conditions' => 'Faire le plein d\'essence. Porter des protections auditives.',
                'main_image' => 'rental/souffleur-feuilles.jpg'
            ],
            [
                'name' => 'Motoculteur',
                'description' => 'Motoculteur thermique pour retourner la terre facilement. Largeur de travail 60cm, profondeur réglable.',
                'rental_price_per_day' => 25.00,
                'deposit_amount' => 120.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'rental_conditions' => 'Vérifier huile et essence. Ne pas utiliser sur sol gelé.',
                'main_image' => 'rental/motoculteur.jpg'
            ],
            [
                'name' => 'Débroussailleuse thermique',
                'description' => 'Débroussailleuse à dos thermique pour nettoyer les zones difficiles d\'accès. Harnais ergonomique inclus.',
                'rental_price_per_day' => 18.00,
                'deposit_amount' => 90.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'rental_conditions' => 'Port d\'équipements de protection obligatoire. Faire le plein avant retour.',
                'main_image' => 'rental/debroussailleuse.jpg'
            ]
        ];

        // Produits de location - Machines & Équipements
        $machines = [
            [
                'name' => 'Nettoyeur haute pression',
                'description' => 'Nettoyeur haute pression 140 bars pour terrasses, façades et véhicules. Livré avec accessoires et détergent.',
                'rental_price_per_day' => 20.00,
                'deposit_amount' => 100.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'rental_conditions' => 'Vider complètement après usage pour éviter le gel. Ranger les accessoires.',
                'main_image' => 'rental/nettoyeur-haute-pression.jpg'
            ],
            [
                'name' => 'Ponceuse à parquet',
                'description' => 'Ponceuse vibrante pour rénover parquets et sols bois. Système d\'aspiration intégré, plusieurs grains de papier fournis.',
                'rental_price_per_day' => 35.00,
                'deposit_amount' => 150.00,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'rental_conditions' => 'Vider le sac à poussière régulièrement. Changer le papier selon l\'usure.',
                'main_image' => 'rental/ponceuse-parquet.jpg'
            ],
            [
                'name' => 'Échafaudage roulant',
                'description' => 'Échafaudage roulant en aluminium, hauteur de travail 4m. Montage facile, roues avec freins de sécurité.',
                'rental_price_per_day' => 25.00,
                'deposit_amount' => 200.00,
                'min_rental_days' => 2,
                'max_rental_days' => 14,
                'rental_conditions' => 'Vérifier tous les éléments avant montage. Respecter la charge maximale 150kg.',
                'main_image' => 'rental/echafaudage-roulant.jpg'
            ],
            [
                'name' => 'Bétonnière électrique',
                'description' => 'Bétonnière électrique 140L pour petits travaux de maçonnerie. Cuve basculante, moteur robuste.',
                'rental_price_per_day' => 22.00,
                'deposit_amount' => 110.00,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'rental_conditions' => 'Nettoyer soigneusement après chaque usage. Vérifier l\'étanchéité électrique.',
                'main_image' => 'rental/betonniere.jpg'
            ],
            [
                'name' => 'Aspirateur de chantier',
                'description' => 'Aspirateur industriel eau et poussière 30L. Puissant et robuste pour tous types de déchets de chantier.',
                'rental_price_per_day' => 15.00,
                'deposit_amount' => 75.00,
                'min_rental_days' => 1,
                'max_rental_days' => 10,
                'rental_conditions' => 'Vider et nettoyer la cuve après usage. Vérifier les filtres.',
                'main_image' => 'rental/aspirateur-chantier.jpg'
            ]
        ];

        // Créer les produits outils
        foreach ($outils as $outil) {
            Product::create([
                'name' => $outil['name'],
                'slug' => Str::slug($outil['name']),
                'description' => $outil['description'],
                'category_id' => $outilsCategory->id,
                'price' => 0, // Pas de prix d'achat, uniquement location
                'rental_price_per_day' => $outil['rental_price_per_day'],
                'deposit_amount' => $outil['deposit_amount'],
                'min_rental_days' => $outil['min_rental_days'],
                'max_rental_days' => $outil['max_rental_days'],
                'rental_conditions' => $outil['rental_conditions'],
                'quantity' => rand(2, 5), // Stock entre 2 et 5 unités
                'unit_symbol' => 'piece',
                'main_image' => $outil['main_image'],
                'critical_stock_threshold' => 1,
                'is_active' => true,
                'is_rentable' => true,
                'is_featured' => rand(0, 1) == 1,
                'views_count' => rand(10, 100),
                'likes_count' => rand(0, 20),
            ]);
        }

        // Créer les produits machines
        foreach ($machines as $machine) {
            Product::create([
                'name' => $machine['name'],
                'slug' => Str::slug($machine['name']),
                'description' => $machine['description'],
                'category_id' => $machinesCategory->id,
                'price' => 0, // Pas de prix d'achat, uniquement location
                'rental_price_per_day' => $machine['rental_price_per_day'],
                'deposit_amount' => $machine['deposit_amount'],
                'min_rental_days' => $machine['min_rental_days'],
                'max_rental_days' => $machine['max_rental_days'],
                'rental_conditions' => $machine['rental_conditions'],
                'quantity' => rand(1, 3), // Stock entre 1 et 3 unités
                'unit_symbol' => 'piece',
                'main_image' => $machine['main_image'],
                'critical_stock_threshold' => 1,
                'is_active' => true,
                'is_rentable' => true,
                'is_featured' => rand(0, 1) == 1,
                'views_count' => rand(5, 80),
                'likes_count' => rand(0, 15),
            ]);
        }

        $this->command->info('✅ 10 produits de location créés avec succès:');
        $this->command->info('   - 5 outils de jardinage');
        $this->command->info('   - 5 machines & équipements');
        $this->command->info('📦 Tous adaptés aux particuliers (aucun véhicule/permis requis)');
    }
}
