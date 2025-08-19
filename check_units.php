<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "=== VALEURS UNIT_SYMBOL EXISTANTES ===\n";

$units = Product::select('unit_symbol')->distinct()->get();
foreach($units as $unit) {
    echo "- " . $unit->unit_symbol . "\n";
}

?>
