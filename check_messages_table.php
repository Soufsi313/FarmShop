<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Vérification de la table messages :\n";
echo "===================================\n";

try {
    $columns = DB::select('DESCRIBE messages');
    echo "Structure de la table 'messages' :\n";
    foreach ($columns as $col) {
        echo "- {$col->Field} : {$col->Type}\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur accès table 'messages' : " . $e->getMessage() . "\n";
    echo "\nRecherche de tables similaires...\n";
    
    $tables = DB::select('SHOW TABLES');
    foreach ($tables as $table) {
        $tableName = array_values((array) $table)[0];
        if (strpos($tableName, 'message') !== false || strpos($tableName, 'inbox') !== false || strpos($tableName, 'notification') !== false) {
            echo "Table trouvée : {$tableName}\n";
        }
    }
}
