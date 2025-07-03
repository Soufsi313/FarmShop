<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Order;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Vérification de la commande ===\n\n";

$order = Order::where('order_number', 'FS202507015882')->first();

if ($order) {
    echo "📦 Commande: {$order->order_number}\n";
    echo "   - Statut: {$order->status}\n";
    echo "   - Confirmée: " . ($order->confirmed_at ? $order->confirmed_at->format('Y-m-d H:i:s') : 'Non') . "\n";
    echo "   - Préparation: " . ($order->preparation_at ? $order->preparation_at->format('Y-m-d H:i:s') : 'Non') . "\n";
    echo "   - Expédiée: " . ($order->shipped_at ? $order->shipped_at->format('Y-m-d H:i:s') : 'Non') . "\n";
    echo "   - Créée: " . $order->created_at->format('Y-m-d H:i:s') . "\n";
    echo "   - Mise à jour: " . $order->updated_at->format('Y-m-d H:i:s') . "\n";
    
    // Ne plus corriger automatiquement le statut
    /*
    if ($order->status !== 'confirmed') {
        echo "\n🔧 Correction du statut vers 'confirmed'...\n";
        $order->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'preparation_at' => null,
            'shipped_at' => null,
        ]);
        echo "✅ Statut corrigé\n";
    }
    */
} else {
    echo "❌ Commande non trouvée\n";
}

echo "\n=== État de toutes les commandes ===\n";
$orders = Order::all(['order_number', 'status', 'confirmed_at', 'preparation_at', 'shipped_at']);

foreach ($orders as $order) {
    echo "📦 {$order->order_number} - {$order->status}";
    if ($order->confirmed_at) echo " (confirmée: " . $order->confirmed_at->format('H:i:s') . ")";
    if ($order->preparation_at) echo " (préparation: " . $order->preparation_at->format('H:i:s') . ")";
    if ($order->shipped_at) echo " (expédiée: " . $order->shipped_at->format('H:i:s') . ")";
    echo "\n";
}
