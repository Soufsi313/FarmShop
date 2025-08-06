<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

try {
    echo "🔧 Création de nouvelles locations test pour inspection manuelle\n";
    echo "=".str_repeat("=", 60)."\n\n";

    $user = User::find(1); // Meftah Soufiane
    if (!$user) {
        echo "❌ Utilisateur non trouvé\n";
        exit(1);
    }

    $products = Product::where('rental_stock', '>', 0)->take(3)->get();
    if ($products->count() < 3) {
        echo "❌ Pas assez de produits avec stock de location\n";
        exit(1);
    }

    $currentDate = Carbon::now();
    $timestamp = time();
    
    // Nettoyer les anciennes locations test
    $oldTestOrders = OrderLocation::where('order_number', 'LIKE', 'LOC-MANUAL-%')->get();
    foreach ($oldTestOrders as $old) {
        echo "🗑️  Suppression de l'ancienne location {$old->order_number}\n";
        $old->items()->delete();
        $old->delete();
    }
    echo "\n";
    
    // Créer 3 locations de test
    $scenarios = [
        [
            'order_number' => 'LOC-MANUAL-001-' . $timestamp,
            'end_date' => $currentDate->copy()->subDays(2), // Retard de 2 jours
            'deposit_amount' => 25.00,
            'late_fee_per_day' => 5.00,
        ],
        [
            'order_number' => 'LOC-MANUAL-002-' . $timestamp,
            'end_date' => $currentDate->copy()->subDays(1), // Retard de 1 jour
            'deposit_amount' => 18.00,
            'late_fee_per_day' => 3.50,
        ],
        [
            'order_number' => 'LOC-MANUAL-003-' . $timestamp,
            'end_date' => $currentDate->copy()->subDays(3), // Retard de 3 jours
            'deposit_amount' => 30.00,
            'late_fee_per_day' => 7.00,
        ]
    ];

    foreach ($scenarios as $index => $scenario) {
        $product = $products[$index];
        $startDate = $scenario['end_date']->copy()->subDays(7); // Location de 7 jours
        $rentalDays = $startDate->diffInDays($scenario['end_date']) + 1;
        $dailyRate = 15.00;
        $totalRentalCost = $dailyRate * $rentalDays;
        $lateDays = $currentDate->diffInDays($scenario['end_date']);
        $lateFees = $lateDays * $scenario['late_fee_per_day'];
        
        echo "📦 Création de {$scenario['order_number']}...\n";
        
        $orderLocation = OrderLocation::create([
            'order_number' => $scenario['order_number'],
            'user_id' => $user->id,
            'start_date' => $startDate,
            'end_date' => $scenario['end_date'],
            'rental_days' => $rentalDays,
            'daily_rate' => $dailyRate,
            'total_rental_cost' => $totalRentalCost,
            'deposit_amount' => $scenario['deposit_amount'],
            'late_fee_per_day' => $scenario['late_fee_per_day'],
            'tax_rate' => 21.00,
            'subtotal' => $totalRentalCost,
            'tax_amount' => $totalRentalCost * 0.21,
            'total_amount' => $totalRentalCost * 1.21,
            'status' => 'finished', // ✅ Terminée mais pas clôturée
            'payment_status' => 'paid',
            'payment_method' => 'stripe',
            'confirmed_at' => $startDate,
            'started_at' => $startDate,
            'completed_at' => $currentDate, // Retournée aujourd'hui
            'actual_return_date' => $currentDate,
            'late_days' => $lateDays,
            'late_fees' => $lateFees,
            'inspection_status' => 'pending', // 🔍 Prête pour inspection
            'billing_address' => json_encode([
                'name' => $user->name,
                'email' => $user->email,
                'address' => $user->address,
                'city' => $user->city,
                'postal_code' => $user->postal_code,
                'country' => $user->country
            ]),
            'delivery_address' => json_encode([
                'name' => $user->name,
                'address' => $user->address,
                'city' => $user->city,
                'postal_code' => $user->postal_code,
                'country' => $user->country
            ]),
            'created_at' => $currentDate,
            'updated_at' => $currentDate
        ]);

        // Créer l'item de location
        OrderItemLocation::create([
            'order_location_id' => $orderLocation->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku ?? 'TEST-SKU',
            'product_description' => $product->description ?? 'Produit de test pour inspection manuelle',
            'quantity' => 1,
            'daily_rate' => $dailyRate,
            'rental_days' => $rentalDays,
            'deposit_per_item' => $scenario['deposit_amount'],
            'subtotal' => $totalRentalCost,
            'total_deposit' => $scenario['deposit_amount'],
            'tax_amount' => $totalRentalCost * 0.21,
            'total_amount' => $totalRentalCost * 1.21,
            'condition_at_pickup' => 'excellent',
            'condition_at_return' => null, // À définir lors de l'inspection
            'item_damage_cost' => 0,
            'item_inspection_notes' => null,
            'damage_details' => null,
            'item_late_days' => $lateDays,
            'item_late_fees' => $lateFees,
            'item_deposit_refund' => 0
        ]);

        echo "   ✅ Créée avec succès!\n";
        echo "   📅 Période: {$startDate->format('d/m/Y')} → {$scenario['end_date']->format('d/m/Y')}\n";
        echo "   ⏰ Retard: {$lateDays} jour(s)\n";
        echo "   💰 Frais de retard: {$lateFees}€\n";
        echo "   🏦 Caution: {$scenario['deposit_amount']}€\n";
        echo "   🔍 Statut: finished (prête pour inspection manuelle)\n";
        echo "   ---\n";
    }

    echo "\n🎯 **Résumé des locations créées:**\n";
    echo "📋 Toutes sont en statut 'finished' et prêtes pour inspection\n";
    echo "🔍 Vous pouvez maintenant aller dans l'admin pour les inspecter\n";
    echo "⚠️  **Note technique:** Les détails des retards ne sont pas visibles dans l'inspection\n";
    echo "💡 Il faudra ajouter l'affichage des sanctions/retards dans l'interface d'inspection\n\n";
    
    echo "🌐 URLs d'accès:\n";
    $newLocations = OrderLocation::whereIn('order_number', array_column($scenarios, 'order_number'))->get();
    foreach ($newLocations as $loc) {
        echo "   - {$loc->order_number}: http://127.0.0.1:8000/admin/rental-returns/{$loc->id}\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
