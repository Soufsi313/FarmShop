<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Filesystem\Filesystem;

// Configuration de base
$app = new Application(realpath(__DIR__));
$fileLoader = new FileLoader(new Filesystem(), __DIR__ . '/resources/lang');

echo "=== TEST DES TRADUCTIONS PRODUITS DE LOCATION ===\n\n";

// Test pour chaque langue
$languages = ['fr', 'en', 'nl'];
$testProducts = [
    'beche-professionnelle-4375',
    'motoculteur-electrique-3249', 
    'remorque-basculante-6437'
];

foreach ($languages as $lang) {
    echo "📍 LANGUE: " . strtoupper($lang) . "\n";
    $translator = new Translator($fileLoader, $lang);
    
    echo "\n🏷️  NOMS DES PRODUITS:\n";
    foreach ($testProducts as $slug) {
        $name = $translator->get("app.product_names.$slug");
        echo "  • $slug: $name\n";
    }
    
    echo "\n📝 DESCRIPTIONS DES PRODUITS:\n";
    foreach ($testProducts as $slug) {
        $desc = $translator->get("app.product_descriptions.$slug");
        $truncated = strlen($desc) > 100 ? substr($desc, 0, 100) . '...' : $desc;
        echo "  • $slug: $truncated\n";
    }
    
    echo "\n🏪 CATÉGORIES:\n";
    $categories = ['outils-agricoles', 'machines', 'equipements'];
    foreach ($categories as $cat) {
        $catName = $translator->get("app.rental_categories.$cat");
        echo "  • $cat: $catName\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n\n";
}

echo "✅ Test terminé avec succès!\n";
