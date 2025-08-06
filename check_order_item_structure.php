<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderItemLocation;

$item = new OrderItemLocation();
echo "Champs fillable pour OrderItemLocation:\n";
print_r($item->getFillable());

// Regarder aussi un item existant
$existing = OrderItemLocation::first();
if ($existing) {
    echo "\nAttributs d'un item existant:\n";
    print_r($existing->getAttributes());
}
