<?php
/**
 * Script pour vérifier la structure de la table products
 */

require 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

// Initialisation de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Vérification de la structure de la table products ===\n\n";

try {
    // Vérifier les colonnes de la table products
    $columns = DB::select("DESCRIBE products");
    
    echo "Colonnes dans la table 'products':\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type}) - {$column->Null} - {$column->Default}\n";
    }
    
    echo "\n";
    
    // Essayer de récupérer quelques produits pour voir les données disponibles
    echo "Exemple de données products (5 premiers):\n";
    $products = Product::take(5)->get();
    
    foreach ($products as $product) {
        echo "- ID: {$product->id}, Nom: {$product->name}";
        
        // Vérifier quels champs de stock sont disponibles
        $stockFields = [];
        if (isset($product->stock)) $stockFields[] = "stock: {$product->stock}";
        if (isset($product->quantity)) $stockFields[] = "quantity: {$product->quantity}";
        if (isset($product->stock_quantity)) $stockFields[] = "stock_quantity: {$product->stock_quantity}";
        if (isset($product->inventory)) $stockFields[] = "inventory: {$product->inventory}";
        
        if (!empty($stockFields)) {
            echo " (" . implode(', ', $stockFields) . ")";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
