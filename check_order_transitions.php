<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;

echo "=== VÉRIFICATION COMMANDE LOC-202508034682 ===\n\n";

$order = OrderLocation::where('order_number', 'LOC-202508034682')->first();
if ($order) {
    echo "✅ COMMANDE TROUVÉE\n";
    echo "Numéro: {$order->order_number}\n";
    echo "Statut: {$order->status}\n";
    echo "Début: {$order->start_date}\n";
    echo "Fin: {$order->end_date}\n";
    echo "Confirmée le: {$order->confirmed_at}\n";
    echo "Email client: {$order->user->email}\n\n";
    
    echo "=== ANALYSE DES TRANSITIONS AUTOMATIQUES ===\n";
    
    // Vérifier les dates
    $now = now();
    $startDate = $order->start_date;
    $endDate = $order->end_date;
    
    echo "Date actuelle: {$now}\n";
    echo "Début location: {$startDate}\n";
    echo "Fin location: {$endDate}\n\n";
    
    // Calculer les transitions
    if ($now->lt($startDate)) {
        $heuresAvantDebut = $now->diffInHours($startDate);
        echo "⏳ Transition vers 'active' dans {$heuresAvantDebut} heures (le {$startDate->format('d/m/Y à H:i')})\n";
    } elseif ($now->between($startDate, $endDate)) {
        echo "🟢 Location ACTIVE actuellement\n";
        $heuresAvantFin = $now->diffInHours($endDate);
        echo "⏳ Transition vers 'completed' dans {$heuresAvantFin} heures (le {$endDate->format('d/m/Y à H:i')})\n";
    } else {
        echo "🔴 Location terminée\n";
    }
    
} else {
    echo "❌ Commande non trouvée\n";
}
