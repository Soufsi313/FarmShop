<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🆕 Création d'une nouvelle commande de test d'inspection\n\n";

DB::beginTransaction();

try {
    // Trouver un utilisateur existant
    $user = App\Models\User::where('email', 'test@example.com')->first();
    if (!$user) {
        $user = App\Models\User::where('role', '!=', 'Admin')->first();
    }
    
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé pour créer la commande\n";
        exit;
    }
    
    // Trouver quelques produits de location
    $products = App\Models\Product::where('is_rental_available', true)
                                 ->where('rental_stock', '>', 0)
                                 ->limit(2)
                                 ->get();
    
    if ($products->count() === 0) {
        echo "❌ Aucun produit de location disponible\n";
        exit;
    }
    
    $orderNumber = 'INSPECT-' . now()->timestamp;
    
    // Créer la commande de location
    $orderLocation = App\Models\OrderLocation::create([
        'user_id' => $user->id,
        'order_number' => $orderNumber,
        'status' => 'completed', // Déjà terminée, prête pour retour
        'start_date' => now()->subDays(8), // Commencée il y a 8 jours
        'end_date' => now()->subDays(3),   // Devait finir il y a 3 jours (en retard)
        'rental_days' => 5,
        'daily_rate' => 30.00, // Tarif par jour
        'total_rental_cost' => 150.00, // Coût total de la location
        'total_amount' => 150.00,
        'deposit_amount' => 100.00,
        'created_at' => now()->subDays(10),
        'updated_at' => now(),
        'completed_at' => now()->subDays(3), // Terminée il y a 3 jours
        // Calculer automatiquement les frais de retard (3 jours × 10€)
        'late_days' => 3,
        'late_fees' => 30.00, // Vous pourrez modifier ce montant
        'actual_return_date' => now()->subDay(), // Retourné hier
    ]);
    
    echo "✅ Commande créée: {$orderLocation->order_number} (ID: {$orderLocation->id})\n";
    echo "   Utilisateur: {$user->name} ({$user->email})\n";
    echo "   Période: du " . $orderLocation->start_date->format('d/m/Y') . " au " . $orderLocation->end_date->format('d/m/Y') . "\n";
    echo "   Retard: 3 jours (frais pré-calculés: 30€)\n";
    echo "   Caution: 100€\n\n";
    
    // Ajouter les produits à la commande
    $totalDeposit = 0;
    foreach ($products as $index => $product) {
        $quantity = 1;
        $depositPerItem = 50.00;
        $totalDeposit += $depositPerItem * $quantity;
        
        $orderItem = App\Models\OrderItemLocation::create([
            'order_location_id' => $orderLocation->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $quantity,
            'unit_price' => $product->rental_price_per_day ?? 15.00,
            'total_price' => ($product->rental_price_per_day ?? 15.00) * $quantity * 5, // 5 jours
            'deposit_per_item' => $depositPerItem,
            'total_deposit' => $depositPerItem * $quantity,
            // Vous pourrez modifier ces valeurs dans l'inspection
            'item_damage_cost' => 0, // Pas de dégâts par défaut
            'condition_at_return' => null, // À définir lors de l'inspection
            'item_inspection_notes' => null
        ]);
        
        echo "   📦 Produit " . ($index + 1) . ": {$product->name} (Quantité: {$quantity}, Dépôt: {$depositPerItem}€)\n";
    }
    
    echo "\n🔄 Mise en statut 'closed' (Retourné, en attente d'inspection)...\n";
    
    // Mettre directement en statut "closed" pour permettre de démarrer l'inspection
    $orderLocation->update([
        'status' => 'closed',
        'closed_at' => now()->subHour(),
        'actual_return_date' => now()->subHour()
    ]);
    
    DB::commit();
    
    echo "\n🎯 Commande de test créée avec succès !\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📋 INFORMATIONS DE TEST:\n";
    echo "   • ID Commande: {$orderLocation->id}\n";
    echo "   • Numéro: {$orderLocation->order_number}\n";
    echo "   • Status: {$orderLocation->status} (prêt pour inspection)\n";
    echo "   • Frais de retard pré-calculés: 30€ (modifiables)\n";
    echo "   • Caution: 100€\n";
    echo "\n🔗 URLs d'accès:\n";
    echo "   • Page admin retours: http://127.0.0.1:8000/admin/rental-returns\n";
    echo "   • Page inspection: http://127.0.0.1:8000/admin/rental-returns/{$orderLocation->id}\n";
    echo "\n📝 ÉTAPES DE TEST:\n";
    echo "   1️⃣ Aller sur la page d'inspection\n";
    echo "   2️⃣ Cliquer 'Démarrer l'inspection'\n";
    echo "   3️⃣ Modifier les frais de retard (30€ par défaut)\n";
    echo "   4️⃣ Ajouter des frais de dégâts si souhaité\n";
    echo "   5️⃣ Sélectionner l'état des produits\n";
    echo "   6️⃣ Finaliser l'inspection\n";
    echo "   7️⃣ Vérifier l'email reçu et le changement de statut\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "❌ Erreur lors de la création: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
