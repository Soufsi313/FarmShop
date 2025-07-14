<?php

/**
 * 🗄️ Générateur de commandes d'export pour schémas FarmShop
 * Usage: php generate_export_commands.php
 */

echo "🗄️ FARMSHOP DATABASE - Générateur de commandes d'export\n\n";

// Configuration par défaut
$config = [
    'username' => 'root',
    'password' => '',  // Laisser vide pour saisie interactive
    'database' => 'farmshop',
    'export_dir' => 'database_schemas'
];

// Définition des schémas
$schemas = [
    '01_products' => [
        'name' => 'Produits & Catalogue',
        'icon' => '📦',
        'tables' => ['products', 'categories', 'rental_categories', 'special_offers'],
        'description' => 'Schéma de base - Aucune dépendance'
    ],
    '02_users' => [
        'name' => 'Utilisateurs & Authentification', 
        'icon' => '👥',
        'tables' => ['users', 'password_reset_tokens', 'sessions', 'product_likes', 'wishlists', 'cookies'],
        'description' => 'Dépend de: products (pour likes/wishlists)'
    ],
    '03_orders' => [
        'name' => 'Commandes & Achats',
        'icon' => '🛒', 
        'tables' => ['orders', 'order_items', 'order_returns', 'carts', 'cart_items'],
        'description' => 'Dépend de: users, products'
    ],
    '04_rentals' => [
        'name' => 'Locations',
        'icon' => '🏠',
        'tables' => ['order_locations', 'order_item_locations', 'cart_locations', 'cart_item_locations'],
        'description' => 'Dépend de: users, products'
    ],
    '05_communication' => [
        'name' => 'Communication & Marketing',
        'icon' => '📢',
        'tables' => ['messages', 'blog_categories', 'blog_posts', 'blog_comments', 'blog_comment_reports', 'newsletters', 'newsletter_subscriptions', 'newsletter_sends'],
        'description' => 'Dépend de: users'
    ],
    '06_system' => [
        'name' => 'Système & Infrastructure',
        'icon' => '⚙️',
        'tables' => ['migrations', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs'],
        'description' => 'Indépendant - Tables techniques Laravel'
    ]
];

// Options mysqldump communes
$dump_options = '--routines --triggers --add-drop-table --single-transaction --lock-tables=false';

echo "📋 Configuration:\n";
echo "   Base de données: {$config['database']}\n";
echo "   Utilisateur: {$config['username']}\n";
echo "   Dossier export: {$config['export_dir']}\n\n";

echo "📊 Schémas à exporter (" . count($schemas) . "):\n";
foreach ($schemas as $key => $schema) {
    echo "   {$schema['icon']} {$schema['name']} (" . count($schema['tables']) . " tables)\n";
    echo "      └─ {$schema['description']}\n";
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "📜 COMMANDES D'EXPORT GÉNÉRÉES\n";
echo str_repeat("=", 70) . "\n\n";

// Créer le dossier d'export
echo "# 1. Créer le dossier d'export\n";
echo "mkdir -p {$config['export_dir']}\n\n";

// Générer les commandes pour chaque schéma
foreach ($schemas as $key => $schema) {
    $filename = "{$config['export_dir']}/{$key}_schema.sql";
    $tables = implode(' ', $schema['tables']);
    
    echo "# {$schema['icon']} {$schema['name']}\n";
    echo "echo \"Export: {$schema['name']}...\"\n";
    
    if (empty($config['password'])) {
        echo "mysqldump -u {$config['username']} -p {$config['database']} \\\n";
    } else {
        echo "mysqldump -u {$config['username']} -p'{$config['password']}' {$config['database']} \\\n";
    }
    
    echo "  {$tables} \\\n";
    echo "  {$dump_options} > {$filename}\n\n";
}

echo str_repeat("-", 50) . "\n";
echo "📝 SCRIPT COMPLET (Copier-coller dans votre terminal)\n";
echo str_repeat("-", 50) . "\n\n";

// Générer le script complet
$script = "#!/bin/bash\n";
$script .= "# Export automatique des schémas FarmShop\n";
$script .= "# Généré le: " . date('Y-m-d H:i:s') . "\n\n";
$script .= "mkdir -p {$config['export_dir']}\n\n";

foreach ($schemas as $key => $schema) {
    $filename = "{$config['export_dir']}/{$key}_schema.sql";
    $tables = implode(' ', $schema['tables']);
    
    $script .= "echo \"{$schema['icon']} Export: {$schema['name']}...\"\n";
    
    if (empty($config['password'])) {
        $script .= "mysqldump -u {$config['username']} -p {$config['database']} ";
    } else {
        $script .= "mysqldump -u {$config['username']} -p'{$config['password']}' {$config['database']} ";
    }
    
    $script .= "{$tables} {$dump_options} > {$filename}\n\n";
}

$script .= "echo \"✅ Export terminé! Fichiers dans: {$config['export_dir']}/\"\n";
$script .= "ls -la {$config['export_dir']}/\n";

echo $script;

// Sauvegarder le script
file_put_contents('run_export.sh', $script);
chmod('run_export.sh', 0755);

echo "\n" . str_repeat("=", 70) . "\n";
echo "💾 FICHIERS GÉNÉRÉS\n";
echo str_repeat("=", 70) . "\n\n";
echo "✅ Script bash sauvegardé: run_export.sh\n";
echo "   Exécuter avec: ./run_export.sh\n\n";

echo "📖 PROCHAINES ÉTAPES:\n";
echo "1. Exécuter: ./run_export.sh\n";
echo "2. Importer dans votre outil de modélisation:\n";
echo "   - MySQL Workbench: File > Reverse Engineer\n";
echo "   - DBeaver: Sélectionner les fichiers .sql\n";
echo "   - phpMyAdmin: Import > Sélectionner fichier\n\n";

echo "🎯 ORDRE D'IMPORT RECOMMANDÉ:\n";
foreach ($schemas as $key => $schema) {
    echo "   {$key}_schema.sql - {$schema['name']}\n";
}

echo "\n📋 Consultez DATABASE_SCHEMA_GUIDE.md pour plus de détails.\n";

?>
