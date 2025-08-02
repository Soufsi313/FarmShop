<?php
require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use Carbon\Carbon;

echo "=== Test de validation des périodes de location ===\n\n";

// Trouver un produit de location avec des contraintes
$product = Product::where('type', 'rental')
    ->whereNotNull('min_rental_days')
    ->whereNotNull('max_rental_days')
    ->first();

if (!$product) {
    echo "❌ Aucun produit de location trouvé avec des contraintes\n";
    exit;
}

echo "📦 Produit testé: {$product->name}\n";
echo "📅 Durée min: {$product->min_rental_days} jour(s)\n";
echo "📅 Durée max: {$product->max_rental_days} jour(s)\n\n";

// Test 1: Période trop courte
$startDate = Carbon::now()->addDay();
$endDate = $startDate->copy()->addDays($product->min_rental_days - 2); // Trop court

echo "🧪 Test 1 - Période trop courte:\n";
echo "Début: " . $startDate->format('Y-m-d') . "\n";
echo "Fin: " . $endDate->format('Y-m-d') . "\n";
echo "Durée: " . $endDate->diffInDays($startDate) . " jour(s)\n";

$validation = $product->validateRentalPeriod($startDate, $endDate);
if ($validation['valid']) {
    echo "✅ Validation passée (inattendu)\n";
} else {
    echo "❌ Validation échouée (attendu):\n";
    foreach ($validation['errors'] as $error) {
        echo "   - $error\n";
    }
}
echo "\n";

// Test 2: Période trop longue
$startDate = Carbon::now()->addDay();
$endDate = $startDate->copy()->addDays($product->max_rental_days + 1); // Trop long

echo "🧪 Test 2 - Période trop longue:\n";
echo "Début: " . $startDate->format('Y-m-d') . "\n";
echo "Fin: " . $endDate->format('Y-m-d') . "\n";
echo "Durée: " . $endDate->diffInDays($startDate) . " jour(s)\n";

$validation = $product->validateRentalPeriod($startDate, $endDate);
if ($validation['valid']) {
    echo "✅ Validation passée (inattendu)\n";
} else {
    echo "❌ Validation échouée (attendu):\n";
    foreach ($validation['errors'] as $error) {
        echo "   - $error\n";
    }
}
echo "\n";

// Test 3: Période valide
$startDate = Carbon::now()->addDay();
$endDate = $startDate->copy()->addDays($product->min_rental_days); // Durée valide

echo "🧪 Test 3 - Période valide:\n";
echo "Début: " . $startDate->format('Y-m-d') . "\n";
echo "Fin: " . $endDate->format('Y-m-d') . "\n";
echo "Durée: " . $endDate->diffInDays($startDate) . " jour(s)\n";

$validation = $product->validateRentalPeriod($startDate, $endDate);
if ($validation['valid']) {
    echo "✅ Validation passée (attendu)\n";
} else {
    echo "❌ Validation échouée (inattendu):\n";
    foreach ($validation['errors'] as $error) {
        echo "   - $error\n";
    }
}

echo "\n=== Tests terminés ===\n";
