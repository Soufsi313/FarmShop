<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

$order = Order::find(108);
if (!$order) {
    echo "Commande 107 non trouvée\n";
    exit(1);
}

echo "État actuel de la commande:\n";
echo "- ID: {$order->id}\n";
echo "- Statut: {$order->status}\n";
echo "- can_be_cancelled: " . ($order->can_be_cancelled ? 'true' : 'false') . "\n";
echo "- can_be_cancelled_now: " . ($order->can_be_cancelled_now ? 'Oui' : 'Non') . "\n";

if ($order->can_be_cancelled_now) {
    echo "\n🟢 TENTATIVE D'ANNULATION...\n";
    
    try {
        $order->cancel('Test d\'annulation pendant transition automatique');
        $order->refresh(); // Recharger depuis la DB
        
        echo "✅ SUCCÈS ! La commande a été annulée avec succès !\n";
        echo "- Nouveau statut: {$order->status}\n";
        echo "- Raison d'annulation: {$order->cancellation_reason}\n";
        echo "- Date d'annulation: {$order->cancelled_at}\n";
        
    } catch (Exception $e) {
        echo "❌ ÉCHEC : {$e->getMessage()}\n";
    }
} else {
    echo "\n🔴 La commande ne peut plus être annulée\n";
}
