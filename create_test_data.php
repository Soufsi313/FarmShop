<?php

use App\Models\Category;
use App\Models\Product;

// Créer quelques catégories
$categories = [
    ['name' => 'Fruits', 'slug' => 'fruits', 'description' => 'Fruits frais de saison'],
    ['name' => 'Légumes', 'slug' => 'legumes', 'description' => 'Légumes bio du potager'],
    ['name' => 'Produits laitiers', 'slug' => 'produits-laitiers', 'description' => 'Fromages et produits frais']
];

foreach ($categories as $cat) {
    Category::firstOrCreate(['slug' => $cat['slug']], $cat);
}

// Créer quelques produits
$products = [
    [
        'name' => 'Pommes Golden',
        'slug' => 'pommes-golden',
        'description' => 'Pommes Golden bio, croquantes et sucrées',
        'category_id' => Category::where('slug', 'fruits')->first()->id,
        'price' => 3.50,
        'original_price' => 4.00,
        'stock_quantity' => 25,
        'quantity' => 25,
        'unit_symbol' => 'kg',
        'is_active' => true,
        'is_featured' => true
    ],
    [
        'name' => 'Carottes',
        'slug' => 'carottes',
        'description' => 'Carottes bio du potager',
        'category_id' => Category::where('slug', 'legumes')->first()->id,
        'price' => 2.80,
        'stock_quantity' => 30,
        'quantity' => 30,
        'unit_symbol' => 'kg',
        'is_active' => true
    ],
    [
        'name' => 'Fromage de chèvre',
        'slug' => 'fromage-chevre',
        'description' => 'Fromage de chèvre fermier',
        'category_id' => Category::where('slug', 'produits-laitiers')->first()->id,
        'price' => 8.50,
        'stock_quantity' => 10,
        'quantity' => 10,
        'unit_symbol' => 'pièce',
        'is_active' => true,
        'is_perishable' => true
    ]
];

foreach ($products as $prod) {
    Product::firstOrCreate(['slug' => $prod['slug']], $prod);
}

echo "Données de test créées : " . Category::count() . " catégories et " . Product::count() . " produits\n";
