<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;

echo "=== Test de restauration du stock lors d'annulation ===\n\n";

// Trouver une commande confirmée existante qui peut être annulée
$orderLocation = OrderLocation::where('status', 'confirmed')
    ->where('start_date', '>', now())
    ->with('items.product')
    ->first();

if (!$orderLocation) {
    echo "Aucune commande confirmée future trouvée pour le test\n";
    exit;
}

echo "Commande trouvée: {$orderLocation->order_number}\n";
echo "Statut actuel: {$orderLocation->status}\n";
echo "Date de début: {$orderLocation->start_date}\n\n";

// Afficher le stock avant annulation
foreach ($orderLocation->items as $item) {
    $product = $item->product;
    echo "Produit: {$product->name}\n";
    echo "Quantité dans commande: {$item->quantity}\n";
    echo "Stock de location avant annulation: {$product->rental_stock}\n";
    echo "Stock de vente avant annulation: {$product->quantity}\n\n";
}

// Annuler la commande
echo "Annulation de la commande...\n";
$result = $orderLocation->cancel('cancelled_before_start');

if ($result) {
    echo "✅ Commande annulée avec succès\n\n";
    
    $orderLocation->refresh();
    echo "Nouveau statut: {$orderLocation->status}\n";
    echo "Raison d'annulation: {$orderLocation->cancellation_reason}\n\n";
    
    // Vérifier le stock après annulation
    echo "Stock après annulation:\n";
    foreach ($orderLocation->items as $item) {
        $product = $item->product;
        $product->refresh();
        echo "Produit: {$product->name}\n";
        echo "Stock de location: {$product->rental_stock}\n";
        echo "Stock de vente: {$product->quantity}\n\n";
    }
    
} else {
    echo "❌ Erreur lors de l'annulation\n";
}

echo "Test terminé.\n";
