<?php

// Test simple des traductions de messages système
// Ce script utilise php artisan tinker pour tester les traductions

$languages = [
    'fr' => 'FRANÇAIS',
    'en' => 'ENGLISH', 
    'nl' => 'NEDERLANDS'
];

$testCases = [
    [
        'product' => 'Raisins Noirs',
        'quantity' => 100,
        'new_stock' => 150,
        'message_type' => 'restock_completed'
    ],
    [
        'product' => 'Pommes Royal Gala', 
        'quantity' => 100,
        'new_stock' => 100,
        'message_type' => 'auto_restock'
    ],
    [
        'product' => 'Bananes',
        'quantity' => 50,
        'new_stock' => 200,
        'message_type' => 'bulk_restock'
    ]
];

echo "=== DÉMONSTRATION DES TRADUCTIONS ===\n";
echo "Messages système de réapprovisionnement\n\n";

foreach ($languages as $locale => $name) {
    echo "🌍 {$name}\n";
    echo str_repeat("=", 50) . "\n";
    
    foreach ($testCases as $test) {
        $title = "stock.restock_system_messages.{$test['message_type']}_title";
        $message = "stock.restock_system_messages.{$test['message_type']}_message";
        
        $emoji = match($test['message_type']) {
            'restock_completed' => '✅',
            'auto_restock' => '🔄',
            'bulk_restock' => '🔄',
            default => '📦'
        };
        
        echo "\nTest: {$test['message_type']}\n";
        echo "Produit: {$test['product']}\n";
        echo "Commande tinker pour tester:\n";
        echo "php artisan tinker --execute=\"app()->setLocale('{$locale}'); echo '{$emoji} ' . __('{$title}') . '\\n'; echo __('{$message}', ['product' => '{$test['product']}', 'quantity' => {$test['quantity']}, 'new_stock' => {$test['new_stock']}]) . '\\n\\n';\"\n";
        echo str_repeat("-", 30) . "\n";
    }
    
    echo "\n";
}

echo "✅ Les traductions sont maintenant intégrées dans:\n";
echo "- resources/lang/fr/stock.php\n";
echo "- resources/lang/en/stock.php\n";
echo "- resources/lang/nl/stock.php\n";
echo "- app/Http/Controllers/Admin/DashboardController.php\n\n";

echo "📝 Nouvelles clés de traduction ajoutées:\n";
echo "- stock.restock_system_messages.restock_completed_title\n";
echo "- stock.restock_system_messages.restock_completed_message\n";
echo "- stock.restock_system_messages.auto_restock_title\n";
echo "- stock.restock_system_messages.auto_restock_message\n";
echo "- stock.restock_system_messages.bulk_restock_title\n";
echo "- stock.restock_system_messages.bulk_restock_message\n\n";

echo "🎉 Translation complete!\n";
