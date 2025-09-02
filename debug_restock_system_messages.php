<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->boot();

// Tester les messages système pour les trois langues
$languages = ['fr', 'en', 'nl'];
$messages = [
    'restock_completed_title',
    'restock_completed_message', 
    'auto_restock_title',
    'auto_restock_message',
    'bulk_restock_title',
    'bulk_restock_message'
];

echo "=== Test des Messages Système de Réapprovisionnement ===\n\n";

foreach ($languages as $lang) {
    app()->setLocale($lang);
    echo "🌍 Langue: " . strtoupper($lang) . "\n";
    echo str_repeat("=", 50) . "\n";
    
    foreach ($messages as $key) {
        $fullKey = "stock.restock_system_messages.{$key}";
        
        if (strpos($key, 'message') !== false) {
            // Pour les messages avec paramètres
            $translation = __($fullKey, [
                'product' => 'Pommes Royal Gala',
                'quantity' => 100,
                'new_stock' => 150
            ]);
        } else {
            // Pour les titres simples
            $translation = __($fullKey);
        }
        
        echo "📝 {$key}: {$translation}\n";
    }
    
    echo "\n";
}

echo "=== Exemple de Messages Complets ===\n\n";

foreach ($languages as $lang) {
    app()->setLocale($lang);
    echo "🌍 " . strtoupper($lang) . ":\n";
    
    // Message normal
    $title1 = "✅ " . __('stock.restock_system_messages.restock_completed_title');
    $message1 = __('stock.restock_system_messages.restock_completed_message', [
        'product' => 'Raisins Noirs',
        'quantity' => 100,
        'new_stock' => 150
    ]);
    echo "🔔 {$title1}\n{$message1}\n\n";
    
    // Message automatique  
    $title2 = "🔄 " . __('stock.restock_system_messages.auto_restock_title');
    $message2 = __('stock.restock_system_messages.auto_restock_message', [
        'product' => 'Pommes Royal Gala',
        'quantity' => 100,
        'new_stock' => 100
    ]);
    echo "🔔 {$title2}\n{$message2}\n\n";
    
    echo str_repeat("-", 60) . "\n\n";
}

echo "✅ Test terminé !\n";
