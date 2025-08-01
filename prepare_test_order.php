<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

// Mettre une commande en statut confirmed pour test
$order = Order::latest()->first();
if ($order) {
    $order->update([
        'status' => 'confirmed',
        'can_be_cancelled' => true
    ]);
    echo "Commande {$order->order_number} remise en statut confirmed\n";
} else {
    echo "Aucune commande trouvée\n";
}
