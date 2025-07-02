<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "🔍 Diagnostic des interactions du panier - FarmShop\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    // Test 1: Vérifier les routes du panier
    echo "📋 1. Vérification des routes du panier:\n";
    echo str_repeat("-", 40) . "\n";
    
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $cartRoutes = [];
    
    foreach ($routes as $route) {
        $uri = $route->uri();
        $methods = implode('|', $route->methods());
        
        if (strpos($uri, 'cart') !== false || strpos($uri, 'panier') !== false) {
            $cartRoutes[] = [
                'uri' => $uri,
                'methods' => $methods,
                'name' => $route->getName(),
                'action' => $route->getAction()['uses'] ?? 'Closure'
            ];
        }
    }
    
    if (empty($cartRoutes)) {
        echo "❌ Aucune route de panier trouvée!\n";
    } else {
        foreach ($cartRoutes as $route) {
            echo "✅ {$route['methods']} /{$route['uri']} -> {$route['action']}\n";
            if ($route['name']) {
                echo "   Nom: {$route['name']}\n";
            }
        }
    }
    
    echo "\n";
    
    // Test 2: Vérifier les routes API
    echo "🔌 2. Vérification des routes API:\n";
    echo str_repeat("-", 40) . "\n";
    
    $apiRoutes = [];
    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'api/') === 0) {
            $apiRoutes[] = [
                'uri' => $uri,
                'methods' => implode('|', $route->methods()),
                'name' => $route->getName(),
                'action' => $route->getAction()['uses'] ?? 'Closure'
            ];
        }
    }
    
    if (empty($apiRoutes)) {
        echo "❌ Aucune route API trouvée!\n";
    } else {
        foreach ($apiRoutes as $route) {
            echo "✅ {$route['methods']} /{$route['uri']} -> {$route['action']}\n";
        }
    }
    
    echo "\n";
    
    // Test 3: Vérifier le middleware CSRF
    echo "🛡️ 3. Vérification du middleware CSRF:\n";
    echo str_repeat("-", 40) . "\n";
    
    $csrfMiddleware = app(\App\Http\Middleware\VerifyCsrfToken::class);
    $exemptions = [];
    
    // Utiliser la réflexion pour accéder aux exemptions
    $reflection = new \ReflectionClass($csrfMiddleware);
    if ($reflection->hasProperty('except')) {
        $property = $reflection->getProperty('except');
        $property->setAccessible(true);
        $exemptions = $property->getValue($csrfMiddleware);
    }
    
    if (empty($exemptions)) {
        echo "ℹ️ Pas d'exemptions CSRF définies\n";
    } else {
        echo "📝 Exemptions CSRF:\n";
        foreach ($exemptions as $exemption) {
            echo "   - {$exemption}\n";
        }
    }
    
    echo "\n";
    
    // Test 4: Vérifier les contrôleurs
    echo "🎮 4. Vérification des contrôleurs de panier:\n";
    echo str_repeat("-", 40) . "\n";
    
    $controllers = [
        'App\\Http\\Controllers\\CartController',
        'App\\Http\\Controllers\\Api\\CartController',
        'App\\Http\\Controllers\\OrderController',
    ];
    
    foreach ($controllers as $controller) {
        if (class_exists($controller)) {
            echo "✅ {$controller} existe\n";
            
            $reflection = new \ReflectionClass($controller);
            $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
            
            echo "   Méthodes publiques:\n";
            foreach ($methods as $method) {
                if (!$method->isConstructor() && $method->getDeclaringClass()->getName() === $controller) {
                    echo "   - {$method->getName()}\n";
                }
            }
        } else {
            echo "❌ {$controller} n'existe pas\n";
        }
        echo "\n";
    }
    
    // Test 5: Vérifier les modèles
    echo "📊 5. Vérification des modèles:\n";
    echo str_repeat("-", 40) . "\n";
    
    $models = [
        'App\\Models\\CartItem',
        'App\\Models\\Product',
        'App\\Models\\Order',
        'App\\Models\\OrderItem',
    ];
    
    foreach ($models as $model) {
        if (class_exists($model)) {
            echo "✅ {$model} existe\n";
            
            if (method_exists($model, 'getTable')) {
                $instance = new $model();
                echo "   Table: {$instance->getTable()}\n";
            }
        } else {
            echo "❌ {$model} n'existe pas\n";
        }
    }
    
    echo "\n";
    
    // Test 6: Vérifier les vues
    echo "👁️ 6. Vérification des vues importantes:\n";
    echo str_repeat("-", 40) . "\n";
    
    $views = [
        'products.index',
        'products.show',
        'cart.index',
        'orders.create',
        'layouts.public',
    ];
    
    foreach ($views as $view) {
        $viewPath = resource_path("views/" . str_replace('.', '/', $view) . ".blade.php");
        if (file_exists($viewPath)) {
            echo "✅ {$view} existe\n";
            echo "   Chemin: {$viewPath}\n";
        } else {
            echo "❌ {$view} n'existe pas\n";
        }
    }
    
    echo "\n";
    
    // Test 7: Vérifier les fichiers JavaScript et CSS
    echo "🎨 7. Vérification des assets:\n";
    echo str_repeat("-", 40) . "\n";
    
    $assets = [
        'public/css/special-offers.css',
        'public/css/custom.css',
        'public/js/app.js',
    ];
    
    foreach ($assets as $asset) {
        $assetPath = base_path($asset);
        if (file_exists($assetPath)) {
            echo "✅ {$asset} existe\n";
            echo "   Taille: " . number_format(filesize($assetPath) / 1024, 2) . " KB\n";
        } else {
            echo "❌ {$asset} n'existe pas\n";
        }
    }
    
    echo "\n";
    
    // Test 8: Suggestions de diagnostic
    echo "💡 8. Suggestions de diagnostic:\n";
    echo str_repeat("-", 40) . "\n";
    
    echo "Pour diagnostiquer les problèmes d'interaction des cartes produits:\n\n";
    
    echo "1. Ouvrez le fichier test_card_interactions.html dans votre navigateur\n";
    echo "2. Testez tous les boutons et vérifiez les messages dans la console\n";
    echo "3. Vérifiez que tous les événements de clic sont détectés\n\n";
    
    echo "Si les interactions ne fonctionnent pas:\n";
    echo "- Vérifiez la console JavaScript pour les erreurs\n";
    echo "- Vérifiez les z-index des éléments superposés\n";
    echo "- Vérifiez que les routes API du panier sont accessibles\n";
    echo "- Vérifiez que le token CSRF est bien inclus\n\n";
    
    echo "Pour tester l'API du panier:\n";
    echo "- Connectez-vous en tant qu'utilisateur\n";
    echo "- Ouvrez les outils de développement\n";
    echo "- Tentez d'ajouter un produit au panier\n";
    echo "- Vérifiez les requêtes dans l'onglet Network\n\n";
    
    echo "📊 Résumé:\n";
    echo "Routes du panier: " . count($cartRoutes) . " trouvées\n";
    echo "Routes API: " . count($apiRoutes) . " trouvées\n";
    echo "Contrôleurs vérifiés: " . count($controllers) . "\n";
    echo "Modèles vérifiés: " . count($models) . "\n";
    echo "Vues vérifiées: " . count($views) . "\n";
    echo "Assets vérifiés: " . count($assets) . "\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du diagnostic: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n🏁 Diagnostic terminé.\n";
echo "Ouvrez maintenant test_card_interactions.html pour tester les interactions.\n";
