<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

// Dictionnaire complet de traduction
$translations = [
    // Phrases d'appréciation
    "Merci pour ces conseils précieux !" => [
        'en' => "Thank you for this valuable advice!",
        'nl' => "Dank je voor dit waardevolle advies!"
    ],
    "Mon grand-père utilisait déjà certaines de ces techniques, c'est du bon sens." => [
        'en' => "My grandfather already used some of these techniques, it's common sense.",
        'nl' => "Mijn grootvader gebruikte al sommige van deze technieken, het is gezond verstand."
    ],
    "Merci pour ce guide pratique !" => [
        'en' => "Thank you for this practical guide!",
        'nl' => "Dank je voor deze praktische gids!"
    ],
    "J'ai enfin trouvé la solution à mon problème de ravageurs." => [
        'en' => "I finally found the solution to my pest problem.",
        'nl' => "Ik heb eindelijk de oplossing voor mijn ongedierteprobleem gevonden."
    ],
    "J'ai testé cette technique l'année dernière et les résultats sont spectaculaires." => [
        'en' => "I tested this technique last year and the results are spectacular.",
        'nl' => "Ik heb deze techniek vorig jaar getest en de resultaten zijn spectaculair."
    ],
    "Je recommande vivement !" => [
        'en' => "I highly recommend!",
        'nl' => "Ik beveel het ten zeerste aan!"
    ],
    "Merci pour ce partage d'expérience !" => [
        'en' => "Thank you for sharing your experience!",
        'nl' => "Dank je voor het delen van je ervaring!"
    ],
    "Vos conseils sont toujours pertinents et pratiques." => [
        'en' => "Your advice is always relevant and practical.",
        'nl' => "Uw adviezen zijn altijd relevant en praktisch."
    ],
    "Merci pour ce guide complet !" => [
        'en' => "Thank you for this comprehensive guide!",
        'nl' => "Dank je voor deze uitgebreide gids!"
    ],
    "Mes voisins sont impressionnés par les résultats de mon potager." => [
        'en' => "My neighbors are impressed by the results of my vegetable garden.",
        'nl' => "Mijn buren zijn onder de indruk van de resultaten van mijn moestuin."
    ],
    "Super article !" => [
        'en' => "Great article!",
        'nl' => "Geweldig artikel!"
    ],
    "Mon potager n'a jamais été aussi productif depuis que j'applique ces principes." => [
        'en' => "My vegetable garden has never been so productive since I apply these principles.",
        'nl' => "Mijn moestuin is nog nooit zo productief geweest sinds ik deze principes toepas."
    ],
    "Formidable explication !" => [
        'en' => "Wonderful explanation!",
        'nl' => "Prachtige uitleg!"
    ],
    "Les étapes sont détaillées et faciles à suivre pour un novice comme moi." => [
        'en' => "The steps are detailed and easy to follow for a novice like me.",
        'nl' => "De stappen zijn gedetailleerd en makkelijk te volgen voor een beginner zoals ik."
    ],
    "Très bon article, j'ajouterais juste qu'il faut adapter selon le type de sol." => [
        'en' => "Very good article, I would just add that you need to adapt according to the soil type.",
        'nl' => "Zeer goed artikel, ik zou er alleen aan toevoegen dat je moet aanpassen volgens het grondtype."
    ],
    "Dans ma région argileuse, j'ai dû modifier légèrement." => [
        'en' => "In my clay region, I had to modify slightly.",
        'nl' => "In mijn kleigebied moest ik licht aanpassen."
    ],
    "Très bon conseil !" => [
        'en' => "Very good advice!",
        'nl' => "Zeer goed advies!"
    ],
    "Cette approche économique et écologique est parfaite pour les petits budgets." => [
        'en' => "This economical and ecological approach is perfect for small budgets.",
        'nl' => "Deze economische en ecologische benadering is perfect voor kleine budgetten."
    ],
    "Très enrichissant !" => [
        'en' => "Very enriching!",
        'nl' => "Zeer verrijkend!"
    ],
    "Cette approche respectueuse de l'environnement correspond parfaitement à mes valeurs." => [
        'en' => "This environmentally respectful approach perfectly matches my values.",
        'nl' => "Deze milieuvriendelijke benadering past perfect bij mijn waarden."
    ]
];

// Traductions supplémentaires pour couvrir plus de commentaires
$additionalTranslations = [
    "Excellent article" => [
        'en' => "Excellent article",
        'nl' => "Uitstekend artikel"
    ],
    "Parfait pour débuter" => [
        'en' => "Perfect for beginners",
        'nl' => "Perfect om te beginnen"
    ],
    "J'ai appliqué ces conseils avec succès" => [
        'en' => "I applied this advice successfully",
        'nl' => "Ik heb dit advies succesvol toegepast"
    ],
    "Mes récoltes ont doublé" => [
        'en' => "My harvests have doubled",
        'nl' => "Mijn oogsten zijn verdubbeld"
    ],
    "Technique révolutionnaire" => [
        'en' => "Revolutionary technique",
        'nl' => "Revolutionaire techniek"
    ],
    "Économique et efficace" => [
        'en' => "Economical and efficient",
        'nl' => "Economisch en efficiënt"
    ],
    "Respectueux de l'environnement" => [
        'en' => "Environmentally friendly",
        'nl' => "Milieuvriendelijk"
    ],
    "Facile à mettre en œuvre" => [
        'en' => "Easy to implement",
        'nl' => "Makkelijk te implementeren"
    ],
    "Résultats visibles rapidement" => [
        'en' => "Results visible quickly",
        'nl' => "Resultaten snel zichtbaar"
    ],
    "Technique ancestrale efficace" => [
        'en' => "Effective ancestral technique",
        'nl' => "Effectieve voorouderlijke techniek"
    ]
];

$translations = array_merge($translations, $additionalTranslations);

// Fonction de traduction intelligente
function smartTranslate($content, $translations) {
    $result = ['en' => $content, 'nl' => $content];
    
    // Recherche exacte d'abord
    if (isset($translations[$content])) {
        return $translations[$content];
    }
    
    // Recherche partielle pour les phrases composées
    foreach ($translations as $french => $translation) {
        if (strpos($content, $french) !== false) {
            $result['en'] = str_replace($french, $translation['en'], $result['en']);
            $result['nl'] = str_replace($french, $translation['nl'], $result['nl']);
        }
    }
    
    // Patterns pour les expressions courantes
    $patterns = [
        [
            'pattern' => '/^Merci (.+)$/',
            'en' => 'Thank you $1',
            'nl' => 'Dank je $1'
        ],
        [
            'pattern' => '/^Super (.+)$/',
            'en' => 'Great $1',
            'nl' => 'Geweldig $1'
        ],
        [
            'pattern' => '/^Très (.+)$/',
            'en' => 'Very $1',
            'nl' => 'Zeer $1'
        ],
        [
            'pattern' => '/^Excellent (.+)$/',
            'en' => 'Excellent $1',
            'nl' => 'Uitstekend $1'
        ],
        [
            'pattern' => '/^Parfait (.+)$/',
            'en' => 'Perfect $1',
            'nl' => 'Perfect $1'
        ]
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern['pattern'], $content)) {
            $result['en'] = preg_replace($pattern['pattern'], $pattern['en'], $content);
            $result['nl'] = preg_replace($pattern['pattern'], $pattern['nl'], $content);
            break;
        }
    }
    
    return $result;
}

echo "=== MISE À JOUR DES COMMENTAIRES AVEC TRADUCTIONS ===\n\n";

$comments = \App\Models\BlogComment::all();
$updated = 0;
$errors = 0;

foreach ($comments as $comment) {
    try {
        $translations = smartTranslate($comment->content, $translations);
        
        // Mise à jour des métadonnées avec les traductions
        $metadata = $comment->metadata ? json_decode($comment->metadata, true) : [];
        $metadata['translations'] = [
            'fr' => $comment->content, // Langue originale
            'en' => $translations['en'],
            'nl' => $translations['nl']
        ];
        
        $comment->metadata = json_encode($metadata);
        $comment->save();
        
        echo "✓ Commentaire {$comment->id} traduit\n";
        $updated++;
        
    } catch (Exception $e) {
        echo "✗ Erreur pour le commentaire {$comment->id}: " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n=== RÉSUMÉ ===\n";
echo "Commentaires mis à jour: $updated\n";
echo "Erreurs: $errors\n";
echo "Total traité: " . $comments->count() . "\n";
