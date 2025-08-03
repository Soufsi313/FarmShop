<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$orders = App\Models\OrderLocation::whereIn('id', [16, 17])->get();

foreach($orders as $order) {
    echo "Location {$order->order_number}:\n";
    echo "  Status: {$order->status}\n";
    echo "  Payment Status: {$order->payment_status}\n";
    echo "  Can Generate Invoice: " . ($order->canGenerateInvoice() ? 'YES' : 'NO') . "\n";
    echo "---\n";
}
