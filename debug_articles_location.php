<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\OrderLocation;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DIAGNOSTIC DES ARTICLES DE LOCATION ===\n\n";

try {
    // Récupérer une commande de location avec ses relations
    $orderLocation = OrderLocation::with(['user', 'items', 'items.product'])->first();
    
    if (!$orderLocation) {
        echo "❌ Aucune commande de location trouvée.\n";
        exit;
    }
    
    echo "✅ Commande trouvée: #{$orderLocation->id}\n";
    echo "👤 Client: {$orderLocation->user->first_name} {$orderLocation->user->last_name}\n\n";
    
    echo "📊 STRUCTURE DES DONNÉES:\n";
    echo str_repeat("-", 50) . "\n";
    
    // Vérifier les items
    echo "🔍 Nombre d'items: " . $orderLocation->items->count() . "\n\n";
    
    if ($orderLocation->items->count() > 0) {
        foreach ($orderLocation->items as $index => $item) {
            echo "📦 ITEM #" . ($index + 1) . ":\n";
            echo "   ID: {$item->id}\n";
            echo "   Quantité: {$item->quantity}\n";
            echo "   Product ID: {$item->product_id}\n";
            
            if ($item->product) {
                echo "   ✅ Produit chargé:\n";
                echo "      - Nom: '{$item->product->name}'\n";
                echo "      - Description: '{$item->product->description}'\n";
                echo "      - Type: '{$item->product->type}'\n";
            } else {
                echo "   ❌ Produit NON chargé (relation manquante)\n";
            }
            echo "\n";
        }
    } else {
        echo "❌ Aucun item trouvé pour cette commande.\n\n";
        
        // Vérifier dans la table directement
        echo "🔍 Vérification directe dans la base de données:\n";
        $directItems = DB::table('order_location_items')
            ->where('order_location_id', $orderLocation->id)
            ->get();
        
        echo "   Items dans la table: " . $directItems->count() . "\n";
        foreach ($directItems as $item) {
            echo "   - Item ID: {$item->id}, Product ID: {$item->product_id}, Qty: {$item->quantity}\n";
        }
    }
    
    // Vérifier la structure de la table order_location_items
    echo "\n📋 STRUCTURE DE LA TABLE order_location_items:\n";
    echo str_repeat("-", 50) . "\n";
    
    $columns = DB::select("SHOW COLUMNS FROM order_location_items");
    foreach ($columns as $column) {
        echo "   - {$column->Field} ({$column->Type})\n";
    }
    
    // Vérifier le modèle OrderLocation
    echo "\n🔧 VÉRIFICATION DU MODÈLE:\n";
    echo str_repeat("-", 50) . "\n";
    
    $reflection = new ReflectionClass($orderLocation);
    echo "   Classe: " . $reflection->getName() . "\n";
    
    // Vérifier les relations définies
    if (method_exists($orderLocation, 'items')) {
        echo "   ✅ Relation 'items' existe\n";
    } else {
        echo "   ❌ Relation 'items' manquante\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Trace: " . $e->getTraceAsString() . "\n";
}
