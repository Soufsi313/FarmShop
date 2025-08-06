<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;
use App\Mail\RentalOrderInspection;
use Illuminate\Support\Facades\Mail;

echo "=== Envoi email inspection avec frais de retard ===\n\n";

$orderLocation = OrderLocation::with(['user', 'orderItemLocations.product'])
    ->where('order_number', 'LOC-TEST-INSPECTION-1754427887')
    ->first();

if (!$orderLocation) {
    echo "❌ Commande non trouvée\n";
    exit;
}

echo "📋 Order: " . $orderLocation->order_number . "\n";
echo "👤 Client: " . $orderLocation->user->name . " (" . $orderLocation->user->email . ")\n";
echo "📅 Date de fin prévue: " . $orderLocation->end_date . "\n";
echo "📅 Date de retour réelle: " . $orderLocation->actual_return_date . "\n";
echo "⏰ Jours de retard: " . $orderLocation->late_days . "\n";
echo "💰 Frais de retard: " . number_format($orderLocation->late_fees, 2) . "€\n";
echo "⚠️ Pénalités: " . number_format($orderLocation->penalty_amount ?? 0, 2) . "€\n\n";

echo "📧 Envoi de l'email d'inspection avec frais de retard...\n";

try {
    Mail::to($orderLocation->user->email)->send(
        new RentalOrderInspection($orderLocation, $orderLocation->user)
    );
    
    echo "✅ Email d'inspection envoyé avec succès!\n";
    echo "📬 Destinataire: " . $orderLocation->user->email . "\n";
    echo "🌾 L'email contient maintenant les frais de retard de " . $orderLocation->late_days . " jours\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de l'envoi: " . $e->getMessage() . "\n";
}
