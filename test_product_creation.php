<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Simuler les données d'un formulaire de produit
$formData = [
    'name' => 'Test Product',
    'description' => 'Test description',
    'price' => '10.99',
    'type' => 'sale', // Valeur corrigée
    'category_id' => '1',
    'stock_quantity' => '100',
    'is_featured' => '0'
];

echo "=== TEST VALIDATION PRODUIT ===\n";
echo "Données du formulaire:\n";
print_r($formData);

// Règles de validation du ProductController
$rules = [
    'name' => 'required|string|max:255',
    'description' => 'required|string',
    'price' => 'required|numeric|min:0',
    'type' => 'required|in:sale,rental,both',
    'category_id' => 'required|exists:categories,id',
    'stock_quantity' => 'required|integer|min:0',
    'rental_price_per_day' => 'nullable|numeric|min:0',
    'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    'is_featured' => 'boolean'
];

echo "\n=== RÈGLES DE VALIDATION ===\n";
print_r($rules);

// Test de validation
$validator = app('validator')->make($formData, $rules);

if ($validator->fails()) {
    echo "\n❌ ERREURS DE VALIDATION:\n";
    foreach ($validator->errors()->all() as $error) {
        echo "- $error\n";
    }
} else {
    echo "\n✅ VALIDATION RÉUSSIE!\n";
}

// Test spécifique du champ type
echo "\n=== TEST CHAMP TYPE ===\n";
$typeValidator = app('validator')->make(['type' => $formData['type']], ['type' => 'required|in:sale,rental,both']);
if ($typeValidator->fails()) {
    echo "❌ Type invalide: " . $formData['type'] . "\n";
    echo "Erreurs: " . implode(', ', $typeValidator->errors()->get('type')) . "\n";
} else {
    echo "✅ Type valide: " . $formData['type'] . "\n";
}

// Tester avec les anciennes valeurs
echo "\n=== TEST ANCIENNES VALEURS ===\n";
$oldValues = ['purchase', 'mixed'];
foreach ($oldValues as $oldValue) {
    $oldValidator = app('validator')->make(['type' => $oldValue], ['type' => 'required|in:sale,rental,both']);
    if ($oldValidator->fails()) {
        echo "❌ Ancienne valeur '$oldValue' est maintenant invalide (normal)\n";
    } else {
        echo "⚠️  Ancienne valeur '$oldValue' est encore acceptée (problème)\n";
    }
}
