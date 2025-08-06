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

echo "=== Création de 3 commandes de test pour l'inspection ===\n\n";

// Trouver un utilisateur et des produits
$user = User::first();
$products = Product::where('rental_stock', '>', 0)->take(3)->get();

if (!$user || $products->count() < 3) {
    echo "❌ Pas assez d'utilisateurs ou de produits disponibles\n";
    exit;
}

$scenarios = [
    [
        'name' => 'RETARD SEULEMENT',
        'order_suffix' => 'RETARD',
        'late_days' => 5,
        'late_fees' => 50.00,
        'damage_cost' => 0,
        'condition' => 'good',
        'notes' => 'Matériel en bon état mais retourné avec 5 jours de retard'
    ],
    [
        'name' => 'DÉGÂTS SEULEMENT', 
        'order_suffix' => 'DEGATS',
        'late_days' => 0,
        'late_fees' => 0,
        'damage_cost' => 75.00,
        'condition' => 'damaged',
        'notes' => 'Rayures importantes sur le matériel, nécessite une réparation'
    ],
    [
        'name' => 'RETARD + DÉGÂTS',
        'order_suffix' => 'MIXTE', 
        'late_days' => 2,
        'late_fees' => 20.00,
        'damage_cost' => 35.00,
        'condition' => 'damaged',
        'notes' => 'Retour tardif de 2 jours avec dommages mineurs constatés'
    ]
];

foreach ($scenarios as $index => $scenario) {
    $product = $products[$index];
    $orderNumber = 'LOC-TEST-' . $scenario['order_suffix'] . '-' . time() . $index;
    
    echo "📋 Création du scénario: " . $scenario['name'] . "\n";
    echo "🏷️ Numéro: " . $orderNumber . "\n";
    echo "🛠️ Produit: " . $product->name . "\n";
    
    // Dates de location (période passée pour simulation retard)
    $startDate = Carbon::now()->subDays(10);
    $endDate = Carbon::now()->subDays(3);
    $actualReturnDate = $endDate->copy()->addDays($scenario['late_days']);
    
    // Créer la commande de location
    $orderLocation = OrderLocation::create([
        'order_number' => $orderNumber,
        'user_id' => $user->id,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'actual_return_date' => $actualReturnDate,
        'rental_days' => 7,
        'daily_rate' => $product->rental_price_per_day ?? 25.00,
        'total_rental_cost' => 175.00,  // Ajout explicite
        'deposit_amount' => 100.00,
        'late_fee_per_day' => 10.00,
        'tax_rate' => 21.00,
        'subtotal' => 175.00,
        'tax_amount' => 36.75,
        'total_amount' => 211.75,
        'status' => 'inspecting',
        'payment_status' => 'paid',
        'payment_method' => 'stripe',
        'stripe_payment_intent_id' => 'pi_test_' . $orderNumber,
        'billing_address' => json_encode(['address' => 'Test Address']),
        'delivery_address' => json_encode(['address' => 'Test Address']),
        'late_days' => $scenario['late_days'],
        'late_fees' => $scenario['late_fees'],
        'damage_cost' => $scenario['damage_cost'],
        'penalty_amount' => $scenario['late_fees'] + $scenario['damage_cost'],
        'inspection_status' => 'pending',
        'product_condition' => null, // Sera défini lors de l'inspection
        'inspection_notes' => null,
        'closed_at' => now(),
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
        'daily_rate' => $product->rental_price_per_day ?? 25.00,  // Ajout du daily_rate
        'rental_days' => 7,  // Ajout des rental_days
        'deposit_per_item' => 100.00,  // Ajout deposit_per_item
        'subtotal' => 175.00,
        'total_deposit' => 100.00,  // Ajout total_deposit
        'tax_amount' => 36.75,  // Ajout tax_amount
        'total_amount' => 211.75,  // Ajout total_amount
        'condition_at_pickup' => 'excellent',
        'pickup_notes' => 'Matériel en excellent état au départ',
        'return_condition' => $scenario['condition'],
        'return_notes' => $scenario['notes'],
        'damage_cost' => $scenario['damage_cost'],
        'penalty_amount' => $scenario['late_fees'] + $scenario['damage_cost'],
        'return_checked_at' => now(),
        'return_checked_by' => 1, // Admin ID
        'item_late_days' => $scenario['late_days'],
        'item_late_fees' => $scenario['late_fees']
    ]);
    
    echo "✅ Commande créée avec succès!\n";
    echo "⏰ Jours de retard: " . $scenario['late_days'] . "\n";
    echo "💰 Frais de retard: " . number_format($scenario['late_fees'], 2) . "€\n";
    echo "🔧 Frais de dégâts: " . number_format($scenario['damage_cost'], 2) . "€\n";
    echo "💸 Total pénalités: " . number_format($scenario['late_fees'] + $scenario['damage_cost'], 2) . "€\n";
    echo "🌐 URL Admin: /admin/order-locations/" . $orderLocation->id . "\n\n";
}

echo "🎯 Les 3 commandes de test sont prêtes pour l'inspection!\n";
echo "👨‍💼 Connectez-vous en tant qu'admin pour tester les scénarios.\n";
