<?php
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 CRÉATION DE 3 LOCATIONS TERMINÉES\n";
echo "====================================\n\n";

// 1. Récupérer l'utilisateur
$user = App\Models\User::where('email', 's.mef2703@gmail.com')->first();
if (!$user) {
    echo "❌ Utilisateur non trouvé\n";
    exit(1);
}

echo "👤 Utilisateur: {$user->name} (ID: {$user->id})\n\n";

// 2. Récupérer 3 produits disponibles
$products = DB::table('products')
    ->where('is_rental_available', true)
    ->where('rental_stock', '>', 0)
    ->select('id', 'name', 'rental_price_per_day', 'deposit_amount')
    ->limit(3)
    ->get();

if ($products->count() < 3) {
    echo "❌ Pas assez de produits disponibles\n";
    exit(1);
}

echo "📦 Produits sélectionnés:\n";
foreach ($products as $i => $product) {
    echo "   " . ($i + 1) . ". {$product->name} (ID: {$product->id}) - {$product->rental_price_per_day}€/jour\n";
}
echo "\n";

// 3. Créer les 3 locations
$scenarios = [
    ['name' => 'À temps - sans dégâts', 'days_late' => 0, 'damage_cost' => 0],
    ['name' => 'En retard - dégâts partiels', 'days_late' => 2, 'damage_cost' => 45],
    ['name' => 'En retard - dégâts importants', 'days_late' => 3, 'damage_cost' => 120]
];

foreach ($scenarios as $i => $scenario) {
    $product = $products[$i];
    $orderNumber = 'LOC-TEST-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT) . '-' . date('Ymd');
    
    echo ($i + 1) . ". Création: {$scenario['name']}\n";
    echo "   Produit: {$product->name}\n";
    echo "   Numéro: {$orderNumber}\n";
    
    // Calculer les dates
    $startDate = now()->subDays(7 + $i);
    $endDate = $startDate->copy()->addDays(3);
    $completedAt = $endDate->copy()->addDays($scenario['days_late']);
    
    // Calculer le montant
    $rentalDays = 3 + $scenario['days_late'];
    
    echo "   Retard: {$scenario['days_late']} jour(s) = " . ($scenario['days_late'] * 10) . "€\n";
    echo "   Dégâts: {$scenario['damage_cost']}€\n";
    
    try {
        // Calculer les montants
        $depositAmount = $product->deposit_amount ?? 50.00; // Valeur par défaut si pas de dépôt
        $subtotal = $product->rental_price_per_day * $rentalDays;
        $taxAmount = $subtotal * 0.21;
        $totalAmount = $subtotal + $taxAmount;
        
        // Créer la commande avec tous les champs obligatoires
        $orderId = DB::table('order_locations')->insertGetId([
            'user_id' => $user->id,
            'order_number' => $orderNumber,
            'status' => 'completed',
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'rental_days' => $rentalDays,
            'daily_rate' => $product->rental_price_per_day,
            'total_rental_cost' => $subtotal,
            'deposit_amount' => $depositAmount,
            'penalty_amount' => 0.00,
            'late_fee_per_day' => 10.00,
            'tax_rate' => 21.00,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'payment_status' => 'paid',
            'stripe_payment_intent_id' => 'pi_test_' . uniqid(),
            'billing_address' => json_encode(['street' => 'Test Address', 'city' => 'Test City', 'postal_code' => '1000']),
            'delivery_address' => json_encode(['street' => 'Test Address', 'city' => 'Test City', 'postal_code' => '1000']),
            'late_days' => $scenario['days_late'],
            'late_fees' => $scenario['days_late'] * 10.00,
            'damage_cost' => $scenario['damage_cost'],
            'total_penalties' => ($scenario['days_late'] * 10.00) + $scenario['damage_cost'],
            'deposit_refund' => max(0, $depositAmount - ($scenario['days_late'] * 10.00) - $scenario['damage_cost']),
            'confirmed_at' => $startDate->copy()->subDay(),
            'started_at' => $startDate,
            'completed_at' => $completedAt,
            'created_at' => $startDate->copy()->subDays(2),
            'updated_at' => $completedAt,
        ]);
        
        // Créer l'item
        DB::table('order_item_locations')->insert([
            'order_location_id' => $orderId,
            'product_id' => $product->id,
            'quantity' => 1,
            'daily_price' => $product->rental_price_per_day,
            'unit_price' => $subtotal,
            'total_price' => $subtotal,
            'deposit_amount' => $depositAmount,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "   ✅ Créé avec succès (ID: {$orderId})\n";
        echo "   💰 Montant: {$totalAmount}€ pour {$rentalDays} jours\n";
        
    } catch (Exception $e) {
        echo "   ❌ Erreur: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

echo "✅ Script terminé !\n";
