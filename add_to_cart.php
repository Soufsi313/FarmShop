<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

$user = App\Models\User::first();
$product = App\Models\Product::first();

if ($user && $product) {
    $user->cartItems()->create([
        'product_id' => $product->id,
        'quantity' => 2
    ]);
    echo "Added {$product->name} to cart for user {$user->name}\n";
} else {
    echo "User or product not found\n";
}
