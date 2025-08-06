<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\OrderLocation;
use Carbon\Carbon;

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Vérification de la commande LOC-TEST-INSPECTION-1754427887\n\n";

// Trouver la commande
$order = OrderLocation::where('order_number', 'LOC-TEST-INSPECTION-1754427887')->first();

if (!$order) {
    echo "❌ Commande non trouvée\n";
    exit(1);
}

echo "📦 Commande trouvée: {$order->order_number}\n";
echo "📊 Statut actuel: {$order->status}\n";
echo "💰 Montant: {$order->total_amount}€\n";
echo "📅 Créée le: {$order->created_at}\n";
echo "📅 Mise à jour: {$order->updated_at}\n";

// Vérifier tous les champs de statut
$statusFields = [
    'status',
    'payment_status', 
    'deposit_status',
    'inspection_status',
    'confirmed_at',
    'started_at',
    'completed_at',
    'closed_at',
    'cancelled_at',
    'returned_at'
];

echo "\n📋 DÉTAILS COMPLETS DU STATUT:\n";
echo "===============================\n";
foreach ($statusFields as $field) {
    $value = $order->$field;
    if ($value) {
        echo "• $field: $value\n";
    } else {
        echo "• $field: null\n";
    }
}

echo "\n🔧 CORRECTION DU STATUT POUR AFFICHER LE BOUTON CLÔTURER:\n";
echo "=========================================================\n";

// Corriger le statut pour permettre la clôture
$order->status = 'finished';  // Statut qui permet la clôture
$order->completed_at = null;  // Pas encore completée
$order->closed_at = null;     // Pas encore clôturée
$order->inspection_status = null; // Pas encore inspectée
$order->updated_at = Carbon::now();

try {
    $order->save();
    echo "✅ Statut corrigé avec succès!\n";
    echo "📊 Nouveau statut: {$order->status}\n";
    echo "🎯 La commande devrait maintenant afficher le bouton 'Clôturer'\n\n";
    
    echo "🔄 INSTRUCTIONS:\n";
    echo "=================\n";
    echo "1. 🔄 Rafraîchissez votre page /rental-orders\n";
    echo "2. 🔍 Cherchez la commande: {$order->order_number}\n";
    echo "3. ✅ Le bouton 'Clôturer' devrait maintenant être visible\n";
    echo "4. 📝 Cliquez dessus pour tester l'inspection\n\n";
    
    echo "💡 Note: Le bouton 'Clôturer' n'apparaît que pour les commandes avec le statut 'finished'\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la correction: " . $e->getMessage() . "\n";
}
