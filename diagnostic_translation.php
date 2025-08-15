<?php

/**
 * Diagnostic du système de traduction
 * Vérifie tous les composants du système multilingue
 */

echo "🔍 Diagnostic du système de traduction FarmShop\n";
echo "================================================\n\n";

// 1. Vérifier la configuration
echo "1. Configuration Laravel:\n";
echo "   - Locale actuelle: " . app()->getLocale() . "\n";
echo "   - Locale de fallback: " . config('app.fallback_locale') . "\n";
echo "   - Locales supportées: " . (config('app.supported_locales') ? 'Configurées' : 'NON CONFIGURÉES') . "\n\n";

// 2. Vérifier les fonctions helper
echo "2. Fonctions Helper:\n";
try {
    $currentConfig = get_current_locale_config();
    echo "   ✅ get_current_locale_config(): " . json_encode($currentConfig) . "\n";
} catch (Exception $e) {
    echo "   ❌ get_current_locale_config(): " . $e->getMessage() . "\n";
}

try {
    $allLocales = get_all_locales();
    echo "   ✅ get_all_locales(): " . count($allLocales) . " locales trouvées\n";
    foreach ($allLocales as $code => $config) {
        echo "      - {$code}: {$config['name']} {$config['flag']}\n";
    }
} catch (Exception $e) {
    echo "   ❌ get_all_locales(): " . $e->getMessage() . "\n";
}

try {
    $localizedUrl = get_localized_url('products.index', 'en');
    echo "   ✅ get_localized_url(): $localizedUrl\n";
} catch (Exception $e) {
    echo "   ❌ get_localized_url(): " . $e->getMessage() . "\n";
}

echo "\n";

// 3. Vérifier les fichiers de traduction
echo "3. Fichiers de traduction:\n";
$locales = ['fr', 'en', 'nl'];
foreach ($locales as $locale) {
    $filePath = "lang/{$locale}/app.php";
    if (file_exists($filePath)) {
        $translations = include $filePath;
        $count = 0;
        array_walk_recursive($translations, function() use (&$count) { $count++; });
        echo "   ✅ {$filePath}: {$count} traductions\n";
    } else {
        echo "   ❌ {$filePath}: FICHIER MANQUANT\n";
    }
}

echo "\n";

// 4. Vérifier les routes
echo "4. Routes de traduction:\n";
try {
    $routeExists = Route::has('locale.change');
    echo "   " . ($routeExists ? '✅' : '❌') . " Route locale.change: " . ($routeExists ? 'EXISTS' : 'MANQUANTE') . "\n";
    
    if ($routeExists) {
        $route = Route::getRoutes()->getByName('locale.change');
        echo "      URI: " . $route->uri() . "\n";
        echo "      Méthodes: " . implode(', ', $route->methods()) . "\n";
    }
} catch (Exception $e) {
    echo "   ❌ Erreur routes: " . $e->getMessage() . "\n";
}

echo "\n";

// 5. Vérifier le middleware
echo "5. Middleware SetLocale:\n";
$middlewareExists = class_exists('App\\Http\\Middleware\\SetLocale');
echo "   " . ($middlewareExists ? '✅' : '❌') . " Classe SetLocale: " . ($middlewareExists ? 'EXISTS' : 'MANQUANTE') . "\n";

// 6. Vérifier la session
echo "\n6. Session et cookies:\n";
if (session()->has('locale')) {
    echo "   ✅ Session locale: " . session('locale') . "\n";
} else {
    echo "   ⚠️  Session locale: NON DÉFINIE\n";
}

if (isset($_COOKIE['locale'])) {
    echo "   ✅ Cookie locale: " . $_COOKIE['locale'] . "\n";
} else {
    echo "   ⚠️  Cookie locale: NON DÉFINI\n";
}

echo "\n";

// 7. Test de traduction
echo "7. Test de traduction:\n";
try {
    $welcomeTitle = __('app.welcome.hero_title');
    echo "   ✅ __('app.welcome.hero_title'): $welcomeTitle\n";
    
    $navHome = __('app.nav.home');
    echo "   ✅ __('app.nav.home'): $navHome\n";
} catch (Exception $e) {
    echo "   ❌ Erreur traduction: " . $e->getMessage() . "\n";
}

echo "\n";

// 8. Vérifier les URLs de test
echo "8. URLs de test:\n";
$testUrls = [
    'Français' => url('/?locale=fr'),
    'Anglais' => url('/?locale=en'),
    'Néerlandais' => url('/?locale=nl'),
];

foreach ($testUrls as $lang => $url) {
    echo "   - {$lang}: {$url}\n";
}

echo "\n🎯 Actions recommandées:\n";
echo "1. Testez chaque URL ci-dessus dans votre navigateur\n";
echo "2. Ouvrez les outils de développement (F12)\n";
echo "3. Vérifiez la console pour les erreurs JavaScript\n";
echo "4. Inspectez le sélecteur de langue pour voir s'il se génère correctement\n";
echo "5. Vérifiez que les liens dans le dropdown pointent vers les bonnes URLs\n";

echo "\n✅ Diagnostic terminé !\n";
