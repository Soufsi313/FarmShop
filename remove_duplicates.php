<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\DB;

echo "Recherche des produits en double...\n";

// Liste des noms de produits à vérifier
$productNames = [
    'Pommes Golden',
    'Tomates cerises', 
    'Carottes bio',
    'Bananes',
    'Salade verte',
    'Courgettes',
    'Fraises',
    'Poivrons rouges',
    'Oranges',
    'Brocolis'
];

foreach ($productNames as $productName) {
    $products = Product::where('name', $productName)->orderBy('created_at')->get();
    
    if ($products->count() > 1) {
        echo "\nProduit: {$productName} - {$products->count()} exemplaires trouvés\n";
        
        // Garder le premier (plus ancien) et supprimer les autres
        $toKeep = $products->first();
        $toDelete = $products->skip(1);
        
        echo "  - Gardé: ID {$toKeep->id} (créé le {$toKeep->created_at})\n";
        
        foreach ($toDelete as $duplicate) {
            echo "  - Supprimé: ID {$duplicate->id} (créé le {$duplicate->created_at})\n";
            $duplicate->delete();
        }
    } else {
        echo "Produit: {$productName} - OK (pas de doublon)\n";
    }
}

echo "\nSuppression des doublons terminée!\n";
