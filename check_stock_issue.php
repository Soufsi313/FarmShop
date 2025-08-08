<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

// Trouver le produit chaussures de sécurité
$product = Product::where('name', 'like', '%chaussure%')->orWhere('name', 'like', '%sécurité%')->first();

if (!$product) {
    $product = Product::where('quantity', 88)->first(); // Chercher par quantité actuelle
}

if ($product) {
    echo "=== DIAGNOSTIC PRODUIT ===\n";
    echo "ID: " . $product->id . "\n";
    echo "Nom: " . $product->name . "\n";
    echo "Stock actuel: " . $product->quantity . "\n";
    echo "Prix: " . $product->price . "€\n";
    
    // Vérifier les commandes récentes
    echo "\n=== COMMANDES RÉCENTES ===\n";
    $recentOrders = Order::whereHas('items', function($query) use ($product) {
        $query->where('product_id', $product->id);
    })->with('items')->orderBy('created_at', 'desc')->take(5)->get();
    
    foreach ($recentOrders as $order) {
        $item = $order->items->where('product_id', $product->id)->first();
        echo "Commande {$order->order_number} - Status: {$order->status} - Qté: {$item->quantity} - Date: {$order->created_at}\n";
    }
    
    // Vérifier l'historique des modifications
    echo "\n=== MODIFICATIONS RÉCENTES ===\n";
    echo "Updated at: " . $product->updated_at . "\n";
    
} else {
    echo "Produit non trouvé\n";
    echo "Produits avec plus de 80 unités:\n";
    $products = Product::where('quantity', '>', 80)->get();
    foreach ($products as $p) {
        echo "- {$p->name} : {$p->quantity} unités\n";
    }
}
