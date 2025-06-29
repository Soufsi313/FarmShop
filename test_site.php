<?php
/**
 * Script de test rapide pour vérifier le bon fonctionnement du site FarmShop
 */

echo "=== TEST FARMSHOP ===\n\n";

// 1. Vérifier la base de données
echo "1. Vérification base de données...\n";
try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    $pdo = DB::connection()->getPdo();
    echo "   ✓ Connexion base de données OK\n";
    
    // Vérifier les tables principales
    $tables = ['users', 'products', 'categories', 'orders', 'cookie_consents'];
    foreach ($tables as $table) {
        $exists = DB::select("SHOW TABLES LIKE '$table'");
        echo "   " . ($exists ? "✓" : "✗") . " Table '$table' " . ($exists ? "existe" : "manquante") . "\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ Erreur base de données: " . $e->getMessage() . "\n";
}

// 2. Vérifier les fichiers de vues
echo "\n2. Vérification fichiers de vues...\n";
$views = [
    'resources/views/welcome.blade.php',
    'resources/views/components/navigation.blade.php', 
    'resources/views/components/footer.blade.php',
    'resources/views/components/cookie-banner-fixed.blade.php'
];

foreach ($views as $view) {
    echo "   " . (file_exists($view) ? "✓" : "✗") . " $view\n";
}

// 3. Vérifier les routes
echo "\n3. Vérification routes API...\n";
try {
    $routes = app('router')->getRoutes();
    $cookieRoutes = 0;
    foreach ($routes as $route) {
        if (str_contains($route->uri(), 'cookie-consent')) {
            $cookieRoutes++;
        }
    }
    echo "   ✓ Routes cookies API: $cookieRoutes trouvées\n";
} catch (Exception $e) {
    echo "   ✗ Erreur routes: " . $e->getMessage() . "\n";
}

// 4. Vérifier les assets
echo "\n4. Vérification assets...\n";
$assets = [
    'public/css/custom.css',
    'public/mix-manifest.json'
];

foreach ($assets as $asset) {
    echo "   " . (file_exists($asset) ? "✓" : "✗") . " $asset\n";
}

echo "\n=== FIN TEST ===\n";
echo "Site accessible sur: http://127.0.0.1:8000\n";
echo "Pour tester la bannière cookies, utilisez le bouton 'Reset' en haut à droite.\n";
