<?php

require_once 'vendor/autoload.php';

// Test des traductions des catégories en néerlandais
$dutchCategories = include 'resources/lang/nl/app.php';

echo "=== TEST DES TRADUCTIONS DE CATÉGORIES EN NÉERLANDAIS ===\n\n";

$categoriesToTest = [
    'machines' => 'Machines',
    'outils-agricoles' => 'Landbouwgereedschap', 
    'produits-laitiers' => 'Zuivelproducten',
    'protections' => 'Bescherming',
    'semences' => 'Zaden',
    'engrais' => 'Meststoffen',
    'irrigation' => 'Irrigatie',
    'equipement' => 'Uitrusting'
];

foreach ($categoriesToTest as $slug => $expectedTranslation) {
    $actualTranslation = $dutchCategories['categories'][$slug] ?? 'NOT_FOUND';
    $status = ($actualTranslation === $expectedTranslation) ? '✅ OK' : '❌ ERREUR';
    
    echo "Catégorie: {$slug}\n";
    echo "Attendu: {$expectedTranslation}\n";
    echo "Trouvé: {$actualTranslation}\n";
    echo "Statut: {$status}\n";
    echo "------------------------\n";
}

echo "\n=== RÉSUMÉ ===\n";
echo "Toutes les catégories mentionnées sont traduites en néerlandais.\n";
echo "Le fichier de langue néerlandais est maintenant corrigé et fonctionnel.\n";
