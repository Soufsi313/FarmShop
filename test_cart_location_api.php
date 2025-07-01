<?php
// Test des routes API du panier de location
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

echo "=== Test des routes API du panier de location ===\n\n";

try {
    // Simuler un utilisateur connecté
    $user = User::first();
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé\n";
        exit;
    }

    // Trouver un produit de location
    $product = Product::where('is_rentable', true)->first();
    if (!$product) {
        echo "❌ Aucun produit de location trouvé\n";
        exit;
    }

    echo "👤 Utilisateur test: {$user->name}\n";
    echo "📦 Produit test: {$product->name}\n\n";

    // 1. Test GET /api/cart-location (panier vide)
    echo "1. Test GET /api/cart-location\n";
    $request = Request::create('/api/cart-location', 'GET');
    $request->setUserResolver(function() use ($user) { return $user; });
    
    $response = $kernel->handle($request);
    echo "   Status: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getContent(), true);
        echo "   ✅ Panier récupéré: " . ($data['data']['item_count'] ?? 0) . " items\n";
    } else {
        echo "   ❌ Erreur: " . $response->getContent() . "\n";
    }
    echo "\n";

    // 2. Test POST /api/cart-location/add
    echo "2. Test POST /api/cart-location/add\n";
    $requestData = [
        'product_id' => $product->id,
        'quantity' => 1,
        'rental_duration_days' => 7,
        'rental_start_date' => date('Y-m-d', strtotime('+1 day'))
    ];
    
    $request = Request::create('/api/cart-location/add', 'POST', $requestData);
    $request->setUserResolver(function() use ($user) { return $user; });
    $request->headers->set('Content-Type', 'application/json');
    $request->headers->set('Accept', 'application/json');
    
    $response = $kernel->handle($request);
    echo "   Status: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() === 200 || $response->getStatusCode() === 201) {
        $data = json_decode($response->getContent(), true);
        echo "   ✅ Produit ajouté au panier\n";
        if (isset($data['data']['id'])) {
            echo "   ID de l'item: " . $data['data']['id'] . "\n";
        }
    } else {
        echo "   ❌ Erreur: " . $response->getContent() . "\n";
    }
    echo "\n";

    // 3. Test compatibilité POST /api/rentals/book
    echo "3. Test compatibilité POST /api/rentals/book\n";
    $requestData = [
        'product_id' => $product->id,
        'quantity' => 1,
        'rental_duration_days' => 5,
        'rental_start_date' => date('Y-m-d', strtotime('+2 days'))
    ];
    
    $request = Request::create('/api/rentals/book', 'POST', $requestData);
    $request->setUserResolver(function() use ($user) { return $user; });
    $request->headers->set('Content-Type', 'application/json');
    $request->headers->set('Accept', 'application/json');
    
    $response = $kernel->handle($request);
    echo "   Status: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() === 200 || $response->getStatusCode() === 201) {
        echo "   ✅ Route de compatibilité fonctionnelle\n";
    } else {
        echo "   ❌ Erreur: " . $response->getContent() . "\n";
    }
    echo "\n";

    // 4. Test GET /api/cart-location/count
    echo "4. Test GET /api/cart-location/count\n";
    $request = Request::create('/api/cart-location/count', 'GET');
    $request->setUserResolver(function() use ($user) { return $user; });
    
    $response = $kernel->handle($request);
    echo "   Status: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getContent(), true);
        echo "   ✅ Nombre d'items: " . ($data['data']['count'] ?? 0) . "\n";
    } else {
        echo "   ❌ Erreur: " . $response->getContent() . "\n";
    }
    echo "\n";

    // 5. Test GET /api/cart-location/total
    echo "5. Test GET /api/cart-location/total\n";
    $request = Request::create('/api/cart-location/total', 'GET');
    $request->setUserResolver(function() use ($user) { return $user; });
    
    $response = $kernel->handle($request);
    echo "   Status: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getContent(), true);
        echo "   ✅ Total: " . ($data['data']['total_amount'] ?? 0) . "€\n";
    } else {
        echo "   ❌ Erreur: " . $response->getContent() . "\n";
    }
    echo "\n";

    echo "✅ Tests des routes API terminés\n";

} catch (Exception $e) {
    echo "❌ Erreur durant les tests: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
