<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ANALYSE STRUCTURE BASE DE DONNÉES FARMSHOP ===\n\n";

// Lister toutes les tables
$tables = DB::select('SHOW TABLES');
$tableNames = array_map(function($table) {
    return array_values((array)$table)[0];
}, $tables);

echo "TABLES TROUVÉES (" . count($tableNames) . ") :\n";
foreach ($tableNames as $table) {
    echo "- $table\n";
}

echo "\n=== GROUPEMENT LOGIQUE SUGGÉRÉ ===\n\n";

// Groupement par domaine métier
$groups = [
    'AUTHENTIFICATION & UTILISATEURS' => [
        'users',
        'password_reset_tokens',
        'sessions',
        'personal_access_tokens'
    ],
    
    'PRODUITS & CATALOGUE' => [
        'products',
        'categories',
        'rental_categories',
        'product_images'
    ],
    
    'COMMANDES & ACHATS' => [
        'orders',
        'order_items',
        'carts',
        'cart_items'
    ],
    
    'LOCATIONS' => [
        'order_locations',
        'order_item_locations',
        'cart_locations',
        'cart_item_locations',
        'rental_constraints'
    ],
    
    'COMMUNICATION' => [
        'messages',
        'message_responses'
    ],
    
    'PAIEMENTS' => [
        'payments',
        'payment_intents',
        'failed_jobs'
    ],
    
    'SYSTÈME' => [
        'migrations',
        'cache',
        'cache_locks',
        'job_batches',
        'jobs'
    ]
];

foreach ($groups as $groupName => $expectedTables) {
    echo "📁 $groupName:\n";
    foreach ($expectedTables as $table) {
        $exists = in_array($table, $tableNames) ? '✅' : '❌';
        echo "   $exists $table\n";
    }
    echo "\n";
}

// Tables non catégorisées
$categorized = array_merge(...array_values($groups));
$uncategorized = array_diff($tableNames, $categorized);

if (!empty($uncategorized)) {
    echo "🔍 TABLES NON CATÉGORISÉES:\n";
    foreach ($uncategorized as $table) {
        echo "   ⚠️  $table\n";
    }
    echo "\n";
}

echo "=== RELATIONS PRINCIPALES ===\n\n";

// Analyser les clés étrangères pour quelques tables importantes
$foreignKeys = [
    'products' => ['category_id', 'rental_category_id'],
    'order_items' => ['order_id', 'product_id'],
    'order_item_locations' => ['order_location_id', 'product_id'],
    'cart_items' => ['cart_id', 'product_id'],
    'orders' => ['user_id'],
    'order_locations' => ['user_id']
];

foreach ($foreignKeys as $table => $fks) {
    if (in_array($table, $tableNames)) {
        echo "🔗 $table:\n";
        foreach ($fks as $fk) {
            echo "   → $fk\n";
        }
        echo "\n";
    }
}
