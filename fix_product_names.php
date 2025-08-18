<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\OrderLocation;
use App\Models\Product;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CORRECTION DES DONNÉES PRODUITS ===\n\n";

try {
    // Vérifier les produits dans la base
    echo "🔍 Vérification des produits...\n";
    
    $orderLocation = OrderLocation::with(['orderItemLocations', 'orderItemLocations.product'])->first();
    
    foreach ($orderLocation->orderItemLocations as $item) {
        echo "📦 Item #{$item->id}:\n";
        echo "   Product ID: {$item->product_id}\n";
        
        $product = Product::find($item->product_id);
        if ($product) {
            echo "   Nom actuel: '{$product->name}'\n";
            echo "   Type: '{$product->type}'\n";
            
            if (empty($product->name)) {
                echo "   ⚠️  Nom vide, correction nécessaire!\n";
                
                // Essayons de trouver un nom logique
                $newName = "Produit de location #{$product->id}";
                
                // Si c'est un produit de location, donnons lui un nom plus spécifique
                if ($product->type === 'rental') {
                    $possibleNames = [
                        'Tracteur agricole',
                        'Bêche motorisée', 
                        'Tondeuse professionnelle',
                        'Débroussailleuse',
                        'Motoculteur'
                    ];
                    $newName = $possibleNames[array_rand($possibleNames)];
                }
                
                echo "   🔧 Mise à jour avec le nom: '{$newName}'\n";
                
                $product->name = $newName;
                $product->description = "Matériel agricole de qualité professionnelle";
                $product->save();
                
                echo "   ✅ Produit mis à jour!\n";
            } else {
                echo "   ✅ Nom OK: '{$product->name}'\n";
            }
        } else {
            echo "   ❌ Produit introuvable!\n";
        }
        echo "\n";
    }
    
    echo "🔄 Rechargement de la commande...\n";
    $orderLocation = OrderLocation::with(['orderItemLocations', 'orderItemLocations.product'])->first();
    
    echo "📦 Articles après correction:\n";
    foreach ($orderLocation->orderItemLocations as $item) {
        echo "   - {$item->product->name} (Qty: {$item->quantity})\n";
    }
    
    echo "\n✅ Correction terminée!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Trace: " . $e->getTraceAsString() . "\n";
}
