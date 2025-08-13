<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class CleanProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Supprimer tous les produits existants
        echo "🗑️ Suppression de tous les produits existants...\n";
        Product::query()->forceDelete(); // Force delete pour supprimer même les soft deleted
        
        // 2. Reset l'auto-increment
        DB::statement('ALTER TABLE products AUTO_INCREMENT = 1');
        
        // 3. Récupérer les catégories existantes
        $categories = Category::where('is_active', true)->get();
        
        if ($categories->isEmpty()) {
            echo "❌ Aucune catégorie active trouvée. Veuillez d'abord créer des catégories.\n";
            return;
        }
        
        echo "📂 Catégories trouvées: " . $categories->count() . "\n";
        
        // 4. Créer de nouveaux produits propres
        $this->createPurchaseProducts($categories);
        $this->createRentalProducts($categories);
        $this->createMixedProducts($categories);
        
        echo "✅ Sanitisation terminée !\n";
    }
    
    /**
     * Créer des produits de vente uniquement
     */
    private function createPurchaseProducts($categories)
    {
        echo "🛒 Création des produits de vente uniquement...\n";
        
        $purchaseProducts = [
            [
                'name' => 'Graines de Tournesol Bio 5kg',
                'description' => 'Graines de tournesol biologiques de haute qualité pour l\'alimentation animale. Riches en protéines et en énergie, parfaites pour volailles et petits animaux.',
                'short_description' => 'Graines tournesol bio 5kg pour alimentation animale',
                'price' => 15.90,
                'quantity' => 50,
                'weight' => 5.000,
                'unit_symbol' => 'sac',
            ],
            [
                'name' => 'Engrais Organique Universel 25kg',
                'description' => 'Engrais organique naturel pour tous types de cultures. Améliore la structure du sol et favorise une croissance saine des plantes.',
                'short_description' => 'Engrais organique universel 25kg',
                'price' => 32.50,
                'quantity' => 25,
                'weight' => 25.000,
                'unit_symbol' => 'sac',
            ],
            [
                'name' => 'Sécateur Professionnel Bypass',
                'description' => 'Sécateur de qualité professionnelle avec lames en acier forgé. Coupe nette et précise pour tous travaux de taille.',
                'short_description' => 'Sécateur professionnel bypass acier',
                'price' => 45.00,
                'quantity' => 15,
                'weight' => 0.350,
                'unit_symbol' => 'pièce',
            ],
            [
                'name' => 'Gants de Jardinage Cuir Renforcé',
                'description' => 'Gants de jardinage en cuir véritable avec renfort aux doigts. Protection optimale pour tous travaux de jardinage.',
                'short_description' => 'Gants jardinage cuir renforcé',
                'price' => 18.90,
                'quantity' => 30,
                'weight' => 0.200,
                'unit_symbol' => 'paire',
            ],
            [
                'name' => 'Arrosoir Galvanisé 10L',
                'description' => 'Arrosoir traditionnel en métal galvanisé de 10 litres. Robuste et durable, avec pomme d\'arrosage amovible.',
                'short_description' => 'Arrosoir galvanisé 10L traditionnel',
                'price' => 28.00,
                'quantity' => 20,
                'weight' => 1.200,
                'unit_symbol' => 'pièce',
            ]
        ];
        
        foreach ($purchaseProducts as $productData) {
            $category = $categories->random();
            $this->createProduct($productData, $category, 'purchase');
        }
    }
    
    /**
     * Créer des produits de location uniquement
     */
    private function createRentalProducts($categories)
    {
        echo "📅 Création des produits de location uniquement...\n";
        
        $rentalProducts = [
            [
                'name' => 'Motoculteur Thermique 7CV',
                'description' => 'Motoculteur puissant de 7CV pour préparation du sol. Largeur de travail 60cm, parfait pour jardins et potagers de taille moyenne.',
                'short_description' => 'Motoculteur thermique 7CV 60cm',
                'rental_price_per_day' => 25.00,
                'deposit_amount' => 200.00,
                'rental_stock' => 3,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 85.000,
                'unit_symbol' => 'unité',
            ],
            [
                'name' => 'Tronçonneuse Électrique 2000W',
                'description' => 'Tronçonneuse électrique professionnelle de 2000W. Guide de 40cm, système de tension automatique de la chaîne.',
                'short_description' => 'Tronçonneuse électrique 2000W 40cm',
                'rental_price_per_day' => 15.00,
                'deposit_amount' => 120.00,
                'rental_stock' => 2,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 4.500,
                'unit_symbol' => 'unité',
            ],
            [
                'name' => 'Broyeur de Végétaux Thermique',
                'description' => 'Broyeur de végétaux thermique pour branches jusqu\'à 7cm de diamètre. Moteur 4 temps, système de coupe à marteaux.',
                'short_description' => 'Broyeur végétaux thermique 7cm max',
                'rental_price_per_day' => 30.00,
                'deposit_amount' => 300.00,
                'rental_stock' => 1,
                'min_rental_days' => 1,
                'max_rental_days' => 5,
                'weight' => 65.000,
                'unit_symbol' => 'unité',
            ],
            [
                'name' => 'Scarificateur Électrique 1500W',
                'description' => 'Scarificateur électrique pour aération de pelouse. Largeur de travail 38cm, bac de ramassage 45L inclus.',
                'short_description' => 'Scarificateur électrique 1500W 38cm',
                'rental_price_per_day' => 18.00,
                'deposit_amount' => 150.00,
                'rental_stock' => 2,
                'min_rental_days' => 1,
                'max_rental_days' => 3,
                'weight' => 12.000,
                'unit_symbol' => 'unité',
            ]
        ];
        
        foreach ($rentalProducts as $productData) {
            $category = $categories->random();
            $this->createProduct($productData, $category, 'rental');
        }
    }
    
    /**
     * Créer des produits mixtes (vente + location)
     */
    private function createMixedProducts($categories)
    {
        echo "🔄 Création des produits mixtes (vente + location)...\n";
        
        $mixedProducts = [
            [
                'name' => 'Bêche Forgée Manche Long',
                'description' => 'Bêche professionnelle forgée avec manche en frêne de 120cm. Lame en acier trempé pour une durabilité maximale.',
                'short_description' => 'Bêche forgée manche long 120cm',
                'price' => 65.00,
                'rental_price_per_day' => 3.00,
                'deposit_amount' => 40.00,
                'quantity' => 8,
                'rental_stock' => 3,
                'min_rental_days' => 1,
                'max_rental_days' => 14,
                'weight' => 2.200,
                'unit_symbol' => 'pièce',
            ],
            [
                'name' => 'Niveau Laser Rotatif Professionnel',
                'description' => 'Niveau laser rotatif auto-nivelant avec portée 300m. Précision ±1mm/10m, résistant aux projections d\'eau.',
                'short_description' => 'Niveau laser rotatif 300m professionnel',
                'price' => 450.00,
                'rental_price_per_day' => 20.00,
                'deposit_amount' => 300.00,
                'quantity' => 2,
                'rental_stock' => 1,
                'min_rental_days' => 1,
                'max_rental_days' => 7,
                'weight' => 3.500,
                'unit_symbol' => 'unité',
            ]
        ];
        
        foreach ($mixedProducts as $productData) {
            $category = $categories->random();
            $this->createProduct($productData, $category, 'both');
        }
    }
    
    /**
     * Créer un produit avec les données fournies
     */
    private function createProduct($data, $category, $type)
    {
        $baseData = [
            'slug' => Str::slug($data['name']),
            'sku' => $this->generateSku($data['name']),
            'type' => $type,
            'category_id' => $category->id,
            'is_active' => true,
            'is_featured' => rand(0, 1),
            'low_stock_threshold' => 5,
            'critical_threshold' => 2,
            'out_of_stock_threshold' => 1,
            'meta_title' => $data['name'],
            'meta_description' => $data['short_description'],
            'meta_keywords' => str_replace(' ', ', ', strtolower($data['name'])),
        ];
        
        // Fusionner avec les données spécifiques
        $productData = array_merge($baseData, $data);
        
        // Ajuster selon le type
        if ($type === 'purchase') {
            // Produit de vente uniquement
            $productData['rental_price_per_day'] = null;
            $productData['deposit_amount'] = null;
            $productData['rental_stock'] = 0;
            $productData['min_rental_days'] = null;
            $productData['max_rental_days'] = null;
            $productData['is_rental_available'] = false;
        } elseif ($type === 'rental') {
            // Produit de location uniquement
            $productData['price'] = 0;
            $productData['quantity'] = 0;
            $productData['is_rental_available'] = true;
        } else {
            // Produit mixte
            $productData['is_rental_available'] = true;
        }
        
        Product::create($productData);
        echo "  ✓ Créé: {$data['name']} (Type: {$type})\n";
    }
    
    /**
     * Générer un SKU unique
     */
    private function generateSku($name)
    {
        $base = strtoupper(Str::slug($name, ''));
        $base = substr($base, 0, 6);
        
        $counter = 1;
        $sku = $base . sprintf('%03d', $counter);
        
        while (Product::where('sku', $sku)->exists()) {
            $counter++;
            $sku = $base . sprintf('%03d', $counter);
        }
        
        return $sku;
    }
}
