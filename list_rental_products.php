<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "Produits de location pour traduction :\n";
echo "=====================================\n\n";

$rentalProducts = Product::where('is_active', true)
    ->where('is_rental_available', true)
    ->whereIn('type', ['rental', 'both'])
    ->where('rental_stock', '>', 0)
    ->with('rentalCategory')
    ->orderBy('slug')
    ->get(['id', 'name', 'slug', 'rental_category_id']);

// Fonction pour convertir slug en nom français
function slugToFrenchName($slug) {
    $name = str_replace('-', ' ', $slug);
    $name = preg_replace('/\d+$/', '', $name); // Enlever les numéros à la fin
    $name = trim($name);
    return ucwords($name);
}

// Générer les traductions FR/EN/NL
$translations = [
    'fr' => [],
    'en' => [],
    'nl' => []
];

foreach ($rentalProducts as $product) {
    $baseSlug = preg_replace('/-\d+$/', '', $product->slug); // Enlever le numéro à la fin
    $frenchName = slugToFrenchName($product->slug);
    
    echo "Slug: {$product->slug}\n";
    echo "Nom FR proposé: {$frenchName}\n";
    
    // Traductions basées sur les mots-clés
    $englishName = $frenchName;
    $dutchName = $frenchName;
    
    // Dictionnaire de traductions pour les termes courants
    $translations_dict = [
        'bache' => ['en' => 'tarp', 'nl' => 'dekzeil'],
        'protection' => ['en' => 'protection', 'nl' => 'bescherming'],
        'beche' => ['en' => 'spade', 'nl' => 'spade'],
        'professionnelle' => ['en' => 'professional', 'nl' => 'professioneel'],
        'broyeur' => ['en' => 'shredder', 'nl' => 'versnipperaar'],
        'vegetaux' => ['en' => 'vegetation', 'nl' => 'planten'],
        'composteur' => ['en' => 'composter', 'nl' => 'composter'],
        'rotatif' => ['en' => 'rotating', 'nl' => 'roterende'],
        'debroussailleuse' => ['en' => 'brush cutter', 'nl' => 'bosmaaier'],
        'echafaudage' => ['en' => 'scaffolding', 'nl' => 'steiger'],
        'mobile' => ['en' => 'mobile', 'nl' => 'mobiele'],
        'filet' => ['en' => 'net', 'nl' => 'net'],
        'anti' => ['en' => 'anti', 'nl' => 'anti'],
        'insectes' => ['en' => 'insects', 'nl' => 'insecten'],
        'fourche' => ['en' => 'fork', 'nl' => 'vork'],
        'becher' => ['en' => 'digging', 'nl' => 'graven'],
        'houe' => ['en' => 'hoe', 'nl' => 'schoffel'],
        'oscillante' => ['en' => 'oscillating', 'nl' => 'oscillerende'],
        'motoculteur' => ['en' => 'cultivator', 'nl' => 'motorfrees'],
        'electrique' => ['en' => 'electric', 'nl' => 'elektrische'],
        'pioche' => ['en' => 'pickaxe', 'nl' => 'houweel'],
        'terrassement' => ['en' => 'earthwork', 'nl' => 'grondwerk'],
        'rateau' => ['en' => 'rake', 'nl' => 'hark'],
        'dents' => ['en' => 'teeth', 'nl' => 'tanden'],
        'droites' => ['en' => 'straight', 'nl' => 'rechte'],
        'remorque' => ['en' => 'trailer', 'nl' => 'aanhanger'],
        'basculante' => ['en' => 'tilting', 'nl' => 'kiep'],
        'scarificateur' => ['en' => 'scarifier', 'nl' => 'verticuteermachine'],
        'secateur' => ['en' => 'pruner', 'nl' => 'snoeischaar'],
        'pneumatique' => ['en' => 'pneumatic', 'nl' => 'pneumatische'],
        'serfouette' => ['en' => 'weeding hoe', 'nl' => 'wiedschoffel'],
        'serre' => ['en' => 'greenhouse', 'nl' => 'kas'],
        'tunnel' => ['en' => 'tunnel', 'nl' => 'tunnel'],
        'souffleur' => ['en' => 'blower', 'nl' => 'blazer'],
        'feuilles' => ['en' => 'leaves', 'nl' => 'bladeren'],
        'tondeuse' => ['en' => 'mower', 'nl' => 'maaier'],
        'autoportee' => ['en' => 'ride-on', 'nl' => 'zitmaaier'],
        'tronconneuse' => ['en' => 'chainsaw', 'nl' => 'kettingzaag'],
        'thermique' => ['en' => 'thermal', 'nl' => 'benzine']
    ];
    
    // Appliquer les traductions
    $words = explode(' ', strtolower($frenchName));
    $englishWords = [];
    $dutchWords = [];
    
    foreach ($words as $word) {
        $englishWords[] = $translations_dict[$word]['en'] ?? $word;
        $dutchWords[] = $translations_dict[$word]['nl'] ?? $word;
    }
    
    $englishName = ucwords(implode(' ', $englishWords));
    $dutchName = ucwords(implode(' ', $dutchWords));
    
    echo "Nom EN: {$englishName}\n";
    echo "Nom NL: {$dutchName}\n";
    echo "---\n";
    
    $translations['fr'][$product->slug] = $frenchName;
    $translations['en'][$product->slug] = $englishName;
    $translations['nl'][$product->slug] = $dutchName;
}

echo "\nCode à ajouter dans les fichiers de langue :\n";
echo "============================================\n\n";

echo "// FRANÇAIS - À ajouter dans resources/lang/fr/app.php dans 'product_names'\n";
foreach ($translations['fr'] as $slug => $name) {
    echo "'{$slug}' => '{$name}',\n";
}

echo "\n// ANGLAIS - À ajouter dans resources/lang/en/app.php dans 'product_names'\n";
foreach ($translations['en'] as $slug => $name) {
    echo "'{$slug}' => '{$name}',\n";
}

echo "\n// NÉERLANDAIS - À ajouter dans resources/lang/nl/app.php dans 'product_names'\n";
foreach ($translations['nl'] as $slug => $name) {
    echo "'{$slug}' => '{$name}',\n";
}
