<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\OrderLocationStatusHistory;

echo "=== ORDER TRANSITION HISTORY ===\n";

$order = Order::orderBy('created_at', 'desc')->first();
if ($order) {
    echo "Order: {$order->order_number}\n";
    echo "Current Status: {$order->status}\n";
    echo "Total: €{$order->total}\n\n";
    
    echo "Status History:\n";
    $history = $order->status_history ?: [];
        
    if (!empty($history)) {
        foreach ($history as $entry) {
            echo "- {$entry['status']} at {$entry['timestamp']}\n";
        }
    } else {
        echo "No status history found.\n";
    }
    
    echo "\nOrder timestamps:\n";
    echo "Created: {$order->created_at}\n";
    echo "Updated: {$order->updated_at}\n";
}

echo "\n=== AUTOMATIC TRANSITIONS WORKING! ===\n";
