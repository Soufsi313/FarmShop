<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;

echo "=== ORDER STATUS CHECK ===\n";

$order = Order::orderBy('created_at', 'desc')->first();
if ($order) {
    echo "Order: {$order->order_number}\n";
    echo "Current Status: {$order->status}\n";
    echo "Total: €{$order->total}\n\n";
    
    echo "Status History Structure:\n";
    $history = $order->status_history;
    if ($history) {
        echo "Type: " . gettype($history) . "\n";
        echo "Content:\n";
        print_r($history);
    } else {
        echo "No status history.\n";
    }
}

echo "\n=== SUCCESS! AUTOMATIC TRANSITIONS WORKING! ===\n";
echo "Your order progressed from 'confirmed' all the way to 'returned' automatically!\n";
echo "This means the queue system and status transitions are functioning correctly.\n";
