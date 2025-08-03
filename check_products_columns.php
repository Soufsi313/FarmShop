<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\DB;

echo "=== VÉRIFICATION COLONNES TABLE PRODUCTS ===\n";

try {
    // Obtenir la structure de la table products
    $columns = DB::select("DESCRIBE products");
    
    echo "Colonnes disponibles dans la table products :\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
    
    echo "\n=== RECHERCHE COLONNES LOCATION ===\n";
    
    $rentalColumns = array_filter($columns, function($col) {
        return strpos(strtolower($col->Field), 'rental') !== false;
    });
    
    if (count($rentalColumns) > 0) {
        echo "Colonnes liées à 'rental' trouvées :\n";
        foreach ($rentalColumns as $col) {
            echo "- {$col->Field}\n";
        }
    } else {
        echo "Aucune colonne contenant 'rental' trouvée.\n";
    }
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}

echo "\n=== FIN VÉRIFICATION ===\n";
