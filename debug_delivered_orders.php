<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;

echo "🔍 Diagnostic des commandes livrées...\n\n";

// Récupérer les commandes livrées récentes
$orders = Order::where('status', 'delivered')
    ->with(['items.product', 'user'])
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

foreach ($orders as $order) {
    echo "📦 Commande #{$order->order_number}\n";
    echo "   Status: {$order->status}\n";
    echo "   Client: {$order->user->name} ({$order->user->email})\n";
    echo "   Articles dans la commande: {$order->items->count()}\n";
    
    if ($order->items->count() > 0) {
        echo "   Détail des articles:\n";
        foreach ($order->items as $item) {
            echo "     - {$item->product_name} (Qté: {$item->quantity})\n";
            echo "       Produit ID: {$item->product_id}\n";
            
            if ($item->product) {
                echo "       Périssable (produit): " . ($item->product->is_perishable ? 'OUI' : 'NON') . "\n";
                echo "       Retournable (produit): " . ($item->product->is_returnable ? 'OUI' : 'NON') . "\n";
                echo "       Méthode isPerishable(): " . ($item->product->isPerishable() ? 'OUI' : 'NON') . "\n";
            } else {
                echo "       ❌ Produit non trouvé dans la base !\n";
            }
            
            echo "       Périssable (item): " . ($item->is_perishable ? 'OUI' : 'NON') . "\n";
            echo "       Retournable (item): " . ($item->is_returnable ? 'OUI' : 'NON') . "\n";
            echo "\n";
        }
    } else {
        echo "   ❌ Aucun article trouvé pour cette commande !\n";
    }
    
    echo "   Deadline retour: " . ($order->return_deadline ? $order->return_deadline->format('d/m/Y') : 'Non définie') . "\n";
    echo "\n" . str_repeat('-', 50) . "\n\n";
}

echo "🎯 Diagnostic terminé!\n";
