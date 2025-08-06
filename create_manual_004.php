<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

echo "=== Création LOC-MANUAL-004 ===\n";

// Utiliser votre utilisateur
$user = User::where('email', 's.mef2703@gmail.com')->first();
if (!$user) {
    echo "❌ Utilisateur non trouvé\n";
    exit;
}

// Prendre un produit disponible
$product = Product::where('type', 'rental')->first();
if (!$product) {
    echo "❌ Aucun produit de location trouvé\n";
    exit;
}

// Créer LOC-MANUAL-004
$orderLocation = OrderLocation::create([
    'order_number' => 'LOC-MANUAL-004-' . time(),
    'user_id' => $user->id,
    'status' => 'finished', // Directement au statut "terminée"
    'start_date' => Carbon::now()->subDays(10), // Commencée il y a 10 jours
    'end_date' => Carbon::now()->subDays(3),    // Devait finir il y a 3 jours
    'actual_return_date' => Carbon::now()->subHours(2), // Retournée il y a 2h
    'rental_days' => 7, // Durée de la location
    'daily_rate' => 12.79, // Tarif journalier (89.50 / 7 jours)
    'total_amount' => 89.50,
    'deposit_amount' => 150.00,
    'payment_status' => 'paid',
    'payment_method' => 'stripe',
    'stripe_payment_intent_id' => 'pi_manual_004_' . time(),
    'invoice_number' => 'FAC-LOC-004-' . date('Y'),
    'created_at' => Carbon::now()->subDays(10),
    'updated_at' => Carbon::now()->subHours(2),
]);

// Ajouter un article à la location
OrderItemLocation::create([
    'order_location_id' => $orderLocation->id,
    'product_id' => $product->id,
    'product_name' => $product->name,
    'quantity' => 1,
    'price' => 89.50,
    'rental_duration' => 7,
]);

echo "✅ Commande créée : " . $orderLocation->order_number . "\n";
echo "📅 Période : du " . $orderLocation->start_date->format('d/m/Y') . " au " . $orderLocation->end_date->format('d/m/Y') . "\n";
echo "📦 Produit : " . $product->name . "\n";
echo "💰 Montant : " . $orderLocation->total_amount . "€\n";
echo "💳 Dépôt : " . $orderLocation->deposit_amount . "€\n";
echo "📍 Statut : " . $orderLocation->status . "\n";
echo "🔗 URL : " . config('app.url') . "/rental-orders\n";

echo "\n🎯 Vous pouvez maintenant :\n";
echo "1. Aller sur /rental-orders\n";
echo "2. Voir la commande " . $orderLocation->order_number . "\n";
echo "3. Cliquer sur 'Clôturer' pour tester l'inspection\n";
echo "4. Recevoir l'email d'inspection détaillé\n";
