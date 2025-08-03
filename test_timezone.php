<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'Vérification avec le nouveau fuseau horaire...' . "\n";
echo 'Date actuelle: ' . now()->format('Y-m-d H:i:s') . "\n";
echo 'Fuseau horaire: ' . config('app.timezone') . "\n";

// Vérifier les locations qui devraient démarrer
$ordersToStart = App\Models\OrderLocation::where('status', 'confirmed')
    ->whereDate('start_date', '<=', now())
    ->get();

echo 'Locations à démarrer: ' . $ordersToStart->count() . "\n";

foreach($ordersToStart as $order) {
    echo 'ID: ' . $order->id . ' - Start: ' . $order->start_date . ' - Status: ' . $order->status . "\n";
}
