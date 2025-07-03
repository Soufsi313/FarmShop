<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Order;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Création d'une commande de test ===\n\n";

$user = User::find(31);
if (!$user) {
    echo "❌ Utilisateur 31 non trouvé\n";
    exit;
}

$order = Order::create([
    'user_id' => $user->id,
    'order_number' => 'TEST-' . time(),
    'status' => 'confirmed',
    'subtotal' => 10.00,
    'tax_amount' => 2.10,
    'shipping_cost' => 2.50,
    'total_amount' => 14.60,
    'shipping_address' => json_encode(['test' => 'address']),
    'billing_address' => json_encode(['test' => 'address']),
    'payment_method' => 'stripe',
    'payment_status' => 'paid',
    'confirmed_at' => now(),
    'paid_at' => now()
]);

echo "✅ Commande créée: {$order->order_number}\n";
echo "   - Utilisateur: {$user->name}\n";
echo "   - Statut: {$order->status}\n";
echo "   - Total: {$order->total_amount}€\n";
echo "   - Confirmée à: {$order->confirmed_at}\n\n";

echo "🔧 Maintenant testez l'automatisation :\n";
echo "   1. Attendez 45 secondes\n";
echo "   2. Exécutez: php artisan orders:update-status\n";
echo "   3. La commande devrait passer à 'preparation'\n\n";
