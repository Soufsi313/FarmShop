<?php

require 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Configuration Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== GÉNÉRATION DES SCHÉMAS THÉMATIQUES ===\n\n";

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

// Fonction pour générer le SVG d'un groupe
function generateGroupSVG($groupData, $tablesInfo, $relations) {
    $tableWidth = 280;
    $tableHeight = 35;
    $columnHeight = 20;
    $margin = 30;
    
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
            'y' => $row * ($maxTableHeight + $margin * 2) + 80,
            'width' => $tableWidth,
            'height' => $tableActualHeight
        ];
    }
    
    $svgWidth = $tablesPerRow * ($tableWidth + $margin) + $margin;
    $svgHeight = $rows * ($maxTableHeight + $margin * 2) + 150;
    
    // Génération du SVG
    $svg = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $svg .= "<svg width=\"{$svgWidth}\" height=\"{$svgHeight}\" xmlns=\"http://www.w3.org/2000/svg\">\n";
    
    // Styles CSS
    $svg .= "<style>\n";
    $svg .= "  .table-border { stroke: #333; stroke-width: 2; }\n";
    $svg .= "  .table-header { fill: {$groupData['color']}; }\n";
    $svg .= "  .table-name { fill: white; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; }\n";
    $svg .= "  .column-text { fill: #333; font-family: Arial, sans-serif; font-size: 11px; }\n";
    $svg .= "  .pk-column { fill: #d35400; font-family: Arial, sans-serif; font-size: 11px; font-weight: bold; }\n";
    $svg .= "  .fk-column { fill: #27ae60; font-family: Arial, sans-serif; font-size: 11px; }\n";
    $svg .= "  .relation-line { stroke: #666; stroke-width: 1.5; fill: none; marker-end: url(#arrowhead); }\n";
    $svg .= "</style>\n\n";
    
    // Définir la flèche pour les relations
    $svg .= "<defs>\n";
    $svg .= "  <marker id=\"arrowhead\" markerWidth=\"10\" markerHeight=\"7\" refX=\"9\" refY=\"3.5\" orient=\"auto\">\n";
    $svg .= "    <polygon points=\"0 0, 10 3.5, 0 7\" fill=\"#666\" />\n";
    $svg .= "  </marker>\n";
    $svg .= "</defs>\n\n";
    
    // Titre et description
    $svg .= "<text x=\"" . ($svgWidth / 2) . "\" y=\"25\" text-anchor=\"middle\" style=\"fill: {$groupData['color']}; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold;\">{$groupData['name']}</text>\n";
    $svg .= "<text x=\"" . ($svgWidth / 2) . "\" y=\"45\" text-anchor=\"middle\" style=\"fill: #666; font-family: Arial, sans-serif; font-size: 12px;\">{$groupData['description']}</text>\n\n";
    
    // Dessiner les tables
    foreach ($tablesInfo as $table) {
        if (!isset($positions[$table['name']])) continue;
        
        $pos = $positions[$table['name']];
        
        // Rectangle principal de la table
        $svg .= "<rect x=\"{$pos['x']}\" y=\"{$pos['y']}\" width=\"{$pos['width']}\" height=\"{$pos['height']}\" class=\"table-border\" fill=\"white\"/>\n";
        
        // En-tête de la table
        $svg .= "<rect x=\"{$pos['x']}\" y=\"{$pos['y']}\" width=\"{$pos['width']}\" height=\"{$tableHeight}\" class=\"table-header\"/>\n";
        $svg .= "<text x=\"" . ($pos['x'] + $pos['width']/2) . "\" y=\"" . ($pos['y'] + 22) . "\" text-anchor=\"middle\" class=\"table-name\">{$table['name']}</text>\n";
        
        // Colonnes
        $currentY = $pos['y'] + $tableHeight;
        foreach ($table['columns'] as $column) {
            $columnClass = 'column-text';
            $columnPrefix = '';
            
            if ($column['key'] === 'PRI') {
                $columnClass = 'pk-column';
                $columnPrefix = '🔑 ';
            } elseif ($column['key'] === 'MUL') {
                $columnClass = 'fk-column';
                $columnPrefix = '🔗 ';
            }
            
            $nullableText = $column['nullable'] ? ' (null)' : '';
            $columnText = htmlspecialchars($columnPrefix . $column['name'] . ': ' . $column['type'] . $nullableText);
            
            $svg .= "<text x=\"" . ($pos['x'] + 8) . "\" y=\"" . ($currentY + 15) . "\" class=\"{$columnClass}\">{$columnText}</text>\n";
            $currentY += $columnHeight;
        }
    }
    
    // Dessiner les relations
    foreach ($relations as $relation) {
        if (!isset($positions[$relation['from_table']]) || !isset($positions[$relation['to_table']])) {
            continue;
        }
        
        $fromPos = $positions[$relation['from_table']];
        $toPos = $positions[$relation['to_table']];
        
        // Points de connexion simplifié (bord des tables)
        $fromX = $fromPos['x'] + $fromPos['width'];
        $fromY = $fromPos['y'] + $fromPos['height'] / 2;
        $toX = $toPos['x'];
        $toY = $toPos['y'] + $toPos['height'] / 2;
        
        // Ligne de relation
        $svg .= "<line x1=\"{$fromX}\" y1=\"{$fromY}\" x2=\"{$toX}\" y2=\"{$toY}\" class=\"relation-line\"/>\n";
    }
    
    $svg .= "</svg>\n";
    
    return $svg;
}

// Générer les schémas pour chaque groupe
foreach ($tableGroups as $groupKey => $groupData) {
    echo "🎨 Génération du schéma: {$groupData['name']}\n";
    
    // Récupérer les informations des tables du groupe
    $tablesInfo = getTableInfo($groupData['tables']);
    $relations = getRelationsForTables($groupData['tables']);
    
    if (empty($tablesInfo)) {
        echo "⚠️  Aucune table trouvée pour le groupe {$groupData['name']}\n";
        continue;
    }
    
    // Générer le SVG
    $svg = generateGroupSVG($groupData, $tablesInfo, $relations);
    
    // Sauvegarder le fichier SVG
    $svgFilename = "docs/diagrams/db_{$groupKey}_schema.svg";
    file_put_contents($svgFilename, $svg);
    
    // Générer le fichier HTML pour la visualisation
    $htmlContent = "<!DOCTYPE html>
<html lang=\"fr\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>FarmShop - Schéma {$groupData['name']}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 100%; overflow: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 20px; }
        .description { color: #666; font-style: italic; margin-bottom: 30px; }
        svg { max-width: 100%; height: auto; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class=\"container\">
        <div class=\"header\">
            <h1 style=\"color: {$groupData['color']};\">FarmShop - {$groupData['name']}</h1>
            <p class=\"description\">{$groupData['description']}</p>
        </div>
        {$svg}
    </div>
</body>
</html>";
    
    $htmlFilename = "docs/diagrams/db_{$groupKey}_schema.html";
    file_put_contents($htmlFilename, $htmlContent);
    
    echo "   ✅ SVG: {$svgFilename}\n";
    echo "   ✅ HTML: {$htmlFilename}\n";
    echo "   📊 Tables: " . count($tablesInfo) . " | Relations: " . count($relations) . "\n\n";
}

echo "🎉 Génération terminée ! Tous les schémas thématiques ont été créés dans docs/diagrams/\n";
echo "\n📁 Fichiers générés :\n";

foreach ($tableGroups as $groupKey => $groupData) {
    echo "   • db_{$groupKey}_schema.svg\n";
    echo "   • db_{$groupKey}_schema.html\n";
}

?>
