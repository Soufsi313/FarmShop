<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

// Simuler une requête
$request = Request::create('/admin/orders', 'GET');
$response = $kernel->handle($request);

echo "Status: " . $response->getStatusCode() . "\n";
echo "Headers:\n";
foreach ($response->headers->all() as $key => $values) {
    echo "$key: " . implode(', ', $values) . "\n";
}

if ($response->getStatusCode() !== 200) {
    echo "Content:\n";
    echo $response->getContent();
}

$kernel->terminate($request, $response);
