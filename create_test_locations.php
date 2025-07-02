<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use App\Models\CartLocation;
use App\Models\CartItemLocation;
use App\Models\OrderLocation;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CRÉATION DE DONNÉES DE TEST POUR LES LOCATIONS ===\n\n";

try {
    // 1. Récupérer un utilisateur de test
    $user = User::where('email', 'admin@farmshop.com')->first();
    if (!$user) {
        $user = User::first();
    }
    
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé. Créez d'abord un utilisateur.\n";
        exit(1);
    }
    
    echo "👤 Utilisateur de test: {$user->name} ({$user->email})\n";

    // 2. Récupérer des produits louables
    $products = Product::where('is_rentable', true)
        ->where('rental_price_per_day', '>', 0)
        ->take(3)
        ->get();
    
    if ($products->count() === 0) {
        echo "❌ Aucun produit louable trouvé. Créez d'abord des produits avec is_rentable=true.\n";
        exit(1);
    }
    
    echo "📦 Produits louables trouvés: {$products->count()}\n";
    foreach ($products as $product) {
        echo "   - {$product->name} ({$product->rental_price_per_day}€/jour)\n";
    }

    // 3. Créer plusieurs commandes de location de test
    $scenarios = [
        [
            'status' => 'pending',
            'days_from_now' => 2,
            'duration' => 5,
            'description' => 'Commande en attente de confirmation'
        ],
        [
            'status' => 'confirmed',
            'days_from_now' => 1,
            'duration' => 3,
            'description' => 'Commande confirmée - récupération demain'
        ],
        [
            'status' => 'active',
            'days_from_now' => -2,
            'duration' => 7,
            'description' => 'Location en cours'
        ],
        [
            'status' => 'completed',
            'days_from_now' => -10,
            'duration' => 4,
            'description' => 'Location terminée'
        ],
        [
            'status' => 'overdue',
            'days_from_now' => -5,
            'duration' => 3,
            'description' => 'Location en retard'
        ]
    ];

    foreach ($scenarios as $index => $scenario) {
        echo "\n📋 Création du scénario " . ($index + 1) . ": {$scenario['description']}\n";
        
        // Créer la commande
        $startDate = now()->addDays($scenario['days_from_now']);
        $endDate = $startDate->copy()->addDays($scenario['duration']);
        
        $order = OrderLocation::create([
            'order_number' => 'LOC-' . now()->format('Ymd') . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
            'user_id' => $user->id,
            'status' => $scenario['status'],
            'total_amount' => 0, // Sera calculé
            'deposit_amount' => 0, // Sera calculé
            'rental_start_date' => $startDate,
            'rental_end_date' => $endDate
        ]);
        
        echo "   ✅ Commande créée: {$order->order_number}\n";
        
        // Ajouter des articles à la commande
        $totalAmount = 0;
        $totalDeposit = 0;
        
        $selectedProducts = $products->random(rand(1, min(2, $products->count())));
        
        foreach ($selectedProducts as $product) {
            $subtotal = $product->rental_price_per_day * $scenario['duration'];
            $deposit = $product->deposit_amount ?? 0;
            
            $item = $order->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_description' => $product->description,
                'rental_price_per_day' => $product->rental_price_per_day,
                'deposit_amount' => $deposit,
                'rental_start_date' => $startDate->format('Y-m-d'),
                'rental_end_date' => $endDate->format('Y-m-d'),
                'duration_days' => $scenario['duration'],
                'subtotal' => $subtotal,
                'total_with_deposit' => $subtotal + $deposit
            ]);
            
            $totalAmount += $subtotal;
            $totalDeposit += $deposit;
            
            echo "     📦 Article ajouté: {$product->name} ({$scenario['duration']} jours = {$subtotal}€)\n";
        }
        
        // Mettre à jour les totaux de la commande
        $order->update([
            'total_amount' => $totalAmount,
            'deposit_amount' => $totalDeposit
        ]);
        
        // Mettre à jour les dates et statuts selon le scénario
        switch ($scenario['status']) {
            case 'confirmed':
                $order->update(['confirmed_at' => now()->subHours(2)]);
                break;
                
            case 'active':
                $order->update([
                    'confirmed_at' => $startDate->copy()->subDays(1),
                    'picked_up_at' => $startDate
                ]);
                break;
                
            case 'completed':
                $order->update([
                    'confirmed_at' => $startDate->copy()->subDays(1),
                    'picked_up_at' => $startDate,
                    'actual_return_date' => $endDate,
                    'returned_at' => $endDate
                ]);
                break;
                
            case 'overdue':
                $order->update([
                    'confirmed_at' => $startDate->copy()->subDays(1),
                    'picked_up_at' => $startDate,
                    'late_fee' => 50.00 // Frais de retard
                ]);
                break;
        }
        
        echo "   💰 Total: {$totalAmount}€ + {$totalDeposit}€ caution\n";
    }

    echo "\n🎉 CRÉATION TERMINÉE AVEC SUCCÈS !\n";
    echo "\n📊 Résumé:\n";
    echo "- " . OrderLocation::count() . " commandes de location totales\n";
    echo "- " . OrderLocation::where('status', 'pending')->count() . " en attente\n";
    echo "- " . OrderLocation::where('status', 'confirmed')->count() . " confirmées\n";
    echo "- " . OrderLocation::where('status', 'active')->count() . " actives\n";
    echo "- " . OrderLocation::where('status', 'completed')->count() . " terminées\n";
    echo "- " . OrderLocation::where('status', 'overdue')->count() . " en retard\n";
    
    echo "\n🌐 Accédez au dashboard admin des locations :\n";
    echo "http://127.0.0.1:8000/admin/locations/dashboard\n";
    echo "http://127.0.0.1:8000/admin/locations\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
