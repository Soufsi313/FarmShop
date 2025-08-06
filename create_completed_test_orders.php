<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

echo "=== Création de commandes 'completed' pour test de clôture client ===\n\n";

// Trouver un utilisateur et des produits
$user = User::first();
$products = Product::where('rental_stock', '>', 0)->take(2)->get();

if (!$user || $products->count() < 2) {
    echo "❌ Pas assez d'utilisateurs ou de produits disponibles\n";
    exit;
}

$scenarios = [
    [
        'name' => 'LOCATION TERMINÉE - À CLÔTURER',
        'order_suffix' => 'COMPLETED1',
        'status' => 'completed'
    ],
    [
        'name' => 'LOCATION TERMINÉE - À CLÔTURER 2',
        'order_suffix' => 'COMPLETED2', 
        'status' => 'completed'
    ]
];

foreach ($scenarios as $index => $scenario) {
    $product = $products[$index];
    $orderNumber = 'LOC-TEST-' . $scenario['order_suffix'] . '-' . time() . $index;
    
    echo "📋 Création du scénario: " . $scenario['name'] . "\n";
    echo "🏷️ Numéro: " . $orderNumber . "\n";
    echo "🛠️ Produit: " . $product->name . "\n";
    
    // Dates de location (période passée pour que ce soit "completed")
    $startDate = Carbon::now()->subDays(10);
    $endDate = Carbon::now()->subDays(1); // Finie hier
    
    // Créer la commande de location
    $orderLocation = OrderLocation::create([
        'order_number' => $orderNumber,
        'user_id' => $user->id,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'rental_days' => 9,
        'daily_rate' => $product->rental_price_per_day ?? 25.00,
        'total_rental_cost' => 225.00,
        'deposit_amount' => 150.00,
        'late_fee_per_day' => 10.00,
        'tax_rate' => 21.00,
        'subtotal' => 225.00,
        'tax_amount' => 47.25,
        'total_amount' => 272.25,
        'status' => $scenario['status'],
        'payment_status' => 'paid',
        'payment_method' => 'stripe',
        'stripe_payment_intent_id' => 'pi_test_' . $orderNumber,
        'billing_address' => json_encode(['address' => 'Test Address']),
        'delivery_address' => json_encode(['address' => 'Test Address']),
        'late_days' => 0,
        'late_fees' => 0,
        'damage_cost' => 0,
        'penalty_amount' => 0,
        'inspection_status' => null,
        'confirmed_at' => $startDate,
        'started_at' => $startDate,
        'completed_at' => $endDate
    ]);
    
    // Créer l'item de location
    $orderItemLocation = OrderItemLocation::create([
        'order_location_id' => $orderLocation->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'quantity' => 1,
        'daily_rate' => $product->rental_price_per_day ?? 25.00,
        'rental_days' => 9,
        'deposit_per_item' => 150.00,
        'subtotal' => 225.00,
        'total_deposit' => 150.00,
        'tax_amount' => 47.25,
        'total_amount' => 272.25,
        'condition_at_pickup' => 'excellent',
        'pickup_notes' => 'Matériel en excellent état au départ'
    ]);
    
    echo "✅ Commande créée avec succès!\n";
    echo "📅 Période: " . $startDate->format('d/m/Y') . " → " . $endDate->format('d/m/Y') . "\n";
    echo "🎯 Statut: " . $scenario['status'] . " (prête à être clôturée)\n";
    echo "🌐 URL Client: /rental-orders\n\n";
}

echo "🎯 Les commandes 'completed' sont prêtes !\n";
echo "👤 Connectez-vous en tant que client pour tester la clôture sur /rental-orders\n";
