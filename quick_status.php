<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use Illuminate\Support\Facades\DB;

echo "=== CURRENT STATUS ===\n";

// Check latest order
$order = Order::orderBy('created_at', 'desc')->first();
if ($order) {
    echo "Latest Order: {$order->order_number}\n";
    echo "Status: {$order->status}\n";
    echo "Updated: {$order->updated_at}\n";
    echo "Time ago: " . $order->updated_at->diffForHumans() . "\n\n";
}

// Check queue counts
$pending = DB::table('jobs')->count();
$failed = DB::table('failed_jobs')->count();

echo "Pending jobs: {$pending}\n";
echo "Failed jobs: {$failed}\n\n";

if ($failed > 0) {
    echo "Recent failed job error:\n";
    $failed = DB::table('failed_jobs')->orderBy('failed_at', 'desc')->first();
    if ($failed) {
        $lines = explode("\n", $failed->exception);
        echo $lines[0] . "\n";
    }
}
