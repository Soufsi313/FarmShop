<?php

echo "=== Test des traductions Stock Alerts ===\n\n";

$languages = ['fr', 'en', 'nl'];
$testPath = __DIR__ . '/resources/lang';

echo "1. Vérification des nouvelles clés d'alertes:\n";
foreach ($languages as $lang) {
    $file = "$testPath/$lang/stock.php";
    if (file_exists($file)) {
        echo "\n--- $lang ---\n";
        $translations = require $file;
        
        // Test des nouvelles clés pour les alertes
        $newKeys = [
            'alerts_page_title',
            'alerts_title', 
            'alerts_subtitle',
            'tab_out_of_stock',
            'tab_critical',
            'tab_low',
            'tab_history',
            'no_out_of_stock',
            'priority_urgent',
            'quick_restock_title'
        ];
        
        foreach ($newKeys as $key) {
            $value = $translations[$key] ?? 'MANQUANT';
            echo "$key: $value\n";
        }
    }
}

echo "\n2. Test des textes d'état vide:\n";
foreach ($languages as $lang) {
    echo "\n=== $lang ===\n";
    $file = "$testPath/$lang/stock.php";
    if (file_exists($file)) {
        $translations = require $file;
        echo "Aucune rupture: " . ($translations['no_out_of_stock'] ?? 'MANQUANT') . "\n";
        echo "Aucun critique: " . ($translations['no_critical_stock'] ?? 'MANQUANT') . "\n";
        echo "Aucun bas: " . ($translations['no_low_stock'] ?? 'MANQUANT') . "\n";
        echo "Aucune alerte: " . ($translations['no_recent_alerts'] ?? 'MANQUANT') . "\n";
    }
}

echo "\n3. Test des priorités et actions:\n";
foreach ($languages as $lang) {
    echo "\n=== $lang ===\n";
    $file = "$testPath/$lang/stock.php";
    if (file_exists($file)) {
        $translations = require $file;
        echo "Urgent: " . ($translations['priority_urgent'] ?? 'MANQUANT') . "\n";
        echo "Élevé/High: " . ($translations['priority_high'] ?? 'MANQUANT') . "\n";
        echo "Normal: " . ($translations['priority_normal'] ?? 'MANQUANT') . "\n";
        echo "Réapprovisionner: " . ($translations['restock_button'] ?? 'MANQUANT') . "\n";
        echo "Modifier: " . ($translations['edit_button'] ?? 'MANQUANT') . "\n";
    }
}

echo "\n4. Test du modal de réapprovisionnement:\n";
foreach ($languages as $lang) {
    echo "\n=== $lang ===\n";
    $file = "$testPath/$lang/stock.php";
    if (file_exists($file)) {
        $translations = require $file;
        echo "Titre modal: " . ($translations['quick_restock_title'] ?? 'MANQUANT') . "\n";
        echo "Produit: " . ($translations['product_label'] ?? 'MANQUANT') . "\n";
        echo "Quantité: " . ($translations['quantity_to_add'] ?? 'MANQUANT') . "\n";
        echo "Annuler: " . ($translations['cancel_button'] ?? 'MANQUANT') . "\n";
        echo "Appliquer: " . ($translations['apply_button'] ?? 'MANQUANT') . "\n";
    }
}

echo "\n=== Test terminé ===\n";
