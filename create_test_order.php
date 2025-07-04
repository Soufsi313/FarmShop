<?php

require_once 'vendor/autoload.php';

// Démarrer l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::first();

if ($user) {
    $order = App\Models\Order::create([
        'user_id' => $user->id,
        'status' => 'confirmed',
        'confirmed_at' => now(),
        'subtotal' => 25.00,
        'total_amount' => 25.00,
        'shipping_address' => json_encode([
            'street' => '123 Test Street',
            'city' => 'Test City',
            'postal_code' => '12345',
            'country' => 'France'
        ]),
        'billing_address' => json_encode([
            'street' => '123 Test Street',
            'city' => 'Test City',
            'postal_code' => '12345',
            'country' => 'France'
        ])
    ]);
    
    echo "✅ Commande test créée: #{$order->order_number}\n";
    echo "📧 Utilisateur: {$user->name} ({$user->email})\n";
    echo "⏰ Status: {$order->status}\n";
    echo "🕒 Confirmée à: {$order->confirmed_at}\n";
} else {
    echo "❌ Aucun utilisateur trouvé dans la base de données\n";
}
