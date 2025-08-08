<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "📋 État des commandes de test :\n\n";

$orders = App\Models\OrderLocation::whereIn('id', [44, 45, 46, 47, 48])->get();

if ($orders->count() > 0) {
    foreach($orders as $order) {
        echo "🏷️  Commande {$order->id} - #{$order->order_number}\n";
        echo "    Status: {$order->status}\n";
        echo "    Inspection: {$order->inspection_status}\n";
        echo "    Finalisée: " . ($order->inspection_finished_at ? $order->inspection_finished_at->format('Y-m-d H:i:s') : 'Non') . "\n";
        echo "    URL: http://127.0.0.1:8000/admin/rental-returns/{$order->id}\n";
        echo "\n";
    }
} else {
    echo "Aucune commande trouvée.\n";
}

// Regardons aussi les commandes en cours d'inspection
echo "🔍 Commandes actuellement en inspection :\n\n";
$inspecting = App\Models\OrderLocation::where('status', 'inspecting')->get();

if ($inspecting->count() > 0) {
    foreach($inspecting as $order) {
        echo "🔧 Commande {$order->id} - #{$order->order_number}\n";
        echo "    Status: {$order->status}\n";
        echo "    Inspection: {$order->inspection_status}\n";
        echo "    URL: http://127.0.0.1:8000/admin/rental-returns/{$order->id}\n";
        echo "\n";
    }
} else {
    echo "Aucune commande en cours d'inspection.\n";
}
