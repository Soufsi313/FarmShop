<?php

require_once 'vendor/autoload.php';
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\Product;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Ajout d'items à la commande LOC-20250701-001 ===\n\n";

// Trouver la commande
$order = OrderLocation::where('order_number', 'LOC-20250701-001')->first();

if (!$order) {
    echo "❌ Commande LOC-20250701-001 non trouvée\n";
    exit(1);
}

echo "✅ Commande trouvée: {$order->order_number} (ID: {$order->id})\n";
echo "   Statut: {$order->status}\n";
echo "   Items actuels: {$order->items->count()}\n\n";

// Prendre quelques produits au hasard
$products = Product::limit(3)->get();
if ($products->count() < 2) {
    echo "❌ Pas assez de produits dans la base\n";
    exit(1);
}

$totalAmount = 0;
$totalDeposit = 0;

foreach($products->take(2) as $index => $product) {
    $durationDays = $order->rental_start_date->diffInDays($order->rental_end_date) + 1;
    $rentalPricePerDay = $product->price * 0.1; // 10% du prix
    $subtotal = $rentalPricePerDay * $durationDays;
    $depositAmount = $product->price * 0.2; // 20% comme caution

    $item = new OrderItemLocation();
    $item->order_location_id = $order->id;
    $item->product_id = $product->id;
    $item->product_name = $product->name;
    $item->product_description = $product->description;
    $item->rental_start_date = $order->rental_start_date;
    $item->rental_end_date = $order->rental_end_date;
    $item->duration_days = $durationDays;
    $item->rental_price_per_day = $rentalPricePerDay;
    $item->subtotal = $subtotal;
    $item->total_with_deposit = $subtotal + $depositAmount;
    $item->deposit_amount = $depositAmount;
    $item->save();

    $totalAmount += $subtotal;
    $totalDeposit += $depositAmount;

    echo "✅ Item ajouté: {$product->name}\n";
    echo "   Prix/jour: " . number_format($rentalPricePerDay, 2) . "€\n";
    echo "   Durée: {$durationDays} jours\n";
    echo "   Sous-total: " . number_format($subtotal, 2) . "€\n";
    echo "   Caution: " . number_format($depositAmount, 2) . "€\n\n";
}

// Mettre à jour les totaux de la commande
$order->total_amount = $totalAmount;
$order->deposit_amount = $totalDeposit;
$order->paid_amount = $totalAmount + $totalDeposit;
$order->save();

echo "=== Mise à jour commande ===\n";
echo "Total location: " . number_format($totalAmount, 2) . "€\n";
echo "Total caution: " . number_format($totalDeposit, 2) . "€\n";
echo "Montant payé: " . number_format($totalAmount + $totalDeposit, 2) . "€\n\n";

echo "✅ Commande LOC-20250701-001 mise à jour avec succès !\n";
echo "💡 Vous pouvez maintenant tester la récupération de cette commande.\n";
