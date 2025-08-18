<?php
/**
 * Générateur Fichier 1 - Introduction et Présentation FarmShop
 * Version corrigée sans caractères spéciaux
 */

require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\Language;

// --- Helpers to ensure text is UTF-8 safe for Word (remove control chars and emojis that may break document) ---
function sanitizeText($text)
{
    if (!is_string($text)) {
        return $text;
    }

    // Ensure the string is valid UTF-8
    $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

    // Remove invalid XML characters (allowed: tab, lf, cr, and #x20-#xD7FF, #xE000-#xFFFD)
    // This prevents invalid bytes from ending up in document.xml inside the .docx archive.
    $text = preg_replace('/[^\\x{9}\\x{A}\\x{D}\\x{20}-\\x{D7FF}\\x{E000}-\\x{FFFD}]/u', '', $text);

    // Remove surrogate pairs / emojis (codepoints above U+FFFF) — some Word versions choke on them in docx XML
    // This removes 4-byte UTF-8 sequences.
    $text = preg_replace('/[\\x{10000}-\\x{10FFFF}]/u', '', $text);

    // Remove other C0 control characters except standard whitespace (safeguard)
    $text = preg_replace('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/u', '', $text);

    return $text;
}

function safeAddText($section, $text, $style = null, $paragraphStyle = null)
{
    $text = sanitizeText($text);
    if ($style === null && $paragraphStyle === null) {
        return $section->addText($text);
    }
    return $section->addText($text, $style, $paragraphStyle);
}

function safeAddTitle($section, $text, $depth)
{
    $text = sanitizeText($text);
    return $section->addTitle($text, $depth);
}

function createFichier1()
{
    $phpWord = new PhpWord();
    
    // Configuration du document
    $properties = $phpWord->getDocInfo();
    $properties->setCreator('Soufiane MEFTAH');
    $properties->setTitle('FarmShop - Rapport Final - Partie 1');
    $properties->setDescription('Introduction et Presentation du projet FarmShop');
    $properties->setSubject('E-commerce agricole avec systeme de location');
    $properties->setKeywords('FarmShop, Laravel, e-commerce, agriculture, location');
    
    $phpWord->setDefaultFontName('Inter');
    $phpWord->setDefaultFontSize(12);
    
    // Styles
    $phpWord->addTitleStyle(1, [
        'name' => 'Inter',
        'size' => 18,
        'bold' => true,
        'color' => '2d5016'
    ], [
        'spaceAfter' => Converter::cmToTwip(0.5),
        'spaceBefore' => Converter::cmToTwip(1),
        'keepNext' => true
    ]);
    
    $phpWord->addTitleStyle(2, [
        'name' => 'Inter', 
        'size' => 16,
        'bold' => true,
        'color' => '8b4513'
    ], [
        'spaceAfter' => Converter::cmToTwip(0.3),
        'spaceBefore' => Converter::cmToTwip(0.8)
    ]);
    
    $phpWord->addTitleStyle(3, [
        'name' => 'Inter', 
        'size' => 14,
        'bold' => true,
        'color' => 'ea580c'
    ], [
        'spaceAfter' => Converter::cmToTwip(0.2),
        'spaceBefore' => Converter::cmToTwip(0.5)
    ]);
    
    $phpWord->addParagraphStyle('Normal', [
        'alignment' => 'both',
        'spaceAfter' => Converter::cmToTwip(0.3),
        'lineHeight' => 1.15,
        'indent' => 0
    ]);
    
    $phpWord->addParagraphStyle('Centered', [
        'alignment' => 'center',
        'spaceAfter' => Converter::cmToTwip(0.5)
    ]);
    
    $phpWord->addParagraphStyle('Encadre', [
        'alignment' => 'both',
        'spaceAfter' => Converter::cmToTwip(0.5),
        'spaceBefore' => Converter::cmToTwip(0.5),
        'lineHeight' => 1.1,
        'borderTopSize' => 6,
        'borderTopColor' => '2d5016',
        'borderBottomSize' => 6,
        'borderBottomColor' => '2d5016',
        'indent' => Converter::cmToTwip(0.5)
    ]);
    
    // === PAGE DE GARDE ===
    $section = $phpWord->addSection([
        'marginTop' => Converter::cmToTwip(3),
        'marginBottom' => Converter::cmToTwip(3),
        'marginLeft' => Converter::cmToTwip(2.5),
        'marginRight' => Converter::cmToTwip(2.5)
    ]);
    
    $section->addTextBreak(3);
    
    safeAddText($section, 'RAPPORT FINAL', [
        'name' => 'Inter',
        'size' => 24,
        'bold' => true,
        'color' => '2d5016'
    ], 'Centered');
    
    $section->addTextBreak(1);
    
    safeAddText($section, 'FARMSHOP', [
        'name' => 'Inter',
        'size' => 32,
        'bold' => true,
        'color' => '2d5016'
    ], 'Centered');
    
    $section->addTextBreak(1);
    
    safeAddText($section, 'L\'agriculture flexible, de l\'achat a la location en un clic', [
        'name' => 'Inter',
        'size' => 16,
        'italic' => true,
        'color' => '8b4513'
    ], 'Centered');
    
    $section->addTextBreak(2);
    
    safeAddText($section, 'Plateforme e-commerce agricole', [
        'name' => 'Inter',
        'size' => 14,
        'bold' => true
    ], 'Centered');
    
    safeAddText($section, 'avec systeme de location integre', [
        'name' => 'Inter',
        'size' => 14,
        'bold' => true
    ], 'Centered');
    
    $section->addTextBreak(3);
    
    safeAddText($section, 'Presente par :', [
        'name' => 'Inter',
        'size' => 12,
        'bold' => true
    ], 'Centered');
    
    safeAddText($section, 'Soufiane MEFTAH & Geoffrey VIGNE', [
        'name' => 'Inter',
        'size' => 14,
        'bold' => true,
        'color' => '2d5016'
    ], 'Centered');
    
    $section->addTextBreak(1);
    
    safeAddText($section, 'Technologies principales :', [
        'name' => 'Inter',
        'size' => 11,
        'bold' => true
    ], 'Centered');
    
    safeAddText($section, 'Laravel 11.45.1 - PHP 8.4.10 - MariaDB 11.5.2', [
        'name' => 'Inter',
        'size' => 10
    ], 'Centered');
    
    safeAddText($section, 'Tailwind CSS 4.1.11 - Alpine.js 3.14.9 - Stripe 17.4', [
        'name' => 'Inter',
        'size' => 10
    ], 'Centered');
    
    $section->addTextBreak(3);
    
    safeAddText($section, 'Annee academique 2024-2025', [
        'name' => 'Inter',
        'size' => 12,
        'bold' => true
    ], 'Centered');
    
    safeAddText($section, '17 aout 2025', [
        'name' => 'Inter',
        'size' => 12
    ], 'Centered');
    
    $section->addPageBreak();
    
    // === TABLE DES MATIERES ===
    safeAddTitle($section, 'TABLE DES MATIERES', 1);
    
    $contents = [
        '1. REMERCIEMENTS ............................................. 3',
        '2. GLOSSAIRE TECHNIQUE ........................................ 4',
        '3. INTRODUCTION ............................................... 8', 
        '4. SYNOPSIS DU PROJET ......................................... 11',
        '5. CAHIER DE CHARGES .......................................... 15',
        '6. ORGANIGRAMME DES TACHES .................................... 18',
        '7. ORGANIGRAMME PREVISIONNEL .................................. 20',
        '8. BUSINESS PLAN .............................................. 22',
        '9. PRESENTATION DES PORTEURS DU PROJET ........................ 25',
        '10. OBJECTIFS DU PROJET ....................................... 27',
        '11. AVANCEMENT DU PROJET ...................................... 30',
        '12. DEFIS RENCONTRES .......................................... 33',
        '13. ETUDES DE MARCHE .......................................... 36',
        '14. ANALYSE CONCURRENTIELLE ................................... 40',
        '15. STRATEGIE MARKETING ....................................... 44',
        '16. ASPECTS TECHNIQUES ........................................ 48',
        '17. FONCTIONNALITES ET SECURITE ............................... 52',
        '18. ETUDE DES RISQUES ......................................... 56',
        '19. ANALYSE SWOT .............................................. 60',
        '20. PLAN FINANCIER ............................................ 64',
        '21. CONCEPTION GRAPHIQUE ...................................... 68',
        '22. STRATEGIE DE SECURITE ..................................... 72',
        '23. ASPECTS JURIDIQUES ........................................ 76',
        '24. CONCLUSIONS ............................................... 80',
        '25. BIBLIOGRAPHIE ............................................. 84',
        '26. ANNEXES ................................................... 86'
    ];
    
    foreach ($contents as $item) {
        safeAddText($section, $item, [
            'name' => 'Inter',
            'size' => 11
        ], 'Normal');
    }
    
    $section->addPageBreak();
    
    // === 1. REMERCIEMENTS ===
    safeAddTitle($section, '1. REMERCIEMENTS', 1);
    
    safeAddText($section, 'Au terme de ce travail de fin d\'etudes consacre au developpement de la plateforme FarmShop, je tiens a exprimer ma profonde gratitude envers toutes les personnes qui ont contribue a la reussite de ce projet ambitieux.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddTitle($section, '1.1 Equipe pedagogique', 2);
    
    safeAddText($section, 'Mes remerciements les plus sinceres s\'adressent en premier lieu a l\'equipe pedagogique exceptionnelle qui m\'a accompagne tout au long de cette formation :', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $remerciements_equipe = [
        'Monsieur RUTH, pour son expertise technique inegalable et ses conseils avises en architecture logicielle qui ont grandement influence la conception technique de FarmShop.',
        'Madame VANCRAYENST, pour son accompagnement methodologique rigoureux et sa vision strategique qui ont permis de structurer efficacement ce projet d\'envergure.',
        'Monsieur VERBIST, pour ses precieux enseignements en gestion de projet et sa disponibilite constante lors des phases critiques du developpement.',
        'Monsieur VANDOOREN, pour son expertise en bases de donnees et ses conseils techniques qui ont optimise la performance de notre architecture.',
        'Monsieur CIULLO, pour son soutien pedagogique constant et sa capacite a transmettre des connaissances complexes avec clarte et passion.'
    ];
    
    foreach ($remerciements_equipe as $remerciement) {
        safeAddText($section, '- ' . $remerciement, [
            'name' => 'Inter',
            'size' => 12
        ], 'Normal');
    }
    
    safeAddTitle($section, '1.2 Soutien familial', 2);
    
    safeAddText($section, 'Je souhaite egalement exprimer ma reconnaissance la plus profonde envers ma famille, pilier indéfectible de ma reussite. Leur soutien inconditionnel, leurs encouragements constants et leur patience durant les longues heures de developpement ont ete essentiels a l\'aboutissement de ce projet. Leur confiance en mes capacites m\'a donne la force de perseverer dans les moments les plus difficiles et de repousser sans cesse les limites de l\'innovation.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddTitle($section, '1.3 Support administratif', 2);
    
    safeAddText($section, 'Mes remerciements s\'etendent egalement au secretariat de l\'etablissement pour son professionnalisme exemplaire, sa reactivity et son efficacite dans la gestion administrative de ce projet. Leur support logistique a grandement facilite le bon deroulement de cette formation et la realisation de ce travail de fin d\'etudes.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddTitle($section, '1.4 Partenaire de projet', 2);
    
    safeAddText($section, 'Enfin, je tiens a remercier chaleureusement Geoffrey VIGNE, mon partenaire de projet et co-fondateur de FarmShop. Sa passion pour l\'agriculture, son expertise technique et sa vision entrepreneuriale ont ete determinantes dans la conception et le developpement de cette plateforme innovante. Notre collaboration fructueuse a permis de creer une solution technique robuste repondant aux veritables besoins du secteur agricole.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddText($section, 'Ce projet n\'aurait pas pu voir le jour sans l\'ensemble de ces contributions precieuses. Qu\'ils trouvent ici l\'expression de ma gratitude la plus sincere.', [
        'name' => 'Inter',
        'size' => 12,
        'italic' => true
    ], 'Normal');
    
    $section->addPageBreak();
    
    // === 2. GLOSSAIRE TECHNIQUE ===
    safeAddTitle($section, '2. GLOSSAIRE TECHNIQUE', 1);
    
    safeAddText($section, 'Ce glossaire presente l\'ensemble des technologies, outils et concepts techniques utilises dans le developpement de la plateforme FarmShop. Chaque terme est defini de maniere exhaustive pour assurer une comprehension optimale du projet.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddTitle($section, '2.1 Technologies Backend', 2);
    
    $glossaire_backend = [
        ['Laravel 11.45.1', 'Framework PHP moderne et elegant base sur l\'architecture MVC (Model-View-Controller). Laravel offre un ecosysteme complet incluant l\'ORM Eloquent, le systeme de routage, la gestion des migrations, et de nombreux outils de productivite. Version 11.45.1 utilisee pour beneficier des dernieres optimisations de performance et fonctionnalites de securite.'],
        ['PHP 8.4.10', 'Langage de programmation serveur interprete, specialement concu pour le developpement web. PHP 8.4.10 apporte des ameliorations significatives en termes de performance (JIT compiler), typage strict, et nouvelles fonctionnalites orientees objet avancees.'],
        ['MariaDB 11.5.2', 'Systeme de gestion de base de donnees relationnelle (SGBDR) open source, fork de MySQL. MariaDB 11.5.2 offre une compatibilite totale avec MySQL tout en apportant des optimisations de performance et des fonctionnalites avancees pour les applications critiques.'],
        ['Eloquent ORM', 'Object-Relational Mapping integre a Laravel permettant d\'interagir avec la base de donnees via des objets PHP plutot que des requetes SQL brutes. Eloquent facilite la manipulation des donnees et assure la securite contre les injections SQL.'],
        ['Artisan CLI', 'Interface en ligne de commande de Laravel offrant de nombreuses commandes utiles pour le developpement, les migrations, la generation de code, et la maintenance de l\'application. Outil indispensable pour la productivite des developpeurs Laravel.'],
        ['Composer', 'Gestionnaire de dependances pour PHP permettant d\'installer, mettre a jour et gerer les bibliotheques externes. Composer assure la coherence des versions et simplifie la gestion des packages dans les projets PHP modernes.']
    ];
    
    foreach ($glossaire_backend as $terme) {
        safeAddText($section, $terme[0], [
            'name' => 'Inter',
            'size' => 12,
            'bold' => true,
            'color' => '2d5016'
        ], 'Normal');
        safeAddText($section, $terme[1], [
            'name' => 'Inter',
            'size' => 11
        ], 'Normal');
        $section->addTextBreak();
    }
    
    safeAddTitle($section, '2.2 Technologies Frontend', 2);
    
    $glossaire_frontend = [
        ['Tailwind CSS 4.1.11', 'Framework CSS utility-first revolutionnaire permettant de construire rapidement des interfaces utilisateur modernes. Contrairement aux frameworks traditionnels, Tailwind fournit des classes utilitaires de bas niveau pour composer des designs personnalises sans ecrire de CSS personnalise.'],
        ['Alpine.js 3.14.9', 'Framework JavaScript leger (seulement 10kb) offrant la reactivite de Vue.js ou React avec la simplicite d\'utilisation de jQuery. Alpine.js permet d\'ajouter des comportements interactifs directement dans le HTML avec une syntaxe declarative intuitive.'],
        ['Vite 5.0', 'Outil de build moderne et ultra-rapide pour les applications web. Vite utilise les modules ES natifs et offre un rechargement a chaud instantane, revolutionnant l\'experience de developpement frontend avec des temps de compilation reduits de 90%.'],
        ['PostCSS', 'Outil de transformation CSS extensible via des plugins JavaScript. PostCSS traite le CSS pour ajouter des fonctionnalites modernes, optimiser le code, et assurer la compatibilite cross-browser. Utilise conjointement avec Tailwind CSS pour l\'optimisation.'],
        ['NPM (Node Package Manager)', 'Gestionnaire de packages pour l\'ecosysteme JavaScript/Node.js. NPM permet d\'installer, gerer et publier des packages JavaScript. Utilise dans FarmShop pour gerer les dependances frontend et les outils de build.'],
        ['Blade Templates', 'Moteur de templates integrate a Laravel offrant une syntaxe elegante et puissante pour la generation de vues HTML. Blade permet l\'heritage de templates, les sections, et l\'inclusion de composants reutilisables tout en maintenant la lisibilite du code.']
    ];
    
    foreach ($glossaire_frontend as $terme) {
        safeAddText($section, $terme[0], [
            'name' => 'Inter',
            'size' => 12,
            'bold' => true,
            'color' => '8b4513'
        ], 'Normal');
        safeAddText($section, $terme[1], [
            'name' => 'Inter',
            'size' => 11
        ], 'Normal');
        $section->addTextBreak();
    }
    
    safeAddTitle($section, '2.3 Services et Integrations', 2);
    
    $glossaire_services = [
        ['Stripe 17.4', 'Plateforme de paiement en ligne leader mondial offrant une API complete pour traiter les paiements par carte bancaire, virements, et moyens de paiement alternatifs. Stripe 17.4 apporte des fonctionnalites avancees de gestion des abonnements et de prevention de la fraude.'],
        ['Git', 'Systeme de controle de version distribue permettant de suivre les modifications du code source, collaborer efficacement en equipe, et maintenir l\'historique complet du developpement. Git assure la tracabilite et la securite du code du projet FarmShop.'],
        ['Webhooks', 'Mecanisme de communication HTTP permettant a une application d\'envoyer des donnees en temps reel vers une autre application lors d\'evenements specifiques. Utilises dans FarmShop pour synchroniser les paiements Stripe et declencher des actions automatiques.'],
        ['API REST', 'Architecture de services web basee sur le protocole HTTP utilisant les methodes standard (GET, POST, PUT, DELETE) pour exposer des fonctionnalites. L\'API REST de FarmShop permet l\'integration avec des systemes tiers et le developpement d\'applications mobiles.'],
        ['SSL/TLS', 'Protocoles de securisation des communications sur Internet utilisant le chiffrement asymetrique. SSL/TLS assure la confidentialite et l\'integrite des donnees echangees entre les utilisateurs et la plateforme FarmShop.'],
        ['Redis', 'Base de donnees en memoire ultra-rapide utilisee pour le cache, les sessions utilisateur, et les files d\'attente. Redis ameliore significativement les performances de FarmShop en reduisant la charge sur la base de donnees principale.']
    ];
    
    foreach ($glossaire_services as $terme) {
        safeAddText($section, $terme[0], [
            'name' => 'Inter',
            'size' => 12,
            'bold' => true,
            'color' => 'ea580c'
        ], 'Normal');
        safeAddText($section, $terme[1], [
            'name' => 'Inter',
            'size' => 11
        ], 'Normal');
        $section->addTextBreak();
    }
    
    $section->addPageBreak();
    
    // === 3. INTRODUCTION ===
    safeAddTitle($section, '3. INTRODUCTION', 1);
    
    safeAddText($section, 'L\'agriculture moderne traverse une periode de transformation profonde, marquee par la digitalisation des processus, l\'evolution des besoins des exploitants, et l\'emergence de nouveaux modeles economiques. Dans ce contexte en perpetuelle mutation, l\'acces aux equipements agricoles represente un defi majeur pour les professionnels du secteur, qu\'ils soient petits producteurs ou grandes exploitations.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddTitle($section, '3.1 Contexte du projet', 2);
    
    safeAddText($section, 'Le secteur agricole europeen, et particulierement dans la region BENELUX, fait face a des defis economiques sans precedent. L\'augmentation constante du cout des equipements agricoles, combinee a leur sous-utilisation frequente, cree un paradoxe economique : les exploitants ont besoin d\'equipements modernes pour rester competitifs, mais ne peuvent souvent pas justifier financierement leur acquisition pour une utilisation occasionnelle.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $section->addText('Cette problematique est particulierement pregnante pour les equipements specialises tels que les machines de recolte specifiques, les outils de precision, ou les equipements de transformation. Un tracteur enjambeur viticole peut couter plusieurs centaines de milliers d\'euros mais n\'etre utilise que quelques semaines par an. Cette realite economique freine l\'innovation et limite l\'acces aux technologies modernes pour de nombreux exploitants.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddTitle($section, '3.2 Innovation et disruption', 2);
    
    safeAddText($section, 'FarmShop emerge comme une reponse innovante a cette problematique en proposant un modele economique hybride revolutionnaire : une plateforme e-commerce permettant a la fois l\'achat traditionnel et la location flexible d\'equipements agricoles. Cette approche disruptive s\'inscrit dans la tendance globale de l\'economie du partage et de la circularite, adaptee aux specificites du secteur agricole.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddText($section, 'L\'innovation majeure de FarmShop reside dans son systeme de location "same-day", permettant aux agriculteurs de louer des equipements pour une duree d\'une journee seulement. Cette fonctionnalite, unique sur le marche europeen, repond aux besoins ponctuels des exploitants tout en optimisant l\'utilisation du parc d\'equipements disponible.', [
        'name' => 'Inter',
        'size' => 12,
        'bold' => true,
        'color' => '2d5016'
    ], 'Encadre');
    
    safeAddTitle($section, '3.3 Vision technologique', 2);
    
    safeAddText($section, 'Le developpement de FarmShop s\'appuie sur une architecture technologique moderne et robuste, concue pour repondre aux exigences de performance, de securite, et de scalabilite d\'une plateforme e-commerce professionnelle. L\'utilisation de Laravel 11.45.1 comme framework principal garantit une base solide et evolutive, tandis que l\'integration de technologies frontend modernes comme Tailwind CSS et Alpine.js assure une experience utilisateur optimale.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddText($section, 'Cette approche technologique permet non seulement de repondre aux besoins actuels du marche, mais aussi d\'anticiper les evolutions futures du secteur agricole vers plus de digitalisation et d\'automatisation. La plateforme est concue pour s\'integrer facilement avec les systemes d\'information existants des exploitations agricoles et pour evoluer vers des fonctionnalites avancees telles que l\'intelligence artificielle predictive ou l\'Internet des Objets (IoT).', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddTitle($section, '3.4 Impact socio-economique', 2);
    
    safeAddText($section, 'Au-dela de ses aspects purement techniques, FarmShop porte une ambition socio-economique forte : democratiser l\'acces aux equipements agricoles modernes et contribuer a la durabilite du secteur agricole europeen. En facilitant le partage d\'equipements, la plateforme reduit l\'empreinte carbone globale du secteur tout en ameliorant la rentabilite des exploitations.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddText($section, 'Cette vision s\'inscrit parfaitement dans les objectifs du Pacte Vert europeen et des politiques agricoles communes visant a promouvoir une agriculture plus durable, plus efficace, et plus respectueuse de l\'environnement. FarmShop contribue ainsi a la transition ecologique du secteur agricole tout en preservant sa competitivite economique.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $section->addPageBreak();
    
    // === 4. SYNOPSIS DU PROJET ===
    safeAddTitle($section, '4. SYNOPSIS DU PROJET', 1);
    
    safeAddText($section, 'FarmShop est une plateforme e-commerce revolutionnaire dediee au secteur agricole, combinant vente traditionnelle et location flexible d\'equipements agricoles. Concue pour repondre aux besoins specifiques des exploitants agricoles, artisans ruraux, et particuliers passionnes d\'agriculture, cette solution digitale transforme radicalement l\'acces aux outils et equipements du secteur primaire.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddTitle($section, '4.1 Concept central', 2);
    
    safeAddText($section, 'Le concept de FarmShop repose sur une observation simple mais fondamentale : la plupart des equipements agricoles sont sous-utilises. Un tracteur specialise peut ne fonctionner que 200 heures par an sur les 8760 heures que compte une annee, soit un taux d\'utilisation inferieur a 3%. Cette sous-utilisation massive represente un gisement economique considerable qui peut beneficier a l\'ensemble de la filiere agricole.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddText($section, 'FarmShop capitalise sur cette realite en creant un ecosysteme digital ou les proprietaires d\'equipements peuvent monetiser leurs investissements en les proposant a la location, tandis que les utilisateurs occasionnels peuvent acceder a des equipements performants sans supporter le cout d\'acquisition. Cette approche collaborative genere de la valeur pour tous les acteurs de la chaine.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddTitle($section, '4.2 Public cible et segmentation', 2);
    
    safeAddText($section, 'La strategie de segmentation de FarmShop s\'articule autour de trois publics principaux, chacun ayant des besoins specifiques et des comportements d\'achat distincts :', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddTitle($section, '4.2.1 Exploitants agricoles professionnels', 3);
    
    safeAddText($section, 'Ce segment represente le coeur de cible de FarmShop. Il comprend les agriculteurs, eleveurs, viticulteurs, marachers, et autres professionnels du secteur primaire. Leurs besoins se caracterisent par :', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $besoins_professionnels = [
        'Acces ponctuel a des equipements specialises pour des operations specifiques (recolte, traitement, transformation)',
        'Optimisation des couts d\'exploitation en evitant l\'immobilisation de capitaux importants',
        'Flexibilite dans le choix des equipements selon les saisons et les cultures',
        'Possibilite de tester des equipements avant acquisition',
        'Mutualisation des couts entre plusieurs exploitations voisines'
    ];
    
    foreach ($besoins_professionnels as $besoin) {
        safeAddText($section, '- ' . $besoin, [
            'name' => 'Inter',
            'size' => 11
        ], 'Normal');
    }
    
    safeAddTitle($section, '4.2.2 Artisans et entrepreneurs ruraux', 3);
    
    safeAddText($section, 'Ce segment englobe les professionnels du batiment rural, paysagistes, entrepreneurs de travaux agricoles, et autres artisans intervenant dans l\'environnement rural. Leurs specificites incluent :', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $besoins_artisans = [
        'Besoins d\'equipements varies selon les chantiers et projets',
        'Contraintes logistiques liees a la mobilite et au transport',
        'Recherche d\'equipements fiables avec service apres-vente',
        'Gestion optimisee des couts selon la saisonnalite des activites',
        'Acces a des technologies modernes sans investissement lourd'
    ];
    
    foreach ($besoins_artisans as $besoin) {
        safeAddText($section, '- ' . $besoin, [
            'name' => 'Inter',
            'size' => 11
        ], 'Normal');
    }
    
    safeAddTitle($section, '4.2.3 Particuliers passionnes', 3);
    
    safeAddText($section, 'Ce segment comprend les proprietaires de jardins, potagers familiaux, petites exploitations de loisir, et amateurs d\'agriculture urbaine. Bien que representant un volume unitaire plus faible, ce marche presente un potentiel de croissance important avec l\'essor de l\'agriculture de proximite :', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $besoins_particuliers = [
        'Acces occasionnel a des outils de qualite professionnelle',
        'Solutions economiques pour l\'entretien de jardins et potagers',
        'Decouverte et apprentissage de nouvelles techniques agricoles',
        'Equipements adaptes aux petites surfaces et usages domestiques',
        'Service de proximite avec conseil et accompagnement'
    ];
    
    foreach ($besoins_particuliers as $besoin) {
        safeAddText($section, '- ' . $besoin, [
            'name' => 'Inter',
            'size' => 11
        ], 'Normal');
    }
    
    safeAddTitle($section, '4.3 Proposition de valeur unique', 2);
    
    safeAddText($section, 'FarmShop se distingue de la concurrence par une proposition de valeur unique articulee autour de quatre piliers fondamentaux :', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    safeAddText($section, 'FLEXIBILITE MAXIMALE : Le systeme de location same-day permet une adaptation en temps reel aux besoins operationnels, avec la possibilite de louer un equipement pour une journee, une semaine, ou plusieurs mois selon les necessites.', [
        'name' => 'Inter',
        'size' => 12,
        'bold' => true,
        'color' => '2d5016'
    ], 'Encadre');
    
    safeAddText($section, 'TECHNOLOGIE DE POINTE : L\'integration de Laravel 11.45.1, Tailwind CSS, et des APIs modernes garantit une experience utilisateur fluide, rapide, et intuitive, comparable aux meilleures plateformes e-commerce du marche.', [
        'name' => 'Inter',
        'size' => 12,
        'bold' => true,
        'color' => '8b4513'
    ], 'Encadre');
    
    safeAddText($section, 'SECURITE RENFORCEE : L\'integration native de Stripe pour les paiements, combinee a un systeme de verification des utilisateurs et d\'assurance des equipements, garantit la securite des transactions et la protection de tous les acteurs.', [
        'name' => 'Inter',
        'size' => 12,
        'bold' => true,
        'color' => 'ea580c'
    ], 'Encadre');
    
    safeAddText($section, 'ECONOMIE CIRCULAIRE : En optimisant l\'utilisation des equipements existants, FarmShop contribue a reduire l\'empreinte environnementale du secteur agricole tout en generant de nouvelles sources de revenus pour les proprietaires d\'equipements.', [
        'name' => 'Inter',
        'size' => 12,
        'bold' => true,
        'color' => '2d5016'
    ], 'Encadre');
    
    safeAddTitle($section, '4.4 Modele economique innovant', 2);
    
    safeAddText($section, 'Le modele economique de FarmShop s\'appuie sur une approche multi-revenus permettant de diversifier les sources de rentabilite tout en maintenant des tarifs competitifs pour les utilisateurs. Cette strategie economique comprend plusieurs composantes complementaires :', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $revenus_model = [
        'Commission sur les ventes : Pourcentage predefini sur chaque transaction de vente d\'equipement',
        'Commission sur les locations : Taux variable selon la duree et le type d\'equipement loue',
        'Abonnements premium : Services avances pour les professionnels (assurance etendue, support prioritaire, analytics)',
        'Services complementaires : Livraison, installation, formation, maintenance',
        'Partenariats strategiques : Commissions avec les fabricants, distributeurs, et services financiers'
    ];
    
    foreach ($revenus_model as $revenu) {
        safeAddText($section, '- ' . $revenu, [
            'name' => 'Inter',
            'size' => 12
        ], 'Normal');
    }
    
    safeAddText($section, 'Cette diversification des revenus permet a FarmShop de maintenir sa competitivite tout en investissant continuellement dans l\'amelioration de la plateforme et l\'expansion de son offre. Le modele est concu pour etre scalable et s\'adapter aux evolutions du marche agricole europeen.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    return $phpWord;
}

function main()
{
    $outputFile = '01_intro_presentation.docx';
    
    echo "=== GENERATION FICHIER 1 - FARMSHOP ===\n";
    echo "Introduction et Presentation du projet\n";
    echo "Format : Document Word professionnel\n";
    echo str_repeat("=", 50) . "\n";
    
    try {
        echo "Generation du contenu...\n";
        $phpWord = createFichier1();
        
        echo "Sauvegarde : $outputFile\n";
        
        if (file_exists($outputFile)) {
            unlink($outputFile);
            echo "Ancien fichier supprime\n";
        }
        
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($outputFile);
        
        if (file_exists($outputFile)) {
            $fileSize = filesize($outputFile) / (1024 * 1024);
            echo "SUCCES ! Fichier 1 cree\n";
            echo "Nom : $outputFile\n";
            echo "Taille : " . number_format($fileSize, 2) . " MB\n";
            
            if (PHP_OS_FAMILY === 'Windows') {
                exec("start \"\" \"$outputFile\"");
                echo "Fichier ouvert automatiquement\n";
            }
        }
        
    } catch (Exception $e) {
        echo "ERREUR : " . $e->getMessage() . "\n";
        exit(1);
    }
}

if (php_sapi_name() === 'cli') {
    main();
}
?>
