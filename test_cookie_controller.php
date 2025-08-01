<?php

// Test rapide du CookieController
require_once 'vendor/autoload.php';

use App\Models\Cookie;

// Simuler Laravel bootstrap minimal
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Test direct du modèle
    echo "=== Test direct du modèle Cookie ===\n";
    $cookies = Cookie::with('user')->orderBy('updated_at', 'desc')->take(5)->get();
    echo "Nombre de cookies trouvés: " . $cookies->count() . "\n";
    
    foreach ($cookies as $cookie) {
        echo "- Cookie ID: {$cookie->id}, Status: {$cookie->status}, Session: {$cookie->session_id}\n";
    }
    
    // Test de la pagination
    echo "\n=== Test de pagination ===\n";
    $paginated = Cookie::with('user')->orderBy('updated_at', 'desc')->paginate(10);
    echo "Total items: " . $paginated->total() . "\n";
    echo "Current page: " . $paginated->currentPage() . "\n";
    echo "Last page: " . $paginated->lastPage() . "\n";
    echo "Items per page: " . $paginated->perPage() . "\n";
    echo "Items in current page: " . $paginated->count() . "\n";
    
    // Structure de données
    echo "\n=== Structure des données paginées ===\n";
    $data = $paginated->toArray();
    echo "Keys: " . implode(', ', array_keys($data)) . "\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
