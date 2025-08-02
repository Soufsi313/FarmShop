<?php
require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\CartLocation;
use App\Models\CartItemLocation;
use App\Models\Product;
use Carbon\Carbon;

echo "=== Test API modification dates panier location ===\n\n";

// Trouver un utilisateur
$user = User::first();
if (!$user) {
    echo "❌ Aucun utilisateur trouvé\n";
    exit;
}

// Trouver un panier de location avec des items
$cartLocation = $user->activeCartLocation;
if (!$cartLocation || $cartLocation->items->isEmpty()) {
    echo "❌ Aucun panier de location actif avec des items trouvé pour l'utilisateur {$user->id}\n";
    exit;
}

$item = $cartLocation->items->first();
echo "📦 Item testé: {$item->product_name}\n";
echo "🗓️ Dates actuelles: {$item->start_date->format('Y-m-d')} → {$item->end_date->format('Y-m-d')}\n";
echo "⏱️ Durée actuelle: {$item->duration_days} jour(s)\n\n";

// Vérifier les contraintes du produit
$product = $item->product;
echo "📋 Contraintes du produit:\n";
echo "   - Durée min: {$product->min_rental_days} jour(s)\n";
echo "   - Durée max: {$product->max_rental_days} jour(s)\n\n";

// Test 1: Essayer une période trop courte
$newStartDate = Carbon::now()->addDays(2);
$newEndDate = $newStartDate->copy()->addDays($product->min_rental_days - 2); // Trop court

echo "🧪 Test 1 - Période trop courte:\n";
echo "Nouvelle période: {$newStartDate->format('Y-m-d')} → {$newEndDate->format('Y-m-d')}\n";
echo "Nouvelle durée: " . $newEndDate->diffInDays($newStartDate) . " jour(s)\n";

// Simuler la validation comme dans le contrôleur
$validation = $product->validateRentalPeriod($newStartDate, $newEndDate);
if ($validation['valid']) {
    echo "✅ Validation passée (inattendu)\n";
} else {
    echo "❌ Validation échouée (attendu):\n";
    foreach ($validation['errors'] as $error) {
        echo "   - $error\n";
    }
}
echo "\n";

// Test 2: Essayer une période trop longue
$newStartDate = Carbon::now()->addDays(2);
$newEndDate = $newStartDate->copy()->addDays($product->max_rental_days + 1); // Trop long

echo "🧪 Test 2 - Période trop longue:\n";
echo "Nouvelle période: {$newStartDate->format('Y-m-d')} → {$newEndDate->format('Y-m-d')}\n";
echo "Nouvelle durée: " . $newEndDate->diffInDays($newStartDate) . " jour(s)\n";

$validation = $product->validateRentalPeriod($newStartDate, $newEndDate);
if ($validation['valid']) {
    echo "✅ Validation passée (inattendu)\n";
} else {
    echo "❌ Validation échouée (attendu):\n";
    foreach ($validation['errors'] as $error) {
        echo "   - $error\n";
    }
}
echo "\n";

// Test 3: Essayer une période valide
$newStartDate = Carbon::now()->addDays(2);
$newEndDate = $newStartDate->copy()->addDays($product->min_rental_days + 1); // Valide

echo "🧪 Test 3 - Période valide:\n";
echo "Nouvelle période: {$newStartDate->format('Y-m-d')} → {$newEndDate->format('Y-m-d')}\n";
echo "Nouvelle durée: " . $newEndDate->diffInDays($newStartDate) . " jour(s)\n";

$validation = $product->validateRentalPeriod($newStartDate, $newEndDate);
if ($validation['valid']) {
    echo "✅ Validation passée (attendu)\n";
} else {
    echo "❌ Validation échouée (inattendu):\n";
    foreach ($validation['errors'] as $error) {
        echo "   - $error\n";
    }
}

echo "\n=== Tests terminés ===\n";
