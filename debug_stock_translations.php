<?php

require_once 'bootstrap/app.php';

use Illuminate\Foundation\Application;
use Illuminate\Translation\Translator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;

echo "=== Test des traductions Stock Management ===\n\n";

// Test direct des traductions
$app = new Application(__DIR__);
$filesystem = new Filesystem;
$loader = new FileLoader($filesystem, __DIR__ . '/resources/lang');
$translator = new Translator($loader, 'fr');

echo "1. Test des traductions principales:\n";
$testKeys = [
    'stock.title',
    'stock.header.title', 
    'stock.header.refresh',
    'stock.stats.out_of_stock',
    'stock.stats.critical_stock',
    'stock.stats.low_stock',
    'stock.stats.normal_stock',
    'stock.alerts.trends_title',
    'stock.by_category.title'
];

$languages = ['fr', 'en', 'nl'];
foreach ($languages as $lang) {
    $translator->setLocale($lang);
    echo "\n--- $lang ---\n";
    foreach ($testKeys as $key) {
        $translation = $translator->get($key);
        echo "$key: $translation\n";
    }
}

echo "\n2. Test des statistiques avec contexte:\n";
foreach ($languages as $lang) {
    $translator->setLocale($lang);
    echo "\n=== $lang ===\n";
    echo "Rupture de stock: " . $translator->get('stock.stats.out_of_stock') . "\n";
    echo "Stock critique: " . $translator->get('stock.stats.critical_stock') . "\n";
    echo "Actions: " . $translator->get('stock.actions.view_urgent') . "\n";
    echo "Gestion: " . $translator->get('stock.actions.manage_products') . "\n";
}

echo "\n3. Test des alertes et tendances:\n";
foreach ($languages as $lang) {
    $translator->setLocale($lang);
    echo "\n=== $lang ===\n";
    echo "Tendances: " . $translator->get('stock.alerts.trends_title') . "\n";
    echo "Voir rapports: " . $translator->get('stock.alerts.view_reports') . "\n";
    echo "Alertes count: " . $translator->get('stock.alerts.alerts_count') . "\n";
}

echo "\n4. Test des boutons et actions:\n";
foreach ($languages as $lang) {
    $translator->setLocale($lang);
    echo "\n=== $lang ===\n";
    echo "Actualiser: " . $translator->get('stock.header.refresh') . "\n";
    echo "Réapprovisionner: " . $translator->get('stock.header.restock') . "\n";
    echo "Actions rapides: " . $translator->get('stock.quick_actions') . "\n";
    echo "Vue détaillée: " . $translator->get('stock.detailed_view') . "\n";
}

echo "\n=== Test terminé ===\n";
