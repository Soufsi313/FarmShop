<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Events\Dispatcher;

// Bootstrap de l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

echo "🏗️ Création de 3 locations terminées pour test d'inspection\n\n";

// Récupérer l'utilisateur de test
$user = User::find(1);
if (!$user) {
    echo "❌ Utilisateur non trouvé\n";
    exit;
}

// Récupérer des produits de location
$products = Product::whereIn('type', ['rental', 'both'])
                  ->where('is_rental_available', true)
                  ->limit(3)
                  ->get();
if ($products->count() < 3) {
    echo "❌ Pas assez de produits de location\n";
    exit;
}

// Dates pour les locations (terminées il y a quelques jours)
$scenarios = [
    [
        'name' => 'Sans retard, sans dommage',
        'start_date' => Carbon::now()->subDays(5),
        'end_date' => Carbon::now()->subDays(3),
        'returned_at' => Carbon::now()->subDays(3)->addHours(2), // Retour à l'heure
        'has_delay' => false,
        'has_damage' => false,
        'delay_days' => 0,
        'damage_amount' => 0
    ],
    [
        'name' => 'Avec retard, sans dommage',
        'start_date' => Carbon::now()->subDays(7),
        'end_date' => Carbon::now()->subDays(4),
        'returned_at' => Carbon::now()->subDays(2), // Retour avec 2 jours de retard
        'has_delay' => true,
        'has_damage' => false,
        'delay_days' => 2,
        'damage_amount' => 0
    ],
    [
        'name' => 'Avec retard et dommages',
        'start_date' => Carbon::now()->subDays(10),
        'end_date' => Carbon::now()->subDays(6),
        'returned_at' => Carbon::now()->subDays(4), // Retour avec 2 jours de retard
        'has_delay' => true,
        'has_damage' => true,
        'delay_days' => 2,
        'damage_amount' => 75.00 // Dommages partiels
    ]
];

foreach ($scenarios as $index => $scenario) {
    $product = $products[$index];
    
    $locationNumber = $index + 1;
    echo "📦 Création location {$locationNumber}: {$scenario['name']}\n";
    echo "   Produit: {$product->name}\n";
    echo "   Période: {$scenario['start_date']->format('d/m/Y')} → {$scenario['end_date']->format('d/m/Y')}\n";
    echo "   Retour: {$scenario['returned_at']->format('d/m/Y H:i')}\n";
    
    // Générer un numéro de commande
    $orderNumber = 'LOC-TEST-' . str_pad($locationNumber, 3, '0', STR_PAD_LEFT);
    
    // Créer la location avec tous les détails
    $dailyRate = $product->rental_price_per_day;
    $rentalDays = max(1, $scenario['end_date']->diffInDays($scenario['start_date']));
    
    $orderLocation = OrderLocation::create([
        'user_id' => $user->id,
        'order_number' => $orderNumber,
        'product_id' => $product->id,
        'quantity' => 1,
        'start_date' => $scenario['start_date'],
        'end_date' => $scenario['end_date'],
        'rental_days' => $rentalDays,
        'daily_rate' => $dailyRate,
        'total_rental_cost' => $dailyRate * $rentalDays,
        'subtotal' => $dailyRate * $rentalDays,
        'tax_rate' => 20.0, // 20% TVA
        'tax_amount' => ($dailyRate * $rentalDays) * 0.20,
        'total_amount' => ($dailyRate * $rentalDays) * 1.20, // Avec TVA
        'deposit_amount' => $product->deposit_amount,
        'late_fee_per_day' => 10.00,
        'status' => 'completed', // ✅ Terminée
        'payment_status' => 'paid',
        'payment_method' => 'stripe',
        'confirmed_at' => $scenario['start_date']->copy()->subHours(24),
        'paid_at' => $scenario['start_date']->copy()->subHours(24),
        'started_at' => $scenario['start_date'],
        'completed_at' => $scenario['end_date'],
        'returned_at' => $scenario['returned_at'],
        'actual_return_date' => $scenario['returned_at'],
        
        // Adresses obligatoires
        'billing_address' => json_encode([
            'name' => $user->name,
            'email' => $user->email,
            'address' => '123 Rue de Test',
            'city' => 'Test City',
            'postal_code' => '12345',
            'country' => 'BE'
        ]),
        'delivery_address' => json_encode([
            'name' => $user->name,
            'address' => '123 Rue de Test',
            'city' => 'Test City',
            'postal_code' => '12345',
            'country' => 'BE'
        ]),
        
        // Stripe IDs fictifs pour les tests
        'stripe_payment_intent_id' => 'pi_test_' . uniqid(),
        'stripe_deposit_authorization_id' => 'pi_test_deposit_' . uniqid(),
        'deposit_status' => 'authorized', // Caution autorisée
        
        // Frontend confirmation
        'frontend_confirmed' => true,
        'frontend_confirmed_at' => $scenario['start_date']->copy()->subHours(23),
        
        // Détails de paiement
        'payment_details' => json_encode([
            'stripe_payment_intent_id' => 'pi_test_' . uniqid(),
            'amount_paid' => ($dailyRate * $rentalDays) * 1.20,
            'currency' => 'eur',
            'paid_at' => $scenario['start_date']->copy()->subHours(24)->toISOString()
        ])
    ]);
    
    echo "   ✅ Location créée: {$orderLocation->order_number} (ID: {$orderLocation->id})\n";
    
    // Afficher les détails spéciaux
    if ($scenario['has_damage']) {
        echo "   🔍 Dommages à définir lors de l'inspection: {$scenario['damage_amount']}€\n";
    }
    
    if ($scenario['has_delay']) {
        $delayHours = $scenario['returned_at']->diffInHours($scenario['end_date']);
        echo "   ⏰ Retour en retard de {$delayHours}h\n";
    }
    
    echo "\n";
}

echo "🎉 3 locations de test créées avec succès !\n\n";

echo "📋 RÉCAPITULATIF:\n";
echo "================\n";
$testLocations = OrderLocation::with('product')->where('order_number', 'LIKE', 'LOC-TEST-%')->get();

foreach ($testLocations as $location) {
    echo "• {$location->order_number}: {$location->product->name}\n";
    echo "  Status: {$location->status} | Retour: " . $location->returned_at->format('d/m/Y H:i') . "\n";
    
    // Calculer si en retard
    $isLate = $location->returned_at->isAfter($location->end_date);
    echo "  Retard: " . ($isLate ? "Oui (" . $location->returned_at->diffInHours($location->end_date) . "h)" : "Non") . "\n";
    echo "  Caution: {$location->deposit_amount}€ (autorisée)\n";
    echo "\n";
}

echo "👨‍💼 Prochaines étapes:\n";
echo "1. Aller dans l'interface admin pour voir ces locations\n";
echo "2. Cliquer sur 'Clôturer' pour chaque location\n";
echo "3. Procéder à l'inspection et définir les montants de dommages\n";
echo "4. Tester la logique de capture/déblocage de caution\n";
