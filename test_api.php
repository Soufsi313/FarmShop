<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Simuler une requête POST vers l'API
$request = \Illuminate\Http\Request::create(
    '/api/rentals/241/calculate-cost',
    'POST',
    [
        'start_date' => '2025-07-20',
        'end_date' => '2025-07-27',
        'quantity' => 1
    ]
);

$request->headers->set('Content-Type', 'application/json');
$request->headers->set('X-CSRF-TOKEN', 'test-token');

try {
    $response = $kernel->handle($request);
    echo 'Status Code: ' . $response->getStatusCode() . PHP_EOL;
    echo 'Response: ' . $response->getContent() . PHP_EOL;
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    echo 'Trace: ' . $e->getTraceAsString() . PHP_EOL;
}
