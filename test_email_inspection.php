<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;
use Illuminate\Support\Facades\Mail;

// Tester l'envoi d'email pour une commande terminée
$order = OrderLocation::where('order_number', 'LOC-MANUAL-001-1754417155')->first();

if ($order) {
    echo "=== Test d'envoi d'email pour LOC-MANUAL-001-1754417155 ===\n";
    echo "Commande: " . $order->order_number . "\n";
    echo "Statut: " . $order->status . "\n";
    echo "Email utilisateur: " . $order->user->email . "\n";
    
    try {
        echo "\n📧 Envoi du rapport d'inspection...\n";
        $order->sendInspectionReport();
        echo "✅ Rapport d'inspection envoyé avec succès !\n";
    } catch (\Exception $e) {
        echo "❌ Erreur lors de l'envoi: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "Commande non trouvée\n";
}
