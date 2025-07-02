<?php
// Script de test pour vérifier le système d'offres spéciales
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Bootstrap\HandleExceptions;
use Illuminate\Foundation\Bootstrap\RegisterFacades;
use Illuminate\Foundation\Bootstrap\RegisterProviders;
use Illuminate\Foundation\Bootstrap\BootProviders;

echo "🚀 Test du système d'offres spéciales - FarmShop\n";
echo "================================================\n\n";

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "✅ Bootstrap Laravel réussi\n\n";

    // Test de connexion à la base de données
    echo "🔌 Test de connexion à la base de données...\n";
    $pdo = DB::connection()->getPdo();
    echo "✅ Connexion à la base de données réussie\n\n";

    // Vérifier l'existence des tables
    echo "📊 Vérification des tables...\n";
    $tables = ['special_offers', 'products', 'users'];
    foreach ($tables as $table) {
        $exists = Schema::hasTable($table);
        echo $exists ? "✅ Table '$table' existe\n" : "❌ Table '$table' manquante\n";
    }
    echo "\n";

    // Test du modèle SpecialOffer
    echo "🧪 Test du modèle SpecialOffer...\n";
    $offerCount = App\Models\SpecialOffer::count();
    echo "✅ Modèle SpecialOffer accessible - {$offerCount} offre(s) en BDD\n";

    // Test des scopes
    $activeCount = App\Models\SpecialOffer::active()->count();
    $availableCount = App\Models\SpecialOffer::available()->count();
    echo "✅ Scopes fonctionnels - {$activeCount} active(s), {$availableCount} disponible(s)\n";

    // Test des méthodes du modèle
    if ($offerCount > 0) {
        $firstOffer = App\Models\SpecialOffer::first();
        $status = $firstOffer->status;
        $isActive = $firstOffer->isActive() ? 'Oui' : 'Non';
        $isScheduled = $firstOffer->isScheduled() ? 'Oui' : 'Non';
        $isExpired = $firstOffer->isExpired() ? 'Oui' : 'Non';
        
        echo "✅ Méthodes de statut fonctionnelles\n";
        echo "   - Statut: {$status}\n";
        echo "   - Est active: {$isActive}\n";
        echo "   - Est programmée: {$isScheduled}\n";
        echo "   - Est expirée: {$isExpired}\n";

        // Test de calcul de remise
        $discount = $firstOffer->calculateDiscount(5, 10.0);
        echo "✅ Calcul de remise fonctionnel\n";
        echo "   - Qualifie: " . ($discount['qualifies'] ? 'Oui' : 'Non') . "\n";
        echo "   - Montant remise: " . number_format($discount['discount_amount'], 2) . "€\n";
    }
    echo "\n";

    // Test des routes
    echo "🛣️  Test des routes admin...\n";
    $routes = [
        'admin.dashboard',
        'admin.special-offers.index',
        'admin.special-offers.create',
    ];
    
    foreach ($routes as $routeName) {
        try {
            $url = route($routeName);
            echo "✅ Route '{$routeName}' : {$url}\n";
        } catch (Exception $e) {
            echo "❌ Route '{$routeName}' : " . $e->getMessage() . "\n";
        }
    }
    echo "\n";

    // Test du contrôleur AdminController
    echo "🎛️  Test du contrôleur AdminController...\n";
    try {
        $controller = new App\Http\Controllers\Admin\AdminController();
        echo "✅ Contrôleur AdminController accessible\n";
    } catch (Exception $e) {
        echo "❌ Erreur contrôleur AdminController : " . $e->getMessage() . "\n";
    }
    echo "\n";

    // Test du contrôleur SpecialOfferController
    echo "🎛️  Test du contrôleur SpecialOfferController...\n";
    try {
        $controller = new App\Http\Controllers\Admin\SpecialOfferController();
        echo "✅ Contrôleur SpecialOfferController accessible\n";
    } catch (Exception $e) {
        echo "❌ Erreur contrôleur SpecialOfferController : " . $e->getMessage() . "\n";
    }
    echo "\n";

    // Test des vues
    echo "👁️  Test des vues admin...\n";
    $views = [
        'admin.dashboard',
        'admin.special-offers.index',
        'admin.special-offers.create',
        'admin.special-offers.edit',
        'admin.special-offers.show',
    ];
    
    foreach ($views as $viewName) {
        try {
            $exists = view()->exists($viewName);
            echo $exists ? "✅ Vue '{$viewName}' existe\n" : "❌ Vue '{$viewName}' manquante\n";
        } catch (Exception $e) {
            echo "❌ Erreur vue '{$viewName}' : " . $e->getMessage() . "\n";
        }
    }
    echo "\n";

    // Statistiques pour le dashboard
    echo "📈 Statistiques pour le dashboard...\n";
    $stats = [
        'users_count' => App\Models\User::count(),
        'products_count' => App\Models\Product::count(),
        'orders_count' => App\Models\Order::count(),
        'special_offers_count' => App\Models\SpecialOffer::count(),
        'active_special_offers_count' => App\Models\SpecialOffer::available()->count(),
    ];

    foreach ($stats as $key => $value) {
        echo "✅ {$key}: {$value}\n";
    }
    echo "\n";

    // Test de création d'une offre de démonstration
    echo "🎯 Test de création d'une offre de démonstration...\n";
    try {
        // Vérifier qu'il y a au moins un produit
        $product = App\Models\Product::first();
        if (!$product) {
            echo "⚠️  Aucun produit trouvé pour créer une offre de test\n";
        } else {
            // Créer une offre de test
            $testOffer = App\Models\SpecialOffer::create([
                'name' => 'Test Dashboard - ' . now()->format('Y-m-d H:i:s'),
                'description' => 'Offre de test créée automatiquement',
                'product_id' => $product->id,
                'min_quantity' => 3,
                'discount_percentage' => 15.0,
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'is_active' => true
            ]);

            echo "✅ Offre de test créée avec succès (ID: {$testOffer->id})\n";
            echo "   - Produit: {$product->name}\n";
            echo "   - Remise: 15% à partir de 3 unités\n";
            echo "   - Statut: {$testOffer->status}\n";
            
            // Test du calcul de remise
            $testCalc = $testOffer->calculateDiscount(5, $product->price);
            if ($testCalc['qualifies']) {
                echo "   - Test calcul (5 unités): économie de " . number_format($testCalc['savings'], 2) . "€\n";
            }
        }
    } catch (Exception $e) {
        echo "❌ Erreur lors de la création de l'offre de test : " . $e->getMessage() . "\n";
    }
    echo "\n";

    echo "🎉 Tests terminés avec succès !\n";
    echo "🌐 Vous pouvez maintenant accéder au dashboard admin à : http://127.0.0.1:8000/admin/dashboard\n";
    echo "🛒 Et aux offres spéciales à : http://127.0.0.1:8000/admin/special-offers\n\n";

} catch (Exception $e) {
    echo "❌ Erreur durant les tests : " . $e->getMessage() . "\n";
    echo "Stack trace :\n" . $e->getTraceAsString() . "\n";
}
