<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'Date actuelle: ' . now()->format('Y-m-d H:i:s') . "\n";
echo 'Date actuelle (timezone): ' . now()->setTimezone('Europe/Paris')->format('Y-m-d H:i:s') . "\n";

$orders = App\Models\OrderLocation::whereDate('start_date', '<=', now())
    ->where('status', 'confirmed')
    ->get();
    
echo 'Locations qui devraient être en cours: ' . $orders->count() . "\n";
foreach($orders as $order) {
    echo 'ID: ' . $order->id . ' - Start: ' . $order->start_date . ' - Status: ' . $order->status . "\n";
}

// Vérifier aussi les jobs en attente
echo "\n--- Jobs en attente ---\n";
$jobs = DB::table('jobs')->get();
echo 'Jobs dans la queue: ' . $jobs->count() . "\n";
foreach($jobs as $job) {
    echo 'Job ID: ' . $job->id . ' - Queue: ' . $job->queue . ' - Created: ' . $job->created_at . "\n";
}
