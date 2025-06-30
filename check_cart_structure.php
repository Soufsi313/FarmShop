<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Vérification des tables existantes ===\n";

try {
    $tables = DB::select('SHOW TABLES');
    echo "Tables trouvées :\n";
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "- $tableName\n";
    }
} catch (Exception $e) {
    echo "Erreur lors de la récupération des tables : " . $e->getMessage() . "\n";
}

echo "\n=== Structure de la table carts ===\n";
try {
    $columns = DB::select('DESCRIBE carts');
    foreach ($columns as $col) {
        echo $col->Field . " - " . $col->Type . " - Null:" . $col->Null . " - Key:" . $col->Key . "\n";
    }
} catch (Exception $e) {
    echo "Erreur table carts : " . $e->getMessage() . "\n";
}

echo "\n=== Structure de la table cart_items (si elle existe) ===\n";
try {
    $columns = DB::select('DESCRIBE cart_items');
    foreach ($columns as $col) {
        echo $col->Field . " - " . $col->Type . " - Null:" . $col->Null . " - Key:" . $col->Key . "\n";
    }
} catch (Exception $e) {
    echo "Table cart_items n'existe pas : " . $e->getMessage() . "\n";
}

echo "\n=== Contenu de quelques lignes de carts ===\n";
try {
    $items = DB::select('SELECT * FROM carts LIMIT 3');
    if (empty($items)) {
        echo "Table carts vide\n";
    } else {
        print_r($items);
    }
} catch (Exception $e) {
    echo "Erreur lors de la lecture de carts : " . $e->getMessage() . "\n";
}
