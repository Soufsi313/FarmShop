<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\User;

echo "🧪 Création rapide de commandes de test...\n\n";

// Utiliser un utilisateur existant
$user = User::first();
if (!$user) {
    echo "❌ Aucun utilisateur trouvé. Créez d'abord un utilisateur.\n";
    exit;
}

// Mettre à jour quelques commandes existantes pour avoir différents statuts
$orders = Order::limit(5)->get();

if ($orders->count() < 3) {
    echo "❌ Pas assez de commandes existantes. Créez d'abord quelques commandes.\n";
    exit;
}

// Modifier les statuts pour avoir des exemples
if (isset($orders[0])) {
    $orders[0]->update([
        'status' => Order::STATUS_CONFIRMED,
        'confirmed_at' => now()->subMinutes(30)
    ]);
    echo "✅ Commande #{$orders[0]->order_number} → Confirmée (peut être annulée)\n";
}

if (isset($orders[1])) {
    $orders[1]->update([
        'status' => Order::STATUS_PREPARATION,
        'confirmed_at' => now()->subHours(2),
        'preparation_at' => now()->subMinutes(45)
    ]);
    echo "✅ Commande #{$orders[1]->order_number} → En préparation (peut être annulée)\n";
}

if (isset($orders[2])) {
    $orders[2]->update([
        'status' => Order::STATUS_DELIVERED,
        'confirmed_at' => now()->subDays(5),
        'preparation_at' => now()->subDays(5)->addMinutes(90),
        'shipped_at' => now()->subDays(4),
        'delivered_at' => now()->subDays(3),
        'return_deadline' => now()->addDays(11) // Encore dans les 14 jours
    ]);
    echo "✅ Commande #{$orders[2]->order_number} → Livrée (peut être retournée)\n";
}

echo "\n🎯 Commandes de test prêtes !\n";
echo "🚀 Rendez-vous sur /admin/orders/cancellation pour tester.\n";
