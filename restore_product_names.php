<?php

require_once 'vendor/autoload.php';

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== RESTAURATION DES NOMS DE PRODUITS ===\n\n";

// Données des fruits (d'après CustomProductSeeder)
$fruitsData = [
    'Pommes Rouges Royal Gala',
    'Pommes Vertes Granny Smith', 
    'Pommes Jaunes Golden Delicious',
    'Pommes Rouges Red Delicious',
    'Pommes Jonagold',
    'Poires Williams',
    'Poires Conference', 
    'Poires Doyenné du Comice',
    'Prunes Reines-Claudes',
    'Prunes Quetsches',
    'Prunes Mirabelles',
    'Pêches Jaunes',
    'Pêches Blanches',
    'Pêches de Vigne',
    'Kiwis Verts Hayward',
    'Kiwis Jaunes Gold',
    'Abricots Bergeron',
    'Abricots Rouge du Roussillon',
    'Bananes Cavendish',
    'Bananes Plantain',
    'Raisins Blancs Chasselas',
    'Raisins Noirs Muscat',
    'Raisins Rouges Red Globe'
];

// Données des légumes
$vegetablesData = [
    'Carottes Nantaises',
    'Pommes de terre Bintje',
    'Tomates Cœur de Bœuf',
    'Courgettes Vertes',
    'Haricots Verts Extra-Fins',
    'Petits Pois Fins',
    'Épinards Frais',
    'Salade Iceberg',
    'Radis Roses',
    'Brocolis Verts',
    'Choux-Fleurs Blancs',
    'Poireaux du Nord',
    'Oignons Jaunes',
    'Ail Blanc',
    'Betteraves Rouges',
    'Navets Blancs',
    'Panais',
    'Céleris-Raves',
    'Endives Belges',
    'Mâche',
    'Roquette',
    'Concombres',
    'Aubergines Violettes',
    'Poivrons Rouges',
    'Poivrons Verts',
    'Poivrons Jaunes'
];

// Données des produits laitiers
$dairyData = [
    'Lait Entier Bio',
    'Beurre Fermier',
    'Yaourt Nature',
    'Fromage Blanc',
    'Crème Fraîche Épaisse',
    'Œufs Fermiers'
];

// Données des produits non-alimentaires
$nonFoodData = [
    'Sac en Toile Réutilisable',
    'Panier en Osier',
    'Livre de Recettes Bio'
];

// Données des céréales
$cerealsData = [
    'Farine de Blé T65',
    'Farine de Seigle',
    'Avoine Bio',
    'Orge Perlé',
    'Quinoa Bio',
    'Riz Complet Bio'
];

// Données des graines
$seedsData = [
    'Graines de Tournesol',
    'Graines de Courge',
    'Graines de Lin',
    'Graines de Chia'
];

// Données des engrais
$fertilizersData = [
    'Compost Bio',
    'Fumier de Cheval',
    'Engrais Naturel NPK'
];

// Données des féculents
$starchyData = [
    'Pâtes Complètes Bio',
    'Riz Basmati Bio',
    'Lentilles Vertes'
];

// Combiner toutes les données de vente
$allSaleProducts = array_merge(
    $fruitsData, 
    $vegetablesData, 
    $dairyData, 
    $nonFoodData, 
    $cerealsData, 
    $seedsData, 
    $fertilizersData, 
    $starchyData
);

// Données des produits de location
$rentalData = [
    'Bêche professionnelle',
    'Serfouette 3 dents',
    'Râteau à dents droites',
    'Pioche de terrassement',
    'Fourche à bêcher',
    'Sécateur pneumatique',
    'Houe oscillante',
    'Motoculteur Honda',
    'Tronçonneuse Stihl',
    'Débroussailleuse',
    'Tondeuse autoportée',
    'Souffleur de feuilles',
    'Taille-haie électrique',
    'Scarificateur',
    'Bâche de protection 6x4m',
    'Serre tunnel 3x2m',
    'Système d\'irrigation goutte-à-goutte',
    'Pulvérisateur à dos',
    'Filet anti-insectes',
    'Voile d\'hivernage'
];

echo "Début de la restauration...\n\n";

// Restaurer les produits de vente
$saleProducts = Product::where('type', 'sale')->orderBy('id')->get();
$saleIndex = 0;

foreach ($saleProducts as $product) {
    if ($saleIndex < count($allSaleProducts)) {
        $newName = $allSaleProducts[$saleIndex];
        echo "Produit ID {$product->id} (SKU: {$product->sku}) -> '{$newName}'\n";
        
        $product->update([
            'name' => $newName,
            'slug' => Str::slug($newName),
            'description' => "Description pour {$newName}. Produit de qualité biologique."
        ]);
        
        $saleIndex++;
    }
}

// Restaurer les produits de location
$rentalProducts = Product::where('type', 'rental')->orderBy('id')->get();
$rentalIndex = 0;

foreach ($rentalProducts as $product) {
    if ($rentalIndex < count($rentalData)) {
        $newName = $rentalData[$rentalIndex];
        echo "Produit Location ID {$product->id} (SKU: {$product->sku}) -> '{$newName}'\n";
        
        $product->update([
            'name' => $newName,
            'slug' => Str::slug($newName),
            'description' => "Description pour {$newName}. Matériel de location professionnel."
        ]);
        
        $rentalIndex++;
    }
}

echo "\n=== RESTAURATION TERMINÉE ===\n";
echo "Produits de vente restaurés: {$saleIndex}\n";
echo "Produits de location restaurés: {$rentalIndex}\n";
