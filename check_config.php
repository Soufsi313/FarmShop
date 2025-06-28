<?php
/**
 * Script de vérification de la configuration du site
 */

echo "=== VÉRIFICATION CONFIGURATION FARMSHOP ===\n\n";

// 1. Vérifier les vues
echo "1. Vérification des vues...\n";
$views = [
    'resources/views/welcome.blade.php',
    'resources/views/components/navigation.blade.php',
    'resources/views/components/footer.blade.php'
];

foreach ($views as $view) {
    if (file_exists($view)) {
        echo "   ✓ $view\n";
        
        // Vérifier s'il y a des @vite non traités
        $content = file_get_contents($view);
        if (strpos($content, '@vite') !== false) {
            echo "   ⚠️  Directive @vite trouvée dans $view\n";
        }
        
        // Vérifier s'il y a des Alpine.js
        if (strpos($content, 'x-data') !== false || strpos($content, 'alpinejs') !== false) {
            echo "   ⚠️  Alpine.js détecté dans $view\n";
        }
    } else {
        echo "   ✗ $view manquant\n";
    }
}

// 2. Vérifier les assets
echo "\n2. Vérification des assets...\n";
$assets = [
    'public/css/custom.css' => 'CSS personnalisé',
    'public/js/debug-cookies.js' => 'JS Debug (peut être supprimé)',
    'public/mix-manifest.json' => 'Manifest Laravel Mix'
];

foreach ($assets as $path => $desc) {
    if (file_exists($path)) {
        echo "   ✓ $desc : $path\n";
    } else {
        echo "   ✗ $desc manquant : $path\n";
    }
}

// 3. Vérifier la configuration Laravel
echo "\n3. Vérification configuration Laravel...\n";
try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    
    echo "   ✓ Laravel chargé correctement\n";
    echo "   ✓ Environnement : " . (app()->environment() ?? 'unknown') . "\n";
    
} catch (Exception $e) {
    echo "   ✗ Erreur Laravel : " . $e->getMessage() . "\n";
}

// 4. Vérifier les fichiers de configuration problématiques
echo "\n4. Vérification fichiers de configuration...\n";
$configs = [
    'vite.config.js' => 'Configuration Vite',
    'webpack.mix.js' => 'Configuration Laravel Mix',
    'package.json' => 'Dépendances NPM'
];

foreach ($configs as $file => $desc) {
    if (file_exists($file)) {
        echo "   ✓ $desc : $file\n";
    } else {
        echo "   ✗ $desc manquant : $file\n";
    }
}

echo "\n=== RÉSUMÉ ===\n";
echo "Si vous voyez encore '@vite([...])' affiché sur le site :\n";
echo "1. Vérifiez que vous utilisez 'php artisan serve' (pas un autre serveur)\n";
echo "2. Vérifiez que les extensions Blade sont bien configurées\n";
echo "3. Videz le cache avec 'php artisan view:clear'\n";
echo "4. Redémarrez le serveur Laravel\n\n";

echo "Site disponible sur : http://127.0.0.1:8000\n";
