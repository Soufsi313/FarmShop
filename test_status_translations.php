<?php
// Test des traductions de statuts pour les commandes
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST DES TRADUCTIONS DE STATUTS ===\n\n";

// Récupérer quelques commandes pour tester
$orders = \App\Models\Order::with(['user'])->limit(5)->get();

if ($orders->isEmpty()) {
    echo "Aucune commande trouvée.\n";
    exit;
}

// Traductions
$statusTranslations = [
    'pending' => 'En attente',
    'confirmed' => 'Confirmée',
    'preparation' => 'En préparation',
    'shipped' => 'Expédiée',
    'delivered' => 'Livrée',
    'cancelled' => 'Annulée',
    'returned' => 'Retournée'
];

foreach ($orders as $order) {
    $statusFr = $statusTranslations[$order->status] ?? $order->status;
    echo "Commande #{$order->order_number}\n";
    echo "  - Statut EN: {$order->status}\n";
    echo "  - Statut FR: {$statusFr}\n";
    echo "  - Client: {$order->user->name}\n";
    echo "  - Date: {$order->created_at->format('d/m/Y H:i')}\n";
    echo "  ---\n";
}

echo "\nTest terminé !\n";
