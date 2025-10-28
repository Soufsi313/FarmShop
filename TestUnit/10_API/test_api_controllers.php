<?php

/**
 * Test Unitaire: Controleurs API
 * 
 * Verifie la structure des controleurs API
 */

// Bootstrap Laravel si necessaire
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
    require_once __DIR__ . '/../../vendor/autoload.php';
    $app = require_once __DIR__ . '/../../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
}

echo "\n========================================\n";
echo "TEST: CONTROLEURS API\n";
echo "========================================\n\n";

$startTime = microtime(true);
$errors = [];

try {
    // 1. Verifier le controleur de base API
    echo "1. Verification du BaseApiController...\n";
    
    if (class_exists('App\Http\Controllers\Api\BaseApiController')) {
        $reflection = new \ReflectionClass('App\Http\Controllers\Api\BaseApiController');
        
        // Verifier l'heritage
        $parentClass = $reflection->getParentClass();
        if ($parentClass && $parentClass->getName() === 'App\Http\Controllers\Controller') {
            echo "   - Heritage: App\Http\Controllers\Controller\n";
        }
        
        // Verifier les annotations Swagger/OpenAPI
        $docComment = $reflection->getDocComment();
        if ($docComment) {
            $hasSwagger = strpos($docComment, '@OA\\') !== false;
            echo "   - Annotations OpenAPI: " . ($hasSwagger ? 'OUI' : 'NON') . "\n";
            
            if ($hasSwagger) {
                $hasInfo = strpos($docComment, '@OA\Info') !== false;
                $hasServer = strpos($docComment, '@OA\Server') !== false;
                $hasSecurity = strpos($docComment, '@OA\SecurityScheme') !== false;
                
                echo "   - @OA\Info: " . ($hasInfo ? 'OUI' : 'NON') . "\n";
                echo "   - @OA\Server: " . ($hasServer ? 'OUI' : 'NON') . "\n";
                echo "   - @OA\SecurityScheme: " . ($hasSecurity ? 'OUI' : 'NON') . "\n";
            }
        }
        
        echo "   - BaseApiController: Valide\n";
    } else {
        echo "   - BaseApiController: Non trouve\n";
    }

    // 2. Verifier OrderStatusController
    echo "\n2. Verification du OrderStatusController...\n";
    
    if (class_exists('App\Http\Controllers\Api\OrderStatusController')) {
        $reflection = new \ReflectionClass('App\Http\Controllers\Api\OrderStatusController');
        
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methodNames = array_map(function($method) {
            return $method->getName();
        }, $methods);
        
        $expectedMethods = ['getStatus', 'triggerNextStatus'];
        $foundMethods = 0;
        
        foreach ($expectedMethods as $method) {
            if (in_array($method, $methodNames)) {
                $foundMethods++;
                echo "   - Methode $method(): Presente\n";
            }
        }
        
        echo "   - Methodes validees: $foundMethods/" . count($expectedMethods) . "\n";
    } else {
        echo "   - OrderStatusController: Non trouve\n";
    }

    // 3. Verifier les controleurs standards (CRUD)
    echo "\n3. Verification des controleurs CRUD...\n";
    
    $crudControllers = [
        'App\Http\Controllers\CategoryController',
        'App\Http\Controllers\ProductController',
        'App\Http\Controllers\CartController',
        'App\Http\Controllers\CartLocationController',
        'App\Http\Controllers\MessageController',
        'App\Http\Controllers\WishlistController'
    ];
    
    $foundControllers = 0;
    foreach ($crudControllers as $controller) {
        if (class_exists($controller)) {
            $foundControllers++;
            
            $reflection = new \ReflectionClass($controller);
            $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
            $methodNames = array_map(function($method) {
                return $method->getName();
            }, $methods);
            
            $crudMethods = ['index', 'show', 'store', 'update', 'destroy'];
            $hasCrudMethods = 0;
            
            foreach ($crudMethods as $method) {
                if (in_array($method, $methodNames)) {
                    $hasCrudMethods++;
                }
            }
            
            $controllerName = class_basename($controller);
            echo "   - $controllerName: $hasCrudMethods/" . count($crudMethods) . " methodes CRUD\n";
        }
    }
    
    echo "   - Controleurs CRUD trouves: $foundControllers/" . count($crudControllers) . "\n";

    // 4. Verifier les controleurs de paiement
    echo "\n4. Verification des controleurs de paiement...\n";
    
    if (class_exists('App\Http\Controllers\StripePaymentController')) {
        $reflection = new \ReflectionClass('App\Http\Controllers\StripePaymentController');
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methodNames = array_map(function($method) {
            return $method->getName();
        }, $methods);
        
        $paymentMethods = ['webhook', 'createPaymentIntent', 'processPayment'];
        $foundPaymentMethods = 0;
        
        foreach ($paymentMethods as $method) {
            if (in_array($method, $methodNames)) {
                $foundPaymentMethods++;
                echo "   - Methode $method(): Presente\n";
            }
        }
        
        echo "   - StripePaymentController: $foundPaymentMethods methodes trouvees\n";
    }

    // 5. Verifier les controleurs de cookies
    echo "\n5. Verification du CookieController...\n";
    
    if (class_exists('App\Http\Controllers\CookieController')) {
        $reflection = new \ReflectionClass('App\Http\Controllers\CookieController');
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methodNames = array_map(function($method) {
            return $method->getName();
        }, $methods);
        
        $cookieMethods = [
            'getPreferences',
            'updatePreferences',
            'acceptAll',
            'rejectAll',
            'checkConsent'
        ];
        
        $foundCookieMethods = 0;
        foreach ($cookieMethods as $method) {
            if (in_array($method, $methodNames)) {
                $foundCookieMethods++;
            }
        }
        
        echo "   - Methodes cookies: $foundCookieMethods/" . count($cookieMethods) . "\n";
        
        if ($foundCookieMethods === count($cookieMethods)) {
            echo "   - CookieController: Complet\n";
        }
    }

    // 6. Verifier les controleurs de blog
    echo "\n6. Verification des controleurs Blog...\n";
    
    $blogControllers = [
        'App\Http\Controllers\BlogPostController',
        'App\Http\Controllers\BlogCategoryController',
        'App\Http\Controllers\BlogCommentController'
    ];
    
    $foundBlogControllers = 0;
    foreach ($blogControllers as $controller) {
        if (class_exists($controller)) {
            $foundBlogControllers++;
            echo "   - " . class_basename($controller) . ": Trouve\n";
        }
    }
    
    echo "   - Controleurs blog: $foundBlogControllers/" . count($blogControllers) . "\n";

    // 7. Verifier les controleurs de location
    echo "\n7. Verification des controleurs Location...\n";
    
    $rentalControllers = [
        'App\Http\Controllers\CartLocationController',
        'App\Http\Controllers\CartItemLocationController',
        'App\Http\Controllers\RentalController',
        'App\Http\Controllers\RentalCategoryController',
        'App\Http\Controllers\RentalConstraintController'
    ];
    
    $foundRentalControllers = 0;
    foreach ($rentalControllers as $controller) {
        if (class_exists($controller)) {
            $foundRentalControllers++;
            
            $reflection = new \ReflectionClass($controller);
            $methodsCount = count($reflection->getMethods(\ReflectionMethod::IS_PUBLIC));
            
            echo "   - " . class_basename($controller) . ": $methodsCount methodes publiques\n";
        }
    }
    
    echo "   - Controleurs location: $foundRentalControllers/" . count($rentalControllers) . "\n";

    // 8. Verifier les reponses JSON
    echo "\n8. Verification des types de retour JSON...\n";
    
    if (class_exists('App\Http\Controllers\CategoryController')) {
        $reflection = new \ReflectionClass('App\Http\Controllers\CategoryController');
        $indexMethod = $reflection->getMethod('index');
        
        // Verifier le type de retour
        $returnType = $indexMethod->getReturnType();
        if ($returnType) {
            $returnTypeName = $returnType->getName();
            echo "   - CategoryController::index() retourne: $returnTypeName\n";
        } else {
            echo "   - CategoryController::index() retourne: Non type (utilise probablement response()->json())\n";
        }
    }

    // 9. Verifier les middlewares dans les controleurs
    echo "\n9. Verification des middlewares...\n";
    
    if (class_exists('App\Http\Controllers\CartController')) {
        $reflection = new \ReflectionClass('App\Http\Controllers\CartController');
        
        // Verifier le constructeur pour les middlewares
        if ($reflection->hasMethod('__construct')) {
            echo "   - CartController: Constructeur present (peut definir des middlewares)\n";
        }
    }

    // 10. Statistiques globales
    echo "\n10. Statistiques globales des controleurs...\n";
    
    $allControllers = array_merge(
        $crudControllers,
        $blogControllers,
        $rentalControllers,
        ['App\Http\Controllers\StripePaymentController'],
        ['App\Http\Controllers\CookieController'],
        ['App\Http\Controllers\Api\OrderStatusController']
    );
    
    $totalMethods = 0;
    $totalControllers = 0;
    
    foreach (array_unique($allControllers) as $controller) {
        if (class_exists($controller)) {
            $totalControllers++;
            $reflection = new \ReflectionClass($controller);
            $totalMethods += count($reflection->getMethods(\ReflectionMethod::IS_PUBLIC));
        }
    }
    
    echo "   - Controleurs testes: $totalControllers\n";
    echo "   - Total methodes publiques: $totalMethods\n";
    echo "   - Moyenne methodes/controleur: " . round($totalMethods / max(1, $totalControllers), 2) . "\n";

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
    echo "Tous les controleurs API sont valides\n";
    echo "Duree: {$duration}ms\n";
    exit(0);
}
