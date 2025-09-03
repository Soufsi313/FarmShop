<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\Product;

// Vérifier et corriger les commandes
$orderNumbers = ['LOC-20250903-3B43A0', 'LOC-20250903-312A6B'];

$product = Product::where('name->fr', 'like', '%Débroussailleuse%')->first();

foreach ($orderNumbers as $orderNumber) {
    $order = OrderLocation::where('order_number', $orderNumber)->first();
    
    if ($order) {
        echo "🔧 Vérification de la commande: " . $order->order_number . "\n";
        
        // Compter les items existants
        $itemsCount = $order->orderItemLocations()->count();
        echo "📦 Nombre d'items trouvés: " . $itemsCount . "\n";
        
        if ($itemsCount == 0 && $product) {
            echo "➕ Ajout de l'item débroussailleuse...\n";
            
            // Créer l'item manquant
            OrderItemLocation::create([
                'order_location_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->getTranslation('name', 'fr'),
                'quantity' => 1,
                'rental_days' => 10,
                'daily_rate' => 25.00,
                'deposit_per_item' => 250.00,
                'subtotal' => 250.00,
                'total_deposit' => 250.00,
                'tax_amount' => 50.00,
                'total_amount' => 300.00,
                'item_damage_cost' => 0
            ]);
            
            echo "✅ Item ajouté avec succès !\n";
        }
        
        // Vérifier le résultat
        $newItemsCount = $order->orderItemLocations()->count();
        echo "� Nombre d'items après correction: " . $newItemsCount . "\n";
        echo "-------------------\n";
        
    } else {
        echo "❌ Commande " . $orderNumber . " non trouvée\n";
    }
}

echo "\n🚀 Rechargez la page admin - vous devriez maintenant voir les produits et les checkboxes !\n";
