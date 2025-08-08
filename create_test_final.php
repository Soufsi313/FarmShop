<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Product;
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🎯 Création d'une commande de test finale\n";
echo "========================================\n\n";

// Récupérer votre compte
$user = User::where('email', 's.mef2703@gmail.com')->first();

if (!$user) {
    echo "❌ Votre compte n'a pas été trouvé. Création...\n";
    $user = User::create([
        'name' => 'Meftah Soufiane',
        'email' => 's.mef2703@gmail.com',
        'password' => Hash::make('password123'),
        'role' => 'Customer',
        'email_verified_at' => now()
    ]);
    echo "✅ Compte créé\n";
}

// Sélectionner un produit avec une belle image
$product = Product::whereNotNull('main_image')
    ->where('is_rental_available', 1)
    ->where('rental_stock', '>', 0)
    ->inRandomOrder()
    ->first();

if (!$product) {
    echo "❌ Aucun produit de location disponible\n";
    exit;
}

echo "✅ Votre compte: {$user->name} ({$user->email})\n";
echo "✅ Produit sélectionné: {$product->name}\n";
echo "🖼️  Image produit: {$product->main_image}\n\n";

// Créer des dates avec un retard de 2 jours pour tester
$startDate = now()->subDays(8); // Commencée il y a 8 jours
$endDate = now()->subDays(4);   // Devait finir il y a 4 jours (= 2 jours de retard)
$actualReturnDate = now()->subDays(2); // Retournée il y a 2 jours

echo "📅 Dates de location:\n";
echo "   Début: {$startDate->format('d/m/Y')}\n";
echo "   Fin prévue: {$endDate->format('d/m/Y')}\n";
echo "   Retour effectif: {$actualReturnDate->format('d/m/Y')}\n";
echo "   Retard: 2 jours (pour tester les frais)\n\n";

// Calculs financiers
$rentalDays = 4; // 4 jours de location
$dailyRate = $product->rental_price_per_day ?? 15.00;
$totalRentalCost = $rentalDays * $dailyRate;
$depositAmount = $product->deposit_amount ?? 50.00;
$taxRate = 20.00;
$subtotal = $totalRentalCost;
$taxAmount = $subtotal * ($taxRate / 100);
$totalAmount = $subtotal + $taxAmount;

echo "💰 Calculs financiers:\n";
echo "   Tarif journalier: {$dailyRate}€\n";
echo "   Durée: {$rentalDays} jours\n";
echo "   Coût location: {$totalRentalCost}€\n";
echo "   Caution: {$depositAmount}€\n";
echo "   TVA ({$taxRate}%): {$taxAmount}€\n";
echo "   Total avec TVA: {$totalAmount}€\n\n";

// Créer la commande
$orderNumber = 'LOC-TEST-' . time();

$orderLocation = OrderLocation::create([
    'order_number' => $orderNumber,
    'user_id' => $user->id,
    'start_date' => $startDate,
    'end_date' => $endDate,
    'rental_days' => $rentalDays,
    'daily_rate' => $dailyRate,
    'total_rental_cost' => $totalRentalCost,
    'deposit_amount' => $depositAmount,
    'late_fee_per_day' => 10.00,
    'tax_rate' => $taxRate,
    'subtotal' => $subtotal,
    'tax_amount' => $taxAmount,
    'total_amount' => $totalAmount,
    'status' => 'completed', // Location terminée, prête pour inspection
    'payment_status' => 'paid',
    'payment_method' => 'card',
    'deposit_status' => 'authorized',
    'actual_return_date' => $actualReturnDate,
    'late_days' => 2, // 2 jours de retard
    'inspection_status' => 'pending',
    'billing_address' => json_encode([
        'name' => $user->name,
        'email' => $user->email,
        'address' => '123 Rue de Test',
        'city' => 'TestVille',
        'postal_code' => '12345',
        'country' => 'France'
    ]),
    'delivery_address' => json_encode([
        'name' => $user->name,
        'address' => '123 Rue de Test',
        'city' => 'TestVille',
        'postal_code' => '12345',
        'country' => 'France'
    ]),
    'confirmed_at' => $startDate,
    'started_at' => $startDate,
    'completed_at' => $actualReturnDate,
    'notes' => 'Commande de test finale - Prête pour inspection complète'
]);

// Créer l'item de la commande
$orderItem = OrderItemLocation::create([
    'order_location_id' => $orderLocation->id,
    'product_id' => $product->id,
    'product_name' => $product->name,
    'quantity' => 1,
    'daily_rate' => $dailyRate,
    'rental_days' => $rentalDays,
    'unit_price' => $dailyRate,
    'total_price' => $totalRentalCost,
    'deposit_per_item' => $depositAmount,
    'total_deposit' => $depositAmount
]);

echo "🎉 Commande créée avec succès!\n";
echo "📋 Détails de la commande:\n";
echo "   ID: {$orderLocation->id}\n";
echo "   Numéro: {$orderNumber}\n";
echo "   Propriétaire: {$user->name} ({$user->email})\n";
echo "   Status: {$orderLocation->status} (prêt pour inspection)\n";
echo "   Retard: {$orderLocation->late_days} jours\n";
echo "   Frais de retard suggéré: " . ($orderLocation->late_days * 10) . "€\n\n";

echo "🔗 URLs de test:\n";
echo "   📱 Admin: http://127.0.0.1:8000/admin/rental-returns/{$orderLocation->id}\n";
echo "   📱 Liste admin: http://127.0.0.1:8000/admin/rental-returns\n\n";

echo "🧪 Scénarios de test suggérés:\n";
echo "   1. 🎯 Test basique: 20€ retard (2 jours × 10€), 0€ dégâts\n";
echo "   2. 💥 Test avec dégâts: 20€ retard + 30€ dégâts = 50€ total\n";
echo "   3. ⚖️  Test remise: 15€ retard (au lieu de 20€) + 0€ dégâts\n";
echo "   4. 🚫 Test zéro: 0€ retard + 0€ dégâts (remise exceptionnelle)\n\n";

echo "🎬 Actions à tester:\n";
echo "   ✅ Démarrer l'inspection\n";
echo "   ✅ Vérifier que l'image du produit s'affiche\n";
echo "   ✅ Modifier les frais de retard\n";
echo "   ✅ Ajouter des frais de dégâts sur le produit\n";
echo "   ✅ Voir les totaux se calculer en temps réel\n";
echo "   ✅ Terminer l'inspection\n";
echo "   ✅ Vérifier que tous les affichages sont cohérents\n";
echo "   ✅ Contrôler l'email d'inspection reçu\n\n";

echo "🚀 Prêt pour votre test final ce soir!\n";
echo "   Connectez-vous en admin et allez sur l'URL ci-dessus.\n";
?>
