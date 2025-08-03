<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'Debug des dates...' . "\n";
echo 'Date actuelle: ' . now()->format('Y-m-d H:i:s') . "\n";
echo 'Date actuelle (date seulement): ' . now()->format('Y-m-d') . "\n";

// Récupérer les locations confirmées
$confirmedOrders = App\Models\OrderLocation::where('status', 'confirmed')->get();

echo 'Locations confirmées: ' . $confirmedOrders->count() . "\n";

foreach($confirmedOrders as $order) {
    echo 'ID: ' . $order->id . ' - Order: ' . $order->order_number . "\n";
    echo 'Start date: ' . $order->start_date . ' (format: ' . $order->start_date->format('Y-m-d H:i:s') . ')' . "\n";
    echo 'Start date (date seulement): ' . $order->start_date->format('Y-m-d') . "\n";
    echo 'Comparaison whereDate start_date <= now(): ' . ($order->start_date->format('Y-m-d') <= now()->format('Y-m-d') ? 'true' : 'false') . "\n";
    echo 'IsToday: ' . ($order->start_date->isToday() ? 'true' : 'false') . "\n";
    echo 'IsPast: ' . ($order->start_date->isPast() ? 'true' : 'false') . "\n";
    echo '---' . "\n";
}
