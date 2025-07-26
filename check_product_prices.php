<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VÉRIFICATION DES PRIX DES PRODUITS ===" . PHP_EOL . PHP_EOL;

// Compter les produits par prix
$withPrice = \App\Models\Product::where('price', '>', 0)->count();
$withoutPrice = \App\Models\Product::where('price', 0)->count();
$nullPrice = \App\Models\Product::whereNull('price')->count();

echo "📊 RÉPARTITION DES PRIX:" . PHP_EOL;
echo "Avec prix (> 0) : {$withPrice}" . PHP_EOL;
echo "Prix à 0 : {$withoutPrice}" . PHP_EOL;
echo "Prix null : {$nullPrice}" . PHP_EOL . PHP_EOL;

// Produits avec les plus hauts prix
echo "💰 TOP 5 PRODUITS AVEC PRIX LE PLUS ÉLEVÉ:" . PHP_EOL;
$expensiveProducts = \App\Models\Product::where('price', '>', 0)
                                       ->orderBy('price', 'desc')
                                       ->take(5)
                                       ->get(['name', 'price', 'quantity']);

foreach ($expensiveProducts as $product) {
    $stockValue = $product->quantity * $product->price;
    echo "- {$product->name}: {$product->price}€ (stock: {$product->quantity}, valeur: {$stockValue}€)" . PHP_EOL;
}

echo PHP_EOL . "🔍 ÉCHANTILLON PRODUITS SANS PRIX:" . PHP_EOL;
$noPrice = \App\Models\Product::where('price', 0)
                             ->orWhereNull('price')
                             ->take(5)
                             ->get(['name', 'price', 'quantity']);

foreach ($noPrice as $product) {
    echo "- {$product->name}: {$product->price}€" . PHP_EOL;
}

// Vérifier particulièrement les pommes et chaussures
echo PHP_EOL . "🍎 POMMES ET CHAUSSURES:" . PHP_EOL;
$pommes = \App\Models\Product::where('name', 'like', '%pommes%')->first(['name', 'price', 'quantity']);
if ($pommes) {
    echo "- {$pommes->name}: {$pommes->price}€ (stock: {$pommes->quantity})" . PHP_EOL;
}

$chaussures = \App\Models\Product::where('name', 'like', '%chaussures%')->first(['name', 'price', 'quantity']);
if ($chaussures) {
    echo "- {$chaussures->name}: {$chaussures->price}€ (stock: {$chaussures->quantity})" . PHP_EOL;
}

echo PHP_EOL . "✅ VÉRIFICATION TERMINÉE" . PHP_EOL;
