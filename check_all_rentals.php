<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'Date actuelle: ' . now()->format('Y-m-d H:i:s') . "\n";
echo 'Date actuelle (timezone): ' . now()->setTimezone('Europe/Paris')->format('Y-m-d H:i:s') . "\n\n";

// Vérifier toutes les locations récentes
$allOrders = App\Models\OrderLocation::orderBy('created_at', 'desc')->take(5)->get();
    
echo "--- Toutes les locations récentes ---\n";
foreach($allOrders as $order) {
    echo 'ID: ' . $order->id . ' - Order: ' . $order->order_number . "\n";
    echo 'Start: ' . $order->start_date . ' - End: ' . $order->end_date . "\n";
    echo 'Status: ' . $order->status . ' - Created: ' . $order->created_at . "\n";
    echo '---' . "\n";
}

// Spécifiquement chercher les locations du 03/08
echo "\n--- Locations pour le 03/08 ---\n";
$todayOrders = App\Models\OrderLocation::whereDate('start_date', '2025-08-03')->get();
foreach($todayOrders as $order) {
    echo 'ID: ' . $order->id . ' - Order: ' . $order->order_number . "\n";
    echo 'Start: ' . $order->start_date . ' - Status: ' . $order->status . "\n";
    echo '---' . "\n";
}
