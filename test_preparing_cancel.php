<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

$order = Order::find(109);
if (!$order) {
    echo "Commande 109 non trouvée\n";
    exit(1);
}

// Mettre en statut 'preparing' sans déclencher onPreparing automatiquement
$order->update(['status' => 'preparing']);
$order->refresh();

echo "Commande mise en statut 'preparing'\n";
echo "- ID: {$order->id}\n";
echo "- Statut: {$order->status}\n";
echo "- can_be_cancelled: " . ($order->can_be_cancelled ? 'true' : 'false') . "\n";
echo "- can_be_cancelled_now: " . ($order->can_be_cancelled_now ? 'Oui' : 'Non') . "\n";

if ($order->can_be_cancelled_now) {
    echo "\n🟢 TENTATIVE D'ANNULATION PENDANT PRÉPARATION...\n";
    
    try {
        $order->cancel('Annulation pendant préparation');
        $order->refresh();
        
        echo "✅ SUCCÈS ! La commande en préparation a été annulée !\n";
        echo "- Nouveau statut: {$order->status}\n";
        echo "- Raison: {$order->cancellation_reason}\n";
        
    } catch (Exception $e) {
        echo "❌ ÉCHEC : {$e->getMessage()}\n";
    }
} else {
    echo "\n🔴 La commande en préparation ne peut pas être annulée\n";
}
