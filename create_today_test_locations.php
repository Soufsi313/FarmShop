<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Product;
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use Carbon\Carbon;

// Chargement de l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Configuration du timezone
Carbon::setLocale('fr');
date_default_timezone_set('Europe/Brussels');

echo "=== Création de commandes de location pour tests (dates d'aujourd'hui) ===\n\n";

try {
    // Récupérer des utilisateurs existants
    $users = User::where('email', '!=', 'admin@farmshop.be')->limit(3)->get();
    if ($users->count() < 2) {
        echo "❌ Pas assez d'utilisateurs dans la base de données.\n";
        echo "   Créez d'abord des utilisateurs avec create_test_user.php\n";
        exit(1);
    }

    // Récupérer des produits (on utilise tous les produits disponibles)
    $rentalProducts = Product::limit(10)->get();
    if ($rentalProducts->count() < 3) {
        echo "❌ Pas assez de produits dans la base de données.\n";
        echo "   Créez d'abord des produits.\n";
        exit(1);
    }

    $today = Carbon::now();
    $createdOrders = 0;

    // Créer plusieurs commandes avec différents scénarios
    $scenarios = [
        [
            'status' => 'confirmed',
            'start_date' => $today->copy(), // Aujourd'hui
            'end_date' => $today->copy()->addDays(3),
            'description' => 'Commande confirmée - récupération aujourd\'hui'
        ],
        [
            'status' => 'confirmed', 
            'start_date' => $today->copy(), // Aujourd'hui
            'end_date' => $today->copy()->addDays(7),
            'description' => 'Commande confirmée - récupération aujourd\'hui (7 jours)'
        ],
        [
            'status' => 'active',
            'start_date' => $today->copy()->subDays(2), // Commencée il y a 2 jours
            'end_date' => $today->copy(), // Se termine aujourd'hui
            'description' => 'Location active - retour aujourd\'hui'
        ],
        [
            'status' => 'active',
            'start_date' => $today->copy()->subDays(5), // Commencée il y a 5 jours
            'end_date' => $today->copy()->addDays(2), // Se termine dans 2 jours
            'description' => 'Location active - en cours'
        ],
        [
            'status' => 'pending',
            'start_date' => $today->copy()->addDays(1), // Commence demain
            'end_date' => $today->copy()->addDays(4),
            'description' => 'Commande en attente - commence demain'
        ]
    ];

    foreach ($scenarios as $index => $scenario) {
        $user = $users->random();
        
        // Générer un numéro de commande unique
        $timestamp = $today->format('Ymd-His');
        $orderNumber = 'LOC-' . $timestamp . '-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
        
        // Créer la commande
        $orderLocation = new OrderLocation();
        $orderLocation->user_id = $user->id;
        $orderLocation->order_number = $orderNumber;
        $orderLocation->status = $scenario['status'];
        $orderLocation->rental_start_date = $scenario['start_date'];
        $orderLocation->rental_end_date = $scenario['end_date'];
        $orderLocation->total_amount = 0;
        $orderLocation->deposit_amount = 0;
        $orderLocation->paid_amount = 0;
        $orderLocation->late_fee = 0;
        $orderLocation->damage_fee = 0;
        $orderLocation->admin_notes = $scenario['description'];
        
        // Définir les timestamps selon le statut
        $orderLocation->created_at = $today->copy()->subHours(rand(1, 48));
        
        if ($scenario['status'] === 'confirmed') {
            $orderLocation->confirmed_at = $orderLocation->created_at->copy()->addMinutes(rand(30, 180));
        } elseif ($scenario['status'] === 'active') {
            $orderLocation->confirmed_at = $orderLocation->created_at->copy()->addMinutes(rand(30, 180));
            $orderLocation->picked_up_at = $scenario['start_date']->copy()->addHours(rand(8, 12));
        }
        
        $orderLocation->save();

        // Ajouter des produits à la commande
        $numberOfProducts = rand(1, 3);
        $selectedProducts = $rentalProducts->random($numberOfProducts);
        $totalAmount = 0;
        $totalDeposit = 0;

        foreach ($selectedProducts as $product) {
            $durationDays = $scenario['start_date']->diffInDays($scenario['end_date']) + 1;
            $rentalPricePerDay = $product->rental_price ?? $product->price * 0.1; // 10% du prix si pas de prix de location
            $subtotal = $rentalPricePerDay * $durationDays;
            $depositAmount = $product->rental_deposit ?? $product->price * 0.2; // 20% du prix comme caution

            $orderItem = new OrderItemLocation();
            $orderItem->order_location_id = $orderLocation->id;
            $orderItem->product_id = $product->id;
            $orderItem->product_name = $product->name;
            $orderItem->product_description = $product->description;
            $orderItem->rental_start_date = $scenario['start_date']->toDateString();
            $orderItem->rental_end_date = $scenario['end_date']->toDateString();
            $orderItem->duration_days = $durationDays;
            $orderItem->rental_price_per_day = $rentalPricePerDay;
            $orderItem->subtotal = $subtotal;
            $orderItem->total_with_deposit = $subtotal + $depositAmount;
            $orderItem->deposit_amount = $depositAmount;
            $orderItem->condition_at_pickup = $scenario['status'] === 'active' ? 'excellent' : null;
            $orderItem->pickup_notes = $scenario['status'] === 'active' ? 'Matériel en parfait état' : null;
            $orderItem->save();

            $totalAmount += $subtotal;
            $totalDeposit += $depositAmount;
        }

        // Mettre à jour les totaux
        $orderLocation->total_amount = $totalAmount;
        $orderLocation->deposit_amount = $totalDeposit;
        $orderLocation->paid_amount = $totalAmount + $totalDeposit; // Supposons que tout est payé
        $orderLocation->save();

        $createdOrders++;
        
        echo "✅ Commande #{$orderLocation->order_number} créée\n";
        echo "   Statut: {$scenario['status']}\n";
        echo "   Client: {$user->name}\n";
        echo "   Période: {$scenario['start_date']->format('d/m/Y')} au {$scenario['end_date']->format('d/m/Y')}\n";
        echo "   Produits: {$numberOfProducts}\n";
        echo "   Total: " . number_format($totalAmount, 2) . "€ + " . number_format($totalDeposit, 2) . "€ de caution\n";
        echo "   Description: {$scenario['description']}\n\n";
    }

    echo "=== Résumé ===\n";
    echo "✅ $createdOrders commandes de location créées avec succès !\n";
    echo "🕒 Date/heure actuelle: " . $today->format('d/m/Y H:i') . "\n\n";
    
    echo "💡 Vous pouvez maintenant tester :\n";
    echo "   - Dashboard admin : /admin/locations\n";
    echo "   - Récupérations du jour\n";
    echo "   - Retours du jour\n";
    echo "   - Gestion des statuts\n\n";

} catch (Exception $e) {
    echo "❌ Erreur lors de la création des commandes de test : " . $e->getMessage() . "\n";
    echo "Trace : " . $e->getTraceAsString() . "\n";
}
