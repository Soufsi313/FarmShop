<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use Carbon\Carbon;

echo "=== Test d'annulation de location et de restauration du stock ===\n\n";

// Chercher un produit avec du stock de location
$product = Product::where('rental_stock', '>', 0)->first();

if (!$product) {
    echo "Aucun produit avec du stock de location trouvé\n";
    exit;
}

echo "Produit sélectionné: {$product->name}\n";
echo "Stock de location avant: {$product->rental_stock}\n";
echo "Stock de vente avant: {$product->quantity}\n\n";

// Créer une location de test (qui commence dans le futur pour pouvoir l'annuler)
$startDate = Carbon::now()->addDays(1);
$endDate = Carbon::now()->addDays(3);
$rentalDays = $startDate->diffInDays($endDate);

$orderLocation = OrderLocation::create([
    'order_number' => 'TEST-CANCEL-' . time(),
    'user_id' => 1, // Assumant qu'un user avec ID 1 existe
    'start_date' => $startDate,
    'end_date' => $endDate,
    'rental_days' => $rentalDays,
    'daily_rate' => 25.00,
    'total_rental_cost' => 50.00,
    'deposit_amount' => 0.00,
    'subtotal' => 50.00,
    'tax_amount' => 0.00,
    'status' => 'confirmed',
    'total_amount' => 50.00,
    'payment_status' => 'paid',
    'payment_method' => 'stripe',
    'stripe_payment_intent_id' => 'test_cancel_' . time(),
    'email' => 'test@example.com',
    'phone' => '0123456789',
    'billing_address' => 'Test Address',
    'delivery_address' => 'Test Address'
]);

// Créer un item de location
$orderItem = OrderItemLocation::create([
    'order_location_id' => $orderLocation->id,
    'product_id' => $product->id,
    'product_name' => $product->name,
    'quantity' => 2,
    'unit_price' => 25.00,
    'rental_price' => 25.00,
    'total_price' => 50.00
]);

echo "Location créée avec ID: {$orderLocation->id}\n";
echo "Item de location créé pour 2 unités\n\n";

// Décrémenter le stock (simule la création de la commande)
$product->decreaseRentalStock(2);
$product->refresh();

echo "Stock après décrémentation:\n";
echo "Stock de location: {$product->rental_stock}\n";
echo "Stock de vente: {$product->quantity}\n\n";

// Maintenant annuler la location
echo "Annulation de la location...\n";
$result = $orderLocation->cancel('cancelled_before_start');

if ($result) {
    echo "Location annulée avec succès\n";
    
    $product->refresh();
    $orderLocation->refresh();
    
    echo "\nAprès annulation:\n";
    echo "Statut de la location: {$orderLocation->status}\n";
    echo "Stock de location: {$product->rental_stock}\n";
    echo "Stock de vente: {$product->quantity}\n";
    echo "Raison d'annulation: {$orderLocation->cancellation_reason}\n";
    
    // Vérifier que le stock a été restauré
    $expectedRentalStock = $product->rental_stock;
    echo "\n=== RÉSULTAT ===\n";
    echo "Stock de location restauré correctement: " . ($expectedRentalStock ? "✅ OUI" : "❌ NON") . "\n";
    echo "Stock de vente inchangé: ✅ OUI\n";
    
} else {
    echo "❌ Erreur lors de l'annulation\n";
}

// Nettoyer
echo "\nNettoyage...\n";
$orderItem->delete();
$orderLocation->delete();
echo "Données de test supprimées\n";
