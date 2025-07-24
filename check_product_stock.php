<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$product = App\Models\Product::where('name', 'LIKE', '%Pommes de Terre Bintje%')->first();

if ($product) {
    echo "Produit trouvé:\n";
    echo "ID: " . $product->id . "\n";
    echo "Nom: " . $product->name . "\n";
    echo "Quantity (stock): " . ($product->quantity ?? 'NULL') . "\n";
    echo "Quantity type: " . gettype($product->quantity) . "\n";
    echo "Actif: " . ($product->is_active ? 'Oui' : 'Non') . "\n";
    echo "Prix: " . $product->price . " €\n";
    
    // Vérifier la valeur raw de la base de données
    $rawProduct = \Illuminate\Support\Facades\DB::table('products')
        ->where('id', $product->id)
        ->first();
    echo "Quantity RAW DB: " . ($rawProduct->quantity ?? 'NULL') . "\n";
    echo "Quantity RAW type: " . gettype($rawProduct->quantity) . "\n";
    
    // Vérifier s'il y a des articles dans le panier pour ce produit
    $user = App\Models\User::find(1); // Assumons utilisateur ID 1
    $cart = $user->getOrCreateActiveCart();
    
    echo "\nPanier:\n";
    echo "Nombre d'articles: " . $cart->items()->count() . "\n";
    
    foreach ($cart->items as $item) {
        if ($item->product_id == $product->id) {
            echo "Article dans le panier:\n";
            echo "- Quantité demandée: " . $item->quantity . "\n";
            echo "- Stock disponible: " . $product->quantity . "\n";
            echo "- Stock suffisant: " . ($product->quantity >= $item->quantity ? 'OUI' : 'NON') . "\n";
        }
    }
    
} else {
    echo "Produit non trouvé\n";
    
    // Afficher tous les produits avec "Pommes" dans le nom
    echo "Produits contenant 'Pommes':\n";
    $products = App\Models\Product::where('name', 'LIKE', '%Pommes%')->get();
    foreach ($products as $p) {
        echo "- " . $p->name . " (Stock: " . $p->quantity . ")\n";
    }
}
