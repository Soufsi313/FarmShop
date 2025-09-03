<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Bootstrap l'application Laravel
$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        api: __DIR__.'/routes/api.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\User;
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use Carbon\Carbon;

try {
    // Trouver un utilisateur
    $user = User::where('email', '!=', null)->first();
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé\n";
        exit(1);
    }
    
    // Trouver un produit de location avec prix et caution
    $product = Product::whereNotNull('rental_price_per_day')
        ->whereNotNull('deposit_amount')
        ->where('rental_price_per_day', '>', 0)
        ->where('deposit_amount', '>', 0)
        ->first();
        
    if (!$product) {
        echo "❌ Aucun produit de location trouvé avec prix et caution\n";
        exit(1);
    }
    
    echo "✅ Produit sélectionné: {$product->name}\n";
    echo "💰 Prix/jour: {$product->rental_price_per_day}€\n";
    echo "🔒 Caution: {$product->deposit_amount}€\n";
    
    // Créer la commande de location
    $startDate = Carbon::today()->addDays(1);
    $endDate = Carbon::today()->addDays(5);
    $rentalDays = 4; // 4 jours de location
    
    $totalRentalCost = $product->rental_price_per_day * $rentalDays * 1; // quantité = 1
    $depositAmount = $product->deposit_amount * 1; // quantité = 1
    $taxAmount = $totalRentalCost * 0.21; // TVA 21%
    $totalAmount = $totalRentalCost + $taxAmount + $depositAmount;
    
    $order = OrderLocation::create([
        'user_id' => $user->id,
        'order_number' => OrderLocation::generateOrderNumber(),
        'status' => 'finished', // Location terminée
        'payment_status' => 'deposit_paid',
        'start_date' => $startDate,
        'end_date' => $endDate,
        'rental_days' => $rentalDays,
        'daily_rate' => $product->rental_price_per_day,
        'total_rental_cost' => $totalRentalCost,
        'subtotal' => $totalRentalCost,
        'tax_amount' => $taxAmount,
        'deposit_amount' => $depositAmount,
        'total_amount' => $totalAmount,
        'billing_address' => [
            'address' => 'Ferme du Test',
            'street' => '1234 Rue de la Location',
            'city' => 'Bruxelles',
            'postal_code' => '1000',
            'country' => 'Belgique'
        ],
        'delivery_address' => [
            'address' => 'Ferme du Test',
            'street' => '1234 Rue de la Location',
            'city' => 'Bruxelles',
            'postal_code' => '1000',
            'country' => 'Belgique'
        ],
        'notes' => 'Location de test créée automatiquement',
        // Simulation d'inspection avec dégâts légers
        'inspection_status' => 'completed',
        'has_damages' => true,
        'damage_notes' => 'Rayure légère sur le côté gauche de l\'équipement. Usure normale constatée sur les poignées.',
        'damage_cost' => 50.00,
        'late_days' => 1, // 1 jour de retard
        'late_fees' => 25.00, // 25€ de frais de retard
        'inspection_notes' => 'Inspection terminée. Dégâts mineurs constatés. Le matériel reste fonctionnel.',
        'deposit_refund' => $depositAmount - 75.00, // Caution moins les pénalités (50€ dégâts + 25€ retard)
        'penalty_amount' => 75.00,
        'total_penalties' => 75.00,
        'damage_photos' => [
            'damage_photos/test_damage_1.jpg',
            'damage_photos/test_damage_2.jpg'
        ],
        'inspection_completed_at' => Carbon::now()->subHours(2),
        'completed_at' => Carbon::now()->subHours(1)
    ]);
    
    // Créer l'élément de commande
    OrderItemLocation::create([
        'order_location_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'product_sku' => $product->sku,
        'product_description' => $product->description,
        'quantity' => 1,
        'daily_rate' => $product->rental_price_per_day,
        'rental_days' => $rentalDays,
        'deposit_per_item' => $product->deposit_amount,
        'subtotal' => $totalRentalCost,
        'total_deposit' => $depositAmount,
        'tax_amount' => $taxAmount,
        'total_amount' => $totalRentalCost + $taxAmount,
        'condition_at_pickup' => 'excellent',
        'condition_at_return' => 'good',
        'item_damage_cost' => 50.00,
        'item_inspection_notes' => 'Rayure légère détectée lors de l\'inspection de retour',
        'item_late_days' => 1,
        'item_late_fees' => 25.00,
        'item_deposit_refund' => $depositAmount - 75.00
    ]);
    
    echo "\n🎉 Location de test créée avec succès!\n";
    echo "📋 Numéro de commande: {$order->order_number}\n";
    echo "👤 Utilisateur: {$user->name} ({$user->email})\n";
    echo "📅 Période: {$startDate->format('d/m/Y')} - {$endDate->format('d/m/Y')} ({$rentalDays} jours)\n";
    echo "💸 Total: {$totalAmount}€ (dont {$depositAmount}€ de caution)\n";
    echo "🔍 Inspection: ✅ Terminée avec dégâts\n";
    echo "💰 Caution libérée: {$order->deposit_refund}€ (sur {$depositAmount}€)\n";
    echo "⚠️  Pénalités: {$order->penalty_amount}€ (dégâts: {$order->damage_cost}€ + retard: {$order->late_fees}€)\n";
    echo "\n🌐 Testez maintenant sur: http://127.0.0.1:8000/rental-orders\n";
    echo "📋 Détails commande: http://127.0.0.1:8000/rental-orders/{$order->id}\n";
    echo "🔍 Détails inspection: http://127.0.0.1:8000/rental-orders/{$order->id}/inspection\n";
    echo "📄 Télécharger facture: http://127.0.0.1:8000/rental-orders/{$order->id}/invoice\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
