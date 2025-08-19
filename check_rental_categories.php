<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RentalCategory;

echo "=== CATÉGORIES DE LOCATION DISPONIBLES ===\n";
$rentalCategories = RentalCategory::all();
foreach($rentalCategories as $cat) {
    echo "ID: " . $cat->id . " | Slug: " . $cat->slug . " | Nom: " . $cat->name . "\n";
}

?>
