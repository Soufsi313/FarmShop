<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;
use App\Mail\RentalOrderInspection;
use Illuminate\Support\Facades\Mail;

echo "=== Test email DIRECT (sans queue) ===\n";

$order = OrderLocation::where('order_number', 'LOC-MANUAL-001-1754417155')->first();

if ($order) {
    echo "Commande: " . $order->order_number . "\n";
    echo "Email: " . $order->user->email . "\n";
    echo "Envoi DIRECT en cours...\n";
    
    try {
        // Créer la classe mail sans queue
        $mail = new class($order) extends RentalOrderInspection {
            // Supprimer l'interface ShouldQueue pour envoyer directement
        };
        
        // Envoyer directement
        Mail::to($order->user->email)->send($mail);
        
        echo "✅ EMAIL ENVOYÉ DIRECTEMENT !\n";
        echo "Vérifiez immédiatement votre boite mail.\n";
        
    } catch (Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . "\n";
        echo "Stack trace: " . $e->getTraceAsString() . "\n";
    }
} else {
    echo "❌ Commande non trouvée\n";
}
