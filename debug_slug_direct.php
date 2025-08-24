<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Vérification directe en base de données ===\n\n";

// Vérification SQL directe
$results = DB::select("SELECT id, name, slug FROM products WHERE slug = 'epandeur-dengrais'");

if (!empty($results)) {
    echo "Produits trouvés avec le slug 'epandeur-dengrais' :\n";
    foreach($results as $result) {
        echo "ID: {$result->id} | Nom: {$result->name} | Slug: {$result->slug}\n";
    }
} else {
    echo "Aucun produit trouvé avec le slug 'epandeur-dengrais'\n";
}

echo "\n";

// Vérifier tous les slugs similaires
$similarResults = DB::select("SELECT id, name, slug FROM products WHERE slug LIKE '%epandeur%'");

echo "Tous les produits avec des slugs contenant 'epandeur' :\n";
foreach($similarResults as $result) {
    echo "ID: {$result->id} | Nom: {$result->name} | Slug: {$result->slug}\n";
}

echo "\n";

// Vérifier le nouveau slug qui sera généré
$newName = "Épandeur d'engrais";
$newSlug = \Illuminate\Support\Str::slug($newName);
echo "Nouveau slug qui sera généré : {$newSlug}\n";

// Vérifier si ce slug existe
$existingWithNewSlug = DB::select("SELECT id, name, slug FROM products WHERE slug = ?", [$newSlug]);
if (!empty($existingWithNewSlug)) {
    echo "CONFLIT DÉTECTÉ ! Un produit existe déjà avec ce slug :\n";
    foreach($existingWithNewSlug as $result) {
        echo "ID: {$result->id} | Nom: {$result->name} | Slug: {$result->slug}\n";
    }
}

?>
