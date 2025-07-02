<?php

// Script pour créer un test utilisateur et vérifier les interactions
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';

// Démarrer Laravel
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Test des interactions sur la vraie page\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    // Vérifier qu'il y a des produits
    $products = \App\Models\Product::take(3)->get();
    
    if ($products->count() === 0) {
        echo "❌ Aucun produit trouvé dans la base de données\n";
        echo "💡 Créez quelques produits de test d'abord\n";
        exit;
    }
    
    echo "✅ {$products->count()} produits trouvés pour les tests\n\n";
    
    foreach ($products as $product) {
        echo "📦 Produit: {$product->name}\n";
        echo "   ID: {$product->id}\n";
        echo "   Prix: " . ($product->price > 0 ? "{$product->price}€" : "N/A") . "\n";
        echo "   Location: " . ($product->is_rentable ? "Oui ({$product->rental_price_per_day}€/jour)" : "Non") . "\n";
        echo "   Stock: {$product->quantity}\n";
        
        if ($product->hasActiveSpecialOffer()) {
            $offer = $product->getActiveSpecialOffer();
            echo "   🔥 Offre spéciale: -{$offer->discount_percentage}%\n";
        }
        echo "\n";
    }
    
    // Vérifier qu'il y a un utilisateur de test
    $testUser = \App\Models\User::where('email', 'test@example.com')->first();
    
    if (!$testUser) {
        echo "👤 Création d'un utilisateur de test...\n";
        $testUser = \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        echo "✅ Utilisateur de test créé: test@example.com / password\n";
    } else {
        echo "✅ Utilisateur de test existe: test@example.com / password\n";
    }
    
    echo "\n";
    
    // Créer un script de test JavaScript à injecter
    $jsTest = "
console.log('🧪 Test d\\'interactions FarmShop');

// Test 1: Vérifier que jQuery/Bootstrap sont chargés
if (typeof bootstrap !== 'undefined') {
    console.log('✅ Bootstrap JS chargé');
} else {
    console.log('❌ Bootstrap JS non chargé');
}

// Test 2: Vérifier les cartes produits
const productCards = document.querySelectorAll('.product-card');
console.log('📊 Nombre de cartes produits:', productCards.length);

// Test 3: Vérifier les boutons
const buttons = document.querySelectorAll('.product-card button, .product-card .btn');
console.log('📊 Nombre de boutons dans les cartes:', buttons.length);

// Test 4: Tester les événements de clic
let clickCount = 0;
buttons.forEach((btn, index) => {
    const originalOnClick = btn.onclick;
    btn.addEventListener('click', function(e) {
        clickCount++;
        console.log('🖱️ Clic détecté sur bouton', index + 1, ':', this.textContent.trim());
        console.log('   Element:', this);
        console.log('   Classes:', this.className);
        console.log('   Position:', this.getBoundingClientRect());
    });
});

// Test 5: Vérifier les overlays
const elementsWithBefore = [];
document.querySelectorAll('*').forEach(el => {
    const before = window.getComputedStyle(el, '::before');
    if (before && before.content !== 'none' && before.position === 'absolute') {
        elementsWithBefore.push({
            element: el,
            className: el.className,
            zIndex: before.zIndex
        });
    }
});

if (elementsWithBefore.length > 0) {
    console.log('⚠️ Éléments avec ::before absolus détectés:', elementsWithBefore);
} else {
    console.log('✅ Aucun overlay ::before problématique détecté');
}

// Test 6: Vérifier le token CSRF
const csrfToken = document.querySelector('meta[name=\"csrf-token\"]');
if (csrfToken) {
    console.log('✅ Token CSRF trouvé:', csrfToken.getAttribute('content').substring(0, 10) + '...');
} else {
    console.log('❌ Token CSRF non trouvé');
}

// Test 7: Simuler un clic sur le premier bouton disponible
setTimeout(() => {
    const firstBtn = document.querySelector('.product-card .btn:not([disabled])');
    if (firstBtn) {
        console.log('🎯 Test de clic automatique sur:', firstBtn.textContent.trim());
        firstBtn.click();
    }
}, 2000);

console.log('🏁 Test d\\'interactions terminé');
    ";
    
    echo "📝 Script de test JavaScript créé\n";
    echo "💡 Pour tester les interactions:\n\n";
    echo "1. Démarrez le serveur Laravel:\n";
    echo "   php artisan serve\n\n";
    echo "2. Ouvrez http://localhost:8000/products dans votre navigateur\n\n";
    echo "3. Connectez-vous avec: test@example.com / password\n\n";
    echo "4. Ouvrez les outils de développement (F12)\n\n";
    echo "5. Collez ce script dans la console:\n\n";
    echo $jsTest . "\n\n";
    echo "6. Observez les messages dans la console et testez manuellement les boutons\n\n";
    
    // Sauvegarder le script dans un fichier
    file_put_contents('test_interactions.js', $jsTest);
    echo "📁 Script sauvegardé dans test_interactions.js\n\n";
    
    echo "🔧 Autres vérifications à faire manuellement:\n";
    echo "- Les boutons changent de couleur au survol\n";
    echo "- Les boutons répondent aux clics\n";
    echo "- Les modals s'ouvrent correctement\n";
    echo "- Les toasts s'affichent après les actions\n";
    echo "- Les requêtes AJAX sont envoyées (onglet Network)\n\n";
    
    echo "🐛 Si les boutons ne répondent pas:\n";
    echo "1. Vérifiez qu'il n'y a pas d'erreurs JavaScript dans la console\n";
    echo "2. Inspectez l'élément pour voir s'il y a des overlays invisibles\n";
    echo "3. Vérifiez que les fonctions JavaScript sont bien définies\n";
    echo "4. Testez avec test_card_interactions.html d'abord\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "🏁 Test terminé.\n";
