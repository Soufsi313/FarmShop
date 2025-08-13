<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\RentalCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RentalProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Création des produits de location...');

        // Créer les produits de location en utilisant les catégories existantes
        $this->createOutilsAgricoles(); // 7 produits - rental_category_id = 1
        $this->createMachines(); // 7 produits - rental_category_id = 2
        $this->createEquipement(); // 6 produits - rental_category_id = 3

        $this->command->info('20 produits de location créés avec succès !');
    }

    /**
     * Créer les outils agricoles (7 produits)
     */
    private function createOutilsAgricoles(): void
    {
        $outils = [
            [
                'name' => 'Bêche professionnelle',
                'description' => 'Bêche robuste en acier forgé pour travaux de terrassement et plantation',
                'rental_price_per_day' => 5.00,
                'deposit_amount' => 25.00,
                'short_description' => 'Bêche professionnelle en acier forgé',
                'rental_category_id' => 1
            ],
            [
                'name' => 'Serfouette 3 dents',
                'description' => 'Outil polyvalent pour biner, sarcler et aérer la terre',
                'rental_price_per_day' => 4.00,
                'deposit_amount' => 20.00,
                'short_description' => 'Serfouette 3 dents pour binage',
                'rental_category_id' => 1
            ],
            [
                'name' => 'Râteau à dents droites',
                'description' => 'Râteau professionnel pour niveler et nettoyer le sol',
                'rental_price_per_day' => 3.50,
                'deposit_amount' => 18.00,
                'short_description' => 'Râteau professionnel à dents droites',
                'rental_category_id' => 1
            ],
            [
                'name' => 'Pioche de terrassement',
                'description' => 'Pioche lourde pour casser la terre dure et les racines',
                'rental_price_per_day' => 6.00,
                'deposit_amount' => 30.00,
                'short_description' => 'Pioche lourde de terrassement',
                'rental_category_id' => 1
            ],
            [
                'name' => 'Fourche à bêcher',
                'description' => 'Fourche à 4 dents pour retourner la terre sans la compacter',
                'rental_price_per_day' => 4.50,
                'deposit_amount' => 22.00,
                'short_description' => 'Fourche à bêcher 4 dents',
                'rental_category_id' => 1
            ],
            [
                'name' => 'Sécateur pneumatique',
                'description' => 'Sécateur professionnel avec assistance pneumatique pour la taille',
                'rental_price_per_day' => 12.00,
                'deposit_amount' => 50.00,
                'short_description' => 'Sécateur pneumatique professionnel',
                'rental_category_id' => 1
            ],
            [
                'name' => 'Houe oscillante',
                'description' => 'Houe oscillante pour désherber efficacement sans effort',
                'rental_price_per_day' => 8.00,
                'deposit_amount' => 45.00,
                'short_description' => 'Houe oscillante pour désherbage',
                'rental_category_id' => 1
            ]
        ];

        foreach ($outils as $outil) {
            $this->createRentalProduct($outil);
        }
    }

    /**
     * Créer les machines (7 produits)
     */
    private function createMachines(): void
    {
        $machines = [
            [
                'name' => 'Motoculteur électrique',
                'description' => 'Motoculteur électrique silencieux pour petites parcelles',
                'rental_price_per_day' => 25.00,
                'deposit_amount' => 80.00,
                'short_description' => 'Motoculteur électrique silencieux',
                'rental_category_id' => 2
            ],
            [
                'name' => 'Tondeuse autoportée',
                'description' => 'Tondeuse autoportée pour grandes surfaces d\'herbe',
                'rental_price_per_day' => 35.00,
                'deposit_amount' => 100.00,
                'short_description' => 'Tondeuse autoportée grandes surfaces',
                'rental_category_id' => 2
            ],
            [
                'name' => 'Tronçonneuse thermique',
                'description' => 'Tronçonneuse puissante pour abattage et élagage',
                'rental_price_per_day' => 20.00,
                'deposit_amount' => 60.00,
                'short_description' => 'Tronçonneuse thermique puissante',
                'rental_category_id' => 2
            ],
            [
                'name' => 'Broyeur de végétaux',
                'description' => 'Broyeur pour transformer branches et déchets verts en paillis',
                'rental_price_per_day' => 30.00,
                'deposit_amount' => 90.00,
                'short_description' => 'Broyeur de végétaux',
                'rental_category_id' => 2
            ],
            [
                'name' => 'Débroussailleuse professionnelle',
                'description' => 'Débroussailleuse thermique pour terrains difficiles',
                'rental_price_per_day' => 18.00,
                'deposit_amount' => 50.00,
                'short_description' => 'Débroussailleuse thermique',
                'rental_category_id' => 2
            ],
            [
                'name' => 'Souffleur à feuilles',
                'description' => 'Souffleur puissant pour nettoyer allées et espaces verts',
                'rental_price_per_day' => 15.00,
                'deposit_amount' => 80.00,
                'short_description' => 'Souffleur à feuilles puissant',
                'rental_category_id' => 2
            ],
            [
                'name' => 'Scarificateur électrique',
                'description' => 'Scarificateur pour aérer et régénérer les pelouses',
                'rental_price_per_day' => 22.00,
                'deposit_amount' => 130.00,
                'short_description' => 'Scarificateur électrique',
                'rental_category_id' => 2
            ]
        ];

        foreach ($machines as $machine) {
            $this->createRentalProduct($machine);
        }
    }

    /**
     * Créer les équipements (6 produits)
     */
    private function createEquipement(): void
    {
        $equipements = [
            [
                'name' => 'Remorque basculante',
                'description' => 'Remorque basculante 500kg pour transport de matériaux',
                'rental_price_per_day' => 40.00,
                'deposit_amount' => 250.00,
                'short_description' => 'Remorque basculante 500kg',
                'rental_category_id' => 3
            ],
            [
                'name' => 'Échafaudage mobile',
                'description' => 'Échafaudage mobile pour travaux en hauteur jusqu\'à 3m',
                'rental_price_per_day' => 28.00,
                'deposit_amount' => 200.00,
                'short_description' => 'Échafaudage mobile 3m',
                'rental_category_id' => 3
            ],
            [
                'name' => 'Bâche de protection 6x4m',
                'description' => 'Bâche imperméable renforcée pour protection cultures',
                'rental_price_per_day' => 8.00,
                'deposit_amount' => 40.00,
                'short_description' => 'Bâche protection 6x4m',
                'rental_category_id' => 3
            ],
            [
                'name' => 'Filet anti-insectes',
                'description' => 'Filet de protection contre les insectes et petits animaux',
                'rental_price_per_day' => 6.00,
                'deposit_amount' => 30.00,
                'short_description' => 'Filet anti-insectes',
                'rental_category_id' => 3
            ],
            [
                'name' => 'Serre tunnel 3x2m',
                'description' => 'Serre tunnel démontable pour cultures protégées',
                'rental_price_per_day' => 15.00,
                'deposit_amount' => 60.00,
                'short_description' => 'Serre tunnel 3x2m',
                'rental_category_id' => 3
            ],
            [
                'name' => 'Composteur rotatif',
                'description' => 'Composteur rotatif 300L pour compostage accéléré',
                'rental_price_per_day' => 10.00,
                'deposit_amount' => 60.00,
                'short_description' => 'Composteur rotatif 300L',
                'rental_category_id' => 3
            ]
        ];

        foreach ($equipements as $equipement) {
            $this->createRentalProduct($equipement);
        }
    }

    /**
     * Créer un produit de location
     */
    private function createRentalProduct(array $data): void
    {
        $slug = Str::slug($data['name']) . '-' . rand(1000, 9999);
        
        Product::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'short_description' => $data['short_description'],
            'slug' => $slug,
            'type' => Product::TYPE_RENTAL,
            'price' => 0.00, // Prix de vente à 0 pour les produits de location uniquement
            'rental_price_per_day' => $data['rental_price_per_day'],
            'deposit_amount' => $data['deposit_amount'],
            'min_rental_days' => 2, // Minimum 2 jours comme demandé
            'max_rental_days' => 365, // Maximum 1 an (autant de jours qu'on veut)
            'available_days' => [1, 2, 3, 4, 5, 6, 7], // Disponible tous les jours
            'quantity' => 25, // Stock de base
            'rental_stock' => 25, // Stock de location
            'critical_threshold' => 5,
            'low_stock_threshold' => 10,
            'out_of_stock_threshold' => 0,
            'category_id' => 1, // Catégorie fallback (obligatoire même pour les produits de location)
            'rental_category_id' => $data['rental_category_id'],
            'unit_symbol' => 'pièce', // Utiliser une valeur valide de l'ENUM
            'sku' => 'LOC-' . strtoupper(Str::random(6)),
            'weight' => rand(5, 500) / 10, // Poids aléatoire entre 0.5kg et 50kg
            'is_active' => true,
            'is_featured' => false,
            'meta_title' => $data['name'] . ' - Location',
            'meta_description' => 'Location de ' . strtolower($data['name']) . ' - ' . $data['short_description'],
        ]);
    }
}
