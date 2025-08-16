<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "=== GENERATING REMAINING PRODUCT DESCRIPTIONS ===\n\n";

// Get categories we haven't covered yet
$coveredProducts = [
    // Fruits (28 products) - DONE
    'pommes-rouges-royal-gala-ozjz', 'pommes-vertes-granny-smith-n1xl', 'pommes-jaunes-golden-delicious-dwhr',
    'pommes-rouges-red-delicious-sqpl', 'pommes-jonagold-hqsp', 'poires-williams-55ca', 'poires-conference-jhik',
    'poires-doyenne-du-comice-pwoo', 'prunes-reines-claudes-v56w', 'prunes-quetsches-jokl', 'prunes-mirabelles-cnnv',
    'peches-jaunes-bxmp', 'peches-blanches-eo5q', 'peches-de-vigne-34hm', 'kiwis-verts-hayward-o1t4',
    'kiwis-jaunes-gold-jees', 'abricots-bergeron-arik', 'abricots-rouge-du-roussillon-elrw', 'bananes-cavendish-xldb',
    'bananes-plantain-kluc', 'raisins-blancs-chasselas-knsr', 'raisins-noirs-muscat-xsbg', 'raisins-rouges-red-globe-txzd',
    'cerises-burlat-tjmm', 'fraises-gariguette-dzku', 'framboises-p56e', 'myrtilles-zjkr', 'mures-mh4v',
    
    // Vegetables (9 products) - DONE  
    'carottes-bio-my8d', 'tomates-cerises-gk5f', 'salade-iceberg-kc1z', 'courgettes-vertes-uhhi',
    'brocolis-zpfz', 'choux-fleurs-io1i', 'epinards-frais-dwlo', 'haricots-verts-h7vg', 'poireaux-5i0n',
    
    // Cereals + Starches (8 products) - DONE
    'avoine-bio-25kg-ie46', 'orge-perle-bio-20kg-v3ba', 'ble-tendre-bio-25kg-5bwb', 'mais-grain-bio-30kg-d0sz',
    'pommes-de-terre-bintje-5kg-iagq', 'pommes-de-terre-charlotte-3kg-0hah', 'patates-douces-bio-2kg-pfya',
    'topinambours-bio-15kg-krq5'
];

// Get all products
$allProducts = Product::all();
$remainingProducts = $allProducts->reject(function($product) use ($coveredProducts) {
    return in_array($product->slug, $coveredProducts);
});

echo "Total products: " . $allProducts->count() . "\n";
echo "Already covered: " . count($coveredProducts) . "\n";  
echo "Remaining: " . $remainingProducts->count() . "\n\n";

// Group by category for easier management
$productsByCategory = $remainingProducts->groupBy('category_id');

foreach ($productsByCategory as $categoryId => $products) {
    $category = Category::find($categoryId);
    echo "📂 Category: {$category->slug} ({$products->count()} products)\n";
    
    foreach ($products as $product) {
        echo "  - {$product->slug}\n";
    }
    echo "\n";
}

// Generate descriptions for remaining products
echo "=== GENERATING GENERIC DESCRIPTIONS ===\n\n";

$descriptions = [
    'fr' => [],
    'en' => [],
    'nl' => []
];

foreach ($remainingProducts as $product) {
    $category = Category::find($product->category_id);
    $cleanName = preg_replace('/-[a-z0-9]{4}$/', '', $product->slug);
    $productName = ucwords(str_replace('-', ' ', $cleanName));
    
    // Generate based on category type
    switch ($category->slug) {
        case 'produits-laitiers':
            $descriptions['fr'][$product->slug] = "Produit laitier {$productName} de qualité premium, issu de l'agriculture biologique. Sélectionné pour sa fraîcheur et ses qualités nutritionnelles exceptionnelles. Riche en protéines et calcium, il contribue à une alimentation saine et équilibrée.";
            $descriptions['en'][$product->slug] = "Premium {$productName} dairy product from organic farming. Selected for its freshness and exceptional nutritional qualities. Rich in proteins and calcium, it contributes to a healthy and balanced diet.";
            $descriptions['nl'][$product->slug] = "Premium zuivelproduct {$productName} uit biologische landbouw. Geselecteerd voor zijn frisheid en uitzonderlijke voedingswaarde. Rijk aan eiwitten en calcium, draagt het bij aan een gezonde en evenwichtige voeding.";
            break;
            
        case 'outils-agricoles':
            $descriptions['fr'][$product->slug] = "Outil agricole {$productName} professionnel, conçu pour durer et performer. Fabriqué avec des matériaux de haute qualité pour répondre aux exigences des agriculteurs. Ergonomique et robuste, il facilite vos travaux au quotidien.";
            $descriptions['en'][$product->slug] = "Professional {$productName} agricultural tool, designed to last and perform. Made with high-quality materials to meet farmers' requirements. Ergonomic and robust, it facilitates your daily work.";
            $descriptions['nl'][$product->slug] = "Professioneel landbouwgereedschap {$productName}, ontworpen om lang mee te gaan en te presteren. Gemaakt met hoogwaardige materialen om aan de eisen van boeren te voldoen. Ergonomisch en robuust, vergemakkelijkt het uw dagelijkse werk.";
            break;
            
        case 'machines':
            $descriptions['fr'][$product->slug] = "Machine agricole {$productName} haute performance, alliant puissance et fiabilité. Conçue pour les professionnels exigeants, elle garantit efficacité et durabilité. Technologie avancée pour optimiser vos rendements agricoles.";
            $descriptions['en'][$product->slug] = "High-performance {$productName} agricultural machine, combining power and reliability. Designed for demanding professionals, it guarantees efficiency and durability. Advanced technology to optimize your agricultural yields.";
            $descriptions['nl'][$product->slug] = "Hoogpresterende landbouwmachine {$productName}, die kracht en betrouwbaarheid combineert. Ontworpen voor veeleisende professionals, garandeert het efficiëntie en duurzaamheid. Geavanceerde technologie om uw landbouwopbrengsten te optimaliseren.";
            break;
            
        case 'equipement':
            $descriptions['fr'][$product->slug] = "Équipement {$productName} polyvalent et résistant, adapté aux conditions agricoles intensives. Matériaux de qualité supérieure pour une longévité exceptionnelle. Facilite vos opérations et améliore votre productivité.";
            $descriptions['en'][$product->slug] = "Versatile and resistant {$productName} equipment, adapted to intensive agricultural conditions. Superior quality materials for exceptional longevity. Facilitates your operations and improves your productivity.";
            $descriptions['nl'][$product->slug] = "Veelzijdige en resistente {$productName} uitrusting, aangepast aan intensieve landbouwomstandigheden. Superieure kwaliteitsmaterialen voor uitzonderlijke levensduur. Vergemakkelijkt uw activiteiten en verbetert uw productiviteit.";
            break;
            
        case 'semences':
            $descriptions['fr'][$product->slug] = "Semences {$productName} biologiques certifiées, sélectionnées pour leur taux de germination élevé. Issues de variétés traditionnelles préservées, elles garantissent des récoltes saines et savoureuses. Parfaites pour l'agriculture durable.";
            $descriptions['en'][$product->slug] = "Certified organic {$productName} seeds, selected for their high germination rate. From preserved traditional varieties, they guarantee healthy and tasty harvests. Perfect for sustainable agriculture.";
            $descriptions['nl'][$product->slug] = "Gecertificeerde biologische {$productName} zaden, geselecteerd voor hun hoge kiemingspercentage. Van bewaarde traditionele variëteiten, garanderen ze gezonde en smakelijke oogsten. Perfect voor duurzame landbouw.";
            break;
            
        case 'engrais':
            $descriptions['fr'][$product->slug] = "Engrais {$productName} naturel et écologique, riche en nutriments essentiels. Améliore la structure du sol et stimule la croissance des plantes. Respectueux de l'environnement pour une agriculture responsable.";
            $descriptions['en'][$product->slug] = "Natural and ecological {$productName} fertilizer, rich in essential nutrients. Improves soil structure and stimulates plant growth. Environmentally friendly for responsible agriculture.";
            $descriptions['nl'][$product->slug] = "Natuurlijke en ecologische {$productName} meststof, rijk aan essentiële voedingsstoffen. Verbetert de bodemstructuur en stimuleert plantengroei. Milieuvriendelijk voor verantwoorde landbouw.";
            break;
            
        case 'irrigation':
            $descriptions['fr'][$product->slug] = "Système d'irrigation {$productName} efficace et économique, conçu pour optimiser l'arrosage. Technologie moderne pour une gestion précise de l'eau. Durable et facile d'installation pour tous types de cultures.";
            $descriptions['en'][$product->slug] = "Efficient and economical {$productName} irrigation system, designed to optimize watering. Modern technology for precise water management. Durable and easy to install for all types of crops.";
            $descriptions['nl'][$product->slug] = "Efficiënt en economisch {$productName} irrigatiesysteem, ontworpen om de bewatering te optimaliseren. Moderne technologie voor nauwkeurig waterbeheer. Duurzaam en eenvoudig te installeren voor alle soorten gewassen.";
            break;
            
        case 'protections':
            $descriptions['fr'][$product->slug] = "Protection {$productName} efficace pour cultures, résistante aux intempéries. Matériau de qualité professionnelle pour une protection optimale. Facile à installer et à entretenir pour préserver vos récoltes.";
            $descriptions['en'][$product->slug] = "Effective {$productName} crop protection, weather resistant. Professional quality material for optimal protection. Easy to install and maintain to preserve your harvests.";
            $descriptions['nl'][$product->slug] = "Effectieve {$productName} gewasbescherming, weerbestendig. Professioneel kwaliteitsmateriaal voor optimale bescherming. Eenvoudig te installeren en te onderhouden om uw oogsten te behouden.";
            break;
            
        default:
            $descriptions['fr'][$product->slug] = "Produit {$productName} de qualité supérieure, sélectionné par nos experts. Conçu pour répondre aux besoins des professionnels et particuliers. Garantit performance, durabilité et satisfaction.";
            $descriptions['en'][$product->slug] = "Superior quality {$productName} product, selected by our experts. Designed to meet the needs of professionals and individuals. Guarantees performance, durability and satisfaction.";
            $descriptions['nl'][$product->slug] = "Superieure kwaliteit {$productName} product, geselecteerd door onze experts. Ontworpen om te voldoen aan de behoeften van professionals en particulieren. Garandeert prestaties, duurzaamheid en tevredenheid.";
    }
}

echo "Generated descriptions for " . count($descriptions['fr']) . " remaining products\n\n";

// Output for copy-paste into language files
echo "=== FRENCH DESCRIPTIONS TO ADD ===\n";
foreach ($descriptions['fr'] as $slug => $desc) {
    echo "    '{$slug}' => '" . addslashes($desc) . "',\n";
}

echo "\n=== ENGLISH DESCRIPTIONS TO ADD ===\n"; 
foreach ($descriptions['en'] as $slug => $desc) {
    echo "    '{$slug}' => '" . addslashes($desc) . "',\n";
}

echo "\n=== DUTCH DESCRIPTIONS TO ADD ===\n";
foreach ($descriptions['nl'] as $slug => $desc) {
    echo "    '{$slug}' => '" . addslashes($desc) . "',\n";
}

echo "\n=== GENERATION COMPLETED ===\n";
