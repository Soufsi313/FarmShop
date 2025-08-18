<?php

require_once 'vendor/autoload.php';

use App\Models\OrderLocation;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DIAGNOSTIC STRUCTURE DONNÉES LOCATION TERMINÉE ===\n\n";

try {
    $orderLocation = OrderLocation::with(['orderItemLocations', 'orderItemLocations.product'])->first();
    
    echo "🔍 Structure de \$orderLocation->orderItemLocations:\n\n";
    
    foreach ($orderLocation->orderItemLocations as $index => $item) {
        echo "📦 Item #{$index}:\n";
        echo "   ID: {$item->id}\n";
        echo "   Quantity: {$item->quantity}\n";
        echo "   Product ID: {$item->product_id}\n";
        echo "   Daily Price: {$item->daily_price}\n";
        echo "   Deposit Amount: {$item->deposit_amount}\n";
        
        // Propriétés disponibles
        echo "   Toutes les propriétés: " . implode(', ', array_keys($item->toArray())) . "\n";
        
        if ($item->product) {
            echo "   ✅ Product chargé:\n";
            echo "      - product->name: '{$item->product->name}'\n";
            echo "      - product->description: '{$item->product->description}'\n";
        }
        
        // Vérifier si product_name existe
        if (isset($item->product_name)) {
            echo "   product_name (direct): '{$item->product_name}'\n";
        } else {
            echo "   ❌ product_name (direct) n'existe pas\n";
        }
        
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
