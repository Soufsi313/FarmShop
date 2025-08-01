<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

echo "=== Test en temps réel des transitions automatiques ===\n\n";

// Récupérer une commande 'pending' ou prendre la dernière et la remettre en pending
$order = Order::where('status', 'pending')->first();

if (!$order) {
    $order = Order::latest()->first();
    if ($order) {
        $order->update(['status' => 'pending']);
        echo "Commande remise en statut 'pending' pour test\n";
    } else {
        echo "Aucune commande disponible\n";
        exit(1);
    }
}

echo "Commande: {$order->order_number} (ID: {$order->id})\n";
echo "Statut initial: {$order->status}\n\n";

echo "Déclenchement de la progression automatique...\n";
$order->updateStatus('confirmed');

echo "Statut après confirmation: {$order->fresh()->status}\n\n";

echo "Observation des transitions en temps réel...\n";
echo "Les transitions devraient se faire automatiquement toutes les 15 secondes:\n\n";

for ($i = 0; $i < 60; $i += 5) {
    sleep(5);
    $order->refresh();
    
    $time = date('H:i:s');
    echo "[{$time}] Statut actuel: {$order->status}\n";
    
    if ($order->status === 'delivered') {
        echo "\n✅ SUCCÈS! Toutes les transitions automatiques ont fonctionné!\n";
        echo "Séquence complète: confirmed -> preparing -> shipped -> delivered\n";
        break;
    }
    
    if ($i >= 55) {
        echo "\n⚠️ Timeout atteint. Les transitions peuvent prendre plus de temps.\n";
        echo "Vérifiez que le queue worker fonctionne: php artisan queue:work\n";
        break;
    }
}

echo "\nStatut final: {$order->fresh()->status}\n";
echo "Historique des statuts:\n";
if ($order->status_history) {
    foreach ($order->status_history as $statusChange) {
        echo "  {$statusChange['from']} -> {$statusChange['to']} ({$statusChange['timestamp']})\n";
    }
}
