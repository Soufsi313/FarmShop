<?php
// Script pour nettoyer les retours en double et diagnostiquer
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DIAGNOSTIC ET NETTOYAGE DES RETOURS ===\n\n";

// Vérifier s'il y a des retours en double pour la commande 8
$returns = \App\Models\OrderReturn::where('order_id', 8)->get();

echo "Retours existants pour la commande 8 :\n";
foreach ($returns as $return) {
    echo "  - ID: {$return->id}\n";
    echo "  - Numéro: {$return->return_number}\n";
    echo "  - Statut: {$return->status}\n";
    echo "  - Créé le: {$return->created_at}\n";
    echo "  ---\n";
}

// Nettoyer les doublons si nécessaire
if ($returns->count() > 1) {
    echo "\nSuppressions des retours en double...\n";
    $keepFirst = $returns->first();
    $toDelete = $returns->skip(1);
    
    foreach ($toDelete as $return) {
        echo "Suppression du retour #{$return->id} ({$return->return_number})\n";
        $return->delete();
    }
    echo "Nettoyage terminé !\n";
} else {
    echo "\nAucun doublon trouvé.\n";
}

// Vérifier l'état de la commande 8
$order = \App\Models\Order::with(['items.product', 'user'])->find(8);
if ($order) {
    echo "\n=== ÉTAT DE LA COMMANDE 8 ===\n";
    echo "Numéro: {$order->order_number}\n";
    echo "Statut: {$order->status}\n";
    echo "Client: {$order->user->name}\n";
    echo "Montant total: {$order->total_amount}€\n";
    echo "Créée le: {$order->created_at->format('d/m/Y H:i')}\n";
    
    if ($order->delivered_at) {
        echo "Livrée le: {$order->delivered_at->format('d/m/Y H:i')}\n";
        $returnDeadline = \Carbon\Carbon::parse($order->delivered_at)->addDays(14);
        echo "Deadline retour: {$returnDeadline->format('d/m/Y')}\n";
        echo "Peut être retournée: " . (now()->lte($returnDeadline) ? 'OUI' : 'NON') . "\n";
    }
    
    echo "\nProduits de la commande :\n";
    foreach ($order->items as $item) {
        $product = $item->product;
        $isPerishable = $product ? $product->isPerishable() : $item->is_perishable;
        echo "  - " . ($product ? $product->name : 'Produit supprimé') . "\n";
        echo "    Prix unitaire: {$item->unit_price}€\n";
        echo "    Quantité: {$item->quantity}\n";
        echo "    Périssable: " . ($isPerishable ? 'OUI' : 'NON') . "\n";
        echo "    Retournable: " . (!$isPerishable ? 'OUI' : 'NON') . "\n";
        echo "    ---\n";
    }
} else {
    echo "\nCommande 8 non trouvée.\n";
}

echo "\nDiagnostic terminé !\n";
