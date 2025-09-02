<?php

echo "=== Test des traductions Stock Management ===\n\n";

// Test en vérifiant directement les fichiers
$languages = ['fr', 'en', 'nl'];
$testPath = __DIR__ . '/resources/lang';

echo "1. Vérification des fichiers de traduction:\n";
foreach ($languages as $lang) {
    $file = "$testPath/$lang/stock.php";
    if (file_exists($file)) {
        echo "✓ $lang/stock.php existe\n";
        $translations = require $file;
        echo "  - Nombre de clés: " . count($translations, COUNT_RECURSIVE) . "\n";
    } else {
        echo "✗ $lang/stock.php manquant\n";
    }
}

echo "\n2. Test des traductions principales:\n";
foreach ($languages as $lang) {
    $file = "$testPath/$lang/stock.php";
    if (file_exists($file)) {
        $translations = require $file;
        echo "\n--- $lang ---\n";
        echo "Titre: " . ($translations['title'] ?? 'MANQUANT') . "\n";
        echo "Actualiser: " . ($translations['header']['refresh'] ?? 'MANQUANT') . "\n";
        echo "Rupture stock: " . ($translations['stats']['out_of_stock'] ?? 'MANQUANT') . "\n";
        echo "Stock critique: " . ($translations['stats']['critical_stock'] ?? 'MANQUANT') . "\n";
        echo "Actions rapides: " . ($translations['quick_actions'] ?? 'MANQUANT') . "\n";
    }
}

echo "\n3. Test complet des sections:\n";
$testSections = [
    'header' => ['title', 'refresh', 'restock'],
    'stats' => ['out_of_stock', 'critical_stock', 'low_stock', 'normal_stock'],
    'alerts' => ['trends_title', 'view_reports', 'alerts_count'],
    'by_category' => ['title', 'products', 'total_value'],
    'actions' => ['view_urgent', 'manage_products', 'view_all_alerts']
];

foreach ($languages as $lang) {
    echo "\n=== $lang ===\n";
    $file = "$testPath/$lang/stock.php";
    if (file_exists($file)) {
        $translations = require $file;
        foreach ($testSections as $section => $keys) {
            echo "$section:\n";
            foreach ($keys as $key) {
                $value = $translations[$section][$key] ?? 'MANQUANT';
                echo "  $key: $value\n";
            }
        }
    }
}

echo "\n=== Test terminé ===\n";
