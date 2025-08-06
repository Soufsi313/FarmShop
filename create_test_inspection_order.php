<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🚜 Création d'une nouvelle commande de location pour test d'inspection\n\n";

// Récupérer l'utilisateur
$user = User::where('email', 's.mef2703@gmail.com')->first();
if (!$user) {
    echo "❌ Utilisateur non trouvé\n";
    exit(1);
}

// Récupérer un produit pour la location
$product = Product::where('type', 'rental')->first();
if (!$product) {
    echo "❌ Aucun produit de location trouvé\n";
    exit(1);
}

echo "👤 Utilisateur: {$user->name} ({$user->email})\n";
echo "📦 Produit: {$product->name}\n\n";

// Générer un numéro de commande unique
$orderNumber = 'LOC-TEST-INSPECTION-' . time();

// Dates de location (période terminée)
$startDate = Carbon::now()->subDays(7); // Commencée il y a 7 jours
$endDate = Carbon::now()->subDays(1);   // Terminée hier
$actualReturnDate = Carbon::now()->subHours(2); // Retournée il y a 2h

echo "📅 Période de location: Du {$startDate->format('d/m/Y')} au {$endDate->format('d/m/Y')}\n";
echo "📅 Retour effectif: {$actualReturnDate->format('d/m/Y à H:i')}\n\n";

// Créer la commande de location
$orderLocation = new OrderLocation();
$orderLocation->user_id = $user->id;
$orderLocation->order_number = $orderNumber;
$orderLocation->start_date = $startDate;
$orderLocation->end_date = $endDate;
$orderLocation->actual_return_date = $actualReturnDate;
$orderLocation->status = 'completed'; // Prête pour clôture (bon statut)
$orderLocation->rental_days = 7;
$orderLocation->daily_rate = 18.50;
$orderLocation->total_rental_cost = 129.50;
$orderLocation->subtotal = 129.50;
$orderLocation->tax_rate = 20.00;
$orderLocation->tax_amount = 25.90;
$orderLocation->total_amount = 155.40;
$orderLocation->deposit_amount = 200.00;
$orderLocation->delivery_address = '123 Rue de la Ferme, 75001 Paris';
$orderLocation->billing_address = '123 Rue de la Ferme, 75001 Paris';
$orderLocation->notes = 'Commande de test pour validation du processus d\'inspection';
$orderLocation->payment_status = 'paid';
$orderLocation->deposit_status = 'authorized';
$orderLocation->created_at = $startDate;
$orderLocation->updated_at = Carbon::now();

try {
    $orderLocation->save();
    echo "✅ Commande de location créée: {$orderNumber}\n";
    
    // Créer l'item de location
    $orderItem = new OrderItemLocation();
    $orderItem->order_location_id = $orderLocation->id;
    $orderItem->product_id = $product->id;
    $orderItem->product_name = $product->name;
    $orderItem->product_sku = $product->sku ?? 'SKU-TEST-001';
    $orderItem->quantity = 1;
    $orderItem->daily_rate = 18.50;
    $orderItem->rental_days = 7;
    $orderItem->subtotal = 129.50;
    $orderItem->deposit_per_item = 200.00;
    $orderItem->total_deposit = 200.00;
    $orderItem->tax_amount = 25.90;
    $orderItem->total_amount = 155.40;
    $orderItem->condition_at_pickup = 'good';
    $orderItem->created_at = $startDate;
    $orderItem->updated_at = Carbon::now();
    
    $orderItem->save();
    echo "✅ Article de location ajouté: {$product->name} (1x)\n\n";
    
    echo "📋 RÉSUMÉ DE LA COMMANDE DE TEST:\n";
    echo "================================\n";
    echo "🔢 Numéro: {$orderNumber}\n";
    echo "👤 Client: {$user->name}\n";
    echo "📧 Email: {$user->email}\n";
    echo "📦 Article: {$product->name}\n";
    echo "💰 Montant: {$orderLocation->total_amount}€\n";
    echo "🏦 Dépôt: {$orderLocation->deposit_amount}€\n";
    echo "📊 Statut: {$orderLocation->status}\n";
    echo "📅 Période: {$startDate->format('d/m/Y')} → {$endDate->format('d/m/Y')}\n";
    echo "🔄 Retour: {$actualReturnDate->format('d/m/Y à H:i')}\n\n";
    
    echo "🎯 INSTRUCTIONS POUR LE TEST:\n";
    echo "=============================\n";
    echo "1. 🌐 Allez sur votre interface /rental-orders\n";
    echo "2. 🔍 Cherchez la commande: {$orderNumber}\n";
    echo "3. ✅ Cliquez sur le bouton 'Clôturer'\n";
    echo "4. 📝 Remplissez les détails d'inspection dans le modal\n";
    echo "5. 💾 Confirmez la clôture\n";
    echo "6. 📧 Vérifiez la réception de l'email d'inspection\n\n";
    
    echo "📬 L'email d'inspection sera envoyé à: {$user->email}\n";
    echo "🎨 Avec le nouveau template professionnel que nous venons de créer!\n\n";
    
    echo "✨ Commande de test créée avec succès! Prête pour l'inspection!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la création: " . $e->getMessage() . "\n";
    echo "📋 Stack trace: " . $e->getTraceAsString() . "\n";
}
