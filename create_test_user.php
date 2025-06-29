<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\AdminMessage;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;

// Créer un utilisateur de test
$testUser = User::create([
    'name' => 'Test User',
    'username' => 'testuser',
    'email' => 'test@example.com', 
    'password' => bcrypt('password123'),
    'email_verified_at' => now(),
    'biography' => 'Je suis un utilisateur de test pour vérifier la suppression de compte.',
    'is_newsletter_subscribed' => true,
    'newsletter_subscribed_at' => now(),
]);

$testUser->assignRole('user');

// Créer quelques données de test pour cet utilisateur
// 1. Messages admin
AdminMessage::create([
    'user_id' => $testUser->id,
    'subject' => 'Question de test',
    'message' => 'Ceci est un message de test pour vérifier l\'export des données.',
    'status' => 'pending'
]);

// 2. Vérifier s'il y a des produits existants
$existingProduct = Product::first();

if ($existingProduct) {
    // 3. Créer un panier pour l'utilisateur avec un produit existant
    Cart::create([
        'user_id' => $testUser->id,
        'product_id' => $existingProduct->id,
        'quantity' => 2,
        'unit_price' => $existingProduct->price ?? 29.99
    ]);
    echo "- 1 article dans le panier (produit: {$existingProduct->name})\n";
} else {
    echo "- Aucun produit disponible pour le panier\n";
}

echo "✅ Utilisateur de test créé avec succès!\n";
echo "Email: test@example.com\n"; 
echo "Mot de passe: password123\n";
echo "ID: " . $testUser->id . "\n";
echo "- 1 message admin créé\n";
echo "- Abonné à la newsletter\n";
echo "- Biographie remplie\n";
