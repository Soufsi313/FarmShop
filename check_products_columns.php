<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "=== COLONNES DE LA TABLE PRODUCTS ===\n";
$columns = Schema::getColumnListing('products');
foreach($columns as $column) {
    echo "- " . $column . "\n";
}

?>
