<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use Carbon\Carbon;

echo "=== CRÉATION DE DONNÉES DE TEST POUR LES LOCATIONS ===" . PHP_EOL;

// Récupérer tous les utilisateurs
$users = User::all();
if ($users->isEmpty()) {
    echo "Aucun utilisateur trouvé. Veuillez créer des utilisateurs d'abord." . PHP_EOL;
    exit(1);
}

// Récupérer les produits qui sont adaptés à la location (outils de jardinage principalement)
$rentalProducts = Product::whereIn('id', [23, 24, 25, 26, 27, 28, 30, 33, 34])->get();

if ($rentalProducts->isEmpty()) {
    echo "Aucun produit de location trouvé." . PHP_EOL;
    exit(1);
}

echo "Produits disponibles pour location :" . PHP_EOL;
foreach($rentalProducts as $product) {
    echo "- ID {$product->id}: {$product->name}" . PHP_EOL;
}
echo PHP_EOL;

// Définir les prix de location pour ces produits (par jour)
$rentalPrices = [
    23 => 15.00, // Tondeuse à gazon électrique
    24 => 12.00, // Taille-haie électrique  
    25 => 10.00, // Souffleur de feuilles
    26 => 25.00, // Motoculteur
    27 => 20.00, // Débroussailleuse thermique
    28 => 18.00, // Nettoyeur haute pression
    30 => 30.00, // Échafaudage roulant
    33 => 14.00, // Broyeur de végétaux électrique
    34 => 12.00, // Scarificateur électrique
];

$statuses = ['pending', 'confirmed', 'active', 'completed', 'cancelled', 'overdue'];
$created = 0;

// Créer 10 commandes de location de test
for ($i = 0; $i < 10; $i++) {
    $user = $users->random();
    $status = $statuses[array_rand($statuses)];
    
    // Dates de location aléatoires
    $startDate = Carbon::now()->addDays(rand(-30, 30));
    $endDate = $startDate->copy()->addDays(rand(1, 14));
    
    // Générer un numéro de commande unique
    $orderNumber = 'LOC-' . date('Y') . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
    
    // Créer la commande de location
    $orderLocation = OrderLocation::create([
        'order_number' => $orderNumber,
        'user_id' => $user->id,
        'status' => $status,
        'rental_start_date' => $startDate,
        'rental_end_date' => $endDate,
        'total_amount' => 0,
        'deposit_amount' => 0,
        'paid_amount' => 0,
        'late_fee' => $status === 'overdue' ? rand(10, 50) : 0,
        'damage_fee' => 0,
        'admin_notes' => $status === 'cancelled' ? 'Annulée par le client' : null,
    ]);
    
    // Ajouter 1 à 3 produits à la commande
    $numProducts = rand(1, 3);
    $selectedProducts = $rentalProducts->random($numProducts);
    $totalAmount = 0;
    
    foreach ($selectedProducts as $product) {
        $quantity = 1; // Pour simplifier, 1 exemplaire par produit
        $dailyPrice = $rentalPrices[$product->id];
        $duration = $startDate->diffInDays($endDate) + 1;
        $subtotal = $dailyPrice * $duration;
        $depositAmount = $dailyPrice * 2; // 2 jours de caution
        
        OrderItemLocation::create([
            'order_location_id' => $orderLocation->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_description' => $product->description ?? 'Outil de jardinage',
            'rental_price_per_day' => $dailyPrice,
            'deposit_amount' => $depositAmount,
            'rental_start_date' => $startDate->toDateString(),
            'rental_end_date' => $endDate->toDateString(),
            'duration_days' => $duration,
            'subtotal' => $subtotal,
            'total_with_deposit' => $subtotal + $depositAmount,
            'condition_at_pickup' => 'excellent',
            'damage_fee' => 0,
            'late_fee' => $status === 'overdue' ? rand(5, 25) : 0,
        ]);
        
        $totalAmount += $subtotal;
    }
    
    // Mettre à jour la commande avec le total
    $orderLocation->update([
        'total_amount' => $totalAmount,
        'deposit_amount' => $totalAmount * 0.5, // 50% de caution
    ]);
    
    $created++;
    
    echo "✓ Commande #{$orderLocation->order_number} créée - Utilisateur: {$user->name} - Statut: {$status} - Total: " . number_format($totalAmount, 2) . "€" . PHP_EOL;
}

echo PHP_EOL;
echo "=== RÉSUMÉ ===" . PHP_EOL;
echo "✓ {$created} commandes de location créées avec succès" . PHP_EOL;
echo "✓ Utilisateurs utilisés: " . $users->count() . PHP_EOL;
echo "✓ Produits de location disponibles: " . $rentalProducts->count() . PHP_EOL;

// Afficher un résumé par statut
echo PHP_EOL . "Répartition par statut :" . PHP_EOL;
$statusCounts = OrderLocation::selectRaw('status, COUNT(*) as count')
    ->groupBy('status')
    ->get();

foreach($statusCounts as $statusCount) {
    echo "- {$statusCount->status}: {$statusCount->count} commande(s)" . PHP_EOL;
}

echo PHP_EOL . "Vous pouvez maintenant tester l'interface admin des locations !" . PHP_EOL;
