<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Debug des traductions de catégories de location ===\n\n";

// Tester en français
app()->setLocale('fr');
echo "🇫🇷 FRANÇAIS (locale: " . app()->getLocale() . ")\n";
foreach(\App\Models\RentalCategory::all() as $category) {
    echo "- Nom DB: '{$category->name}'\n";
    echo "- Traduit: '{$category->translated_name}'\n";
    echo "- Description traduite: '" . substr($category->translated_description, 0, 50) . "...'\n";
    echo "---\n";
}

// Tester en néerlandais  
app()->setLocale('nl');
echo "\n🇳🇱 NÉERLANDAIS (locale: " . app()->getLocale() . ")\n";
foreach(\App\Models\RentalCategory::all() as $category) {
    echo "- Nom DB: '{$category->name}'\n";
    echo "- Traduit: '{$category->translated_name}'\n";
    echo "- Description traduite: '" . substr($category->translated_description, 0, 50) . "...'\n";
    echo "---\n";
}

// Tester en anglais
app()->setLocale('en');
echo "\n🇬🇧 ANGLAIS (locale: " . app()->getLocale() . ")\n";
foreach(\App\Models\RentalCategory::all() as $category) {
    echo "- Nom DB: '{$category->name}'\n";
    echo "- Traduit: '{$category->translated_name}'\n";
    echo "- Description traduite: '" . substr($category->translated_description, 0, 50) . "...'\n";
    echo "---\n";
}

echo "\n=== Test des clés de traduction directes ===\n";
app()->setLocale('nl');
echo "🇳🇱 En néerlandais :\n";
echo "- __('app.rental_categories.outils-agricoles'): " . __('app.rental_categories.outils-agricoles') . "\n";
echo "- __('app.rental_categories.machines'): " . __('app.rental_categories.machines') . "\n";
echo "- __('app.rental_categories.equipements'): " . __('app.rental_categories.equipements') . "\n";
