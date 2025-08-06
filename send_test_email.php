<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

echo "=== Test d'envoi email inspection ===\n";

$order = OrderLocation::where('order_number', 'LOC-MANUAL-001-1754417155')->first();

if ($order) {
    echo "Commande trouvée: " . $order->order_number . "\n";
    echo "Email destinataire: " . $order->user->email . "\n";
    echo "Envoi en cours...\n";
    
    try {
        $order->sendInspectionReport();
        echo "✅ EMAIL ENVOYÉ AVEC SUCCÈS !\n";
        echo "Vérifiez votre boîte mail: " . $order->user->email . "\n";
    } catch (Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Commande non trouvée\n";
}
