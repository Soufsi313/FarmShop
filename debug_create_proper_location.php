<?php

require_once __DIR__ . '/vendor/autoload.php';

// Booter Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

echo "🔧 Création d'une location test pour statut 'completed'\n";

try {
    // Trouver un utilisateur
    $user = User::first();
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé\n";
        exit(1);
    }
    
    // Trouver un produit de location
    $product = Product::where('type', 'rental')->where('rental_stock', '>', 0)->first();
    if (!$product) {
        echo "❌ Aucun produit de location disponible\n";
        exit(1);
    }
    
    echo "👤 Utilisateur: {$user->name} (ID: {$user->id})\n";
    echo "📦 Produit: {$product->name} (ID: {$product->id})\n";
    
    // Créer une location avec des dates dans le passé pour qu'elle soit automatiquement 'completed'
    $startDate = Carbon::now()->subDays(5); // Commencé il y a 5 jours
    $endDate = Carbon::now()->subDays(2);   // Fini il y a 2 jours
    
    $orderNumber = 'LOC-TEST-' . now()->format('YmdHis');
    
    $rentalDays = $startDate->diffInDays($endDate) + 1; // +1 pour inclure le jour de fin
    $dailyRate = 50.00 / $rentalDays; // Calculer le tarif journalier
    
    $orderLocation = OrderLocation::create([
        'user_id' => $user->id,
        'order_number' => $orderNumber,
        'status' => 'confirmed', // On commence par confirmed
        'payment_status' => 'deposit_paid',
        'start_date' => $startDate,
        'end_date' => $endDate,
        'rental_days' => $rentalDays,
        'daily_rate' => $dailyRate,
        'total_rental_cost' => 50.00,
        'deposit_amount' => 25.00,
        'subtotal' => 50.00,
        'tax_amount' => 5.00,
        'total_amount' => 55.00,
        'notes' => 'Location test pour debug - doit rester en completed',
        'created_at' => $startDate->copy()->subDay(),
        'updated_at' => $startDate->copy()->subDay(),
    ]);
    
    // Ajouter un item
    OrderItemLocation::create([
        'order_location_id' => $orderLocation->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'rental_price' => 50.00,
        'subtotal' => 50.00,
    ]);
    
    echo "✅ Location créée: {$orderLocation->order_number}\n";
    echo "📅 Dates: {$startDate->format('Y-m-d H:i')} → {$endDate->format('Y-m-d H:i')}\n";
    echo "🔄 Statut initial: {$orderLocation->status}\n";
    
    // Maintenant, on va simuler le passage par les statuts automatiquement
    // mais en s'arrêtant à 'completed'
    
    echo "\n🔄 Passage à 'active'...\n";
    $orderLocation->update(['status' => 'active', 'started_at' => $startDate]);
    $orderLocation->refresh();
    echo "✅ Statut: {$orderLocation->status}\n";
    
    echo "\n🔄 Passage à 'completed' (retour matériel signalé par l'utilisateur)...\n";
    $orderLocation->update([
        'status' => 'completed',
        'completed_at' => $endDate->copy()->addHours(2),
        'actual_return_date' => $endDate->copy()->addHours(2),
    ]);
    $orderLocation->refresh();
    echo "✅ Statut: {$orderLocation->status}\n";
    
    // Vérifier le statut après quelques secondes
    sleep(2);
    $orderLocation->refresh();
    echo "🔍 Statut après refresh: {$orderLocation->status}\n";
    
    if ($orderLocation->status === 'completed') {
        echo "✅ SUCCÈS: Location reste au statut 'completed'\n";
        echo "🔧 Numéro de location pour tests: {$orderLocation->order_number}\n";
        echo "🆔 ID: {$orderLocation->id}\n";
        
        // Vérifier si elle peut générer une facture
        if ($orderLocation->canGenerateInvoice()) {
            echo "📄 ✅ Peut générer une facture\n";
        } else {
            echo "📄 ❌ Ne peut pas générer une facture\n";
        }
        
    } else {
        echo "❌ ÉCHEC: Le statut a changé automatiquement vers: {$orderLocation->status}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Ligne: " . $e->getLine() . "\n";
    echo "📂 Fichier: " . $e->getFile() . "\n";
}

echo "\n🏁 Script terminé\n";
