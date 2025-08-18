<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ANALYSE DE LA STRUCTURE DE BASE DE DONNÉES ===\n\n";

try {
    // Récupérer toutes les tables
    $tables = DB::select('SHOW TABLES');
    $databaseName = config('database.connections.mariadb.database');
    $tableColumn = "Tables_in_{$databaseName}";
    
    // Tables système à exclure
    $systemTables = [
        'migrations',
        'password_reset_tokens', 
        'password_resets',
        'personal_access_tokens',
        'sessions',
        'jobs',
        'job_batches',
        'failed_jobs',
        'cache',
        'cache_locks'
    ];
    
    $businessTables = [];
    
    echo "📊 TABLES MÉTIER DÉTECTÉES:\n";
    echo str_repeat("-", 60) . "\n";
    
    foreach ($tables as $table) {
        $tableName = $table->$tableColumn;
        
        if (!in_array($tableName, $systemTables)) {
            $businessTables[] = $tableName;
            
            // Récupérer les colonnes de la table
            $columns = DB::select("SHOW COLUMNS FROM `{$tableName}`");
            
            echo "🔹 {$tableName}\n";
            
            foreach ($columns as $column) {
                $key = $column->Key === 'PRI' ? ' [PK]' : 
                      ($column->Key === 'MUL' ? ' [FK]' : '');
                $nullable = $column->Null === 'YES' ? ' (nullable)' : '';
                $default = $column->Default ? " = {$column->Default}" : '';
                
                echo "   • {$column->Field}: {$column->Type}{$key}{$nullable}{$default}\n";
            }
            echo "\n";
        }
    }
    
    echo str_repeat("=", 60) . "\n";
    echo "📋 GÉNÉRATION DU SCHÉMA DE BASE DE DONNÉES\n";
    echo str_repeat("=", 60) . "\n";
    
    // Analyser les relations entre les tables
    $relations = [];
    
    foreach ($businessTables as $tableName) {
        $foreignKeys = DB::select("
            SELECT 
                COLUMN_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = ? 
            AND TABLE_NAME = ? 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [config('database.connections.mariadb.database'), $tableName]);
        
        foreach ($foreignKeys as $fk) {
            $relations[] = [
                'from_table' => $tableName,
                'from_column' => $fk->COLUMN_NAME,
                'to_table' => $fk->REFERENCED_TABLE_NAME,
                'to_column' => $fk->REFERENCED_COLUMN_NAME
            ];
        }
    }
    
    echo "🔗 RELATIONS DÉTECTÉES:\n";
    foreach ($relations as $relation) {
        echo "   {$relation['from_table']}.{$relation['from_column']} → {$relation['to_table']}.{$relation['to_column']}\n";
    }
    
    // Générer le fichier de données pour le schéma
    $schemaData = [
        'tables' => [],
        'relations' => $relations
    ];
    
    foreach ($businessTables as $tableName) {
        $columns = DB::select("SHOW COLUMNS FROM `{$tableName}`");
        
        $tableData = [
            'name' => $tableName,
            'columns' => []
        ];
        
        foreach ($columns as $column) {
            $tableData['columns'][] = [
                'name' => $column->Field,
                'type' => $column->Type,
                'key' => $column->Key,
                'nullable' => $column->Null === 'YES',
                'default' => $column->Default
            ];
        }
        
        $schemaData['tables'][] = $tableData;
    }
    
    // Sauvegarder les données
    file_put_contents('database_schema_data.json', json_encode($schemaData, JSON_PRETTY_PRINT));
    echo "\n✅ Données du schéma sauvegardées dans database_schema_data.json\n";
    
    // Générer le schéma SVG
    generateDatabaseSchema($schemaData);
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Trace: " . $e->getTraceAsString() . "\n";
}

function generateDatabaseSchema($data) {
    echo "\n🎨 GÉNÉRATION DU SCHÉMA VISUEL...\n";
    
    // Configuration du schéma
    $tableWidth = 200;
    $tableHeight = 30; // Base height
    $columnHeight = 20;
    $margin = 50;
    $tablesPerRow = 3;
    
    // Calculer les positions des tables
    $tablePositions = [];
    $currentX = $margin;
    $currentY = $margin;
    $maxHeightInRow = 0;
    $tablesInCurrentRow = 0;
    
    foreach ($data['tables'] as $index => $table) {
        $tableH = $tableHeight + (count($table['columns']) * $columnHeight);
        
        if ($tablesInCurrentRow >= $tablesPerRow) {
            $currentX = $margin;
            $currentY += $maxHeightInRow + $margin;
            $maxHeightInRow = 0;
            $tablesInCurrentRow = 0;
        }
        
        $tablePositions[$table['name']] = [
            'x' => $currentX,
            'y' => $currentY,
            'width' => $tableWidth,
            'height' => $tableH
        ];
        
        $currentX += $tableWidth + $margin;
        $maxHeightInRow = max($maxHeightInRow, $tableH);
        $tablesInCurrentRow++;
    }
    
    // Calculer la taille totale du SVG
    $maxX = 0;
    $maxY = 0;
    foreach ($tablePositions as $pos) {
        $maxX = max($maxX, $pos['x'] + $pos['width']);
        $maxY = max($maxY, $pos['y'] + $pos['height']);
    }
    
    $svgWidth = $maxX + $margin;
    $svgHeight = $maxY + $margin;
    
    // Générer le SVG
    $svg = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $svg .= "<svg width=\"{$svgWidth}\" height=\"{$svgHeight}\" xmlns=\"http://www.w3.org/2000/svg\">\n";
    $svg .= "<defs>\n";
    $svg .= "  <style>\n";
    $svg .= "    .table-header { fill: #2c5aa0; color: white; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; }\n";
    $svg .= "    .table-name { fill: white; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; }\n";
    $svg .= "    .column-text { fill: #333; font-family: Arial, sans-serif; font-size: 10px; }\n";
    $svg .= "    .pk-column { fill: #d4af37; font-weight: bold; }\n";
    $svg .= "    .fk-column { fill: #4a90e2; }\n";
    $svg .= "    .table-border { fill: none; stroke: #ccc; stroke-width: 1; }\n";
    $svg .= "    .relation-line { stroke: #666; stroke-width: 1.5; fill: none; marker-end: url(#arrowhead); }\n";
    $svg .= "  </style>\n";
    $svg .= "  <marker id=\"arrowhead\" markerWidth=\"10\" markerHeight=\"7\" refX=\"10\" refY=\"3.5\" orient=\"auto\">\n";
    $svg .= "    <polygon points=\"0 0, 10 3.5, 0 7\" fill=\"#666\" />\n";
    $svg .= "  </marker>\n";
    $svg .= "</defs>\n\n";
    
    // Titre
    $svg .= "<text x=\"" . ($svgWidth / 2) . "\" y=\"25\" text-anchor=\"middle\" style=\"fill: #2c5aa0; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold;\">FarmShop - Schéma de Base de Données</text>\n\n";
    
    // Dessiner les tables
    foreach ($data['tables'] as $table) {
        $pos = $tablePositions[$table['name']];
        
        // Rectangle principal de la table
        $svg .= "<rect x=\"{$pos['x']}\" y=\"{$pos['y']}\" width=\"{$pos['width']}\" height=\"{$pos['height']}\" class=\"table-border\" fill=\"white\"/>\n";
        
        // En-tête de la table
        $svg .= "<rect x=\"{$pos['x']}\" y=\"{$pos['y']}\" width=\"{$pos['width']}\" height=\"{$tableHeight}\" class=\"table-header\"/>\n";
        $svg .= "<text x=\"" . ($pos['x'] + $pos['width']/2) . "\" y=\"" . ($pos['y'] + 18) . "\" text-anchor=\"middle\" class=\"table-name\">{$table['name']}</text>\n";
        
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
            $columnText = $columnPrefix . $column['name'] . ': ' . $column['type'] . $nullableText;
            
            $svg .= "<text x=\"" . ($pos['x'] + 5) . "\" y=\"" . ($currentY + 15) . "\" class=\"{$columnClass}\">{$columnText}</text>\n";
            $currentY += $columnHeight;
        }
    }
    
    // Dessiner les relations
    foreach ($data['relations'] as $relation) {
        if (!isset($tablePositions[$relation['from_table']]) || !isset($tablePositions[$relation['to_table']])) {
            continue;
        }
        
        $fromPos = $tablePositions[$relation['from_table']];
        $toPos = $tablePositions[$relation['to_table']];
        
        // Points de connexion (centre des tables)
        $fromX = $fromPos['x'] + $fromPos['width'] / 2;
        $fromY = $fromPos['y'] + $fromPos['height'] / 2;
        $toX = $toPos['x'] + $toPos['width'] / 2;
        $toY = $toPos['y'] + $toPos['height'] / 2;
        
        // Ajuster les points aux bords des rectangles
        if ($fromX < $toX) {
            $fromX = $fromPos['x'] + $fromPos['width'];
            $toX = $toPos['x'];
        } else {
            $fromX = $fromPos['x'];
            $toX = $toPos['x'] + $toPos['width'];
        }
        
        $svg .= "<line x1=\"{$fromX}\" y1=\"{$fromY}\" x2=\"{$toX}\" y2=\"{$toY}\" class=\"relation-line\"/>\n";
    }
    
    $svg .= "</svg>";
    
    // Sauvegarder le SVG
    file_put_contents('farmshop_database_schema.svg', $svg);
    echo "✅ Schéma SVG généré: farmshop_database_schema.svg\n";
    
    // Générer aussi une version HTML pour visualisation
    $html = "<!DOCTYPE html>
<html>
<head>
    <title>FarmShop - Schéma de Base de Données</title>
    <style>
        body { margin: 0; padding: 20px; background: #f5f5f5; font-family: Arial, sans-serif; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2c5aa0; text-align: center; margin-bottom: 30px; }
        .schema { text-align: center; }
        .legend { margin-top: 30px; display: flex; justify-content: center; gap: 30px; }
        .legend-item { display: flex; align-items: center; gap: 5px; }
        .legend-color { width: 15px; height: 15px; border-radius: 3px; }
        .pk { background: #d4af37; }
        .fk { background: #4a90e2; }
        .relation { background: #666; height: 2px; width: 20px; }
    </style>
</head>
<body>
    <div class=\"container\">
        <h1>🌾 FarmShop - Schéma de Base de Données</h1>
        <div class=\"schema\">
            {$svg}
        </div>
        <div class=\"legend\">
            <div class=\"legend-item\">
                <div class=\"legend-color pk\"></div>
                <span>🔑 Clé primaire</span>
            </div>
            <div class=\"legend-item\">
                <div class=\"legend-color fk\"></div>
                <span>🔗 Clé étrangère</span>
            </div>
            <div class=\"legend-item\">
                <div class=\"legend-color relation\"></div>
                <span>→ Relation</span>
            </div>
        </div>
        <div style=\"margin-top: 30px; text-align: center; color: #666; font-size: 12px;\">
            Généré automatiquement le " . date('d/m/Y à H:i') . "
        </div>
    </div>
</body>
</html>";
    
    file_put_contents('farmshop_database_schema.html', $html);
    echo "✅ Version HTML générée: farmshop_database_schema.html\n";
    
    echo "\n🌐 Ouverture du schéma dans le navigateur...\n";
    exec('start "" "farmshop_database_schema.html"');
}
