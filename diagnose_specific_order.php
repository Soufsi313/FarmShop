<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Diagnostic spécifique de la commande LOC-AUTO-TEST-1751441424 ===\n\n";

// Chercher la commande problématique
$order = OrderLocation::where('order_number', 'LOC-AUTO-TEST-1751441424')
    ->with(['items.product', 'user'])
    ->first();

if (!$order) {
    echo "❌ Commande LOC-AUTO-TEST-1751441424 non trouvée.\n";
    echo "Recherche d'autres commandes récentes en attente d'inspection...\n";
    
    $pendingOrders = OrderLocation::where('status', 'pending_inspection')
        ->with(['items.product', 'user'])
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
    
    if ($pendingOrders->isEmpty()) {
        echo "❌ Aucune commande en attente d'inspection trouvée.\n";
        exit(1);
    }
    
    echo "📦 Commandes en attente d'inspection trouvées:\n";
    foreach ($pendingOrders as $pendingOrder) {
        echo "   - {$pendingOrder->order_number} (ID: {$pendingOrder->id}) - {$pendingOrder->items->count()} articles\n";
    }
    
    $order = $pendingOrders->first();
    echo "\n🎯 Analyse de la première commande: {$order->order_number}\n";
}

echo "\n📦 Détails de la commande:\n";
echo "   - ID: {$order->id}\n";
echo "   - Numéro: {$order->order_number}\n";
echo "   - Client: {$order->user->name}\n";
echo "   - Statut: {$order->status}\n";
echo "   - Date de création: {$order->created_at}\n";
echo "   - Date de clôture client: " . ($order->client_return_date ? $order->client_return_date : 'Non définie') . "\n";
echo "   - Notes client: " . ($order->client_notes ?? 'Aucune') . "\n";
echo "   - Nombre d'articles: {$order->items->count()}\n\n";

if ($order->items->isEmpty()) {
    echo "❌ PROBLÈME: Aucun article dans cette commande !\n\n";
    echo "=== Correction: Ajout d'articles de test ===\n";
    
    // Récupérer des produits d'outillage
    $products = Product::where('category_id', 6)->take(2)->get();
    
    if ($products->isEmpty()) {
        echo "❌ Aucun produit d'outillage trouvé. Recherche dans toutes les catégories...\n";
        $products = Product::whereNotNull('rental_price_per_day')->take(2)->get();
    }
    
    if ($products->isEmpty()) {
        echo "❌ Aucun produit louable trouvé.\n";
        exit(1);
    }
    
    foreach ($products as $product) {
        $rentalPricePerDay = $product->rental_price_per_day ?? 15.00;
        $depositAmount = $product->rental_deposit ?? 50.00;
        $durationDays = 3;
        $subtotal = $rentalPricePerDay * $durationDays;
        $totalWithDeposit = $subtotal + $depositAmount;
        
        $item = OrderItemLocation::create([
            'order_location_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'rental_price_per_day' => $rentalPricePerDay,
            'deposit_amount' => $depositAmount,
            'duration_days' => $durationDays,
            'rental_start_date' => $order->rental_start_date,
            'rental_end_date' => $order->rental_end_date,
            'subtotal' => $subtotal,
            'total_with_deposit' => $totalWithDeposit,
        ]);
        
        echo "✅ Article ajouté: {$item->product_name} (ID: {$item->id})\n";
        echo "   - Sous-total: {$subtotal}€\n";
        echo "   - Total avec caution: {$totalWithDeposit}€\n";
    }
    
    // Recalculer les totaux
    $totalAmount = $order->items()->sum(DB::raw('rental_price_per_day * duration_days'));
    $totalDeposit = $order->items()->sum('deposit_amount');
    
    $order->update([
        'total_amount' => $totalAmount,
        'deposit_amount' => $totalDeposit
    ]);
    
    echo "\n✅ Totaux mis à jour:\n";
    echo "   - Montant total: {$totalAmount}€\n";
    echo "   - Caution totale: {$totalDeposit}€\n\n";
    
    // Recharger la commande
    $order->load(['items.product']);
}

echo "✅ Articles dans la commande:\n";
foreach ($order->items as $item) {
    echo "   - {$item->product_name} (ID: {$item->id})\n";
    echo "     Prix/jour: {$item->rental_price_per_day}€\n";
    echo "     Durée: {$item->duration_days} jours\n";
    echo "     Caution: {$item->deposit_amount}€\n";
    echo "     Produit: " . ($item->product ? $item->product->name : 'Supprimé') . "\n\n";
}

echo "=== URLs de test ===\n";
echo "URL détail: http://127.0.0.1:8000/admin/locations/{$order->id}\n";
echo "URL inspection: http://127.0.0.1:8000/admin/locations/{$order->id}/return\n\n";

echo "✅ Diagnostic terminé !\n";
