<?php

require_once 'vendor/autoload.php';

// Bootstrap l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Configuration de l'environnement
config(['app.locale' => 'fr']);

echo "=== TEST DES TRADUCTIONS RENTAL_ORDERS ===\n\n";

// Clés à tester
$keys = [
    'app.rental_orders.my_rentals',
    'app.rental_orders.description', 
    'app.rental_orders.filter_by_status',
    'app.rental_orders.all_statuses',
    'app.rental_orders.start_date',
    'app.rental_orders.end_date',
    'app.rental_orders.filter',
    'app.rental_orders.order_number',
    'app.rental_orders.placed_on',
    'app.rental_orders.rental_period',
    'app.rental_orders.items',
    'app.rental_orders.last_update',
    'app.rental_orders.rental_days',
    'app.rental_orders.other_items',
    'app.rental_orders.see_details',
    'app.rental_orders.download_invoice',
    'app.rental_orders.close_rental',
    'app.rental_orders.cancel_rental',
    'app.rental_orders.no_rentals',
    'app.rental_orders.no_rentals_description',
    'app.rental_orders.discover_products'
];

// Tester pour chaque langue
$languages = ['fr', 'en', 'nl'];

foreach ($languages as $lang) {
    echo "LANGUE: " . strtoupper($lang) . "\n";
    echo "----------------------------------------\n";
    
    app()->setLocale($lang);
    
    foreach ($keys as $key) {
        $translation = __($key);
        $keyShort = str_replace('app.rental_orders.', '', $key);
        echo str_pad($keyShort, 25) . " : " . $translation . "\n";
    }
    echo "\n";
}

// Test des statuses
echo "=== TEST DES STATUSES ===\n\n";
$statusKeys = [
    'app.rental_status.pending',
    'app.rental_status.confirmed', 
    'app.rental_status.active',
    'app.rental_status.completed',
    'app.rental_status.cancelled',
    'app.rental_status.closed'
];

$paymentKeys = [
    'app.payment_status.pending',
    'app.payment_status.paid',
    'app.payment_status.failed', 
    'app.payment_status.refunded'
];

foreach ($languages as $lang) {
    echo "LANGUE: " . strtoupper($lang) . " - RENTAL STATUS\n";
    echo "----------------------------------------\n";
    
    app()->setLocale($lang);
    
    foreach ($statusKeys as $key) {
        $translation = __($key);
        $keyShort = str_replace('app.rental_status.', '', $key);
        echo str_pad($keyShort, 15) . " : " . $translation . "\n";
    }
    
    echo "\nPAYMENT STATUS:\n";
    foreach ($paymentKeys as $key) {
        $translation = __($key);
        $keyShort = str_replace('app.payment_status.', '', $key);
        echo str_pad($keyShort, 15) . " : " . $translation . "\n";
    }
    echo "\n";
}

// Test direct du fichier de traduction français
echo "=== TEST DIRECT DU FICHIER FRANÇAIS ===\n";
$frTranslations = include('resources/lang/fr/app.php');

if (isset($frTranslations['rental_orders'])) {
    echo "✅ Section 'rental_orders' trouvée !\n";
    echo "Nombre de clés: " . count($frTranslations['rental_orders']) . "\n\n";
} else {
    echo "❌ Section 'rental_orders' NON trouvée !\n";
}

if (isset($frTranslations['rental_status'])) {
    echo "✅ Section 'rental_status' trouvée !\n";
    echo "Nombre de clés: " . count($frTranslations['rental_status']) . "\n\n";
} else {
    echo "❌ Section 'rental_status' NON trouvée !\n";
}

if (isset($frTranslations['payment_status'])) {
    echo "✅ Section 'payment_status' trouvée !\n";
    echo "Nombre de clés: " . count($frTranslations['payment_status']) . "\n\n";
} else {
    echo "❌ Section 'payment_status' NON trouvée !\n";
}

if (isset($frTranslations['rentals'])) {
    echo "ℹ️ Section 'rentals' (existante) trouvée aussi !\n";
    echo "Nombre de clés: " . count($frTranslations['rentals']) . "\n";
    echo "But: page des produits de location (différent des commandes)\n";
}

?>
