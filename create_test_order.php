<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Order;
use App\Models\Product;

$user = User::first();
if (!$user) {
    echo "Aucun utilisateur trouvé\n";
    exit(1);
}

$order = Order::create([
    'user_id' => $user->id,
    'order_number' => 'TEST-' . time(),
    'total_amount' => 25.00,
    'subtotal' => 20.00,
    'tax_amount' => 5.00,
    'status' => 'confirmed',
    'payment_status' => 'paid',
    'payment_method' => 'stripe',
    'can_be_cancelled' => true,
    'shipping_address' => [
        'name' => 'Test User', 
        'address' => '123 Test St', 
        'city' => 'Test City', 
        'postal_code' => '12345', 
        'country' => 'France'
    ],
    'billing_address' => [
        'name' => 'Test User', 
        'address' => '123 Test St', 
        'city' => 'Test City', 
        'postal_code' => '12345', 
        'country' => 'France'
    ]
]);

echo "Commande créée avec ID: {$order->id}\n";
echo "Statut: {$order->status}\n";
echo "Peut être annulée: " . ($order->can_be_cancelled_now ? 'Oui' : 'Non') . "\n";
echo "can_be_cancelled: " . ($order->can_be_cancelled ? 'true' : 'false') . "\n";
