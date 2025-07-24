<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Vérification des numéros de commande...\n\n";

// Vérifier la commande en conflit
$conflictOrder = App\Models\Order::where('order_number', 'ORD-2025070001')->first();
if ($conflictOrder) {
    echo "Commande en conflit trouvée:\n";
    echo "- ID: " . $conflictOrder->id . "\n";
    echo "- Numéro: " . $conflictOrder->order_number . "\n";
    echo "- Créée le: " . $conflictOrder->created_at . "\n";
    echo "- Status: " . $conflictOrder->status . "\n\n";
} else {
    echo "Aucune commande trouvée avec le numéro ORD-2025070001\n\n";
}

// Vérifier toutes les commandes du mois actuel
$prefix = 'ORD-' . date('Y') . date('m');
echo "Préfixe actuel: " . $prefix . "\n";

$orders = App\Models\Order::where('order_number', 'like', $prefix . '%')
    ->orderBy('order_number', 'desc')
    ->limit(5)
    ->get();

echo "Dernières commandes du mois:\n";
foreach ($orders as $order) {
    echo "- " . $order->order_number . " (ID: " . $order->id . ", créée le " . $order->created_at . ")\n";
}

// Tester la génération du prochain numéro
echo "\nProchain numéro généré: " . App\Models\Order::generateOrderNumber() . "\n";
