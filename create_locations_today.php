<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// Charger l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Création de commandes de location pour AUJOURD'HUI ===\n\n";

try {
    // Récupérer des utilisateurs et produits existants
    $users = User::where('email', '!=', 'admin@farmshop.com')->take(5)->get();
    $products = Product::where('rental_price', '>', 0)->take(10)->get();
    
    if ($users->isEmpty()) {
        echo "❌ Aucun utilisateur trouvé (à part admin)\n";
        exit(1);
    }
    
    if ($products->isEmpty()) {
        echo "❌ Aucun produit avec prix de location trouvé\n";
        exit(1);
    }
    
    echo "✅ Utilisateurs disponibles : " . $users->count() . "\n";
    echo "✅ Produits disponibles : " . $products->count() . "\n\n";
    
    // Date d'aujourd'hui
    $today = now()->startOfDay();
    $tomorrow = $today->copy()->addDay();
    $nextWeek = $today->copy()->addWeek();
    
    echo "📅 Aujourd'hui : " . $today->format('d/m/Y') . "\n";
    echo "📅 Demain : " . $tomorrow->format('d/m/Y') . "\n";
    echo "📅 Dans une semaine : " . $nextWeek->format('d/m/Y') . "\n\n";
    
    $createdOrders = 0;
    
    // Créer 3 commandes avec début AUJOURD'HUI
    for ($i = 1; $i <= 3; $i++) {
        $user = $users->random();
        
        // Commande qui commence aujourd'hui
        $startDate = $today->copy();
        $endDate = $today->copy()->addDays(rand(1, 7)); // 1 à 7 jours de location
        
        $order = OrderLocation::create([
            'user_id' => $user->id,
            'order_number' => 'LOC-' . now()->format('Y') . '-' . str_pad(rand(8000, 8999), 4, '0', STR_PAD_LEFT),
            'status' => 'confirmed', // Prêt pour récupération
            'rental_start_date' => $startDate,
            'rental_end_date' => $endDate,
            'total_amount' => 0, // Sera calculé après ajout des items
            'notes' => "Commande de test créée le " . now()->format('d/m/Y H:i') . " - Début aujourd'hui",
            'created_at' => now()->subMinutes(rand(10, 120)), // Créée il y a 10min à 2h
        ]);
        
        // Ajouter 1-3 produits à la commande
        $numProducts = rand(1, 3);
        $totalAmount = 0;
        
        for ($j = 0; $j < $numProducts; $j++) {
            $product = $products->random();
            $quantity = rand(1, 3);
            $dailyPrice = $product->rental_price;
            $days = $startDate->diffInDays($endDate) + 1;
            $itemTotal = $dailyPrice * $quantity * $days;
            
            OrderItemLocation::create([
                'order_location_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'daily_price' => $dailyPrice,
                'total_price' => $itemTotal,
                'pickup_condition' => null, // Sera rempli lors de l'inspection
                'return_condition' => null,
            ]);
            
            $totalAmount += $itemTotal;
        }
        
        // Mettre à jour le montant total
        $order->update(['total_amount' => $totalAmount]);
        
        echo "✅ Commande créée : {$order->order_number}\n";
        echo "   👤 Utilisateur : {$user->name} ({$user->email})\n";
        echo "   📅 Période : {$startDate->format('d/m/Y')} → {$endDate->format('d/m/Y')} ({$days} jour(s))\n";
        echo "   💰 Total : {$totalAmount}€\n";
        echo "   📦 Produits : {$numProducts}\n";
        echo "   🎯 Statut : {$order->status} (prêt pour récupération AUJOURD'HUI)\n\n";
        
        $createdOrders++;
    }
    
    // Créer 2 commandes avec retour AUJOURD'HUI (statut active)
    for ($i = 1; $i <= 2; $i++) {
        $user = $users->random();
        
        // Commande qui se termine aujourd'hui (était active, doit être retournée)
        $startDate = $today->copy()->subDays(rand(1, 5)); // Commencée il y a 1-5 jours
        $endDate = $today->copy(); // Se termine aujourd'hui
        
        $order = OrderLocation::create([
            'user_id' => $user->id,
            'order_number' => 'LOC-' . now()->format('Y') . '-' . str_pad(rand(9000, 9999), 4, '0', STR_PAD_LEFT),
            'status' => 'active', // En cours, doit être retournée
            'rental_start_date' => $startDate,
            'rental_end_date' => $endDate,
            'total_amount' => 0,
            'notes' => "Commande de test créée le " . now()->format('d/m/Y H:i') . " - Retour aujourd'hui",
            'created_at' => $startDate->copy()->addHours(rand(1, 6)),
        ]);
        
        // Ajouter 1-2 produits
        $numProducts = rand(1, 2);
        $totalAmount = 0;
        
        for ($j = 0; $j < $numProducts; $j++) {
            $product = $products->random();
            $quantity = rand(1, 2);
            $dailyPrice = $product->rental_price;
            $days = $startDate->diffInDays($endDate) + 1;
            $itemTotal = $dailyPrice * $quantity * $days;
            
            OrderItemLocation::create([
                'order_location_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'daily_price' => $dailyPrice,
                'total_price' => $itemTotal,
                'pickup_condition' => 'good', // Déjà récupérée
                'return_condition' => null, // En attente de retour
            ]);
            
            $totalAmount += $itemTotal;
        }
        
        $order->update(['total_amount' => $totalAmount]);
        
        echo "✅ Commande créée : {$order->order_number}\n";
        echo "   👤 Utilisateur : {$user->name} ({$user->email})\n";
        echo "   📅 Période : {$startDate->format('d/m/Y')} → {$endDate->format('d/m/Y')} ({$days} jour(s))\n";
        echo "   💰 Total : {$totalAmount}€\n";
        echo "   📦 Produits : {$numProducts}\n";
        echo "   🎯 Statut : {$order->status} (doit être retournée AUJOURD'HUI)\n\n";
        
        $createdOrders++;
    }
    
    echo "🎉 SUCCÈS ! {$createdOrders} commandes de location créées pour les tests d'aujourd'hui.\n\n";
    
    // Afficher un résumé
    echo "=== RÉSUMÉ POUR LE DASHBOARD ADMIN ===\n";
    echo "📊 Récupérations prévues aujourd'hui : 3 commandes (statut 'confirmed')\n";
    echo "📊 Retours prévus aujourd'hui : 2 commandes (statut 'active')\n\n";
    
    echo "🔗 Allez sur le dashboard admin : /admin/locations/dashboard\n";
    echo "🔗 Liste des locations : /admin/locations\n\n";
    
    echo "💡 Vous pouvez maintenant tester :\n";
    echo "   1. Voir les compteurs sur le dashboard\n";
    echo "   2. Cliquer sur 'Inspecter et activer' pour les commandes confirmées\n";
    echo "   3. Cliquer sur 'Marquer comme retourné' pour les commandes actives\n";

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "Trace : " . $e->getTraceAsString() . "\n";
    exit(1);
}

?>
