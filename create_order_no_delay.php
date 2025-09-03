<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

// Créer une nouvelle commande de location sans retard
echo "🚜 Création d'une nouvelle commande de retour SANS frais de retard...\n";

// Trouver l'utilisateur
$user = User::where('email', 's.mef2703@gmail.com')->first();
if (!$user) {
    echo "❌ Utilisateur non trouvé\n";
    exit;
}

// Trouver le produit débroussailleuse
$product = Product::where('name->fr', 'like', '%Débroussailleuse%')->first();
if (!$product) {
    echo "❌ Produit débroussailleuse non trouvé\n";
    exit;
}

// Générer un numéro de commande unique
$orderNumber = 'LOC-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));

// Créer la commande avec retour À TEMPS (pas de retard)
$startDate = Carbon::now()->subDays(12); // Il y a 12 jours
$endDate = Carbon::now()->subDays(2);    // Il y a 2 jours (fin prévue)
$actualReturnDate = Carbon::now()->subDays(2)->addHours(14); // Retourné le jour prévu à 14h

$order = OrderLocation::create([
    'order_number' => $orderNumber,
    'user_id' => $user->id,
    'start_date' => $startDate,
    'end_date' => $endDate,
    'rental_days' => 10,
    'daily_rate' => 25.00,
    'total_rental_cost' => 250.00,
    'deposit_amount' => 250.00,
    'late_fee_per_day' => 20.00,
    'tax_rate' => 20.00,
    'subtotal' => 250.00,
    'tax_amount' => 50.00,
    'total_amount' => 300.00,
    'status' => 'completed',
    'payment_status' => 'paid',
    'payment_method' => 'stripe',
    'payment_reference' => 'test_no_delay_' . time(),
    'deposit_status' => 'authorized',
    'billing_address' => json_encode([
        'name' => $user->first_name . ' ' . $user->last_name,
        'email' => $user->email,
        'address' => 'Test Address',
        'city' => 'Test City',
        'postal_code' => '12345',
        'country' => 'FR'
    ]),
    'delivery_address' => json_encode([
        'name' => $user->first_name . ' ' . $user->last_name,
        'address' => 'Test Delivery Address',
        'city' => 'Test City',
        'postal_code' => '12345',
        'country' => 'FR'
    ]),
    'actual_return_date' => $actualReturnDate,
    'late_days' => 0, // PAS DE RETARD !
    'late_fees' => 0,
    'damage_cost' => 0,
    'penalty_amount' => 0,
    'deposit_refund' => 250.00, // Tout le dépôt disponible
    'inspection_status' => 'pending',
    'has_damages' => false,
    'auto_calculate_damages' => true
]);

// Ajouter l'item de location
OrderItemLocation::create([
    'order_location_id' => $order->id,
    'product_id' => $product->id,
    'product_name' => $product->getTranslation('name', 'fr'),
    'quantity' => 1,
    'rental_days' => 10,
    'daily_rate' => 25.00,
    'deposit_per_item' => 250.00,
    'subtotal' => 250.00,
    'total_deposit' => 250.00,
    'tax_amount' => 50.00,
    'total_amount' => 300.00,
    'damage_cost' => 0,
    'item_damage_cost' => 0
]);

echo "✅ Nouvelle commande créée avec succès !\n";
echo "📋 Numéro: " . $order->order_number . "\n";
echo "👤 Client: " . $user->first_name . " " . $user->last_name . "\n";
echo "📅 Période: " . $startDate->format('d/m/Y') . " au " . $endDate->format('d/m/Y') . "\n";
echo "⏰ Retour effectif: " . $actualReturnDate->format('d/m/Y à H:i') . "\n";
echo "🎯 Jours de retard: " . $order->late_days . " (AUCUN RETARD !)\n";
echo "💰 Dépôt de garantie: " . number_format($order->deposit_amount, 2) . "€\n";
echo "🔍 Prêt pour inspection de dégâts uniquement\n";

echo "\n🚀 Vous pouvez maintenant tester l'inspection pour les dégâts seulement !\n";
