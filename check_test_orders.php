<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Vérification des locations de test...\n";

try {
    $orders = DB::table('order_locations')
        ->where('order_number', 'like', 'LOC-TEST-%')
        ->orderBy('created_at', 'desc')
        ->get(['id', 'order_number', 'status', 'start_date', 'end_date', 'total_amount']);
    
    echo "Locations de test trouvées: " . $orders->count() . "\n";
    
    foreach ($orders as $order) {
        echo "- {$order->order_number} (ID: {$order->id}) - Status: {$order->status} - {$order->total_amount}€\n";
    }
    
    if ($orders->count() == 0) {
        echo "\nAucune location de test trouvée. Vérification des dernières locations...\n";
        $recent = DB::table('order_locations')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'order_number', 'status', 'created_at']);
        
        foreach ($recent as $order) {
            echo "- {$order->order_number} (ID: {$order->id}) - Status: {$order->status} - {$order->created_at}\n";
        }
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
