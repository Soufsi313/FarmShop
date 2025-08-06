<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

// Vérifier la commande LOC-MANUAL-001-1754417155
$order = OrderLocation::where('order_number', 'LOC-MANUAL-001-1754417155')->first();

if ($order) {
    echo "=== Commande LOC-MANUAL-001-1754417155 ===\n";
    echo "ID: " . $order->id . "\n";
    echo "Statut: " . $order->status . "\n";
    echo "Peut être clôturée: " . ($order->can_be_closed ? 'OUI' : 'NON') . "\n";
    echo "Date de fin: " . $order->end_date . "\n";
    echo "Finished at: " . $order->finished_at . "\n";
    echo "Completed at: " . $order->completed_at . "\n";
    echo "Payment status: " . $order->payment_status . "\n";
    
    // Vérifier la méthode can_be_closed
    echo "\n=== Détails pour can_be_closed ===\n";
    echo "Status est 'finished': " . ($order->status === 'finished' ? 'OUI' : 'NON') . "\n";
    echo "Completed_at est null: " . (is_null($order->completed_at) ? 'OUI' : 'NON') . "\n";
    
} else {
    echo "Commande LOC-MANUAL-001-1754417155 non trouvée\n";
}
