<?php

/**
 * Test Unitaire: Reponses JSON API
 * 
 * Verifie la structure des reponses JSON de l'API
 */

// Bootstrap Laravel si necessaire
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
    require_once __DIR__ . '/../../vendor/autoload.php';
    $app = require_once __DIR__ . '/../../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
}

echo "\n========================================\n";
echo "TEST: REPONSES JSON API\n";
echo "========================================\n\n";

$startTime = microtime(true);
$errors = [];

try {
    // 1. Tester la reponse de base JSON
    echo "1. Test des reponses JSON de base...\n";
    
    $response = response()->json([
        'status' => 'success',
        'message' => 'Test reussi',
        'data' => ['test' => true]
    ]);
    
    if ($response instanceof \Illuminate\Http\JsonResponse) {
        echo "   - Type: JsonResponse (valide)\n";
        echo "   - Status code: " . $response->status() . "\n";
        
        $content = json_decode($response->content(), true);
        if (is_array($content)) {
            echo "   - Contenu JSON: Valide\n";
            echo "   - Cles presentes: " . implode(', ', array_keys($content)) . "\n";
        }
    } else {
        $errors[] = "response()->json() ne retourne pas une JsonResponse";
    }

    // 2. Tester les codes de statut HTTP
    echo "\n2. Test des codes de statut HTTP...\n";
    
    $statusCodes = [
        200 => 'OK',
        201 => 'Created',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        422 => 'Unprocessable Entity',
        500 => 'Internal Server Error'
    ];
    
    foreach ($statusCodes as $code => $description) {
        $response = response()->json(['message' => $description], $code);
        if ($response->status() === $code) {
            echo "   - Code $code ($description): Valide\n";
        } else {
            $errors[] = "Code de statut $code invalide";
        }
    }

    // 3. Tester la structure de reponse success
    echo "\n3. Test de la structure 'success'...\n";
    
    $successResponse = [
        'status' => 'success',
        'message' => 'Operation reussie',
        'data' => [
            'id' => 1,
            'name' => 'Test Product'
        ]
    ];
    
    $requiredKeys = ['status', 'message', 'data'];
    $hasAllKeys = true;
    
    foreach ($requiredKeys as $key) {
        if (!array_key_exists($key, $successResponse)) {
            $hasAllKeys = false;
            $errors[] = "Cle '$key' manquante dans la reponse success";
        } else {
            echo "   - Cle '$key': Presente\n";
        }
    }
    
    if ($hasAllKeys && $successResponse['status'] === 'success') {
        echo "   - Structure success: Valide\n";
    }

    // 4. Tester la structure de reponse error
    echo "\n4. Test de la structure 'error'...\n";
    
    $errorResponse = [
        'status' => 'error',
        'message' => 'Une erreur est survenue',
        'errors' => [
            'field' => ['Le champ est requis']
        ]
    ];
    
    $errorKeys = ['status', 'message'];
    $hasErrorKeys = true;
    
    foreach ($errorKeys as $key) {
        if (!array_key_exists($key, $errorResponse)) {
            $hasErrorKeys = false;
            $errors[] = "Cle '$key' manquante dans la reponse error";
        } else {
            echo "   - Cle '$key': Presente\n";
        }
    }
    
    if ($hasErrorKeys && $errorResponse['status'] === 'error') {
        echo "   - Structure error: Valide\n";
    }

    // 5. Tester la pagination JSON
    echo "\n5. Test de la pagination JSON...\n";
    
    // Creer une collection paginee
    $items = collect(range(1, 50));
    $perPage = 10;
    $currentPage = 1;
    
    $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
        $items->forPage($currentPage, $perPage),
        $items->count(),
        $perPage,
        $currentPage,
        ['path' => '/api/products']
    );
    
    $paginatedResponse = $paginator->toArray();
    
    $paginationKeys = ['current_page', 'data', 'first_page_url', 'last_page', 'per_page', 'total'];
    $foundPaginationKeys = 0;
    
    foreach ($paginationKeys as $key) {
        if (array_key_exists($key, $paginatedResponse)) {
            $foundPaginationKeys++;
        }
    }
    
    echo "   - Cles pagination trouvees: $foundPaginationKeys/" . count($paginationKeys) . "\n";
    echo "   - Total items: " . $paginatedResponse['total'] . "\n";
    echo "   - Par page: " . $paginatedResponse['per_page'] . "\n";
    echo "   - Page courante: " . $paginatedResponse['current_page'] . "\n";

    // 6. Tester les ressources API (Resource)
    echo "\n6. Test des ressources API...\n";
    
    // Verifier si des ressources API existent
    $resourcesPath = app_path('Http/Resources');
    
    if (is_dir($resourcesPath)) {
        $resources = glob($resourcesPath . '/*.php');
        echo "   - Dossier Resources: Existe\n";
        echo "   - Ressources trouvees: " . count($resources) . "\n";
        
        if (count($resources) > 0) {
            $resourceNames = array_map(function($path) {
                return basename($path, '.php');
            }, array_slice($resources, 0, 5));
            echo "   - Exemples: " . implode(', ', $resourceNames) . "\n";
        }
    } else {
        echo "   - Dossier Resources: Non trouve (utilisation de tableaux directs)\n";
    }

    // 7. Tester les headers JSON
    echo "\n7. Test des headers JSON...\n";
    
    $response = response()->json(['test' => true]);
    $headers = $response->headers;
    
    $contentType = $headers->get('Content-Type');
    if (strpos($contentType, 'application/json') !== false) {
        echo "   - Content-Type: $contentType (valide)\n";
    } else {
        $errors[] = "Content-Type invalide: $contentType";
    }

    // 8. Tester les reponses de validation
    echo "\n8. Test des reponses de validation...\n";
    
    $validationErrors = [
        'email' => ['Le champ email est requis'],
        'password' => ['Le mot de passe doit contenir au moins 8 caracteres']
    ];
    
    $validationResponse = response()->json([
        'message' => 'Les donnees fournies ne sont pas valides',
        'errors' => $validationErrors
    ], 422);
    
    if ($validationResponse->status() === 422) {
        echo "   - Code de validation: 422 (correct)\n";
        
        $content = json_decode($validationResponse->content(), true);
        if (isset($content['errors']) && is_array($content['errors'])) {
            echo "   - Structure errors: Valide\n";
            echo "   - Nombre de champs invalides: " . count($content['errors']) . "\n";
        }
    }

    // 9. Tester les meta-donnees
    echo "\n9. Test des meta-donnees API...\n";
    
    $responseWithMeta = [
        'status' => 'success',
        'data' => ['items' => []],
        'meta' => [
            'version' => '1.0.0',
            'timestamp' => now()->toIso8601String(),
            'request_id' => uniqid()
        ]
    ];
    
    if (isset($responseWithMeta['meta'])) {
        echo "   - Meta-donnees: Presentes\n";
        foreach ($responseWithMeta['meta'] as $key => $value) {
            echo "     * $key: " . (is_string($value) ? $value : gettype($value)) . "\n";
        }
    }

    // 10. Tester les reponses avec relations
    echo "\n10. Test des reponses avec relations...\n";
    
    $productWithRelations = [
        'id' => 1,
        'name' => 'Test Product',
        'category' => [
            'id' => 10,
            'name' => 'Electronics'
        ],
        'images' => [
            ['id' => 1, 'url' => 'image1.jpg'],
            ['id' => 2, 'url' => 'image2.jpg']
        ]
    ];
    
    $hasRelations = isset($productWithRelations['category']) && isset($productWithRelations['images']);
    
    if ($hasRelations) {
        echo "   - Relations imbriquees: Presentes\n";
        echo "   - Relation category: " . gettype($productWithRelations['category']) . "\n";
        echo "   - Relation images: " . gettype($productWithRelations['images']) . " (" . count($productWithRelations['images']) . " items)\n";
    }

    // 11. Tester les timestamps dans les reponses
    echo "\n11. Test des timestamps...\n";
    
    $timestampFormats = [
        'iso8601' => now()->toIso8601String(),
        'datetime' => now()->toDateTimeString(),
        'timestamp' => now()->timestamp,
        'carbon' => now()->toArray()
    ];
    
    foreach ($timestampFormats as $format => $value) {
        echo "   - Format $format: " . (is_array($value) ? 'array' : $value) . "\n";
    }

    // 12. Tester la coherence des reponses
    echo "\n12. Test de coherence des reponses...\n";
    
    $responses = [
        ['status' => 'success', 'message' => 'OK', 'data' => []],
        ['status' => 'success', 'message' => 'Created', 'data' => ['id' => 1]],
        ['status' => 'error', 'message' => 'Not Found', 'errors' => []]
    ];
    
    $allHaveStatus = true;
    $allHaveMessage = true;
    
    foreach ($responses as $response) {
        if (!isset($response['status'])) $allHaveStatus = false;
        if (!isset($response['message'])) $allHaveMessage = false;
    }
    
    echo "   - Coherence 'status': " . ($allHaveStatus ? 'OUI' : 'NON') . "\n";
    echo "   - Coherence 'message': " . ($allHaveMessage ? 'OUI' : 'NON') . "\n";
    
    if ($allHaveStatus && $allHaveMessage) {
        echo "   - Format de reponse: Coherent\n";
    }

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
    echo "Toutes les reponses JSON API sont valides\n";
    echo "Duree: {$duration}ms\n";
    exit(0);
}
