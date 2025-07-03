<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Order;
use Carbon\Carbon;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test des délais d'automatisation ===\n\n";

// Trouver ou créer une commande de test
$testOrder = Order::where('status', 'confirmed')->first();

if (!$testOrder) {
    echo "Aucune commande confirmée trouvée. Création d'une commande de test...\n";
    
    $user = \App\Models\User::first();
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé pour créer une commande de test\n";
        exit;
    }
    
    $testOrder = Order::create([
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
        'confirmed_at' => Carbon::now()->subMinutes(2), // Il y a 2 minutes
        'paid_at' => Carbon::now()->subMinutes(2),
    ]);
    
    echo "✅ Commande de test créée: {$testOrder->order_number}\n";
}

echo "📦 Commande test: {$testOrder->order_number}\n";
echo "   - Statut: {$testOrder->status}\n";
echo "   - Confirmée à: " . ($testOrder->confirmed_at ? $testOrder->confirmed_at->format('Y-m-d H:i:s') : 'Non définie') . "\n";
echo "   - Il y a: " . ($testOrder->confirmed_at ? $testOrder->confirmed_at->diffForHumans() : 'Non définie') . "\n";

// Vérifier si la commande devrait être mise à jour
$now = Carbon::now();
$delaySeconds = 60; // 1 minute
$shouldBeUpdated = false;

if ($testOrder->confirmed_at) {
    $timeDiff = $now->diffInSeconds($testOrder->confirmed_at);
    echo "   - Temps écoulé: {$timeDiff} secondes\n";
    echo "   - Délai requis: {$delaySeconds} secondes\n";
    $shouldBeUpdated = $timeDiff >= $delaySeconds;
} else {
    $timeDiff = $now->diffInSeconds($testOrder->updated_at);
    echo "   - Temps écoulé (updated_at): {$timeDiff} secondes\n";
    echo "   - Délai requis: {$delaySeconds} secondes\n";
    $shouldBeUpdated = $timeDiff >= $delaySeconds;
}

echo "   - Devrait être mise à jour: " . ($shouldBeUpdated ? '✅ OUI' : '❌ NON') . "\n\n";

echo "🔧 Exécution de l'automatisation...\n";

// Exécuter la commande d'automatisation
$exitCode = \Artisan::call('orders:update-status', ['--verbose' => true]);
$output = \Artisan::output();

echo "📄 Sortie de la commande:\n";
echo $output;

// Vérifier l'état après automatisation
$testOrder->refresh();
echo "\n📊 État après automatisation:\n";
echo "   - Nouveau statut: {$testOrder->status}\n";
echo "   - Préparation à: " . ($testOrder->preparation_at ? $testOrder->preparation_at->format('Y-m-d H:i:s') : 'Non définie') . "\n";

echo "\n🎯 Test terminé !\n";
