<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🏗️  Création d'une commande de location pour VOTRE compte\n\n";

// Récupérer VOTRE compte
$user = App\Models\User::find(1); // Meftah Soufiane
if (!$user) {
    echo "❌ Votre compte non trouvé\n";
    exit(1);
}

// Prendre un produit de location existant
$product = App\Models\Product::where('type', 'rental')
    ->where('is_active', true)
    ->where('rental_stock', '>', 0)
    ->first();

if (!$product) {
    echo "❌ Aucun produit de location disponible\n";
    exit(1);
}

echo "✅ Votre compte: {$user->name} ({$user->email})\n";
echo "✅ Produit sélectionné: {$product->name}\n\n";

// Dates de location (terminée il y a quelques jours pour pouvoir tester les retards)
$startDate = now()->subDays(8);  // Commencée il y a 8 jours
$endDate = now()->subDays(3);    // Finie il y a 3 jours (donc 3 jours de retard)
$rentalDays = $startDate->diffInDays($endDate);

// Calculs financiers
$dailyRate = 15.00;
$quantity = 1;
$totalRentalCost = $dailyRate * $rentalDays * $quantity;
$depositAmount = 50.00;
$taxRate = 0.20; // 20%
$subtotal = $totalRentalCost;
$taxAmount = $subtotal * $taxRate;
$totalAmount = $subtotal + $taxAmount;

echo "📅 Dates de location:\n";
echo "   Début: {$startDate->format('d/m/Y')}\n";
echo "   Fin prévue: {$endDate->format('d/m/Y')}\n";
echo "   Durée: {$rentalDays} jours\n";
echo "   Retard prévu: 3 jours (pour tester les frais)\n\n";

echo "💰 Calculs financiers:\n";
echo "   Tarif journalier: {$dailyRate}€\n";
echo "   Coût location: {$totalRentalCost}€\n";
echo "   Caution: {$depositAmount}€\n";
echo "   Total avec TVA: {$totalAmount}€\n\n";

try {
    DB::beginTransaction();

    // Créer la commande de location avec TOUS les champs pour VOTRE compte
    $orderLocation = App\Models\OrderLocation::create([
        'order_number' => 'LOC-INSPECT-' . time(),
        'user_id' => $user->id,  // VOTRE ID
        'start_date' => $startDate,
        'end_date' => $endDate,
        'rental_days' => $rentalDays,
        'daily_rate' => $dailyRate,
        'total_rental_cost' => $totalRentalCost,
        'deposit_amount' => $depositAmount,
        'late_fee_per_day' => 10.00, // 10€ par jour de retard
        'tax_rate' => $taxRate,
        'subtotal' => $subtotal,
        'tax_amount' => $taxAmount,
        'total_amount' => $totalAmount,
        'status' => 'completed', // Location terminée, prête pour inspection
        'payment_status' => 'paid',
        'payment_method' => 'card',
        'payment_reference' => 'test_ref_' . time(),
        'deposit_status' => 'authorized',
        'confirmed_at' => $startDate->copy()->subHours(2),
        'started_at' => $startDate,
        'completed_at' => now(), // Terminée maintenant
        'billing_address' => [
            'name' => $user->name,
            'address' => '123 Rue de Test',
            'city' => 'Test City',
            'postal_code' => '12345',
            'country' => 'France'
        ],
        'delivery_address' => [
            'name' => $user->name,
            'address' => '123 Rue de Test',
            'city' => 'Test City',
            'postal_code' => '12345',
            'country' => 'France'
        ],
        'notes' => 'Commande de test pour inspection avec retards - Créée pour ' . $user->name
    ]);

    // Créer l'item de location avec TOUS les champs
    $orderItem = App\Models\OrderItemLocation::create([
        'order_location_id' => $orderLocation->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'quantity' => $quantity,
        'daily_rate' => $dailyRate,
        'rental_days' => $rentalDays,
        'deposit_per_item' => $depositAmount,
        'subtotal' => $subtotal,
        'total_deposit' => $depositAmount,
        'tax_amount' => $taxAmount,
        'total_amount' => $totalAmount,
        'condition_at_pickup' => 'excellent'
    ]);

    DB::commit();

    echo "🎉 Commande créée avec succès pour VOTRE compte!\n\n";
    echo "📋 Détails de la commande:\n";
    echo "   ID: {$orderLocation->id}\n";
    echo "   Numéro: {$orderLocation->order_number}\n";
    echo "   Propriétaire: {$user->name} ({$user->email})\n";
    echo "   Status: {$orderLocation->status}\n";
    echo "   URL Client: http://127.0.0.1:8000/rental-orders\n";
    echo "   URL Admin: http://127.0.0.1:8000/admin/rental-returns/{$orderLocation->id}\n\n";
    
    echo "🎯 Maintenant vous pouvez:\n";
    echo "1. Aller sur http://127.0.0.1:8000/rental-orders (connecté avec votre compte)\n";
    echo "2. Clôturer cette location manuellement\n";
    echo "3. En tant qu'admin, faire l'inspection et tester les frais\n\n";
    
    echo "✨ Cette commande VOUS appartient maintenant!\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "❌ Erreur lors de la création: " . $e->getMessage() . "\n";
    echo "📍 Ligne: " . $e->getLine() . "\n";
}
