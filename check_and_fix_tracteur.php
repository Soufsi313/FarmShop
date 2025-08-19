<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "=== PRODUITS CATÉGORIE MACHINES ===\n";

$machines = Category::where('slug', 'machines')->first();
if (!$machines) {
    echo "❌ Catégorie Machines non trouvée\n";
    exit;
}

$products = Product::where('category_id', $machines->id)->get();

foreach($products as $p) {
    echo "ID: " . $p->id . " | Nom: " . $p->name . " | Prix: " . $p->price . "€\n";
}

// Chercher spécifiquement le tracteur
$tracteur = Product::where('name', 'LIKE', '%Tracteur%')->first();
if ($tracteur) {
    echo "\n📍 Tracteur trouvé: " . $tracteur->name . " (ID: " . $tracteur->id . ")\n";
    
    // Le modifier
    $tracteur->update([
        'name' => 'Tondeuse Professionnelle',
        'name_en' => 'Professional Mower',
        'name_nl' => 'Professionele Maaier',
        'description' => 'Tondeuse professionnelle haute performance pour entretien des espaces verts',
        'description_en' => 'High-performance professional mower for green space maintenance',
        'description_nl' => 'High-performance professionele maaier voor onderhoud van groene ruimtes',
        'price' => 2500.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ]);
    
    echo "✅ Tracteur modifié en: " . $tracteur->name . "\n";
} else {
    echo "❌ Aucun tracteur trouvé\n";
}

?>
