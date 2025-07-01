<?php

require_once 'vendor/autoload.php';

// Démarrer l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::first();

if ($user) {
    // Créer une commande avec une date dans le passé pour tester immédiatement
    $confirmedAt = now()->subMinutes(2); // 2 minutes dans le passé
    
    $order = App\Models\Order::create([
        'user_id' => $user->id,
        'status' => 'confirmed',
        'confirmed_at' => $confirmedAt,
        'subtotal' => 35.00,
        'total_amount' => 35.00,
        'shipping_address' => json_encode([
            'street' => '456 Old Test Street',
            'city' => 'Old Test City',
            'postal_code' => '54321',
            'country' => 'France'
        ]),
        'billing_address' => json_encode([
            'street' => '456 Old Test Street',
            'city' => 'Old Test City',
            'postal_code' => '54321',
            'country' => 'France'
        ])
    ]);
    
    echo "✅ Commande test (ancienne) créée: #{$order->order_number}\n";
    echo "📧 Utilisateur: {$user->name} ({$user->email})\n";
    echo "⏰ Status: {$order->status}\n";
    echo "🕒 Confirmée à: {$order->confirmed_at} (il y a " . $confirmedAt->diffInSeconds(now()) . " secondes)\n";
    echo "🔄 Cette commande devrait être automatiquement mise à jour lors de la prochaine exécution\n";
} else {
    echo "❌ Aucun utilisateur trouvé dans la base de données\n";
}
