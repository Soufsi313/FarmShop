<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$userId = 102; // saurouk313@gmail.com
$productId = 258;

// Créer une location avec 2 jours de retard
// Date de début: il y a 12 jours
// Date de fin prévue: il y a 5 jours (7 jours de location)
// Date de retour effectif: il y a 3 jours (donc 2 jours de retard)
$startDate = Carbon::now()->subDays(12);
$endDate = Carbon::now()->subDays(5);
$actualReturnDate = Carbon::now()->subDays(3); // 2 jours après la fin prévue
$lateDays = 2;

// Générer un numéro de commande unique
$orderNumber = 'LOC-RETARD-' . time();

// Frais de retard suggérés: 2 jours × 10€ = 20€
$suggestedLateFees = $lateDays * 10;

// Créer la commande de location
$orderLocationId = DB::table('order_locations')->insertGetId([
    'order_number' => $orderNumber,
    'user_id' => $userId,
    'start_date' => $startDate,
    'end_date' => $endDate,
    'rental_days' => 7,
    'daily_rate' => 25.00,
    'total_rental_cost' => 175.00,
    'deposit_amount' => 100.00,
    'tax_rate' => 21,
    'subtotal' => 175.00,
    'tax_amount' => 36.75,
    'total_amount' => 311.75,
    'status' => 'closed',
    'payment_status' => 'paid',
    'payment_method' => 'stripe',
    'payment_reference' => 'pi_test_' . uniqid(),
    'stripe_payment_intent_id' => 'pi_test_' . uniqid(),
    'deposit_status' => 'authorized',
    'inspection_status' => 'pending',
    'product_condition' => null,
    'damage_cost' => 0,
    'late_days' => $lateDays,
    'late_fees' => $suggestedLateFees,
    'total_penalties' => 0,
    'deposit_refund' => 0,
    'actual_return_date' => $actualReturnDate,
    'billing_address' => json_encode([
        'name' => 'Saurouk Test',
        'address' => 'Rue de Test 123',
        'postal_code' => '1000',
        'city' => 'Bruxelles',
        'country' => 'Belgique'
    ]),
    'delivery_address' => json_encode([
        'name' => 'Saurouk Test',
        'address' => 'Rue de Test 123',
        'postal_code' => '1000',
        'city' => 'Bruxelles',
        'country' => 'Belgique'
    ]),
    'created_at' => $startDate,
    'updated_at' => Carbon::now(),
]);

// Créer l'item de location
DB::table('order_item_locations')->insert([
    'order_location_id' => $orderLocationId,
    'product_id' => $productId,
    'product_name' => 'Produit de location test',
    'product_sku' => 'TEST-LOC-001',
    'product_description' => 'Produit de location pour test inspection',
    'quantity' => 1,
    'daily_rate' => 25.00,
    'rental_days' => 7,
    'deposit_per_item' => 100.00,
    'subtotal' => 175.00, // 25 * 1 * 7
    'total_deposit' => 100.00, // 100 * 1
    'tax_amount' => 36.75, // 21% TVA
    'total_amount' => 211.75, // 175 + 36.75
    'condition_at_pickup' => 'excellent',
    'created_at' => $startDate,
    'updated_at' => Carbon::now(),
]);

echo "✅ Location avec retard créée avec succès!\n";
echo "📦 Produit ID: $productId\n";
echo "👤 User ID: $userId (saurouk313@gmail.com)\n";
echo "📅 Période de location: Du " . $startDate->format('d/m/Y') . " au " . $endDate->format('d/m/Y') . " (7 jours)\n";
echo "📅 Date de retour effective: " . $actualReturnDate->format('d/m/Y') . "\n";
echo "⏰ Retard: {$lateDays} jours\n";
echo "💰 Frais de retard suggérés: {$suggestedLateFees} €\n";
echo "💰 Total location: 311,75 € (175€ + 36,75€ TVA + 100€ caution)\n";
echo "📊 Statut: closed (prêt pour inspection admin)\n";
echo "🔢 Numéro: {$orderNumber}\n";
echo "\n🎯 Cette location a 2 jours de retard. L'admin peut maintenant l'inspecter et appliquer les frais de retard!\n";
