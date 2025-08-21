<?php
/**
 * Script pour générer 100 commandes de location réalistes
 * Avec distribution des statuts incluant             if ($status === 'finished') {
                $additionalFields = [
                    'inspected_at' => $dates['updated_at']->subDays(1),
                    'inspected_by' => $adminUser->id,
                    'inspection_notes' => 'Inspection terminée - Équipement en bon état',
                    'inspection_status' => 'completed'
                ];"
 */

require 'vendor/autoload.php';

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

// Initialisation de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Génération de 100 commandes de location ===\n\n";

try {
    // Vérifier les données nécessaires
    $users = User::where('role', '!=', 'Admin')->get();
    $adminUser = User::where('role', 'Admin')->first(); // Récupérer l'admin
    $rentalProducts = Product::where('type', 'rental')
                            ->orWhere('type', 'both')
                            ->where('is_active', true)
                            ->get();
    
    if ($users->count() < 5) {
        echo "❌ Pas assez d'utilisateurs (minimum 5)\n";
        exit;
    }
    
    if (!$adminUser) {
        echo "❌ Aucun utilisateur admin trouvé\n";
        exit;
    }
    
    if ($rentalProducts->count() < 5) {
        echo "❌ Pas assez de produits de location (minimum 5)\n";
        exit;
    }
    
    echo "📊 Données disponibles:\n";
    echo "- Utilisateurs: {$users->count()}\n";
    echo "- Admin: {$adminUser->name} ({$adminUser->email})\n";
    echo "- Produits de location: {$rentalProducts->count()}\n\n";
    
    // Distribution des statuts pour 100 commandes
    $statusDistribution = [
        'pending' => 5,      // 5% En attente
        'confirmed' => 15,   // 15% Confirmées
        'active' => 10,      // 10% En cours
        'completed' => 20,   // 20% Terminées
        'closed' => 15,      // 15% Clôturées (en attente inspection)
        'inspecting' => 10,  // 10% En inspection
        'finished' => 15,    // 15% Inspection terminée ✅
        'cancelled' => 10    // 10% Annulées
    ];
    
    echo "📋 Distribution des statuts prévue:\n";
    foreach ($statusDistribution as $status => $count) {
        echo "- {$status}: {$count} commandes\n";
    }
    echo "\n";
    
    // Générer les commandes
    $generatedOrders = [];
    $totalRevenue = 0;
    
    foreach ($statusDistribution as $status => $count) {
        echo "🔄 Génération de {$count} commandes avec statut '{$status}'...\n";
        
        for ($i = 0; $i < $count; $i++) {
            // Sélectionner un utilisateur aléatoire (inclure l'admin pour certaines commandes)
            $shouldUseAdmin = ($status === 'finished' && $i === 0) || rand(0, 10) === 0; // 10% chance d'utiliser admin
            $user = $shouldUseAdmin ? $adminUser : $users->random();
            
            // Générer des dates cohérentes selon le statut
            $dates = generateDatesForStatus($status);
            
            // Calculer les jours de location
            $rentalDays = $dates['start_date']->diffInDays($dates['end_date']) + 1;
            
            // Générer un numéro de commande unique
            $orderNumber = 'LOC-' . now()->format('Ymd') . '-' . uniqid() . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // Assurer l'unicité (avec uniqid, c'est très improbable mais on vérifie quand même)
            while (OrderLocation::where('order_number', $orderNumber)->exists()) {
                $orderNumber = 'LOC-' . now()->format('Ymd') . '-' . uniqid() . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            // Sélectionner 1-3 produits aléatoirement
            $selectedProducts = $rentalProducts->random(rand(1, 3));
            
            $subtotal = 0;
            $totalDeposit = 0;
            
            // Calculer le sous-total
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 2); // 1-2 unités par produit
                $dailyPrice = $product->rental_price_per_day ?? 25; // Prix par défaut si null
                $productTotal = $dailyPrice * $quantity * $rentalDays;
                $subtotal += $productTotal;
                $totalDeposit += ($product->deposit_amount ?? 100) * $quantity;
            }
            
            $taxAmount = $subtotal * 0.21; // TVA 21%
            $totalAmount = $subtotal + $taxAmount;
            $totalRevenue += $totalAmount;
            
            // Ajouter des champs spéciaux pour inspection terminée
            $additionalFields = [];
            if ($status === 'finished') {
                $additionalFields = [
                    'inspected_at' => $dates['updated_at'],
                    'inspected_by' => $adminUser->id,
                    'inspection_notes' => 'Inspection terminée - Équipement en bon état',
                    'inspection_status' => 'completed'
                ];
            } elseif ($status === 'inspecting') {
                $additionalFields = [
                    'inspection_started_at' => $dates['updated_at']->subHours(2),
                    'inspected_by' => $adminUser->id,
                    'inspection_status' => 'in_progress'
                ];
            }
            
            // Créer la commande de location
            $orderLocationData = array_merge([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'start_date' => $dates['start_date'],
                'end_date' => $dates['end_date'],
                'rental_days' => $rentalDays,
                'daily_rate' => $subtotal / $rentalDays,
                'total_rental_cost' => $subtotal,
                'deposit_amount' => $totalDeposit,
                'late_fee_per_day' => 10.00,
                'tax_rate' => 21.00,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'status' => $status,
                'payment_status' => getPaymentStatusForStatus($status),
                'payment_method' => 'stripe',
                'payment_reference' => 'pi_' . uniqid(),
                'billing_address' => json_encode([
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => '0' . rand(400000000, 799999999),
                    'street' => rand(1, 999) . ' Rue ' . ['du Commerce', 'des Fleurs', 'de la Paix'][rand(0, 2)],
                    'city' => ['Bruxelles', 'Anvers', 'Gand', 'Liège', 'Namur'][rand(0, 4)],
                    'postal_code' => rand(1000, 9999),
                    'country' => 'BE'
                ]),
                'delivery_address' => json_encode([
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => '0' . rand(400000000, 799999999),
                    'street' => rand(1, 999) . ' Rue ' . ['du Commerce', 'des Fleurs', 'de la Paix'][rand(0, 2)],
                    'city' => ['Bruxelles', 'Anvers', 'Gand', 'Liège', 'Namur'][rand(0, 4)],
                    'postal_code' => rand(1000, 9999),
                    'country' => 'BE'
                ]),
                'created_at' => $dates['created_at'],
                'updated_at' => $dates['updated_at']
            ], $additionalFields);
            
            $orderLocation = OrderLocation::create($orderLocationData);
            
            // Ajouter les produits à la commande (SANS AFFECTER LE STOCK)
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 2);
                $dailyPrice = $product->rental_price_per_day ?? 25;
                
                $totalPrice = $dailyPrice * $quantity * $rentalDays;
                $taxAmount = $totalPrice * 0.21; // 21% TVA
                $totalAmount = $totalPrice + $taxAmount;
                
                OrderItemLocation::create([
                    'order_location_id' => $orderLocation->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $quantity,
                    'daily_rate' => $dailyPrice,
                    'rental_days' => $rentalDays,
                    'subtotal' => $totalPrice,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                    'deposit_per_item' => $product->deposit_amount ?? 100,
                    'total_deposit' => ($product->deposit_amount ?? 100) * $quantity
                ]);
                
                // NOTE: Nous n'affectons PAS le stock rental ici
                // Le stock reste intact pour éviter les problèmes de disponibilité
            }
            
            $generatedOrders[] = [
                'order_number' => $orderNumber,
                'status' => $status,
                'user' => $user->name,
                'amount' => $totalAmount,
                'products' => $selectedProducts->count()
            ];
        }
    }
    
    echo "\n✅ Génération terminée !\n\n";
    
    // Afficher le résumé
    echo "📊 Résumé:\n";
    echo "- Total commandes générées: " . count($generatedOrders) . "\n";
    echo "- Chiffre d'affaires total: " . number_format($totalRevenue, 2) . "€\n\n";
    
    // Vérifier les commandes par statut
    echo "🔍 Vérification par statut:\n";
    foreach ($statusDistribution as $status => $expectedCount) {
        $actualCount = OrderLocation::where('status', $status)->count();
        echo "- {$status}: {$actualCount} commandes\n";
    }
    
    echo "\n🎉 Les 100 commandes de location ont été créées avec succès !\n";
    echo "Le statut 'closed' (clôturé) est bien inclus avec 20 commandes.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

/**
 * Générer des dates cohérentes selon le statut
 */
function generateDatesForStatus($status) {
    $now = Carbon::now();
    
    switch ($status) {
        case 'pending':
        case 'confirmed':
            // Commandes futures
            $startDate = $now->copy()->addDays(rand(1, 30));
            $endDate = $startDate->copy()->addDays(rand(1, 14));
            $createdAt = $now->copy()->subDays(rand(1, 7));
            break;
            
        case 'active':
            // Commandes en cours
            $startDate = $now->copy()->subDays(rand(1, 10));
            $endDate = $now->copy()->addDays(rand(1, 10));
            $createdAt = $startDate->copy()->subDays(rand(1, 14));
            break;
            
        case 'completed':
        case 'closed':
        case 'inspecting':
        case 'finished':
            // Commandes terminées
            $endDate = $now->copy()->subDays(rand(1, 90));
            $startDate = $endDate->copy()->subDays(rand(1, 14));
            $createdAt = $startDate->copy()->subDays(rand(1, 14));
            break;
            
        case 'cancelled':
            // Commandes annulées (peuvent être à n'importe quel moment)
            $startDate = $now->copy()->addDays(rand(-30, 30));
            $endDate = $startDate->copy()->addDays(rand(1, 14));
            $createdAt = $startDate->copy()->subDays(rand(1, 14));
            break;
            
        default:
            $startDate = $now->copy()->addDays(rand(1, 30));
            $endDate = $startDate->copy()->addDays(rand(1, 14));
            $createdAt = $now->copy()->subDays(rand(1, 7));
    }
    
    return [
        'start_date' => $startDate,
        'end_date' => $endDate,
        'created_at' => $createdAt,
        'updated_at' => $createdAt->copy()->addMinutes(rand(1, 60))
    ];
}

/**
 * Déterminer le statut de paiement selon le statut de commande
 */
function getPaymentStatusForStatus($status) {
    switch ($status) {
        case 'pending':
            return 'pending';
        case 'confirmed':
        case 'active':
        case 'completed':
        case 'closed':
        case 'inspecting':
        case 'finished':
            return 'paid';
        case 'cancelled':
            return rand(0, 1) ? 'failed' : 'refunded';
        default:
            return 'pending';
    }
}
