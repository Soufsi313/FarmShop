<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

// Mappings de traduction pour les expressions communes
$frenchToEnglish = [
    "Merci pour ces conseils précieux !" => "Thank you for this valuable advice!",
    "Mon grand-père utilisait déjà certaines de ces techniques, c'est du bon sens." => "My grandfather already used some of these techniques, it's common sense.",
    "Merci pour ce guide pratique !" => "Thank you for this practical guide!",
    "J'ai enfin trouvé la solution à mon problème de ravageurs." => "I finally found the solution to my pest problem.",
    "J'ai testé cette technique l'année dernière et les résultats sont spectaculaires." => "I tested this technique last year and the results are spectacular.",
    "Je recommande vivement !" => "I highly recommend!",
    "Merci pour ce partage d'expérience !" => "Thank you for sharing your experience!",
    "Vos conseils sont toujours pertinents et pratiques." => "Your advice is always relevant and practical.",
    "Merci pour ce guide complet !" => "Thank you for this comprehensive guide!",
    "Mes voisins sont impressionnés par les résultats de mon potager." => "My neighbors are impressed by the results of my vegetable garden.",
    "Super article !" => "Great article!",
    "Mon potager n'a jamais été aussi productif depuis que j'applique ces principes." => "My vegetable garden has never been so productive since I apply these principles.",
    "Formidable explication !" => "Wonderful explanation!",
    "Les étapes sont détaillées et faciles à suivre pour un novice comme moi." => "The steps are detailed and easy to follow for a novice like me.",
    "Très bon article" => "Very good article",
    "j'ajouterais juste qu'il faut adapter selon le type de sol." => "I would just add that you need to adapt according to the soil type.",
    "Dans ma région argileuse, j'ai dû modifier légèrement." => "In my clay region, I had to modify slightly.",
    "Très bon conseil !" => "Very good advice!",
    "Cette approche économique et écologique est parfaite pour les petits budgets." => "This economical and ecological approach is perfect for small budgets.",
    "Très enrichissant !" => "Very enriching!",
    "Cette approche respectueuse de l'environnement correspond parfaitement à mes valeurs." => "This environmentally respectful approach perfectly matches my values."
];

$frenchToDutch = [
    "Merci pour ces conseils précieux !" => "Dank je voor dit waardevolle advies!",
    "Mon grand-père utilisait déjà certaines de ces techniques, c'est du bon sens." => "Mijn grootvader gebruikte al sommige van deze technieken, het is gezond verstand.",
    "Merci pour ce guide pratique !" => "Dank je voor deze praktische gids!",
    "J'ai enfin trouvé la solution à mon problème de ravageurs." => "Ik heb eindelijk de oplossing voor mijn ongedierteprobleem gevonden.",
    "J'ai testé cette technique l'année dernière et les résultats sont spectaculaires." => "Ik heb deze techniek vorig jaar getest en de resultaten zijn spectaculair.",
    "Je recommande vivement !" => "Ik beveel het ten zeerste aan!",
    "Merci pour ce partage d'expérience !" => "Dank je voor het delen van je ervaring!",
    "Vos conseils sont toujours pertinents et pratiques." => "Uw adviezen zijn altijd relevant en praktisch.",
    "Merci pour ce guide complet !" => "Dank je voor deze uitgebreide gids!",
    "Mes voisins sont impressionnés par les résultats de mon potager." => "Mijn buren zijn onder de indruk van de resultaten van mijn moestuin.",
    "Super article !" => "Geweldig artikel!",
    "Mon potager n'a jamais été aussi productif depuis que j'applique ces principes." => "Mijn moestuin is nog nooit zo productief geweest sinds ik deze principes toepas.",
    "Formidable explication !" => "Prachtige uitleg!",
    "Les étapes sont détaillées et faciles à suivre pour un novice comme moi." => "De stappen zijn gedetailleerd en makkelijk te volgen voor een beginner zoals ik.",
    "Très bon article" => "Zeer goed artikel",
    "j'ajouterais juste qu'il faut adapter selon le type de sol." => "ik zou er alleen aan toevoegen dat je moet aanpassen volgens het grondtype.",
    "Dans ma région argileuse, j'ai dû modifier légèrement." => "In mijn kleigebied moest ik licht aanpassen.",
    "Très bon conseil !" => "Zeer goed advies!",
    "Cette approche économique et écologique est parfaite pour les petits budgets." => "Deze economische en ecologische benadering is perfect voor kleine budgetten.",
    "Très enrichissant !" => "Zeer verrijkend!",
    "Cette approche respectueuse de l'environnement correspond parfaitement à mes valeurs." => "Deze milieuvriendelijke benadering past perfect bij mijn waarden."
];

// Fonction pour traduire un texte
function translateText($text, $translations) {
    $translated = $text;
    
    foreach ($translations as $french => $foreign) {
        $translated = str_replace($french, $foreign, $translated);
    }
    
    // Si pas de traduction exacte, utiliser des patterns simples
    if ($translated === $text) {
        // Patterns basiques pour des phrases courantes
        $patterns = [
            '/^Merci (.+)$/' => ['en' => 'Thank you $1', 'nl' => 'Dank je $1'],
            '/^Super (.+)$/' => ['en' => 'Great $1', 'nl' => 'Geweldig $1'],
            '/^Très (.+)$/' => ['en' => 'Very $1', 'nl' => 'Zeer $1'],
            '/^Formidable (.+)$/' => ['en' => 'Wonderful $1', 'nl' => 'Prachtig $1'],
        ];
        
        foreach ($patterns as $pattern => $replacements) {
            if (preg_match($pattern, $text)) {
                $lang = (strpos(json_encode($translations), 'Thank') !== false) ? 'en' : 'nl';
                $translated = preg_replace($pattern, $replacements[$lang], $text);
                break;
            }
        }
    }
    
    return $translated;
}

echo "=== TRADUCTION DES COMMENTAIRES ===\n\n";

// Commencer la traduction
$comments = \App\Models\BlogComment::all();
$processed = 0;

foreach ($comments as $comment) {
    $englishContent = translateText($comment->content, $frenchToEnglish);
    $dutchContent = translateText($comment->content, $frenchToDutch);
    
    echo "ID: {$comment->id}\n";
    echo "FR: " . $comment->content . "\n";
    echo "EN: " . $englishContent . "\n";
    echo "NL: " . $dutchContent . "\n";
    echo "---\n";
    
    $processed++;
    if ($processed >= 10) {
        echo "Arrêt après 10 commentaires pour test...\n";
        break;
    }
}

echo "\nTotal traité: $processed commentaires\n";
