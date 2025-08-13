<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Vérifier la commande LOC-202508139827
echo "=== VÉRIFICATION COMMANDE LOC-202508139827 ===\n\n";

// 1. État de la commande
$order = DB::table('order_locations')
    ->where('order_number', 'LOC-202508139827')
    ->first();

if ($order) {
    echo "📦 ÉTAT DE LA COMMANDE:\n";
    echo "- ID: {$order->id}\n";
    echo "- Numéro: {$order->order_number}\n";
    echo "- Statut: {$order->status}\n";
    echo "- Statut paiement: {$order->payment_status}\n";
    echo "- Montant: {$order->total_amount}€\n";
    echo "- Créée le: {$order->created_at}\n";
    echo "- Mise à jour: {$order->updated_at}\n\n";
    
    // 2. Articles de la commande
    echo "📋 ARTICLES:\n";
    $items = DB::table('order_item_locations as oil')
        ->join('products as p', 'oil.product_id', '=', 'p.id')
        ->where('oil.order_location_id', $order->id)
        ->select('p.name', 'p.id as product_id', 'oil.quantity', 'oil.daily_rate', 'oil.rental_days', 'oil.total_amount')
        ->get();
    
    foreach ($items as $item) {
        echo "- {$item->name} x{$item->quantity} à {$item->daily_rate}€/jour\n";
        echo "  Durée: {$item->rental_days} jour(s), Total: {$item->total_amount}€\n";
    }
    echo "\n";
    
    // 3. État du stock du produit
    echo "📊 STOCK PRODUIT:\n";
    foreach ($items as $item) {
        $product = DB::table('products')
            ->where('id', $item->product_id)
            ->select('name', 'stock_quantity', 'rental_stock')
            ->first();
        
        if ($product) {
            echo "- {$product->name}:\n";
            echo "  Stock achat: {$product->stock_quantity}\n";
            echo "  Stock location: {$product->rental_stock}\n\n";
        }
    }
    
} else {
    echo "❌ Commande LOC-202508139827 non trouvée\n";
}

// 4. Vérifier l'état du panier
echo "🛒 ÉTAT DU PANIER:\n";
$cartLocation = DB::table('cart_locations')
    ->where('user_id', 1)
    ->first();

if ($cartLocation) {
    echo "- ID panier: {$cartLocation->id}\n";
    echo "- Utilisateur: {$cartLocation->user_id}\n";
    echo "- Créé le: {$cartLocation->created_at}\n";
    
    $cartItems = DB::table('cart_item_locations as cil')
        ->join('products as p', 'cil.product_id', '=', 'p.id')
        ->where('cil.cart_location_id', $cartLocation->id)
        ->select('p.name', 'cil.quantity', 'cil.start_date', 'cil.end_date')
        ->get();
    
    if ($cartItems->count() > 0) {
        echo "- Articles dans le panier: {$cartItems->count()}\n";
        foreach ($cartItems as $item) {
            echo "  • {$item->name} x{$item->quantity}\n";
        }
    } else {
        echo "- Panier vide\n";
    }
} else {
    echo "- Aucun panier trouvé\n";
}
