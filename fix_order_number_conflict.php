<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

echo "=== DIAGNOSTIC NUMÉRO COMMANDE LOCATION ===\n";

$problematicNumber = 'LOC-2025080001';
echo "Recherche commande avec numéro: {$problematicNumber}\n";

$existing = OrderLocation::where('order_number', $problematicNumber)->first();
if ($existing) {
    echo "❌ TROUVÉ: Commande existante avec ce numéro:\n";
    echo "   - ID: {$existing->id}\n";
    echo "   - Statut: {$existing->status}\n";
    echo "   - Utilisateur: {$existing->user_id}\n";
    echo "   - Créée le: {$existing->created_at}\n";
    
    echo "\n🗑️ Suppression de cette commande en conflit...\n";
    $existing->delete();
    echo "✅ Commande supprimée\n";
} else {
    echo "✅ Aucune commande trouvée avec ce numéro\n";
}

// Tester la génération du prochain numéro
echo "\n📝 Test génération nouveau numéro:\n";
$newNumber = OrderLocation::generateOrderNumber();
echo "Nouveau numéro généré: {$newNumber}\n";

// Vérifier toutes les commandes de location existantes
echo "\n📋 Commandes de location existantes:\n";
$orders = OrderLocation::orderBy('created_at', 'desc')->take(5)->get(['id', 'order_number', 'status', 'created_at']);
if ($orders->count() > 0) {
    foreach ($orders as $order) {
        echo "- {$order->order_number} (ID: {$order->id}, Statut: {$order->status})\n";
    }
} else {
    echo "Aucune commande de location en base\n";
}

echo "\n=== FIN DIAGNOSTIC ===\n";
