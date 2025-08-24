<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Diagnostic du conflit 'motoculteur' ===\n\n";

// Vérifier tous les produits avec des slugs contenant 'motoculteur'
$results = DB::select("SELECT id, name, slug FROM products WHERE slug LIKE '%motoculteur%'");

echo "Produits avec des slugs contenant 'motoculteur' :\n";
foreach($results as $result) {
    echo "ID: {$result->id} | Nom: {$result->name} | Slug: {$result->slug}\n";
}

echo "\n";

// Vérifier spécifiquement le slug 'motoculteur'
$conflict = DB::select("SELECT id, name, slug FROM products WHERE slug = 'motoculteur'");

if (!empty($conflict)) {
    echo "⚠️  CONFLIT : Produit avec le slug 'motoculteur' :\n";
    foreach($conflict as $result) {
        echo "ID: {$result->id} | Nom: {$result->name} | Slug: {$result->slug}\n";
    }
} else {
    echo "✅ Aucun produit avec le slug 'motoculteur'\n";
}

echo "\n";

// Informations sur le produit 210
$product210 = DB::select("SELECT id, name, slug FROM products WHERE id = 210");
if (!empty($product210)) {
    $p = $product210[0];
    echo "=== Produit 210 (celui en cours de modification) ===\n";
    echo "ID: {$p->id} | Nom: {$p->name} | Slug actuel: {$p->slug}\n";
    
    // Simuler la génération du nouveau slug
    $newName = "Motoculteur";
    $baseSlug = \Illuminate\Support\Str::slug($newName);
    echo "Nouveau nom : {$newName}\n";
    echo "Slug qui sera généré : {$baseSlug}\n";
    
    // Vérifier le conflit
    $existingWithSlug = DB::select("SELECT id FROM products WHERE slug = ? AND id != 210", [$baseSlug]);
    if (!empty($existingWithSlug)) {
        echo "❌ CONFLIT avec le produit ID: {$existingWithSlug[0]->id}\n";
    } else {
        echo "✅ Pas de conflit détecté\n";
    }
}

?>
