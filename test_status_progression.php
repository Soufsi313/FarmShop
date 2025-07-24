<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

echo "=== Test de progression des statuts ===\n\n";

// Récupérer la dernière commande
$lastOrder = Order::latest()->first();

if (!$lastOrder) {
    echo "Aucune commande trouvée.\n";
    exit;
}

echo "Commande ID: {$lastOrder->id}\n";
echo "Numéro de commande: {$lastOrder->order_number}\n";
echo "Statut actuel: {$lastOrder->status}\n";
echo "Statut paiement: {$lastOrder->payment_status}\n\n";

echo "Historique des statuts:\n";
if ($lastOrder->status_history && is_array($lastOrder->status_history)) {
    foreach ($lastOrder->status_history as $status) {
        echo "  {$status['from']} -> {$status['to']} ({$status['timestamp']})\n";
    }
} else {
    echo "  Aucun historique trouvé\n";
}

echo "\n=== Test de progression manuelle ===\n";

// Si la commande est en "pending", la faire progresser manuellement pour tester
if ($lastOrder->status === 'pending') {
    echo "Progression: pending -> confirmed\n";
    $lastOrder->updateStatus('confirmed');
    
    echo "Progression: confirmed -> preparing\n";
    $lastOrder->updateStatus('preparing');
    
    echo "Progression: preparing -> shipped\n";
    $lastOrder->updateStatus('shipped');
    
    echo "Progression: shipped -> delivered\n";
    $lastOrder->updateStatus('delivered');
    
    echo "\nStatut final: {$lastOrder->fresh()->status}\n";
    
    echo "\nHistorique final:\n";
    $finalOrder = $lastOrder->fresh();
    if ($finalOrder->status_history && is_array($finalOrder->status_history)) {
        foreach ($finalOrder->status_history as $status) {
            echo "  {$status['from']} -> {$status['to']} ({$status['timestamp']})\n";
        }
    }
}

echo "\n=== Fin du test ===\n";
