<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test de la méthode directement ===\n\n";

// Connecter un admin
$admin = App\Models\User::where('role', 'Admin')->first();
if (!$admin) {
    echo "❌ Aucun admin trouvé\n";
    exit;
}

Auth::login($admin);
echo "✅ Connecté en tant qu'admin: {$admin->email}\n";

// Récupérer le produit 344
$product = App\Models\Product::find(344);
if (!$product) {
    echo "❌ Produit 344 non trouvé\n";
    exit;
}

echo "✅ Produit trouvé: {$product->name} (Stock: {$product->quantity})\n";

// Créer une requête de test
$request = new Illuminate\Http\Request();
$request->merge(['quantity' => 10]);
$request->headers->set('Content-Type', 'application/json');

// Tester la méthode directement
try {
    $controller = new App\Http\Controllers\Admin\DashboardController();
    echo "\n🔄 Test de la méthode restockProduct...\n";
    
    $response = $controller->restockProduct($request, $product);
    
    echo "✅ Méthode exécutée avec succès!\n";
    echo "Status: {$response->getStatusCode()}\n";
    echo "Response: {$response->getContent()}\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n";
    echo "File: {$e->getFile()}:{$e->getLine()}\n";
    echo "Trace:\n{$e->getTraceAsString()}\n";
}

echo "\n=== Fin ===\n";
