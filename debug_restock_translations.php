<?php

echo "=== Test des traductions Stock Restock ===\n\n";

$languages = ['fr', 'en', 'nl'];
$testPath = __DIR__ . '/resources/lang';

echo "1. Vérification des nouvelles clés de réapprovisionnement:\n";
foreach ($languages as $lang) {
    $file = "$testPath/$lang/stock.php";
    if (file_exists($file)) {
        echo "\n--- $lang ---\n";
        $translations = require $file;
        
        // Test des nouvelles clés pour le réapprovisionnement
        $newKeys = [
            'restock_page_title',
            'restock_title', 
            'restock_subtitle',
            'products_to_restock',
            'estimated_total_cost',
            'urgent_priority',
            'total_quantity',
            'restock_suggestions',
            'select_all',
            'apply_selection'
        ];
        
        foreach ($newKeys as $key) {
            $value = $translations[$key] ?? 'MANQUANT';
            echo "$key: $value\n";
        }
    }
}

echo "\n2. Test des étiquettes de priorité:\n";
foreach ($languages as $lang) {
    echo "\n=== $lang ===\n";
    $file = "$testPath/$lang/stock.php";
    if (file_exists($file)) {
        $translations = require $file;
        echo "Tag urgent: " . ($translations['urgent_tag'] ?? 'MANQUANT') . "\n";
        echo "Tag élevé: " . ($translations['high_tag'] ?? 'MANQUANT') . "\n";
    }
}

echo "\n3. Test des informations de stock:\n";
foreach ($languages as $lang) {
    echo "\n=== $lang ===\n";
    $file = "$testPath/$lang/stock.php";
    if (file_exists($file)) {
        $translations = require $file;
        echo "Stock actuel: " . ($translations['current_stock'] ?? 'MANQUANT') . "\n";
        echo "Stock recommandé: " . ($translations['recommended_stock'] ?? 'MANQUANT') . "\n";
        echo "À commander: " . ($translations['to_order'] ?? 'MANQUANT') . "\n";
        echo "Ventes mensuelles: " . ($translations['monthly_sales'] ?? 'MANQUANT') . "\n";
    }
}

echo "\n4. Test des boutons d'action:\n";
foreach ($languages as $lang) {
    echo "\n=== $lang ===\n";
    $file = "$testPath/$lang/stock.php";
    if (file_exists($file)) {
        $translations = require $file;
        echo "Personnaliser: " . ($translations['customize_button'] ?? 'MANQUANT') . "\n";
        echo "Appliquer: " . ($translations['apply_button'] ?? 'MANQUANT') . "\n";
        echo "Sélectionner tout: " . ($translations['select_all'] ?? 'MANQUANT') . "\n";
        echo "Appliquer sélection: " . ($translations['apply_selection'] ?? 'MANQUANT') . "\n";
    }
}

echo "\n5. Test du modal personnalisé:\n";
foreach ($languages as $lang) {
    echo "\n=== $lang ===\n";
    $file = "$testPath/$lang/stock.php";
    if (file_exists($file)) {
        $translations = require $file;
        echo "Titre modal: " . ($translations['custom_restock_title'] ?? 'MANQUANT') . "\n";
        echo "Suggéré: " . ($translations['suggested_quantity'] ?? 'MANQUANT') . "\n";
        echo "Aucun réappro: " . ($translations['no_restock_needed'] ?? 'MANQUANT') . "\n";
        echo "Historique: " . ($translations['restock_history'] ?? 'MANQUANT') . "\n";
    }
}

echo "\n6. Récapitulatif complet:\n";
foreach ($languages as $lang) {
    echo "\n=== $lang ===\n";
    $file = "$testPath/$lang/stock.php";
    if (file_exists($file)) {
        $translations = require $file;
        echo "Page titre: " . ($translations['restock_page_title'] ?? 'MANQUANT') . "\n";
        echo "Titre principal: " . ($translations['restock_title'] ?? 'MANQUANT') . "\n";
        echo "Suggestions: " . ($translations['restock_suggestions'] ?? 'MANQUANT') . "\n";
        echo "Coût total: " . ($translations['estimated_total_cost'] ?? 'MANQUANT') . "\n";
        echo "Priorité urgente: " . ($translations['urgent_priority'] ?? 'MANQUANT') . "\n";
        
        // Compter le total de clés
        $totalKeys = count($translations, COUNT_RECURSIVE);
        echo "Total clés de traduction: $totalKeys\n";
    }
}

echo "\n=== Test terminé ===\n";
