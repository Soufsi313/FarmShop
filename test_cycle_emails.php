<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST COMPLET CYCLE DE VIE EMAILS LOCATION ===\n\n";

// Trouver la dernière commande confirmée
$orderLocation = \App\Models\OrderLocation::where('status', 'confirmed')
    ->orderBy('created_at', 'desc')
    ->first();

if ($orderLocation) {
    echo "📦 Test avec commande: {$orderLocation->order_number}\n";
    echo "   Utilisateur: {$orderLocation->user->email}\n";
    echo "   Période: {$orderLocation->start_date->format('d/m/Y')} → {$orderLocation->end_date->format('d/m/Y')}\n\n";
    
    echo "🧪 SIMULATION DU CYCLE COMPLET:\n\n";
    
    // 1. Confirmation (déjà testée)
    echo "1. ✅ Confirmation - TESTÉ ET REÇU\n\n";
    
    // 2. Activation (début de location)
    echo "2. 🟢 Activation (début de location)...\n";
    try {
        event(new \App\Events\OrderLocationStatusChanged($orderLocation, 'confirmed', 'active'));
        echo "   ✅ Event 'active' envoyé\n\n";
    } catch (\Exception $e) {
        echo "   ❌ Erreur: " . $e->getMessage() . "\n\n";
    }
    
    // 3. Terminaison (fin de location)
    echo "3. 🔴 Terminaison (fin de location)...\n";
    try {
        event(new \App\Events\OrderLocationStatusChanged($orderLocation, 'active', 'completed'));
        echo "   ✅ Event 'completed' envoyé\n\n";
    } catch (\Exception $e) {
        echo "   ❌ Erreur: " . $e->getMessage() . "\n\n";
    }
    
    // 4. Fermeture (retour matériel)
    echo "4. 🔒 Fermeture (retour matériel)...\n";
    try {
        event(new \App\Events\OrderLocationStatusChanged($orderLocation, 'completed', 'closed'));
        echo "   ✅ Event 'closed' envoyé\n\n";
    } catch (\Exception $e) {
        echo "   ❌ Erreur: " . $e->getMessage() . "\n\n";
    }
    
    // 5. Test d'annulation
    echo "5. ❌ Test annulation...\n";
    try {
        event(new \App\Events\OrderLocationStatusChanged($orderLocation, 'confirmed', 'cancelled'));
        echo "   ✅ Event 'cancelled' envoyé\n\n";
    } catch (\Exception $e) {
        echo "   ❌ Erreur: " . $e->getMessage() . "\n\n";
    }
    
    echo "🎯 VÉRIFIEZ VOS EMAILS pour tous ces statuts !\n";
    echo "📧 Vous devriez recevoir un email pour chaque transition.\n";
    
} else {
    echo "❌ Aucune commande confirmée trouvée\n";
}
