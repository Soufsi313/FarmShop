<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test de la route restock ===\n\n";

// Test 1: Vérifier l'utilisateur connecté
$user = Auth::user();
if ($user) {
    echo "✅ Utilisateur connecté: {$user->email} (Role: {$user->role})\n";
} else {
    echo "❌ Aucun utilisateur connecté\n";
    
    // Se connecter comme admin pour le test
    $admin = App\Models\User::where('role', 'Admin')->first();
    if ($admin) {
        Auth::login($admin);
        echo "✅ Connecté en tant qu'admin: {$admin->email}\n";
    } else {
        echo "❌ Aucun admin trouvé\n";
        exit;
    }
}

// Test 2: Vérifier le produit 344
$product = App\Models\Product::find(344);
if ($product) {
    echo "✅ Produit trouvé: {$product->name} (Stock: {$product->quantity})\n";
} else {
    echo "❌ Produit 344 non trouvé\n";
    exit;
}

// Test 3: Simuler l'appel de la méthode restockProduct
echo "\n=== Test de la méthode restockProduct ===\n";
try {
    $controller = new App\Http\Controllers\Admin\DashboardController();
    
    // Créer une fausse requête
    $request = new Illuminate\Http\Request();
    $request->merge(['quantity' => 10]);
    
    // Appeler la méthode
    $response = $controller->restockProduct($request, $product);
    
    echo "✅ Méthode exécutée avec succès\n";
    echo "Response: " . $response->getContent() . "\n";
    
} catch (Exception $e) {
    echo "❌ Erreur dans la méthode: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Fin des tests ===\n";
