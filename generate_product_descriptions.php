<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\Category;

// Descriptions par catégorie en français
$categoryDescriptions = [
    'fruits' => [
        'template' => '{name} de qualité exceptionnelle, {characteristics}. {usage}. {benefits}.',
        'characteristics' => [
            'cultivés localement avec soin',
            'sélectionnés à parfaite maturité',
            'riches en saveurs naturelles',
            'gorgés de vitamines et minéraux',
            'cueillis au meilleur moment'
        ],
        'usages' => [
            'Parfaits pour la consommation directe ou vos desserts',
            'Idéaux pour compotes, confitures et pâtisseries',
            'Excellents en salade de fruits ou smoothies',
            'Parfaits à déguster nature ou en cuisine',
            'Idéaux pour jus frais et préparations gourmandes'
        ],
        'benefits' => [
            'Riches en vitamines C et antioxydants',
            'Source naturelle de fibres et nutriments',
            'Apportent fraîcheur et énergie',
            'Excellents pour la santé au quotidien',
            'Allient plaisir gustatif et bienfaits nutritionnels'
        ]
    ],
    'legumes' => [
        'template' => '{name} frais et croquants, {characteristics}. {usage}. {benefits}.',
        'characteristics' => [
            'cultivés selon les méthodes traditionnelles',
            'récoltés à maturité optimale',
            'sélectionnés pour leur fraîcheur',
            'produits de saison authentiques',
            'issus de notre terroir local'
        ],
        'usages' => [
            'Parfaits pour vos préparations culinaires quotidiennes',
            'Idéaux pour salades, gratins et plats mijotés',
            'Excellents crus, cuits ou transformés',
            'Parfaits pour une cuisine saine et savoureuse',
            'Idéaux pour accompagner tous vos plats'
        ],
        'benefits' => [
            'Riches en vitamines et minéraux essentiels',
            'Source importante de fibres alimentaires',
            'Apportent fraîcheur et vitalité',
            'Excellents pour une alimentation équilibrée',
            'Contribuent à votre bien-être au quotidien'
        ]
    ],
    'cereales' => [
        'template' => '{name} biologiques de première qualité, {characteristics}. {usage}. {benefits}.',
        'characteristics' => [
            'cultivées sans pesticides ni engrais chimiques',
            'issues de l\'agriculture biologique certifiée',
            'soigneusement triées et nettoyées',
            'conditionnées dans le respect des normes',
            'provenant de producteurs sélectionnés'
        ],
        'usages' => [
            'Parfaites pour l\'alimentation animale et humaine',
            'Idéales pour la préparation de farines et bouillies',
            'Excellentes pour vos recettes traditionnelles',
            'Parfaites pour brassage et distillation',
            'Idéales pour semis et cultures diverses'
        ],
        'benefits' => [
            'Riches en protéines végétales et glucides complexes',
            'Source naturelle de fibres et vitamines B',
            'Apportent énergie durable et satiété',
            'Excellentes pour une nutrition complète',
            'Base alimentaire saine et nourrissante'
        ]
    ]
];

// Fonction pour générer une description
function generateDescription($productName, $categoryData) {
    $template = $categoryData['template'];
    $characteristics = $categoryData['characteristics'][array_rand($categoryData['characteristics'])];
    $usage = $categoryData['usages'][array_rand($categoryData['usages'])];
    $benefits = $categoryData['benefits'][array_rand($categoryData['benefits'])];
    
    return str_replace(
        ['{name}', '{characteristics}', '{usage}', '{benefits}'],
        [$productName, $characteristics, $usage, $benefits],
        $template
    );
}

echo "=== GENERATING PRODUCT DESCRIPTIONS ===\n\n";

$categories = Category::select('id', 'name', 'slug')->get();
$allDescriptions = [];

foreach ($categories as $category) {
    $products = Product::where('category_id', $category->id)->get();
    
    if ($products->isEmpty()) continue;
    
    echo "📂 Processing category: {$category->slug}\n";
    
    foreach ($products as $product) {
        // Générer le nom du produit (sans le code à la fin)
        $cleanName = preg_replace('/-[a-z0-9]{4}$/', '', $product->slug);
        $productName = ucwords(str_replace('-', ' ', $cleanName));
        
        // Déterminer le type de description basé sur la catégorie
        $categoryKey = $category->slug;
        
        if (in_array($categoryKey, ['fruits'])) {
            $description = generateDescription($productName, $categoryDescriptions['fruits']);
        } elseif (in_array($categoryKey, ['legumes'])) {
            $description = generateDescription($productName, $categoryDescriptions['legumes']);
        } elseif (in_array($categoryKey, ['cereales', 'feculents', 'semences'])) {
            $description = generateDescription($productName, $categoryDescriptions['cereales']);
        } else {
            // Description générique pour les autres catégories
            $description = "Produit {$productName} de qualité supérieure, sélectionné avec soin par nos experts. Conçu pour répondre aux exigences des professionnels et particuliers. Garantit performance, durabilité et satisfaction. Conforme aux normes de qualité les plus strictes pour vous offrir le meilleur.";
        }
        
        $allDescriptions[$product->slug] = $description;
        echo "  ✅ {$product->slug}: " . substr($description, 0, 80) . "...\n";
    }
    echo "\n";
}

// Générer le code PHP pour les descriptions
echo "=== GENERATING PHP CODE FOR FRENCH DESCRIPTIONS ===\n\n";

echo "// Add this to resources/lang/fr/app.php in product_descriptions array:\n\n";

foreach ($allDescriptions as $slug => $description) {
    echo "    '{$slug}' => '" . addslashes($description) . "',\n";
}

echo "\n=== SUMMARY ===\n";
echo "Generated " . count($allDescriptions) . " product descriptions\n";
