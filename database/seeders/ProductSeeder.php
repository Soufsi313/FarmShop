<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Vérifier ou créer la catégorie "Fruits et Légumes"
        $category = Category::firstOrCreate(
            ['name' => 'Fruits et Légumes'],
            [
                'slug' => 'fruits-et-legumes',
                'description' => 'Fruits et légumes frais de saison, cultivés localement avec amour.',
                'type' => 'purchase', // Catégorie uniquement pour achat (pas location)
                'is_active' => true
            ]
        );

        echo "Catégorie 'Fruits et Légumes' (ID: {$category->id}) créée ou trouvée." . PHP_EOL;

        // Créer 10 produits alimentaires en utilisant la factory
        $products = Product::factory()->count(10)->create([
            'category_id' => $category->id
        ]);

        echo "10 produits alimentaires créés avec succès dans la catégorie 'Fruits et Légumes'!" . PHP_EOL;
        
        // Afficher la liste des produits créés
        foreach ($products as $product) {
            echo "- {$product->name} ({$product->price}€/{$product->unit_symbol})" . PHP_EOL;
        }
    }
}
