<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

// Tester la clôture de la commande LOC-MANUAL-001-1754417155
$order = OrderLocation::where('order_number', 'LOC-MANUAL-001-1754417155')->first();

if ($order) {
    echo "=== Test de clôture pour LOC-MANUAL-001-1754417155 ===\n";
    echo "ID: " . $order->id . "\n";
    echo "Statut: " . $order->status . "\n";
    echo "Can be closed: " . ($order->can_be_closed ? 'OUI' : 'NON') . "\n";
    echo "End date: " . $order->end_date . "\n";
    echo "Now: " . now() . "\n";
    echo "Now >= end_date: " . (now()->gte($order->end_date) ? 'OUI' : 'NON') . "\n";
    
    if ($order->can_be_closed) {
        echo "\n✅ La commande PEUT être clôturée maintenant !\n";
    } else {
        echo "\n❌ La commande ne peut PAS être clôturée.\n";
        echo "Raison: Statut = {$order->status}, End date = {$order->end_date}\n";
    }
} else {
    echo "Commande non trouvée\n";
}
