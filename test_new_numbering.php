<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

echo "Test nouveau système de numérotation:\n";
for ($i = 1; $i <= 3; $i++) {
    $number = OrderLocation::generateOrderNumber();
    echo "#{$i}: {$number}\n";
}
