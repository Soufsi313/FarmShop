<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test category translations
$category = \App\Models\Category::where('name->fr', 'Fruits')->first();

echo "Testing Fruits category:\n";
echo "French name: " . $category->getTranslation('name', 'fr') . "\n";
echo "English name: " . $category->getTranslation('name', 'en') . "\n";
echo "Dutch name: " . $category->getTranslation('name', 'nl') . "\n\n";

// Test with different locale
app()->setLocale('en');
echo "With locale set to EN: " . $category->name . "\n";

app()->setLocale('nl');
echo "With locale set to NL: " . $category->name . "\n";

app()->setLocale('fr');
echo "With locale set to FR: " . $category->name . "\n";
