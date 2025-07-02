<?php

echo "🔍 Diagnostic des interactions du panier - FarmShop\n";
echo "=" . str_repeat("=", 50) . "\n\n";

// Test 1: Vérifier les fichiers importants
echo "📁 1. Vérification des fichiers importants:\n";
echo str_repeat("-", 40) . "\n";

$files = [
    'resources/views/products/index.blade.php' => 'Vue liste des produits',
    'resources/views/products/show.blade.php' => 'Vue détail produit',
    'resources/views/layouts/public.blade.php' => 'Layout public',
    'public/css/special-offers.css' => 'CSS offres spéciales',
    'public/css/custom.css' => 'CSS personnalisé',
    'public/js/app.js' => 'JavaScript principal',
    'routes/web.php' => 'Routes web',
    'routes/api.php' => 'Routes API',
];

foreach ($files as $file => $description) {
    if (file_exists($file)) {
        $size = number_format(filesize($file) / 1024, 2);
        echo "✅ {$description}: {$file} ({$size} KB)\n";
    } else {
        echo "❌ {$description}: {$file} - MANQUANT\n";
    }
}

echo "\n";

// Test 2: Vérifier la structure des contrôleurs
echo "🎮 2. Vérification des contrôleurs:\n";
echo str_repeat("-", 40) . "\n";

$controllers = [
    'app/Http/Controllers/ProductController.php' => 'Contrôleur produits',
    'app/Http/Controllers/CartController.php' => 'Contrôleur panier',
    'app/Http/Controllers/OrderController.php' => 'Contrôleur commandes',
];

foreach ($controllers as $file => $description) {
    if (file_exists($file)) {
        echo "✅ {$description}: {$file}\n";
        
        // Vérifier les méthodes importantes
        $content = file_get_contents($file);
        $methods = [];
        
        if (preg_match_all('/public function (\w+)\s*\(/', $content, $matches)) {
            $methods = $matches[1];
        }
        
        if (!empty($methods)) {
            echo "   Méthodes: " . implode(', ', $methods) . "\n";
        }
    } else {
        echo "❌ {$description}: {$file} - MANQUANT\n";
    }
}

echo "\n";

// Test 3: Analyser le JavaScript de la page produits
echo "🔍 3. Analyse du JavaScript dans products/index.blade.php:\n";
echo str_repeat("-", 40) . "\n";

$productsView = 'resources/views/products/index.blade.php';
if (file_exists($productsView)) {
    $content = file_get_contents($productsView);
    
    // Vérifier les fonctions JavaScript importantes
    $jsFunctions = [
        'addToCart' => 'Fonction d\'ajout au panier',
        'buyNow' => 'Fonction d\'achat immédiat',
        'rentNow' => 'Fonction de location',
        'showToast' => 'Fonction d\'affichage des notifications',
        'addToCartWithQuantity' => 'Fonction d\'ajout avec quantité',
    ];
    
    foreach ($jsFunctions as $func => $description) {
        if (strpos($content, "function {$func}(") !== false) {
            echo "✅ {$description}: {$func}()\n";
        } else {
            echo "❌ {$description}: {$func}() - MANQUANTE\n";
        }
    }
    
    // Vérifier les événements onclick
    $onclickCount = substr_count($content, 'onclick=');
    echo "\n📊 Nombre d'événements onclick détectés: {$onclickCount}\n";
    
    // Vérifier les classes CSS importantes
    if (strpos($content, 'product-card') !== false) {
        echo "✅ Classe 'product-card' trouvée\n";
    } else {
        echo "❌ Classe 'product-card' non trouvée\n";
    }
    
    if (strpos($content, 'clickable-test') !== false) {
        echo "⚠️ Classe de test 'clickable-test' trouvée (à retirer en production)\n";
    }
    
} else {
    echo "❌ Fichier de vue produits non trouvé\n";
}

echo "\n";

// Test 4: Vérifier les routes dans web.php
echo "🛣️ 4. Vérification des routes dans web.php:\n";
echo str_repeat("-", 40) . "\n";

$webRoutes = 'routes/web.php';
if (file_exists($webRoutes)) {
    $content = file_get_contents($webRoutes);
    
    $routes = [
        'products.index' => "Route::get('/products'",
        'products.show' => "Route::get('/products/{",
        'cart.index' => "/cart",
        'orders.create' => "/orders",
    ];
    
    foreach ($routes as $name => $pattern) {
        if (strpos($content, $pattern) !== false) {
            echo "✅ Route {$name} trouvée\n";
        } else {
            echo "❌ Route {$name} non trouvée (pattern: {$pattern})\n";
        }
    }
} else {
    echo "❌ Fichier routes/web.php non trouvé\n";
}

echo "\n";

// Test 5: Vérifier les modèles
echo "📊 5. Vérification des modèles:\n";
echo str_repeat("-", 40) . "\n";

$models = [
    'app/Models/Product.php' => 'Modèle produit',
    'app/Models/CartItem.php' => 'Modèle article panier',
    'app/Models/Order.php' => 'Modèle commande',
    'app/Models/SpecialOffer.php' => 'Modèle offre spéciale',
];

foreach ($models as $file => $description) {
    if (file_exists($file)) {
        echo "✅ {$description}: {$file}\n";
        
        // Vérifier les méthodes importantes
        $content = file_get_contents($file);
        
        if (strpos($content, 'hasActiveSpecialOffer') !== false) {
            echo "   ✅ Méthode hasActiveSpecialOffer() trouvée\n";
        }
        
        if (strpos($content, 'getActiveSpecialOffer') !== false) {
            echo "   ✅ Méthode getActiveSpecialOffer() trouvée\n";
        }
    } else {
        echo "❌ {$description}: {$file} - MANQUANT\n";
    }
}

echo "\n";

// Test 6: Problèmes potentiels identifiés
echo "⚠️ 6. Problèmes potentiels identifiés:\n";
echo str_repeat("-", 40) . "\n";

$issues = [];

// Vérifier le layout public
$layout = 'resources/views/layouts/public.blade.php';
if (file_exists($layout)) {
    $content = file_get_contents($layout);
    
    // CSS en double
    if (substr_count($content, 'special-offers.css') > 1) {
        $issues[] = "CSS special-offers.css inclus plusieurs fois dans le layout";
    }
    
    if (substr_count($content, 'custom.css') > 1) {
        $issues[] = "CSS custom.css inclus plusieurs fois dans le layout";
    }
    
    // Ordre des CSS
    if (strpos($content, 'special-offers.css') < strpos($content, 'bootstrap')) {
        $issues[] = "special-offers.css chargé avant Bootstrap (peut causer des conflits)";
    }
}

// Vérifier le CSS des offres spéciales
$specialCss = 'public/css/special-offers.css';
if (file_exists($specialCss)) {
    $content = file_get_contents($specialCss);
    
    // Z-index élevés
    if (preg_match('/z-index:\s*(\d+)/', $content, $matches)) {
        $zIndex = intval($matches[1]);
        if ($zIndex > 1000) {
            $issues[] = "Z-index très élevé dans special-offers.css ({$zIndex}) peut causer des problèmes d'interaction";
        }
    }
    
    // Overlays
    if (strpos($content, '::before') !== false && strpos($content, 'position: absolute') !== false) {
        $issues[] = "Pseudo-éléments ::before avec position absolute peuvent bloquer les clics";
    }
}

if (empty($issues)) {
    echo "✅ Aucun problème évident détecté\n";
} else {
    foreach ($issues as $issue) {
        echo "⚠️ {$issue}\n";
    }
}

echo "\n";

// Test 7: Solutions recommandées
echo "💡 7. Solutions recommandées:\n";
echo str_repeat("-", 40) . "\n";

echo "1. Corriger le layout public:\n";
echo "   - Supprimer les CSS en double\n";
echo "   - Inclure special-offers.css APRÈS Bootstrap\n\n";

echo "2. Tester les interactions:\n";
echo "   - Ouvrir test_card_interactions.html dans le navigateur\n";
echo "   - Vérifier que tous les boutons répondent aux clics\n";
echo "   - Consulter la console JavaScript pour les erreurs\n\n";

echo "3. Vérifier les z-index:\n";
echo "   - Inspecter les éléments avec les outils de développement\n";
echo "   - S'assurer qu'aucun overlay invisible ne bloque les clics\n\n";

echo "4. Tester l'API du panier:\n";
echo "   - Se connecter en tant qu'utilisateur\n";
echo "   - Tenter d'ajouter un produit au panier\n";
echo "   - Vérifier les requêtes dans l'onglet Network\n\n";

echo "📊 Résumé du diagnostic:\n";
echo "- Fichiers vérifiés: " . count($files) . "\n";
echo "- Contrôleurs vérifiés: " . count($controllers) . "\n";
echo "- Modèles vérifiés: " . count($models) . "\n";
echo "- Problèmes détectés: " . count($issues) . "\n";

echo "\n🏁 Diagnostic terminé.\n";
echo "Prochaine étape: Corriger le layout public et tester les interactions.\n";
