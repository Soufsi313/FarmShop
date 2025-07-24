<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

echo "=== Test de la progression des statuts après paiement ===\n\n";

// Récupérer la dernière commande
$lastOrder = Order::latest()->first();

if (!$lastOrder) {
    echo "Aucune commande trouvée.\n";
    exit;
}

echo "Commande ID: {$lastOrder->id}\n";
echo "Numéro: {$lastOrder->order_number}\n";
echo "Statut actuel: {$lastOrder->status}\n";
echo "Statut paiement: {$lastOrder->payment_status}\n\n";

// Si la commande est payée mais pas encore expédiée, montrer les prochaines étapes
if ($lastOrder->payment_status === 'paid' && $lastOrder->status === 'confirmed') {
    echo "=== Prochaines étapes disponibles ===\n";
    echo "1. Marquer comme expédiée (shipped)\n";
    echo "2. Marquer comme livrée (delivered)\n\n";
    
    echo "Voulez-vous progresser vers 'shipped' puis 'delivered' ? (pour test)\n";
    echo "Simulation de l'expédition...\n";
    $lastOrder->updateStatus('shipped');
    echo "Statut mis à jour: shipped\n";
    
    echo "Simulation de la livraison...\n";
    $lastOrder->updateStatus('delivered');
    echo "Statut mis à jour: delivered\n\n";
    
    $lastOrder = $lastOrder->fresh();
    echo "Statut final: {$lastOrder->status}\n";
    echo "Expédié le: " . ($lastOrder->shipped_at ? $lastOrder->shipped_at->format('Y-m-d H:i:s') : 'Non défini') . "\n";
    echo "Livré le: " . ($lastOrder->delivered_at ? $lastOrder->delivered_at->format('Y-m-d H:i:s') : 'Non défini') . "\n";
}

echo "\nHistorique des statuts:\n";
if ($lastOrder->status_history && is_array($lastOrder->status_history)) {
    foreach ($lastOrder->status_history as $status) {
        echo "  {$status['from']} -> {$status['to']} (" . date('Y-m-d H:i:s', strtotime($status['timestamp'])) . ")\n";
    }
} else {
    echo "  Aucun historique trouvé\n";
}

echo "\n=== Test terminé ===\n";
