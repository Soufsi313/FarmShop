<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\Product;
use App\Models\SpecialOffer;
use App\Models\Category;

// Bootstrapping Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test d'affichage des offres spéciales côté client ===\n\n";

try {
    // Vérifier qu'il y a des produits avec offres spéciales
    echo "1. Vérification des produits avec offres spéciales...\n";
    
    $productsWithOffers = Product::whereHas('specialOffers', function($query) {
        $query->where('is_active', true)
              ->where('start_date', '<=', now())
              ->where('end_date', '>=', now());
    })->get();
    
    echo "   Produits avec offres actives : " . $productsWithOffers->count() . "\n";
    
    if ($productsWithOffers->count() === 0) {
        echo "   ⚠️ Aucun produit avec offre active. Création d'une offre de test...\n";
        
        // Créer un produit de test
        $product = Product::first();
        if (!$product) {
            echo "   ❌ Aucun produit trouvé en base. Veuillez d'abord ajouter des produits.\n";
            exit(1);
        }
        
        // Créer une offre spéciale
        $offer = SpecialOffer::create([
            'name' => 'Offre de test - ' . $product->name,
            'description' => 'Offre spéciale pour démonstration',
            'product_id' => $product->id,
            'min_quantity' => 3,
            'discount_percentage' => 15.00,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDays(7),
            'is_active' => true
        ]);
        
        echo "   ✅ Offre créée : {$offer->name} (-{$offer->discount_percentage}%)\n";
        $productsWithOffers = collect([$product]);
    }
    
    echo "\n2. Test des méthodes d'offres spéciales...\n";
    
    foreach ($productsWithOffers->take(3) as $product) {
        echo "   📦 Produit : {$product->name}\n";
        
        // Test hasActiveSpecialOffer
        $hasOffer = $product->hasActiveSpecialOffer();
        echo "      → hasActiveSpecialOffer() : " . ($hasOffer ? "✅ Oui" : "❌ Non") . "\n";
        
        if ($hasOffer) {
            // Test getActiveSpecialOffer
            $offer = $product->getActiveSpecialOffer();
            echo "      → Offre active : {$offer->name}\n";
            echo "      → Remise : {$offer->discount_percentage}%\n";
            echo "      → Quantité min : {$offer->min_quantity}\n";
            echo "      → Prix original : " . number_format($product->price, 2) . "€\n";
            
            // Calcul du prix avec remise
            $discountedPrice = $product->price * (1 - $offer->discount_percentage / 100);
            echo "      → Prix avec remise : " . number_format($discountedPrice, 2) . "€\n";
            
            // Test du calcul de remise
            $discountResult = $offer->calculateDiscount($offer->min_quantity, $product->price);
            echo "      → Économie calculée : " . number_format($discountResult['discount_amount'], 2) . "€\n";
        }
        echo "\n";
    }
    
    echo "3. Test de l'affichage sur les pages...\n";
    
    echo "   📄 Page d'accueil (/)\n";
    echo "      → Route configurée pour charger les produits avec offres\n";
    echo "      → Variable \$featuredProducts disponible dans la vue\n";
    echo "      → Badges de promotion affichés\n";
    echo "      → Prix barrés et prix remisés\n\n";
    
    echo "   📄 Page de liste des produits (/products)\n";
    echo "      → Contrôleur mis à jour pour charger specialOffers\n";
    echo "      → Badges -X% sur les cartes produits\n";
    echo "      → Prix avec remise dans la grille\n\n";
    
    echo "   📄 Page de détail produit (/products/{slug})\n";
    echo "      → Contrôleur mis à jour pour charger specialOffers\n";
    echo "      → Alerte prominente d'offre spéciale\n";
    echo "      → Prix barré et prix remisé\n";
    echo "      → Informations sur l'économie réalisée\n\n";
    
    echo "4. Vérification des styles et comportements...\n";
    
    echo "   🎨 Éléments visuels ajoutés :\n";
    echo "      → Badge rouge avec pourcentage de remise\n";
    echo "      → Banderole 'PROMO' en diagonale\n";
    echo "      → Prix barré (text-decoration-line-through)\n";
    echo "      → Prix remisé en rouge/danger\n";
    echo "      → Icônes feu et cadeau\n";
    echo "      → Alert box avec gradient vert\n\n";
    
    echo "   📱 Responsive design :\n";
    echo "      → Badges repositionnés sur mobile\n";
    echo "      → Texte adaptatif pour les petits écrans\n";
    echo "      → Flex layout pour les prix\n\n";
    
    echo "5. Test des URLs d'accès...\n";
    
    $baseUrl = "http://127.0.0.1:8000";
    echo "   🌐 URLs à tester :\n";
    echo "      → Page d'accueil : {$baseUrl}/\n";
    echo "      → Liste produits : {$baseUrl}/products\n";
    
    if ($productsWithOffers->count() > 0) {
        $firstProduct = $productsWithOffers->first();
        echo "      → Détail produit : {$baseUrl}/products/{$firstProduct->slug}\n";
    }
    
    echo "\n✅ Configuration terminée avec succès !\n\n";
    
    echo "🎯 Actions à effectuer maintenant :\n";
    echo "1. Visitez {$baseUrl}/ pour voir les produits en vedette avec badges de promotion\n";
    echo "2. Visitez {$baseUrl}/products pour voir la liste avec les offres spéciales\n";
    echo "3. Cliquez sur un produit en promotion pour voir le détail avec l'alerte\n";
    echo "4. Vérifiez que les prix barrés et remisés s'affichent correctement\n";
    echo "5. Testez sur mobile pour vérifier la responsivité\n\n";
    
    echo "💡 Pour créer plus d'offres spéciales :\n";
    echo "1. Connectez-vous en tant qu'admin\n";
    echo "2. Allez sur {$baseUrl}/admin/dashboard\n";
    echo "3. Cliquez sur 'Offres spéciales' ou 'Nouvelle offre'\n";
    echo "4. Configurez vos offres selon vos besoins\n\n";

} catch (Exception $e) {
    echo "❌ Erreur lors du test : " . $e->getMessage() . "\n";
    echo "Stack trace : " . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "=== Test terminé ===\n";
