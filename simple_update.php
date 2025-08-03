<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Mise à jour directe avec DB
$updated = DB::table('order_locations')
    ->where('status', 'confirmed')
    ->whereDate('start_date', '<=', now())
    ->update([
        'status' => 'active',
        'started_at' => now(),
        'updated_at' => now()
    ]);

echo "Locations mises à jour: " . $updated . "\n";

// Vérifier le résultat
$orders = DB::table('order_locations')
    ->whereIn('id', [16, 17])
    ->select('id', 'order_number', 'status', 'started_at')
    ->get();

foreach($orders as $order) {
    echo "ID: {$order->id} - Order: {$order->order_number} - Status: {$order->status} - Started: {$order->started_at}\n";
}
