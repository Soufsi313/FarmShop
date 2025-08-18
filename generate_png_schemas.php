<?php

require 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Configuration Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== GÉNÉRATION DES SCHÉMAS PNG ===\n\n";

// Vérifier si Imagick est disponible
if (!extension_loaded('imagick')) {
    echo "⚠️  Extension Imagick non disponible. Génération via conversion HTML vers image...\n\n";
}

// Définition des groupes thématiques
$tableGroups = [
    'users_authentication' => [
        'name' => 'Utilisateurs & Authentification',
        'tables' => ['users', 'cookies', 'messages'],
        'description' => 'Gestion des utilisateurs, authentification et messagerie système',
        'color' => '#3498db'
    ],
    'products_catalog' => [
        'name' => 'Catalogue de Produits',
        'tables' => ['products', 'categories', 'rental_categories', 'product_likes', 'wishlists', 'special_offers'],
        'description' => 'Gestion du catalogue, catégories et système de favoris',
        'color' => '#e74c3c'
    ],
    'shopping_carts' => [
        'name' => 'Paniers d\'Achat',
        'tables' => ['carts', 'cart_items', 'cart_locations', 'cart_item_locations'],
        'description' => 'Système de paniers pour ventes et locations',
        'color' => '#f39c12'
    ],
    'orders_rentals' => [
        'name' => 'Commandes & Locations',
        'tables' => ['orders', 'order_items', 'order_locations', 'order_item_locations', 'order_returns', 'order_status_transitions', 'email_logs'],
        'description' => 'Gestion des commandes, locations et processus de retour',
        'color' => '#2ecc71'
    ],
    'blog_system' => [
        'name' => 'Système de Blog',
        'tables' => ['blog_categories', 'blog_posts', 'blog_post_translations', 'blog_comments', 'blog_comment_reports', 'comment_translations'],
        'description' => 'Système de blog avec commentaires et support multilingue',
        'color' => '#9b59b6'
    ],
    'newsletter_system' => [
        'name' => 'Système de Newsletter',
        'tables' => ['newsletters', 'newsletter_subscriptions', 'newsletter_sends'],
        'description' => 'Gestion des newsletters et système d\'abonnement',
        'color' => '#1abc9c'
    ]
];

// Fonction pour récupérer les informations des tables
function getTableInfo($tableNames) {
    $tablesInfo = [];
    
    foreach ($tableNames as $tableName) {
        try {
            // Récupérer les colonnes avec SHOW COLUMNS
            $columns = DB::select("SHOW COLUMNS FROM `{$tableName}`");
            
            $tableInfo = [
                'name' => $tableName,
                'columns' => []
            ];
            
            foreach ($columns as $column) {
                $tableInfo['columns'][] = [
                    'name' => $column->Field,
                    'type' => $column->Type,
                    'nullable' => $column->Null === 'YES',
                    'key' => $column->Key,
                    'default' => $column->Default,
                    'extra' => $column->Extra
                ];
            }
            
            $tablesInfo[] = $tableInfo;
            
        } catch (Exception $e) {
            echo "⚠️  Table {$tableName} non trouvée ou erreur : " . $e->getMessage() . "\n";
        }
    }
    
    return $tablesInfo;
}

// Fonction pour détecter les relations pour un groupe de tables
function getRelationsForTables($tableNames) {
    $relations = [];
    
    foreach ($tableNames as $tableName) {
        try {
            // Récupérer les foreign keys
            $foreignKeys = DB::select("
                SELECT 
                    COLUMN_NAME as column_name,
                    REFERENCED_TABLE_NAME as referenced_table,
                    REFERENCED_COLUMN_NAME as referenced_column
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = ? 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ", [$tableName]);
            
            foreach ($foreignKeys as $fk) {
                // Ne garder que les relations internes au groupe
                if (in_array($fk->referenced_table, $tableNames)) {
                    $relations[] = [
                        'from_table' => $tableName,
                        'from_column' => $fk->column_name,
                        'to_table' => $fk->referenced_table,
                        'to_column' => $fk->referenced_column
                    ];
                }
            }
            
        } catch (Exception $e) {
            echo "⚠️  Erreur lors de la récupération des relations pour {$tableName}: " . $e->getMessage() . "\n";
        }
    }
    
    return $relations;
}

// Fonction pour créer une image PNG avec GD
function generatePNGWithGD($groupData, $tablesInfo, $relations) {
    $tableWidth = 320;
    $tableHeight = 40;
    $columnHeight = 22;
    $margin = 40;
    $headerHeight = 80;
    
    // Calculer les positions des tables en grille
    $tablesPerRow = min(3, count($tablesInfo));
    $rows = ceil(count($tablesInfo) / $tablesPerRow);
    
    $positions = [];
    $maxTableHeight = 0;
    
    for ($i = 0; $i < count($tablesInfo); $i++) {
        $row = floor($i / $tablesPerRow);
        $col = $i % $tablesPerRow;
        
        $tableActualHeight = $tableHeight + (count($tablesInfo[$i]['columns']) * $columnHeight);
        $maxTableHeight = max($maxTableHeight, $tableActualHeight);
        
        $positions[$tablesInfo[$i]['name']] = [
            'x' => $col * ($tableWidth + $margin) + $margin,
            'y' => $row * ($maxTableHeight + $margin * 2) + $headerHeight,
            'width' => $tableWidth,
            'height' => $tableActualHeight
        ];
    }
    
    $imgWidth = $tablesPerRow * ($tableWidth + $margin) + $margin;
    $imgHeight = $rows * ($maxTableHeight + $margin * 2) + $headerHeight + 50;
    
    // Créer l'image
    $image = imagecreatetruecolor($imgWidth, $imgHeight);
    
    // Couleurs
    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    $gray = imagecolorallocate($image, 102, 102, 102);
    $lightGray = imagecolorallocate($image, 220, 220, 220);
    
    // Couleur du thème
    $colorHex = str_replace('#', '', $groupData['color']);
    $colorR = hexdec(substr($colorHex, 0, 2));
    $colorG = hexdec(substr($colorHex, 2, 2));
    $colorB = hexdec(substr($colorHex, 4, 2));
    $themeColor = imagecolorallocate($image, $colorR, $colorG, $colorB);
    
    // Couleurs pour les colonnes
    $pkColor = imagecolorallocate($image, 211, 84, 0);  // Orange pour PK
    $fkColor = imagecolorallocate($image, 39, 174, 96); // Vert pour FK
    
    // Fond blanc
    imagefill($image, 0, 0, $white);
    
    // Titre
    $titleX = $imgWidth / 2;
    $font = 5; // Taille de police built-in
    imagestring($image, $font, $titleX - (strlen($groupData['name']) * 6), 20, $groupData['name'], $themeColor);
    
    // Description
    imagestring($image, 3, $titleX - (strlen($groupData['description']) * 3), 45, $groupData['description'], $gray);
    
    // Dessiner les tables
    foreach ($tablesInfo as $table) {
        if (!isset($positions[$table['name']])) continue;
        
        $pos = $positions[$table['name']];
        
        // Rectangle principal de la table
        imagerectangle($image, $pos['x'], $pos['y'], $pos['x'] + $pos['width'], $pos['y'] + $pos['height'], $black);
        
        // En-tête de la table
        imagefilledrectangle($image, $pos['x'], $pos['y'], $pos['x'] + $pos['width'], $pos['y'] + $tableHeight, $themeColor);
        
        // Nom de la table
        $tableNameX = $pos['x'] + ($pos['width'] / 2) - (strlen($table['name']) * 6);
        imagestring($image, 4, $tableNameX, $pos['y'] + 12, $table['name'], $white);
        
        // Colonnes
        $currentY = $pos['y'] + $tableHeight;
        foreach ($table['columns'] as $column) {
            $columnColor = $black;
            $columnPrefix = '';
            
            if ($column['key'] === 'PRI') {
                $columnColor = $pkColor;
                $columnPrefix = '[PK] ';
            } elseif ($column['key'] === 'MUL') {
                $columnColor = $fkColor;
                $columnPrefix = '[FK] ';
            }
            
            $nullableText = $column['nullable'] ? ' (null)' : '';
            $columnText = $columnPrefix . $column['name'] . ': ' . $column['type'] . $nullableText;
            
            // Limiter la longueur du texte
            if (strlen($columnText) > 35) {
                $columnText = substr($columnText, 0, 32) . '...';
            }
            
            imagestring($image, 2, $pos['x'] + 8, $currentY + 5, $columnText, $columnColor);
            $currentY += $columnHeight;
        }
    }
    
    // Dessiner les relations (lignes simples)
    foreach ($relations as $relation) {
        if (!isset($positions[$relation['from_table']]) || !isset($positions[$relation['to_table']])) {
            continue;
        }
        
        $fromPos = $positions[$relation['from_table']];
        $toPos = $positions[$relation['to_table']];
        
        // Points de connexion
        $fromX = $fromPos['x'] + $fromPos['width'];
        $fromY = $fromPos['y'] + $fromPos['height'] / 2;
        $toX = $toPos['x'];
        $toY = $toPos['y'] + $toPos['height'] / 2;
        
        // Ligne de relation
        imageline($image, $fromX, $fromY, $toX, $toY, $gray);
        
        // Petite flèche
        $arrowSize = 5;
        imageline($image, $toX, $toY, $toX - $arrowSize, $toY - $arrowSize, $gray);
        imageline($image, $toX, $toY, $toX - $arrowSize, $toY + $arrowSize, $gray);
    }
    
    return $image;
}

// Générer les images PNG pour chaque groupe
foreach ($tableGroups as $groupKey => $groupData) {
    echo "🎨 Génération du schéma PNG: {$groupData['name']}\n";
    
    // Récupérer les informations des tables du groupe
    $tablesInfo = getTableInfo($groupData['tables']);
    $relations = getRelationsForTables($groupData['tables']);
    
    if (empty($tablesInfo)) {
        echo "⚠️  Aucune table trouvée pour le groupe {$groupData['name']}\n";
        continue;
    }
    
    // Générer l'image PNG
    $image = generatePNGWithGD($groupData, $tablesInfo, $relations);
    
    // Sauvegarder le fichier PNG
    $pngFilename = "docs/diagrams/db_{$groupKey}_schema.png";
    
    if (!is_dir('docs/diagrams')) {
        mkdir('docs/diagrams', 0755, true);
    }
    
    if (imagepng($image, $pngFilename)) {
        echo "   ✅ PNG: {$pngFilename}\n";
        echo "   📊 Tables: " . count($tablesInfo) . " | Relations: " . count($relations) . "\n";
    } else {
        echo "   ❌ Erreur lors de la sauvegarde: {$pngFilename}\n";
    }
    
    // Libérer la mémoire
    imagedestroy($image);
    echo "\n";
}

echo "🎉 Génération terminée ! Tous les schémas PNG ont été créés dans docs/diagrams/\n";
echo "\n📁 Fichiers PNG générés :\n";

foreach ($tableGroups as $groupKey => $groupData) {
    echo "   • db_{$groupKey}_schema.png\n";
}

echo "\n💡 Vous pouvez maintenant ouvrir ces fichiers PNG directement sur votre PC !\n";

?>
