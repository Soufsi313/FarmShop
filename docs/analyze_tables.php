<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    // Connexion à la base de données
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=FarmShop', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Récupérer toutes les tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Exclure les tables système
    $excludeTables = ['failed_jobs', 'jobs', 'job_batches', 'cache', 'cache_locks', 'migrations', 'password_reset_tokens', 'sessions'];
    $filteredTables = array_filter($tables, function($table) use ($excludeTables) {
        return !in_array($table, $excludeTables);
    });
    
    echo "Tables trouvées dans la base de données FarmShop :\n";
    echo "========================================\n";
    foreach ($filteredTables as $table) {
        echo "- $table\n";
    }
    echo "\nTotal: " . count($filteredTables) . " tables\n";
    
    // Analyser chaque table pour récupérer la structure
    $tableStructures = [];
    foreach ($filteredTables as $tableName) {
        $stmt = $pdo->query("DESCRIBE $tableName");
        $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $tableStructures[$tableName] = [
            'description' => "Table $tableName du système FarmShop",
            'fields' => []
        ];
        
        foreach ($fields as $field) {
            $tableStructures[$tableName]['fields'][] = [
                'name' => $field['Field'],
                'type' => $field['Type'],
                'size' => '',
                'null' => $field['Null'] === 'YES' ? 'OUI' : 'NON',
                'key' => $field['Key'] === 'PRI' ? 'PRI' : ($field['Key'] === 'UNI' ? 'UNI' : ($field['Key'] === 'MUL' ? 'FOR' : '')),
                'default' => $field['Default'] ?: '',
                'description' => "Champ {$field['Field']} de la table $tableName"
            ];
        }
        
        echo "✓ Table $tableName analysée : " . count($fields) . " champs\n";
    }
    
    // Sauvegarder les structures dans un fichier JSON pour le script Excel
    $jsonResult = json_encode($tableStructures, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($jsonResult === false) {
        echo "Erreur lors de l'encodage JSON : " . json_last_error_msg() . "\n";
    } else {
        $bytesWritten = file_put_contents(__DIR__ . '/table_structures.json', $jsonResult);
        if ($bytesWritten === false) {
            echo "Erreur lors de l'écriture du fichier JSON\n";
        } else {
            echo "\n✅ Structures sauvegardées dans table_structures.json ($bytesWritten bytes)\n";
        }
    }
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}

?>
