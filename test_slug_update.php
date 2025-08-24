<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Test de génération de slug pour le produit 212 ===\n\n";

// Simuler la mise à jour du produit 212
$productId = 212;
$productName = "Épandeur d'engrais"; // Le nom que vous voulez utiliser

// Générer le slug de base
$baseSlug = \Illuminate\Support\Str::slug($productName);
echo "Nom du produit : {$productName}\n";
echo "Slug de base généré : {$baseSlug}\n\n";

// Vérifier si le slug existe déjà (en excluant le produit actuel)
$existingProduct = DB::table('products')
    ->where('slug', $baseSlug)
    ->where('id', '!=', $productId)
    ->first();

if ($existingProduct) {
    echo "⚠️  CONFLIT DÉTECTÉ !\n";
    echo "Un autre produit (ID: {$existingProduct->id}) utilise déjà le slug '{$baseSlug}'\n\n";
    
    // Générer un slug unique
    $slug = $baseSlug;
    $counter = 1;
    
    do {
        $slug = $baseSlug . '-' . $counter;
        $exists = DB::table('products')
            ->where('slug', $slug)
            ->where('id', '!=', $productId)
            ->exists();
        
        if ($exists) {
            echo "Slug '{$slug}' déjà utilisé, essai suivant...\n";
            $counter++;
        }
    } while ($exists);
    
    echo "✅ Slug unique trouvé : {$slug}\n";
} else {
    echo "✅ Le slug '{$baseSlug}' est disponible pour le produit {$productId}\n";
}

echo "\n=== État actuel du produit 212 ===\n";
$currentProduct = DB::table('products')->where('id', 212)->first();
if ($currentProduct) {
    echo "Nom actuel : {$currentProduct->name}\n";
    echo "Slug actuel : {$currentProduct->slug}\n";
} else {
    echo "Produit 212 non trouvé !\n";
}

?>
