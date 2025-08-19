<?php

require_once 'vendor/autoload.php';

use App\Models\Product;
use App\Models\Category;
use App\Models\RentalCategory;
use Illuminate\Support\Str;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CRÉATION DE 80 PRODUITS D'ACHAT + 20 PRODUITS DE LOCATION ===\n\n";

// Récupérer les catégories existantes
$categories = Category::all()->keyBy('slug');
$rentalCategories = RentalCategory::all()->keyBy('slug');

echo "📂 Catégories disponibles : " . $categories->count() . "\n";
echo "📂 Catégories de location : " . $rentalCategories->count() . "\n\n";

// PRODUITS D'ACHAT (80 produits, type 'sale')
$saleProducts = [
    // FRUITS (20 produits) - Catégorie: fruits
    ['name' => ['fr' => 'Pommes Royal Gala', 'en' => 'Royal Gala Apples', 'nl' => 'Royal Gala Appels'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 3.50],
    ['name' => ['fr' => 'Pommes Granny Smith', 'en' => 'Granny Smith Apples', 'nl' => 'Granny Smith Appels'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 3.20],
    ['name' => ['fr' => 'Pommes Golden', 'en' => 'Golden Apples', 'nl' => 'Golden Appels'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 3.80],
    ['name' => ['fr' => 'Poires Williams', 'en' => 'Williams Pears', 'nl' => 'Williams Peren'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 4.20],
    ['name' => ['fr' => 'Poires Conference', 'en' => 'Conference Pears', 'nl' => 'Conference Peren'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 4.50],
    ['name' => ['fr' => 'Bananes Bio', 'en' => 'Organic Bananas', 'nl' => 'Bio Bananen'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 2.80],
    ['name' => ['fr' => 'Oranges Valencia', 'en' => 'Valencia Oranges', 'nl' => 'Valencia Sinaasappels'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 2.90],
    ['name' => ['fr' => 'Citrons Bio', 'en' => 'Organic Lemons', 'nl' => 'Bio Citroenen'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 3.40],
    ['name' => ['fr' => 'Kiwis Verts', 'en' => 'Green Kiwis', 'nl' => 'Groene Kiwi\'s'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 5.20],
    ['name' => ['fr' => 'Raisins Blancs', 'en' => 'White Grapes', 'nl' => 'Witte Druiven'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 4.80],
    ['name' => ['fr' => 'Raisins Noirs', 'en' => 'Black Grapes', 'nl' => 'Zwarte Druiven'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 5.10],
    ['name' => ['fr' => 'Fraises Bio', 'en' => 'Organic Strawberries', 'nl' => 'Bio Aardbeien'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 6.50],
    ['name' => ['fr' => 'Framboises', 'en' => 'Raspberries', 'nl' => 'Frambozen'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 8.20],
    ['name' => ['fr' => 'Myrtilles', 'en' => 'Blueberries', 'nl' => 'Bosbessen'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 7.80],
    ['name' => ['fr' => 'Pêches Jaunes', 'en' => 'Yellow Peaches', 'nl' => 'Gele Perziken'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 4.60],
    ['name' => ['fr' => 'Abricots Bio', 'en' => 'Organic Apricots', 'nl' => 'Bio Abrikozen'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 5.40],
    ['name' => ['fr' => 'Prunes Reines-Claudes', 'en' => 'Greengage Plums', 'nl' => 'Reine-Claude Pruimen'], 'category' => 'fruits', 'unit' => 'kg', 'price' => 4.20],
    ['name' => ['fr' => 'Melons Charentais', 'en' => 'Charentais Melons', 'nl' => 'Charentais Meloenen'], 'category' => 'fruits', 'unit' => 'pièce', 'price' => 3.80],
    ['name' => ['fr' => 'Pastèques Bio', 'en' => 'Organic Watermelons', 'nl' => 'Bio Watermeloenen'], 'category' => 'fruits', 'unit' => 'pièce', 'price' => 5.20],
    ['name' => ['fr' => 'Ananas Victoria', 'en' => 'Victoria Pineapples', 'nl' => 'Victoria Ananassen'], 'category' => 'fruits', 'unit' => 'pièce', 'price' => 4.50],

    // LÉGUMES (15 produits) - Catégorie: legumes
    ['name' => ['fr' => 'Carottes Bio', 'en' => 'Organic Carrots', 'nl' => 'Bio Wortelen'], 'category' => 'legumes', 'unit' => 'kg', 'price' => 2.40],
    ['name' => ['fr' => 'Pommes de terre', 'en' => 'Potatoes', 'nl' => 'Aardappelen'], 'category' => 'legumes', 'unit' => 'kg', 'price' => 1.80],
    ['name' => ['fr' => 'Tomates Cœur de Bœuf', 'en' => 'Beefsteak Tomatoes', 'nl' => 'Beefsteak Tomaten'], 'category' => 'legumes', 'unit' => 'kg', 'price' => 4.20],
    ['name' => ['fr' => 'Courgettes Vertes', 'en' => 'Green Zucchini', 'nl' => 'Groene Courgettes'], 'category' => 'legumes', 'unit' => 'kg', 'price' => 2.80],
    ['name' => ['fr' => 'Aubergines Bio', 'en' => 'Organic Eggplants', 'nl' => 'Bio Aubergines'], 'category' => 'legumes', 'unit' => 'kg', 'price' => 3.60],
    ['name' => ['fr' => 'Poivrons Rouges', 'en' => 'Red Bell Peppers', 'nl' => 'Rode Paprika\'s'], 'category' => 'legumes', 'unit' => 'kg', 'price' => 5.20],
    ['name' => ['fr' => 'Brocolis Frais', 'en' => 'Fresh Broccoli', 'nl' => 'Verse Broccoli'], 'category' => 'legumes', 'unit' => 'kg', 'price' => 3.40],
    ['name' => ['fr' => 'Choux-fleurs', 'en' => 'Cauliflower', 'nl' => 'Bloemkolen'], 'category' => 'legumes', 'unit' => 'pièce', 'price' => 2.80],
    ['name' => ['fr' => 'Épinards Bio', 'en' => 'Organic Spinach', 'nl' => 'Bio Spinazie'], 'category' => 'legumes', 'unit' => 'kg', 'price' => 4.60],
    ['name' => ['fr' => 'Salade Batavia', 'en' => 'Batavia Lettuce', 'nl' => 'Batavia Sla'], 'category' => 'legumes', 'unit' => 'pièce', 'price' => 1.80],
    ['name' => ['fr' => 'Concombres Bio', 'en' => 'Organic Cucumbers', 'nl' => 'Bio Komkommers'], 'category' => 'legumes', 'unit' => 'pièce', 'price' => 1.60],
    ['name' => ['fr' => 'Radis Roses', 'en' => 'Pink Radishes', 'nl' => 'Roze Radijsjes'], 'category' => 'legumes', 'unit' => 'kg', 'price' => 2.20],
    ['name' => ['fr' => 'Oignons Jaunes', 'en' => 'Yellow Onions', 'nl' => 'Gele Uien'], 'category' => 'legumes', 'unit' => 'kg', 'price' => 1.40],
    ['name' => ['fr' => 'Ail Violet', 'en' => 'Purple Garlic', 'nl' => 'Paarse Knoflook'], 'category' => 'legumes', 'unit' => 'kg', 'price' => 8.50],
    ['name' => ['fr' => 'Poireaux Bio', 'en' => 'Organic Leeks', 'nl' => 'Bio Prei'], 'category' => 'legumes', 'unit' => 'kg', 'price' => 3.20],

    // PRODUITS LAITIERS (8 produits) - Catégorie: produits-laitiers
    ['name' => ['fr' => 'Lait Entier Bio', 'en' => 'Organic Whole Milk', 'nl' => 'Bio Volle Melk'], 'category' => 'produits-laitiers', 'unit' => 'litre', 'price' => 1.45],
    ['name' => ['fr' => 'Beurre Fermier', 'en' => 'Farm Butter', 'nl' => 'Boeren Boter'], 'category' => 'produits-laitiers', 'unit' => 'kg', 'price' => 6.80],
    ['name' => ['fr' => 'Fromage Blanc', 'en' => 'Cottage Cheese', 'nl' => 'Kwark'], 'category' => 'produits-laitiers', 'unit' => 'kg', 'price' => 3.20],
    ['name' => ['fr' => 'Yaourt Nature Bio', 'en' => 'Organic Plain Yogurt', 'nl' => 'Bio Natuuryoghurt'], 'category' => 'produits-laitiers', 'unit' => 'kg', 'price' => 4.50],
    ['name' => ['fr' => 'Crème Fraîche', 'en' => 'Fresh Cream', 'nl' => 'Verse Room'], 'category' => 'produits-laitiers', 'unit' => 'litre', 'price' => 3.80],
    ['name' => ['fr' => 'Œufs Bio', 'en' => 'Organic Eggs', 'nl' => 'Bio Eieren'], 'category' => 'produits-laitiers', 'unit' => 'pièce', 'price' => 0.45],
    ['name' => ['fr' => 'Fromage de Chèvre', 'en' => 'Goat Cheese', 'nl' => 'Geitenkaas'], 'category' => 'produits-laitiers', 'unit' => 'kg', 'price' => 18.50],
    ['name' => ['fr' => 'Mozzarella Bio', 'en' => 'Organic Mozzarella', 'nl' => 'Bio Mozzarella'], 'category' => 'produits-laitiers', 'unit' => 'kg', 'price' => 12.80],

    // CÉRÉALES (8 produits) - Catégorie: cereales
    ['name' => ['fr' => 'Farine de Blé T65', 'en' => 'T65 Wheat Flour', 'nl' => 'T65 Tarwemeel'], 'category' => 'cereales', 'unit' => 'kg', 'price' => 2.20],
    ['name' => ['fr' => 'Farine d\'Épeautre', 'en' => 'Spelt Flour', 'nl' => 'Speltmeel'], 'category' => 'cereales', 'unit' => 'kg', 'price' => 3.80],
    ['name' => ['fr' => 'Avoine Bio', 'en' => 'Organic Oats', 'nl' => 'Bio Haver'], 'category' => 'cereales', 'unit' => 'kg', 'price' => 2.60],
    ['name' => ['fr' => 'Orge Perlé', 'en' => 'Pearl Barley', 'nl' => 'Parelgerst'], 'category' => 'cereales', 'unit' => 'kg', 'price' => 3.40],
    ['name' => ['fr' => 'Quinoa Bio', 'en' => 'Organic Quinoa', 'nl' => 'Bio Quinoa'], 'category' => 'cereales', 'unit' => 'kg', 'price' => 8.50],
    ['name' => ['fr' => 'Sarrasin Bio', 'en' => 'Organic Buckwheat', 'nl' => 'Bio Boekweit'], 'category' => 'cereales', 'unit' => 'kg', 'price' => 4.20],
    ['name' => ['fr' => 'Millet Bio', 'en' => 'Organic Millet', 'nl' => 'Bio Gierst'], 'category' => 'cereales', 'unit' => 'kg', 'price' => 3.90],
    ['name' => ['fr' => 'Blé Dur Bio', 'en' => 'Organic Durum Wheat', 'nl' => 'Bio Durumtarwe'], 'category' => 'cereales', 'unit' => 'kg', 'price' => 2.80],

    // FÉCULENTS (8 produits) - Catégorie: feculents
    ['name' => ['fr' => 'Pâtes Complètes Bio', 'en' => 'Organic Whole Pasta', 'nl' => 'Bio Volkoren Pasta'], 'category' => 'feculents', 'unit' => 'kg', 'price' => 3.60],
    ['name' => ['fr' => 'Riz Basmati Bio', 'en' => 'Organic Basmati Rice', 'nl' => 'Bio Basmati Rijst'], 'category' => 'feculents', 'unit' => 'kg', 'price' => 4.80],
    ['name' => ['fr' => 'Riz Complet', 'en' => 'Brown Rice', 'nl' => 'Bruine Rijst'], 'category' => 'feculents', 'unit' => 'kg', 'price' => 3.20],
    ['name' => ['fr' => 'Lentilles Vertes', 'en' => 'Green Lentils', 'nl' => 'Groene Linzen'], 'category' => 'feculents', 'unit' => 'kg', 'price' => 4.50],
    ['name' => ['fr' => 'Lentilles Corail', 'en' => 'Red Lentils', 'nl' => 'Rode Linzen'], 'category' => 'feculents', 'unit' => 'kg', 'price' => 5.20],
    ['name' => ['fr' => 'Haricots Blancs', 'en' => 'White Beans', 'nl' => 'Witte Bonen'], 'category' => 'feculents', 'unit' => 'kg', 'price' => 3.80],
    ['name' => ['fr' => 'Pois Chiches Bio', 'en' => 'Organic Chickpeas', 'nl' => 'Bio Kikkererwten'], 'category' => 'feculents', 'unit' => 'kg', 'price' => 4.20],
    ['name' => ['fr' => 'Polenta Bio', 'en' => 'Organic Polenta', 'nl' => 'Bio Polenta'], 'category' => 'feculents', 'unit' => 'kg', 'price' => 3.60],

    // GRAINES (6 produits) - Catégorie: semences
    ['name' => ['fr' => 'Graines de Tournesol', 'en' => 'Sunflower Seeds', 'nl' => 'Zonnebloempitten'], 'category' => 'semences', 'unit' => 'kg', 'price' => 4.20],
    ['name' => ['fr' => 'Graines de Courge', 'en' => 'Pumpkin Seeds', 'nl' => 'Pompoenpitten'], 'category' => 'semences', 'unit' => 'kg', 'price' => 8.50],
    ['name' => ['fr' => 'Graines de Lin', 'en' => 'Flax Seeds', 'nl' => 'Lijnzaad'], 'category' => 'semences', 'unit' => 'kg', 'price' => 6.80],
    ['name' => ['fr' => 'Graines de Chia', 'en' => 'Chia Seeds', 'nl' => 'Chia Zaden'], 'category' => 'semences', 'unit' => 'kg', 'price' => 12.50],
    ['name' => ['fr' => 'Graines de Sésame', 'en' => 'Sesame Seeds', 'nl' => 'Sesamzaad'], 'category' => 'semences', 'unit' => 'kg', 'price' => 7.20],
    ['name' => ['fr' => 'Graines de Pavot', 'en' => 'Poppy Seeds', 'nl' => 'Maanzaad'], 'category' => 'semences', 'unit' => 'kg', 'price' => 15.80],

    // ENGRAIS (7 produits) - Catégorie: engrais
    ['name' => ['fr' => 'Compost Bio', 'en' => 'Organic Compost', 'nl' => 'Bio Compost'], 'category' => 'engrais', 'unit' => 'kg', 'price' => 0.85],
    ['name' => ['fr' => 'Fumier de Cheval', 'en' => 'Horse Manure', 'nl' => 'Paardenmest'], 'category' => 'engrais', 'unit' => 'kg', 'price' => 0.60],
    ['name' => ['fr' => 'Engrais NPK Bio', 'en' => 'Organic NPK Fertilizer', 'nl' => 'Bio NPK Meststof'], 'category' => 'engrais', 'unit' => 'kg', 'price' => 2.40],
    ['name' => ['fr' => 'Corne Broyée', 'en' => 'Ground Horn', 'nl' => 'Gemalen Hoorn'], 'category' => 'engrais', 'unit' => 'kg', 'price' => 3.80],
    ['name' => ['fr' => 'Sang Séché', 'en' => 'Dried Blood', 'nl' => 'Gedroogd Bloed'], 'category' => 'engrais', 'unit' => 'kg', 'price' => 4.20],
    ['name' => ['fr' => 'Guano d\'Oiseaux', 'en' => 'Bird Guano', 'nl' => 'Vogelguano'], 'category' => 'engrais', 'unit' => 'kg', 'price' => 5.60],
    ['name' => ['fr' => 'Algues Marines', 'en' => 'Seaweed Fertilizer', 'nl' => 'Zeewier Meststof'], 'category' => 'engrais', 'unit' => 'kg', 'price' => 3.20],
];

echo "🛒 CRÉATION DES PRODUITS D'ACHAT:\n";
$createdSale = 0;

foreach ($saleProducts as $index => $productData) {
    try {
        $category = $categories[$productData['category']];
        if (!$category) {
            echo "❌ Catégorie '{$productData['category']}' non trouvée\n";
            continue;
        }

        $product = Product::create([
            'name' => $productData['name'],
            'slug' => Str::slug($productData['name']['fr']),
            'sku' => 'SALE-' . strtoupper(Str::random(6)),
            'description' => [
                'fr' => "Produit {$productData['name']['fr']} de qualité biologique, cultivé selon les standards de l'agriculture durable.",
                'en' => "High-quality {$productData['name']['en']} product, grown according to sustainable agriculture standards.",
                'nl' => "Hoogwaardige {$productData['name']['nl']} product, geteeld volgens duurzame landbouwstandaarden."
            ],
            'short_description' => [
                'fr' => "Produit frais et bio de qualité supérieure.",
                'en' => "Fresh and organic product of superior quality.",
                'nl' => "Vers en biologisch product van superieure kwaliteit."
            ],
            'price' => $productData['price'],
            'quantity' => rand(10, 100),
            'unit_symbol' => $productData['unit'],
            'type' => 'sale', // TYPE SALE UNIQUEMENT
            'is_active' => true,
            'is_featured' => rand(1, 10) <= 2, // 20% de chance d'être en vedette
            'category_id' => $category->id,
            'meta_title' => [
                'fr' => "Achat {$productData['name']['fr']} Bio - FarmShop",
                'en' => "Buy {$productData['name']['en']} Organic - FarmShop",
                'nl' => "Koop {$productData['name']['nl']} Bio - FarmShop"
            ],
            'meta_description' => [
                'fr' => "Achetez {$productData['name']['fr']} bio de qualité supérieure sur FarmShop. Livraison rapide et produits frais garantis.",
                'en' => "Buy organic {$productData['name']['en']} of superior quality on FarmShop. Fast delivery and guaranteed fresh products.",
                'nl' => "Koop biologische {$productData['name']['nl']} van superieure kwaliteit op FarmShop. Snelle levering en gegarandeerd verse producten."
            ],
            'critical_threshold' => 5,
            'low_stock_threshold' => 15,
        ]);

        $createdSale++;
        echo "✅ Produit d'achat {$createdSale}: {$productData['name']['fr']} (Catégorie: {$category->name})\n";

    } catch (Exception $e) {
        echo "❌ Erreur produit {$index}: " . $e->getMessage() . "\n";
    }
}

echo "\n📊 RÉSUMÉ PRODUITS D'ACHAT:\n";
echo "- Créés avec succès: {$createdSale}/80\n";
echo "- Type: 'sale' uniquement\n";
echo "- Unités: kg, litre, pièce selon le produit\n";
echo "- Traductions: FR/EN/NL complètes\n\n";

echo "=== CRÉATION TERMINÉE ===\n";
echo "Les produits d'achat ont été créés selon vos spécifications exactes.\n";
