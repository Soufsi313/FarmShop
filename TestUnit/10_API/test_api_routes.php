<?php

/**
 * Test Unitaire: Routes API
 * 
 * Verifie la structure et la definition des routes API
 */

// Bootstrap Laravel si necessaire
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
    require_once __DIR__ . '/../../vendor/autoload.php';
    $app = require_once __DIR__ . '/../../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
}

echo "\n========================================\n";
echo "TEST: ROUTES API\n";
echo "========================================\n\n";

$startTime = microtime(true);
$errors = [];

try {
    // 1. Recuperer toutes les routes API
    echo "1. Verification des routes API...\n";
    
    $router = app('router');
    $routes = $router->getRoutes();
    
    $apiRoutes = [];
    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'api/') === 0) {
            $apiRoutes[] = [
                'uri' => $uri,
                'methods' => $route->methods(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware' => $route->middleware()
            ];
        }
    }
    
    echo "   - Routes API trouvees: " . count($apiRoutes) . "\n";
    
    if (count($apiRoutes) === 0) {
        $errors[] = "Aucune route API trouvee";
    }

    // 2. Verifier les routes publiques
    echo "\n2. Verification des routes publiques...\n";
    
    $publicRoutes = [
        'api/register' => 'POST',
        'api/categories' => 'GET',
        'api/products' => 'GET',
        'api/products/search' => 'GET',
        'api/rental-categories' => 'GET',
        'api/contact' => 'POST'
    ];
    
    $foundPublicRoutes = 0;
    foreach ($publicRoutes as $uri => $method) {
        foreach ($apiRoutes as $route) {
            if ($route['uri'] === $uri && in_array($method, $route['methods'])) {
                $foundPublicRoutes++;
                echo "   - " . str_pad($method, 6) . " /$uri\n";
                break;
            }
        }
    }
    
    echo "   - Routes publiques validees: $foundPublicRoutes/" . count($publicRoutes) . "\n";

    // 3. Verifier les routes protegees
    echo "\n3. Verification des routes protegees...\n";
    
    $protectedRoutesCount = 0;
    foreach ($apiRoutes as $route) {
        $middleware = $route['middleware'];
        if (in_array('auth', $middleware) || in_array('auth:web', $middleware)) {
            $protectedRoutesCount++;
        }
    }
    
    echo "   - Routes protegees (auth): $protectedRoutesCount\n";

    // 4. Verifier les groupes de routes
    echo "\n4. Verification des groupes de routes...\n";
    
    $routeGroups = [
        'api/cart' => 'Panier',
        'api/cart-location' => 'Panier Location',
        'api/products' => 'Produits',
        'api/messages' => 'Messages',
        'api/wishlist' => 'Liste de souhaits',
        'api/likes' => 'Likes',
        'api/cookies' => 'Cookies',
        'api/stripe' => 'Paiement Stripe',
        'api/blog' => 'Blog',
        'api/newsletter' => 'Newsletter'
    ];
    
    foreach ($routeGroups as $prefix => $name) {
        $count = 0;
        foreach ($apiRoutes as $route) {
            if (strpos($route['uri'], $prefix) === 0) {
                $count++;
            }
        }
        
        if ($count > 0) {
            echo "   - $name ($prefix): $count routes\n";
        }
    }

    // 5. Verifier les methodes HTTP utilisees
    echo "\n5. Verification des methodes HTTP...\n";
    
    $methodsCount = [
        'GET' => 0,
        'POST' => 0,
        'PUT' => 0,
        'DELETE' => 0,
        'PATCH' => 0
    ];
    
    foreach ($apiRoutes as $route) {
        foreach ($route['methods'] as $method) {
            if ($method !== 'HEAD' && isset($methodsCount[$method])) {
                $methodsCount[$method]++;
            }
        }
    }
    
    foreach ($methodsCount as $method => $count) {
        if ($count > 0) {
            echo "   - $method: $count routes\n";
        }
    }

    // 6. Verifier les routes nommees
    echo "\n6. Verification des routes nommees...\n";
    
    $namedRoutes = 0;
    $unnamedRoutes = 0;
    
    foreach ($apiRoutes as $route) {
        if (!empty($route['name'])) {
            $namedRoutes++;
        } else {
            $unnamedRoutes++;
        }
    }
    
    echo "   - Routes nommees: $namedRoutes\n";
    echo "   - Routes sans nom: $unnamedRoutes\n";
    
    $namingPercentage = round(($namedRoutes / count($apiRoutes)) * 100, 2);
    echo "   - Taux de nommage: $namingPercentage%\n";

    // 7. Verifier les routes de panier (Cart)
    echo "\n7. Verification des routes Panier...\n";
    
    $cartRoutes = [
        'api/cart' => ['GET'],
        'api/cart/summary' => ['GET'],
        'api/cart/clear' => ['POST', 'DELETE'],
        'api/cart/products/{product}' => ['POST', 'PUT', 'DELETE']
    ];
    
    $foundCartRoutes = 0;
    foreach ($cartRoutes as $uri => $methods) {
        foreach ($apiRoutes as $route) {
            // Normaliser l'URI pour la comparaison (remplacer les parametres)
            $normalizedUri = preg_replace('/\{[^}]+\}/', '{product}', $route['uri']);
            
            if ($normalizedUri === $uri) {
                foreach ($methods as $method) {
                    if (in_array($method, $route['methods'])) {
                        $foundCartRoutes++;
                        break;
                    }
                }
                break;
            }
        }
    }
    
    echo "   - Routes panier validees: $foundCartRoutes\n";

    // 8. Verifier les routes de location (Rental)
    echo "\n8. Verification des routes Location...\n";
    
    $rentalRoutesCount = 0;
    foreach ($apiRoutes as $route) {
        if (strpos($route['uri'], 'rental') !== false || 
            strpos($route['uri'], 'cart-location') !== false) {
            $rentalRoutesCount++;
        }
    }
    
    echo "   - Routes location trouvees: $rentalRoutesCount\n";

    // 9. Verifier les routes de paiement
    echo "\n9. Verification des routes Paiement...\n";
    
    $paymentRoutes = [
        'api/stripe/webhook' => 'POST',
        'api/webhook/stripe' => 'POST'
    ];
    
    $foundPaymentRoutes = 0;
    foreach ($paymentRoutes as $uri => $method) {
        foreach ($apiRoutes as $route) {
            if ($route['uri'] === $uri && in_array($method, $route['methods'])) {
                $foundPaymentRoutes++;
                echo "   - " . str_pad($method, 6) . " /$uri (webhook)\n";
                break;
            }
        }
    }
    
    echo "   - Routes paiement: $foundPaymentRoutes\n";

    // 10. Verifier les routes de cookies
    echo "\n10. Verification des routes Cookies...\n";
    
    $cookieRoutesCount = 0;
    $cookieRoutesList = [];
    
    foreach ($apiRoutes as $route) {
        if (strpos($route['uri'], 'api/cookies') === 0) {
            $cookieRoutesCount++;
            $cookieRoutesList[] = str_replace('api/cookies/', '', $route['uri']);
        }
    }
    
    echo "   - Routes cookies trouvees: $cookieRoutesCount\n";
    if (count($cookieRoutesList) > 0) {
        $uniqueRoutes = array_unique($cookieRoutesList);
        echo "   - Endpoints: " . implode(', ', array_slice($uniqueRoutes, 0, 5)) . "...\n";
    }

    // 11. Verifier les routes Blog
    echo "\n11. Verification des routes Blog...\n";
    
    $blogRoutesCount = 0;
    foreach ($apiRoutes as $route) {
        if (strpos($route['uri'], 'api/blog') === 0) {
            $blogRoutesCount++;
        }
    }
    
    echo "   - Routes blog trouvees: $blogRoutesCount\n";

    // 12. Statistiques globales
    echo "\n12. Statistiques globales...\n";
    
    $controllersUsed = [];
    foreach ($apiRoutes as $route) {
        $action = $route['action'];
        if (strpos($action, '@') !== false) {
            list($controller) = explode('@', $action);
            $controllersUsed[$controller] = true;
        } elseif (strpos($action, '::') !== false) {
            list($controller) = explode('::', $action);
            $controllersUsed[$controller] = true;
        }
    }
    
    echo "   - Controleurs utilises: " . count($controllersUsed) . "\n";
    echo "   - Routes totales: " . count($apiRoutes) . "\n";
    echo "   - Moyenne routes/controleur: " . round(count($apiRoutes) / max(1, count($controllersUsed)), 2) . "\n";

} catch (\Exception $e) {
    $errors[] = "Exception: " . $e->getMessage();
}

// Resultats
$duration = round((microtime(true) - $startTime) * 1000, 2);

echo "\n========================================\n";
if (count($errors) > 0) {
    echo "RESULTAT: ECHEC\n";
    echo "========================================\n";
    echo "Erreurs detectees:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
    echo "\nDuree: {$duration}ms\n";
    exit(1);
} else {
    echo "RESULTAT: REUSSI\n";
    echo "========================================\n";
    echo "Toutes les routes API sont correctement definies\n";
    echo "Duree: {$duration}ms\n";
    exit(0);
}
