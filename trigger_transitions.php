<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

$order = Order::find(107);
if (!$order) {
    echo "Commande 107 non trouvée\n";
    exit(1);
}

echo "État avant transition:\n";
echo "- Statut: {$order->status}\n";
echo "- Peut être annulée: " . ($order->can_be_cancelled_now ? 'Oui' : 'Non') . "\n";

// Forcer une mise à jour qui déclenchera onConfirmed
if ($order->status === 'confirmed') {
    // Déclencher onConfirmed manuellement via updateStatus
    $order->updateStatus('confirmed', true); // Le flag true force l'exécution automatique
    
    echo "\nTransitions automatiques programmées !\n";
    echo "Une transition vers 'preparing' aura lieu dans 15 secondes.\n";
    echo "Pendant ce temps, la commande peut encore être annulée.\n";
}
