<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\OrderItem;

echo "🔧 Correction des prix des OrderItems...\n\n";

// Récupérer la commande
$order = Order::where('order_number', 'FS202507015879')->with(['items.product'])->first();

if (!$order) {
    echo "❌ Commande non trouvée\n";
    exit;
}

echo "📦 Commande: {$order->order_number}\n";
echo "💰 Montant total: {$order->total_amount}€\n\n";

// Corriger les prix des articles
foreach ($order->items as $item) {
    echo "🔧 Correction article: {$item->product_name}\n";
    
    // Si le produit existe, utiliser son prix actuel
    if ($item->product && $item->product->price > 0) {
        $unitPrice = $item->product->price;
        $totalPrice = $unitPrice * $item->quantity;
        
        echo "   Prix unitaire: {$unitPrice}€\n";
        echo "   Quantité: {$item->quantity}\n";
        echo "   Prix total: {$totalPrice}€\n";
        
        // Mettre à jour l'OrderItem
        $item->update([
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice
        ]);
        
        echo "   ✅ Article corrigé\n\n";
    } else {
        // Si pas de prix, utiliser une estimation basée sur le total de la commande
        $estimatedPrice = $order->total_amount / $order->items->sum('quantity');
        $totalPrice = $estimatedPrice * $item->quantity;
        
        echo "   Prix estimé: {$estimatedPrice}€\n";
        echo "   Quantité: {$item->quantity}\n";
        echo "   Prix total: {$totalPrice}€\n";
        
        $item->update([
            'unit_price' => $estimatedPrice,
            'total_price' => $totalPrice
        ]);
        
        echo "   ✅ Article corrigé (prix estimé)\n\n";
    }
}

echo "🎉 Correction terminée ! Vous pouvez maintenant retenter le retour.\n";
