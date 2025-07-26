<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Récupérer le premier utilisateur et son panier
$user = \App\Models\User::first();
$cart = $user->getOrCreateActiveCart();

// Trouver le produit pommes vertes
$product = \App\Models\Product::where('name', 'like', '%pommes%')->first();

if ($product) {
    echo "=== AJOUT DU PRODUIT AU PANIER ===" . PHP_EOL;
    echo "Produit: " . $product->name . PHP_EOL;
    echo "Prix: " . $product->price . "€" . PHP_EOL;
    
    // Vérifier l'offre spéciale
    $offer = $product->getActiveSpecialOffer(10);
    if ($offer) {
        echo "Offre spéciale trouvée: " . $offer->name . " (-" . $offer->discount_percentage . "%)" . PHP_EOL;
        echo "Quantité minimum: " . $offer->minimum_quantity . PHP_EOL;
    }
    
    // Vider le panier d'abord
    $cart->clear();
    
    // Ajouter 10 kg de pommes
    $cartItem = $cart->addProduct($product, 10);
    echo "Produit ajouté avec 10 kg" . PHP_EOL . PHP_EOL;
}

echo "=== DIAGNOSTIC DU PANIER ===" . PHP_EOL;
echo "Utilisateur: " . $user->name . PHP_EOL;
echo "Nombre d'éléments: " . $cart->items()->count() . PHP_EOL . PHP_EOL;

foreach($cart->fresh()->items as $item) {
    $displayData = $item->toDisplayArray();
    
    echo "--- PRODUIT ---" . PHP_EOL;
    echo "Nom: " . $displayData['product_name'] . PHP_EOL;
    echo "Quantité: " . $displayData['quantity'] . PHP_EOL;
    echo "Prix unitaire TTC: " . $displayData['price_per_unit_ttc_formatted'] . PHP_EOL;
    echo "Total: " . $displayData['total_formatted'] . PHP_EOL;
    
    if ($displayData['special_offer']) {
        echo PHP_EOL . "🔥 OFFRE SPÉCIALE APPLIQUÉE:" . PHP_EOL;
        echo "Prix original: " . $displayData['special_offer']['original_price_ttc_formatted'] . PHP_EOL;
        echo "Réduction: " . $displayData['special_offer']['discount_percentage'] . "%" . PHP_EOL;
        echo "Économie par unité: " . $displayData['special_offer']['discount_amount_ttc_formatted'] . PHP_EOL;
        echo "Économie totale: " . $displayData['special_offer']['savings_total_ttc_formatted'] . PHP_EOL;
    } else {
        echo "Aucune offre spéciale" . PHP_EOL;
    }
    echo PHP_EOL;
}

$cart = $cart->fresh();
echo "=== TOTAUX DU PANIER ===" . PHP_EOL;
echo "Sous-total: " . $cart->subtotal . PHP_EOL;
echo "TVA: " . $cart->tax_amount . PHP_EOL;
echo "Total: " . $cart->total . PHP_EOL;
