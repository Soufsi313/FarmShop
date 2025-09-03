<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use Illuminate\Support\Str;

echo "=== Création de commandes de location pour test d'inspection ===\n\n";

// Récupérer un utilisateur existant
$user = User::where('email', 'LIKE', '%test%')->first() ?: User::first();
if (!$user) {
    echo "❌ Aucun utilisateur trouvé\n";
    exit(1);
}

// Récupérer des produits disponibles pour location
$products = Product::where('is_rental_available', true)
    ->where('rental_stock', '>', 0)
    ->limit(3)
    ->get();

if ($products->count() < 2) {
    echo "❌ Pas assez de produits de location disponibles\n";
    exit(1);
}

$product1 = $products->first();
$product2 = $products->count() > 1 ? $products->skip(1)->first() : $products->first();

echo "📋 Utilisateur sélectionné:\n";
echo "   - Nom: {$user->name}\n";
echo "   - Email: {$user->email}\n\n";

echo "📦 Produits sélectionnés:\n";
echo "   - Produit 1: {$product1->getTranslation('name', 'fr')}\n";
echo "   - Produit 2: {$product2->getTranslation('name', 'fr')}\n\n";

// Configuration des dates
$startDate = now()->subDays(10); // Commencé il y a 10 jours
$endDate = now()->subDays(3);    // Fini il y a 3 jours
$returnDate = now()->subDays(1); // Retourné il y a 1 jour

echo "🗓️  Dates configurées:\n";
echo "   - Début: {$startDate->format('d/m/Y')}\n";
echo "   - Fin: {$endDate->format('d/m/Y')}\n";
echo "   - Retour: {$returnDate->format('d/m/Y')}\n\n";

// COMMANDE 1: SANS DOMMAGES
echo "🟢 Création de la commande 1 (SANS dommages)...\n";

$orderNumber1 = 'LOC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

$orderLocation1 = OrderLocation::create([
    'order_number' => $orderNumber1,
    'user_id' => $user->id,
    'start_date' => $startDate,
    'end_date' => $endDate,
    'rental_days' => $startDate->diffInDays($endDate) + 1,
    'daily_rate' => 25.00,
    'total_rental_cost' => 175.00, // 7 jours × 25€
    'deposit_amount' => 200.00,
    'late_fee_per_day' => 10.00,
    'tax_rate' => 0.21,
    'subtotal' => 175.00,
    'tax_amount' => 36.75,
    'total_amount' => 211.75,
    'status' => 'completed', // Terminée, prête pour inspection
    'payment_status' => 'deposit_paid',
    'payment_method' => 'stripe',
    'payment_reference' => 'pi_test_' . Str::random(20),
    'stripe_payment_intent_id' => 'pi_test_' . Str::random(20),
    'stripe_deposit_authorization_id' => 'pi_test_auth_' . Str::random(15),
    'deposit_status' => 'authorized',
    'billing_address' => [
        'name' => $user->name,
        'street' => '123 Rue de Test',
        'city' => 'Paris',
        'postal_code' => '75001',
        'country' => 'FR'
    ],
    'delivery_address' => [
        'name' => $user->name,
        'street' => '123 Rue de Test',
        'city' => 'Paris',
        'postal_code' => '75001',
        'country' => 'FR'
    ],
    'late_days' => 0,
    'late_fees' => 0,
    'actual_return_date' => $returnDate,
    'inspection_status' => 'pending',
    'confirmed_at' => $startDate->subDays(1),
    'started_at' => $startDate,
    'ended_at' => $endDate,
    'completed_at' => $returnDate,
    'invoice_number' => 'FACTURE-' . now()->format('Ymd') . '-' . rand(1000, 9999),
    'invoice_generated_at' => now(),
    'frontend_confirmed' => true,
    'frontend_confirmed_at' => $startDate->subDays(1),
]);

// Ajouter les items pour la commande 1
OrderItemLocation::create([
    'order_location_id' => $orderLocation1->id,
    'product_id' => $product1->id,
    'product_name' => $product1->getTranslation('name', 'fr'),
    'product_sku' => $product1->sku ?? 'TEST-SKU-1',
    'product_description' => $product1->getTranslation('description', 'fr') ?? 'Produit de test',
    'quantity' => 1,
    'daily_rate' => 25.00,
    'rental_days' => 7,
    'total_price' => 175.00,
    'deposit_per_item' => 200.00,
    'subtotal' => 175.00,
    'total_deposit' => 200.00,
    'tax_amount' => 36.75,
    'total_amount' => 211.75,
    'condition_at_pickup' => 'excellent',
]);

echo "   ✅ Commande créée: {$orderNumber1}\n";
echo "   ✅ Item ajouté: {$product1->getTranslation('name', 'fr')}\n\n";

// COMMANDE 2: AVEC DOMMAGES (retard de 2 jours)
echo "🔴 Création de la commande 2 (AVEC dommages)...\n";

$orderNumber2 = 'LOC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

// Pour cette commande, simulons un retard de 2 jours
$lateReturnDate = $endDate->copy()->addDays(2);
$lateDays = 2;
$lateFees = $lateDays * 10; // 20€ de frais de retard

$orderLocation2 = OrderLocation::create([
    'order_number' => $orderNumber2,
    'user_id' => $user->id,
    'start_date' => $startDate,
    'end_date' => $endDate,
    'rental_days' => $startDate->diffInDays($endDate) + 1,
    'daily_rate' => 30.00,
    'total_rental_cost' => 210.00, // 7 jours × 30€
    'deposit_amount' => 250.00,
    'late_fee_per_day' => 10.00,
    'tax_rate' => 0.21,
    'subtotal' => 210.00,
    'tax_amount' => 44.10,
    'total_amount' => 254.10,
    'status' => 'completed', // Terminée, prête pour inspection
    'payment_status' => 'deposit_paid',
    'payment_method' => 'stripe',
    'payment_reference' => 'pi_test_' . Str::random(20),
    'stripe_payment_intent_id' => 'pi_test_' . Str::random(20),
    'stripe_deposit_authorization_id' => 'pi_test_auth_' . Str::random(15),
    'deposit_status' => 'authorized',
    'billing_address' => [
        'name' => $user->name,
        'street' => '456 Avenue des Tests',
        'city' => 'Lyon',
        'postal_code' => '69001',
        'country' => 'FR'
    ],
    'delivery_address' => [
        'name' => $user->name,
        'street' => '456 Avenue des Tests',
        'city' => 'Lyon',
        'postal_code' => '69001',
        'country' => 'FR'
    ],
    'late_days' => $lateDays,
    'late_fees' => $lateFees,
    'actual_return_date' => $lateReturnDate,
    'inspection_status' => 'pending',
    'confirmed_at' => $startDate->subDays(1),
    'started_at' => $startDate,
    'ended_at' => $endDate,
    'completed_at' => $lateReturnDate,
    'invoice_number' => 'FACTURE-' . now()->format('Ymd') . '-' . rand(1000, 9999),
    'invoice_generated_at' => now(),
    'frontend_confirmed' => true,
    'frontend_confirmed_at' => $startDate->subDays(1),
]);

// Ajouter les items pour la commande 2
OrderItemLocation::create([
    'order_location_id' => $orderLocation2->id,
    'product_id' => $product2->id,
    'product_name' => $product2->getTranslation('name', 'fr'),
    'product_sku' => $product2->sku ?? 'TEST-SKU-2',
    'product_description' => $product2->getTranslation('description', 'fr') ?? 'Produit de test',
    'quantity' => 1,
    'daily_rate' => 30.00,
    'rental_days' => 7,
    'total_price' => 210.00,
    'deposit_per_item' => 250.00,
    'subtotal' => 210.00,
    'total_deposit' => 250.00,
    'tax_amount' => 44.10,
    'total_amount' => 254.10,
    'condition_at_pickup' => 'excellent',
]);

echo "   ✅ Commande créée: {$orderNumber2}\n";
echo "   ✅ Item ajouté: {$product2->getTranslation('name', 'fr')}\n";
echo "   ⚠️  Retard simulé: {$lateDays} jours (frais: {$lateFees}€)\n\n";

echo "📊 RÉSUMÉ DES COMMANDES CRÉÉES:\n\n";

echo "🟢 COMMANDE 1 (Sans dommages attendus):\n";
echo "   - Numéro: {$orderLocation1->order_number}\n";
echo "   - Produit: {$product1->getTranslation('name', 'fr')}\n";
echo "   - Caution: " . number_format($orderLocation1->deposit_amount, 2) . "€\n";
echo "   - Retard: Aucun\n";
echo "   - Statut: {$orderLocation1->status}\n";
echo "   - URL Admin: http://127.0.0.1:8000/admin/rental-returns/{$orderLocation1->id}\n\n";

echo "🔴 COMMANDE 2 (Avec dommages attendus):\n";
echo "   - Numéro: {$orderLocation2->order_number}\n";
echo "   - Produit: {$product2->getTranslation('name', 'fr')}\n";
echo "   - Caution: " . number_format($orderLocation2->deposit_amount, 2) . "€\n";
echo "   - Retard: {$orderLocation2->late_days} jours ({$orderLocation2->late_fees}€)\n";
echo "   - Statut: {$orderLocation2->status}\n";
echo "   - URL Admin: http://127.0.0.1:8000/admin/rental-returns/{$orderLocation2->id}\n\n";

echo "🎯 INSTRUCTIONS POUR LE TEST:\n\n";
echo "1. 🟢 COMMANDE SANS DOMMAGES:\n";
echo "   - Marquer comme 'retournée'\n";
echo "   - Commencer l'inspection\n";
echo "   - Sélectionner 'Aucun dommage' pour tous les items\n";
echo "   - Finaliser → Caution libérée intégralement\n\n";

echo "2. 🔴 COMMANDE AVEC DOMMAGES:\n";
echo "   - Marquer comme 'retournée'\n";
echo "   - Commencer l'inspection\n";
echo "   - Sélectionner 'Dommages détectés' pour au moins un item\n";
echo "   - Ajouter des photos de dommages (optionnel)\n";
echo "   - Finaliser → Caution capturée (250€ + 20€ frais retard = 270€)\n\n";

echo "🚀 Accès admin: http://127.0.0.1:8000/admin/rental-returns\n";
echo "\n=== Commandes créées avec succès ! ===\n";
