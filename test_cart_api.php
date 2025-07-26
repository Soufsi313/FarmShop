<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\SpecialOffer;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;

// Initialiser Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST API PANIER ===\n\n";

// Récupérer l'utilisateur
$user = User::first();
if (!$user) {
    echo "❌ Aucun utilisateur trouvé\n";
    exit;
}

echo "Utilisateur: {$user->name}\n\n";

// Récupérer le panier de l'utilisateur
$cart = Cart::where('user_id', $user->id)->first();
if (!$cart) {
    echo "❌ Aucun panier trouvé\n";
    exit;
}

// Récupérer les articles du panier
$cartItems = CartItem::where('cart_id', $cart->id)->get();

if ($cartItems->isEmpty()) {
    echo "❌ Panier vide\n";
    exit;
}

echo "=== DONNÉES API PANIER ===\n";
foreach ($cartItems as $item) {
    $displayData = $item->toDisplayArray();
    
    echo "Produit: {$displayData['product_name']}\n";
    echo "Quantité: {$displayData['quantity']}\n";
    echo "Prix unitaire TTC: {$displayData['price_per_unit_ttc_formatted']}\n";
    
    if ($displayData['special_offer']) {
        echo "🔥 OFFRE SPÉCIALE:\n";
        echo "  - Titre: " . ($displayData['special_offer']['title'] ?? 'NON DÉFINI') . "\n";
        echo "  - Description: " . ($displayData['special_offer']['description'] ?? 'NON DÉFINI') . "\n";
        echo "  - Réduction: {$displayData['special_offer']['discount_percentage']}%\n";
        echo "  - Prix original: {$displayData['special_offer']['original_price_ttc_formatted']}\n";
        echo "  - Économie totale: {$displayData['special_offer']['savings_total_ttc_formatted']}\n";
    } else {
        echo "Aucune offre spéciale\n";
    }
    
    echo "Total: {$displayData['total_formatted']}\n";
    echo "---\n";
}

echo "\n=== DONNÉES JSON COMME L'API ===\n";
$apiData = [];
foreach ($cartItems as $item) {
    $apiData[] = $item->toDisplayArray();
}

echo json_encode($apiData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
