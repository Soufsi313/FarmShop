<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🗑️  Suppression de la commande de test incorrecte\n\n";

// Supprimer la commande créée avec le mauvais utilisateur
$order = App\Models\OrderLocation::where('order_number', 'LOC-INSPECT-1754469812')->first();
if ($order) {
    echo "📋 Commande trouvée: {$order->order_number}\n";
    echo "👤 Propriétaire: {$order->user->name} ({$order->user->email})\n";
    
    // Supprimer les items d'abord
    $order->orderItemLocations()->delete();
    echo "✅ Items supprimés\n";
    
    // Supprimer la commande
    $order->delete();
    echo "✅ Commande supprimée\n\n";
} else {
    echo "❌ Commande non trouvée\n";
}

// Supprimer aussi l'utilisateur de test créé
$testUser = App\Models\User::where('email', 'test@farmshop.local')->first();
if ($testUser) {
    $testUser->delete();
    echo "✅ Utilisateur test supprimé\n";
}

echo "🧹 Nettoyage terminé!\n";
