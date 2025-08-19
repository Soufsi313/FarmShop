<?php

require_once 'vendor/autoload.php';

use App\Models\Category;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VÉRIFICATION DES SLUGS EXISTANTS ===\n\n";

$categories = Category::all(['id', 'name', 'slug']);

foreach ($categories as $cat) {
    echo "ID: {$cat->id} | Nom: '{$cat->name}' | Slug: '{$cat->slug}'\n";
}

echo "\nNombre total: " . $categories->count() . "\n";
