<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test PDF generation
$orderLocation = App\Models\OrderLocation::find(17);
if ($orderLocation) {
    echo "Order found: FL-" . $orderLocation->order_number . "\n";
    echo "Items count: " . $orderLocation->orderItemLocations->count() . "\n";
    
    // Check item data
    foreach ($orderLocation->orderItemLocations as $item) {
        echo "Product: " . $item->product_name . "\n";
        echo "Daily rate: " . $item->daily_rate . "\n";
        echo "Deposit per item: " . $item->deposit_per_item . "\n";
        echo "Total amount: " . $item->total_amount . "\n";
        echo "---\n";
    }
    
    // Generate PDF
    $pdfPath = $orderLocation->generateInvoicePdf();
    echo "PDF generated: " . $pdfPath . "\n";
} else {
    echo "Order not found\n";
}
