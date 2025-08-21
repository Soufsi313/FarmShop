<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Order;

// Initialiser Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Diagnostic des commandes\n\n";

// Compter les commandes
$totalOrders = Order::count();
echo "📊 Total commandes en base: $totalOrders\n";

// Dernière commande
$lastOrder = Order::latest()->first();
if ($lastOrder) {
    echo "📋 Dernière commande:\n";
    echo "   - ID: {$lastOrder->id}\n";
    echo "   - Numéro: {$lastOrder->order_number}\n";
    echo "   - Total: {$lastOrder->total_amount}€\n";
    echo "   - Statut: {$lastOrder->status}\n";
    echo "   - Client: " . ($lastOrder->user ? $lastOrder->user->email : 'N/A') . "\n";
}

// Statistiques par statut
echo "\n📈 Répartition par statut:\n";
$statuses = ['pending', 'confirmed', 'preparing', 'shipped', 'delivered', 'cancelled', 'returned'];
foreach ($statuses as $status) {
    $count = Order::where('status', $status)->count();
    if ($count > 0) {
        $total = Order::where('status', $status)->sum('total_amount');
        echo "   - $status: $count commandes (Total: " . number_format($total, 2) . "€)\n";
    }
}

// Chiffre d'affaires total
$totalRevenue = Order::whereIn('status', ['delivered', 'shipped'])->sum('total_amount');
echo "\n💰 Chiffre d'affaires (livrées + expédiées): " . number_format($totalRevenue, 2) . "€\n";

// Vérifier quelques commandes avec numéro manquant
echo "\n🔎 Vérification commandes récentes:\n";
$recentOrders = Order::latest()->take(5)->get();
foreach ($recentOrders as $order) {
    $orderNumber = $order->order_number ?: 'MANQUANT';
    echo "   - ID: {$order->id} | Numéro: $orderNumber | Total: {$order->total_amount}€\n";
}
