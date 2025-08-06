<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;
use App\Mail\RentalOrderInspection;
use Illuminate\Support\Facades\Mail;

echo "=== Test email DIRECT SIMPLE ===\n";

$order = OrderLocation::where('order_number', 'LOC-MANUAL-001-1754417155')->first();

if ($order) {
    echo "Commande: " . $order->order_number . "\n";
    echo "Email: " . $order->user->email . "\n";
    echo "Envoi DIRECT en cours...\n";
    
    try {
        Mail::to($order->user->email)->send(new RentalOrderInspection($order));
        
        echo "✅ EMAIL ENVOYÉ DIRECTEMENT À " . $order->user->email . " !\n";
        echo "Vérifiez immédiatement votre boite mail.\n";
        
    } catch (Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Commande non trouvée\n";
}
