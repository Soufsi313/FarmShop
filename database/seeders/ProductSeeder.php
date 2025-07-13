<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les catégories existantes
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            $this->command->info('Aucune catégorie trouvée. Veuillez d\'abord exécuter CategorySeeder.');
            return;
        }

        $products = [
            // Produits alimentaires
            [
                'name' => 'Pommes Biologiques',
                'description' => 'Pommes fraîches issues de l\'agriculture biologique, variété Gala.',
                'sku' => 'POMME-BIO-001',
                'price' => 3.50,
                'quantity' => 250,
                'unit_symbol' => 'kg',
                'type' => 'purchase',
                'category_slug' => 'fruits-et-legumes',
            ],
            [
                'name' => 'Carottes du Potager',
                'description' => 'Carottes fraîches cultivées localement, parfaites pour vos plats.',
                'sku' => 'CAROTTE-001',
                'price' => 2.80,
                'quantity' => 180,
                'unit_symbol' => 'kg',
                'type' => 'purchase',
                'category_slug' => 'fruits-et-legumes',
            ],
            [
                'name' => 'Blé Tendre',
                'description' => 'Blé tendre de qualité supérieure pour la boulangerie.',
                'sku' => 'BLE-TENDRE-001',
                'price' => 0.45,
                'quantity' => 5000,
                'unit_symbol' => 'kg',
                'type' => 'purchase',
                'category_slug' => 'cereales-et-grains',
            ],

            // Matériel agricole
            [
                'name' => 'Tracteur Compact 25CV',
                'description' => 'Tracteur compact idéal pour petites exploitations. Location possible.',
                'sku' => 'TRACT-25CV-001',
                'price' => 25000.00,
                'rental_price_per_day' => 85.00,
                'quantity' => 3,
                'unit_symbol' => 'unité',
                'type' => 'both',
                'category_slug' => 'machines-agricoles',
                'min_rental_days' => 1,
                'max_rental_days' => 30,
                'deposit_amount' => 500.00,
            ],
            [
                'name' => 'Pulvérisateur 200L',
                'description' => 'Pulvérisateur professionnel pour traitements phytosanitaires.',
                'sku' => 'PULV-200L-001',
                'price' => 1200.00,
                'rental_price_per_day' => 25.00,
                'quantity' => 8,
                'unit_symbol' => 'unité',
                'type' => 'both',
                'category_slug' => 'equipements-de-traitement',
                'min_rental_days' => 1,
                'max_rental_days' => 15,
                'deposit_amount' => 150.00,
            ],
            [
                'name' => 'Bineuse 3 Rangs',
                'description' => 'Bineuse pour désherbage mécanique, 3 rangs.',
                'sku' => 'BINEUSE-3R-001',
                'price' => 3500.00,
                'rental_price_per_day' => 45.00,
                'quantity' => 2,
                'unit_symbol' => 'unité',
                'type' => 'both',
                'category_slug' => 'outils-de-travail-du-sol',
                'min_rental_days' => 2,
                'max_rental_days' => 20,
                'deposit_amount' => 200.00,
            ],

            // Matériaux et produits
            [
                'name' => 'Graines de Tournesol',
                'description' => 'Graines de tournesol hybride haute production.',
                'sku' => 'GRAINE-TOUR-001',
                'price' => 8.50,
                'quantity' => 500,
                'unit_symbol' => 'kg',
                'type' => 'purchase',
                'category_slug' => 'graines-et-semences',
            ],
            [
                'name' => 'Engrais NPK 15-15-15',
                'description' => 'Engrais complet équilibré pour toutes cultures.',
                'sku' => 'ENGRAIS-NPK-001',
                'price' => 1.20,
                'quantity' => 2000,
                'unit_symbol' => 'kg',
                'type' => 'purchase',
                'category_slug' => 'engrais-et-amendements',
            ],

            // Équipements spécialisés
            [
                'name' => 'Système d\'Irrigation Goutte-à-Goutte',
                'description' => 'Kit complet d\'irrigation goutte-à-goutte pour 1 hectare.',
                'sku' => 'IRRIG-GAG-001',
                'price' => 850.00,
                'quantity' => 15,
                'unit_symbol' => 'kit',
                'type' => 'purchase',
                'category_slug' => 'systemes-dirrigation',
            ],
            [
                'name' => 'Composteur Rotatif 300L',
                'description' => 'Composteur rotatif pour transformation des déchets organiques.',
                'sku' => 'COMPOST-300L-001',
                'price' => 280.00,
                'quantity' => 12,
                'unit_symbol' => 'unité',
                'type' => 'purchase',
                'category_slug' => 'equipements-de-compostage',
            ],
        ];

        foreach ($products as $productData) {
            // Trouver la catégorie par slug
            $category = $categories->where('slug', $productData['category_slug'])->first();
            
            if (!$category) {
                // Utiliser la première catégorie disponible si le slug n'est pas trouvé
                $category = $categories->first();
            }

            // Retirer category_slug des données
            unset($productData['category_slug']);

            // Ajouter les champs obligatoires
            $productData['category_id'] = $category->id;
            $productData['slug'] = Str::slug($productData['name']);
            $productData['is_active'] = true;
            $productData['is_featured'] = rand(0, 1) === 1; // Aléatoirement en vedette
            $productData['critical_threshold'] = max(1, intval($productData['quantity'] * 0.1)); // 10% du stock
            $productData['low_stock_threshold'] = max(5, intval($productData['quantity'] * 0.2)); // 20% du stock

            Product::create($productData);
        }

        $this->command->info('Produits créés avec succès : ' . count($products) . ' produits ajoutés.');
    }
}
