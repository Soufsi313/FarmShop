<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\App;

// Test the description translation system in all languages
echo "=== TESTING MULTILINGUAL PRODUCT DESCRIPTIONS ===\n\n";

$testProducts = [
    'pommes-rouges-royal-gala-ozjz',  // Fruit
    'carottes-bio-my8d',              // Vegetable  
    'avoine-bio-25kg-ie46'            // Cereal
];

$languages = ['fr', 'en', 'nl'];
$languageNames = [
    'fr' => 'Français',
    'en' => 'English', 
    'nl' => 'Nederlands'
];

foreach ($testProducts as $slug) {
    $product = Product::where('slug', $slug)->first();
    
    if (!$product) {
        echo "❌ Product not found: $slug\n\n";
        continue;
    }
    
    echo "🔍 PRODUCT: " . strtoupper($slug) . "\n";
    echo str_repeat("=", 80) . "\n";
    
    foreach ($languages as $locale) {
        App::setLocale($locale);
        
        echo "\n🌍 {$languageNames[$locale]} ({$locale}):\n";
        echo str_repeat("-", 40) . "\n";
        
        $name = trans_product($product, 'name');
        $description = trans_product($product, 'description');
        
        echo "📝 Name: {$name}\n";
        echo "📄 Description: " . substr($description, 0, 150) . "...\n";
        
        // Verify translation exists (not fallback)
        $translationKey = "app.product_descriptions.{$product->slug}";
        $translation = __($translationKey, [], $locale);
        $hasTranslation = $translation !== $translationKey;
        
        echo "✅ Translation status: " . ($hasTranslation ? "FOUND" : "FALLBACK") . "\n";
    }
    
    echo "\n" . str_repeat("=", 80) . "\n\n";
}

// Reset to default locale
App::setLocale('fr');

echo "=== TEST COMPLETED ===\n";
