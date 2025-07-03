<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Créer une commande de test
    $user = App\Models\User::find(31);
    
    if (!$user) {
        echo "Utilisateur avec ID 31 non trouvé.\n";
        exit(1);
    }
    
    $orderData = [
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
        'paid_at' => now(),
        'created_at' => now(),
        'updated_at' => now()
    ];
    
    $order = App\Models\Order::create($orderData);
    
    echo "Commande créée avec succès !\n";
    echo "ID: " . $order->id . "\n";
    echo "Numéro: " . $order->order_number . "\n";
    echo "Statut: " . $order->status . "\n";
    echo "Utilisateur: " . $user->name . " (" . $user->email . ")\n";
    echo "Date de confirmation: " . $order->confirmed_at . "\n";
    
} catch (Exception $e) {
    echo "Erreur lors de la création de la commande: " . $e->getMessage() . "\n";
}
