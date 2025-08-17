<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test des traductions rentals
$translations = [
    'app.rentals.my_rentals',
    'app.rentals.description',
    'app.rentals.filter_by_status',
    'app.rentals.order_number',
    'app.rentals.placed_on',
    'app.rentals.filter',
    'app.rentals.items',
    'app.rentals.last_update',
    'app.rentals.see_details',
    'app.rentals.download_invoice',
    'app.rentals.close_rental'
];

echo "=== TEST DES TRADUCTIONS RENTALS ===\n\n";

foreach (['fr', 'en', 'nl'] as $locale) {
    app()->setLocale($locale);
    echo "LANGUE: " . strtoupper($locale) . "\n";
    echo str_repeat('-', 40) . "\n";
    
    foreach ($translations as $key) {
        $translated = __($key);
        echo sprintf("%-30s: %s\n", $key, $translated);
    }
    
    echo "\n";
}

echo "=== TEST DIRECT DU FICHIER FRANÇAIS ===\n";
$frenchFile = include 'resources/lang/fr/app.php';
if (isset($frenchFile['rentals'])) {
    echo "Section 'rentals' trouvée !\n";
    print_r($frenchFile['rentals']);
} else {
    echo "Section 'rentals' NOT FOUND!\n";
}
