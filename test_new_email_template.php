<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;
use Illuminate\Support\Facades\Log;

// Tester l'envoi du nouveau template d'email personnalisé
$order = OrderLocation::where('order_number', 'LOC-MANUAL-001-1754417155')->first();

if ($order) {
    echo "=== Test du nouveau template email personnalisé ===\n";
    echo "Commande: " . $order->order_number . "\n";
    echo "Utilisateur: " . $order->user->email . "\n";
    
    try {
        $result = $order->sendInspectionReport();
        echo "✅ Email envoyé avec succès avec le nouveau template FarmShop !\n";
        
        Log::info("Test email inspection avec nouveau template pour " . $order->order_number);
        
    } catch (Exception $e) {
        echo "❌ Erreur lors de l'envoi : " . $e->getMessage() . "\n";
        Log::error("Erreur test email inspection: " . $e->getMessage());
    }
    
} else {
    echo "❌ Commande non trouvée\n";
}
