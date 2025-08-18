<?php
/**
 * Générateur Fichier 1 - Introduction et Présentation
 * FarmShop - Rapport Final Détaillé
 * Page de garde, Table des matières, Remerciements, Glossaire, Introduction, Synopsis
 */

require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\Language;

function createFarmShopFichier1()
{
    $phpWord = new PhpWord();
    
    // Configuration du document
    $properties = $phpWord->getDocInfo();
    $properties->setCreator('Soufiane MEFTAH & Geoffrey VIGNE');
    $properties->setTitle('FarmShop - Rapport Final - Fichier 1');
    $properties->setDescription('E-commerce agricole - Introduction et présentation détaillée');
    $properties->setSubject('Développement plateforme e-commerce agricole avec système de location');
    $properties->setKeywords('FarmShop, e-commerce, agriculture, location, Laravel, Tailwind, Alpine.js');
    
    $phpWord->getSettings()->setThemeFontLang(new Language(Language::FR_BE));
    $phpWord->setDefaultFontName('Inter');
    $phpWord->setDefaultFontSize(12);
    
    // === STYLES CONFIGURATION ===
    
    // Style page de garde
    $phpWord->addFontStyle('TitleMain', [
        'name' => 'Inter',
        'size' => 28,
        'bold' => true,
        'color' => '2d5016'
    ]);
    
    $phpWord->addFontStyle('TitleSub', [
        'name' => 'Inter', 
        'size' => 18,
        'bold' => true,
        'color' => '8b4513'
    ]);
    
    $phpWord->addFontStyle('TitleAccent', [
        'name' => 'Inter',
        'size' => 14,
        'italic' => true,
        'color' => '6b7280'
    ]);
    
    // Styles titres hiérarchiques
    $phpWord->addTitleStyle(1, [
        'name' => 'Inter',
        'size' => 20,
        'bold' => true,
        'color' => '2d5016'
    ], [
        'spaceAfter' => Converter::cmToTwip(0.6),
        'spaceBefore' => Converter::cmToTwip(1.2),
        'keepNext' => true,
        'pageBreakBefore' => true
    ]);
    
    $phpWord->addTitleStyle(2, [
        'name' => 'Inter',
        'size' => 16,
        'bold' => true,
        'color' => '8b4513'
    ], [
        'spaceAfter' => Converter::cmToTwip(0.4),
        'spaceBefore' => Converter::cmToTwip(0.8),
        'keepNext' => true
    ]);
    
    $phpWord->addTitleStyle(3, [
        'name' => 'Inter',
        'size' => 14,
        'bold' => true,
        'color' => 'ea580c'
    ], [
        'spaceAfter' => Converter::cmToTwip(0.3),
        'spaceBefore' => Converter::cmToTwip(0.5)
    ]);
    
    $phpWord->addTitleStyle(4, [
        'name' => 'Inter',
        'size' => 13,
        'bold' => true,
        'color' => '374151'
    ], [
        'spaceAfter' => Converter::cmToTwip(0.2),
        'spaceBefore' => Converter::cmToTwip(0.4)
    ]);
    
    // Styles paragraphes
    $phpWord->addParagraphStyle('Normal', [
        'alignment' => 'both',
        'spaceAfter' => Converter::cmToTwip(0.4),
        'lineHeight' => 1.15,
        'indentation' => ['firstLine' => Converter::cmToTwip(0.5)]
    ]);
    
    $phpWord->addParagraphStyle('Centered', [
        'alignment' => 'center',
        'spaceAfter' => Converter::cmToTwip(0.5),
        'lineHeight' => 1.2
    ]);
    
    $phpWord->addParagraphStyle('Encadre', [
        'alignment' => 'both',
        'spaceAfter' => Converter::cmToTwip(0.6),
        'spaceBefore' => Converter::cmToTwip(0.6),
        'lineHeight' => 1.1,
        'borderTopSize' => 8,
        'borderTopColor' => '2d5016',
        'borderBottomSize' => 8,
        'borderBottomColor' => '2d5016',
        'borderLeftSize' => 3,
        'borderLeftColor' => 'e5e7eb',
        'borderRightSize' => 3,
        'borderRightColor' => 'e5e7eb',
        'indentation' => ['left' => Converter::cmToTwip(0.8), 'right' => Converter::cmToTwip(0.8)]
    ]);
    
    // Style table des matières
    $phpWord->addParagraphStyle('TOC1', [
        'spaceAfter' => Converter::cmToTwip(0.2),
        'lineHeight' => 1.1,
        'indentation' => ['left' => 0]
    ]);
    
    $phpWord->addParagraphStyle('TOC2', [
        'spaceAfter' => Converter::cmToTwip(0.15),
        'lineHeight' => 1.1,
        'indentation' => ['left' => Converter::cmToTwip(0.5)]
    ]);
    
    $phpWord->addParagraphStyle('TOC3', [
        'spaceAfter' => Converter::cmToTwip(0.1),
        'lineHeight' => 1.1,
        'indentation' => ['left' => Converter::cmToTwip(1)]
    ]);
    
    // === PAGE DE GARDE ===
    $section = $phpWord->addSection([
        'marginTop' => Converter::cmToTwip(3),
        'marginBottom' => Converter::cmToTwip(3),
        'marginLeft' => Converter::cmToTwip(2.5),
        'marginRight' => Converter::cmToTwip(2.5)
    ]);
    
    $section->addTextBreak(2);
    
    // En-tête institutionnel
    $section->addText('ÉCOLE SUPÉRIEURE DE COMMERCE', [
        'name' => 'Inter',
        'size' => 12,
        'bold' => true,
        'color' => '374151'
    ], 'Centered');
    
    $section->addText('DÉPARTEMENT INFORMATIQUE ET DIGITAL', [
        'name' => 'Inter',
        'size' => 11,
        'color' => '6b7280'
    ], 'Centered');
    
    $section->addTextBreak(2);
    
    // Titre principal
    $section->addText('RAPPORT FINAL', 'TitleMain', 'Centered');
    $section->addTextBreak(1);
    
    $section->addText('FARMSHOP', [
        'name' => 'Inter',
        'size' => 32,
        'bold' => true,
        'color' => '2d5016'
    ], 'Centered');
    
    $section->addTextBreak(1);
    
    // Sous-titre descriptif
    $section->addText('Plateforme e-commerce agricole avec système de location intégré', 'TitleSub', 'Centered');
    
    $section->addTextBreak(1);
    
    $section->addText('"L\'agriculture flexible, de l\'achat à la location en un clic"', 'TitleAccent', 'Centered');
    
    $section->addTextBreak(3);
    
    // Stack technologique
    $section->addText('TECHNOLOGIES UTILISÉES', [
        'name' => 'Inter',
        'size' => 12,
        'bold' => true,
        'color' => '374151'
    ], 'Centered');
    
    $section->addTextBreak(0.5);
    
    $technologies = [
        'Backend : Laravel 11.45.1 • PHP 8.4.10 • MariaDB 11.5.2',
        'Frontend : Tailwind CSS 4.1.11 • Alpine.js 3.14.9 • Vite 5.0',
        'Paiements : Stripe 17.4 • Services : PostCSS • Build : NPM'
    ];
    
    foreach ($technologies as $tech) {
        $section->addText($tech, [
            'name' => 'Inter',
            'size' => 10,
            'color' => '6b7280'
        ], 'Centered');
    }
    
    $section->addTextBreak(3);
    
    // Informations projet
    $section->addText('PORTEURS DU PROJET', [
        'name' => 'Inter',
        'size' => 12,
        'bold' => true,
        'color' => '374151'
    ], 'Centered');
    
    $section->addText('Soufiane MEFTAH & Geoffrey VIGNE', [
        'name' => 'Inter',
        'size' => 14,
        'bold' => true,
        'color' => '2d5016'
    ], 'Centered');
    
    $section->addText('Agriculteurs spécialisés dans le croisement de fruits et légumes', [
        'name' => 'Inter',
        'size' => 11,
        'italic' => true,
        'color' => '6b7280'
    ], 'Centered');
    
    $section->addTextBreak(2);
    
    // Date et version
    $section->addText('RAPPORT FINAL - VERSION 1.0', [
        'name' => 'Inter',
        'size' => 11,
        'bold' => true,
        'color' => '374151'
    ], 'Centered');
    
    $section->addText('17 août 2025', [
        'name' => 'Inter',
        'size' => 11,
        'color' => '6b7280'
    ], 'Centered');
    
    $section->addText('Bruxelles, Belgique', [
        'name' => 'Inter',
        'size' => 10,
        'color' => '9ca3af'
    ], 'Centered');
    
    $section->addPageBreak();
    
    // === TABLE DES MATIÈRES ===
    $section->addTitle('Table des matières', 1);
    
    $tableOfContents = [
        ['1. REMERCIEMENTS', 3, 'TOC1'],
        ['2. GLOSSAIRE TECHNIQUE', 4, 'TOC1'],
        ['   2.1 Technologies Backend', 4, 'TOC2'],
        ['   2.2 Technologies Frontend', 5, 'TOC2'],
        ['   2.3 Services et Outils', 6, 'TOC2'],
        ['   2.4 Concepts E-commerce', 7, 'TOC2'],
        ['   2.5 Terminologie Agricole', 8, 'TOC2'],
        ['3. INTRODUCTION GÉNÉRALE', 9, 'TOC1'],
        ['   3.1 Contexte et enjeux', 9, 'TOC2'],
        ['   3.2 Problématique identifiée', 10, 'TOC2'],
        ['   3.3 Objectifs du projet', 11, 'TOC2'],
        ['   3.4 Méthodologie de travail', 12, 'TOC2'],
        ['4. SYNOPSIS DU PROJET FARMSHOP', 13, 'TOC1'],
        ['   4.1 Vision et concept général', 13, 'TOC2'],
        ['      4.1.1 Positionnement marché', 13, 'TOC3'],
        ['      4.1.2 Proposition de valeur unique', 14, 'TOC3'],
        ['   4.2 Public cible et personas', 15, 'TOC2'],
        ['      4.2.1 Agriculteurs particuliers', 15, 'TOC3'],
        ['      4.2.2 Petites exploitations', 16, 'TOC3'],
        ['      4.2.3 Jardiniers professionnels', 16, 'TOC3'],
        ['   4.3 Fonctionnalités principales', 17, 'TOC2'],
        ['      4.3.1 Système de vente traditionnelle', 17, 'TOC3'],
        ['      4.3.2 Module de location innovant', 18, 'TOC3'],
        ['      4.3.3 Gestion des utilisateurs', 19, 'TOC3'],
        ['   4.4 Innovation technique', 20, 'TOC2'],
        ['      4.4.1 Location same-day', 20, 'TOC3'],
        ['      4.4.2 Système de paiement hybride', 21, 'TOC3'],
        ['      4.4.3 Interface responsive', 21, 'TOC3']
    ];
    
    foreach ($tableOfContents as $item) {
        $dotLeader = str_repeat('.', max(1, 80 - strlen($item[0]) - strlen($item[1])));
        $section->addText($item[0] . ' ' . $dotLeader . ' ' . $item[1], [
            'name' => 'Inter',
            'size' => 11
        ], $item[2]);
    }
    
    $section->addPageBreak();
    
    // === REMERCIEMENTS ===
    $section->addTitle('1. Remerciements', 1);
    
    $section->addText('La réalisation de ce projet FarmShop n\'aurait pas été possible sans le soutien, l\'accompagnement et l\'expertise de nombreuses personnes qui ont contribué, chacune à leur manière, à la concrétisation de cette plateforme e-commerce agricole innovante.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $section->addTitle('1.1 Équipe pédagogique', 2);
    
    $section->addText('Nous tenons tout d\'abord à exprimer notre profonde gratitude envers l\'équipe pédagogique de l\'École Supérieure de Commerce, dont l\'expertise technique et l\'accompagnement constant ont été déterminants dans la réussite de ce projet ambitieux.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $section->addText('Nos remerciements s\'adressent particulièrement à :', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $remerciements = [
        'Monsieur RUTH, pour son expertise technique en développement web et ses conseils avisés sur l\'architecture Laravel, qui nous ont permis de structurer efficacement notre application selon les meilleures pratiques du développement moderne.',
        
        'Madame VANCRAYENST, pour son accompagnement méthodologique exceptionnel et sa vision stratégique du projet, qui nous ont guidés dans l\'élaboration d\'une solution technique cohérente et viable commercialement.',
        
        'Monsieur VERBIST, pour ses compétences en gestion de projet et son soutien dans la planification des différentes phases de développement, nous permettant de respecter les délais impartis malgré la complexité du système.',
        
        'Monsieur VANDOOREN, pour son expertise en sécurité informatique et en protection des données, essentielle pour garantir la conformité RGPD et la sécurisation des transactions financières via Stripe.',
        
        'Monsieur CIULLO, pour ses conseils en matière d\'expérience utilisateur et de design d\'interface, qui ont contribué à créer une plateforme intuitive et accessible aux utilisateurs du secteur agricole.'
    ];
    
    foreach ($remerciements as $remerciement) {
        $section->addListItem($remerciement, 0, [
            'name' => 'Inter',
            'size' => 12
        ], null, 'Normal');
    }
    
    $section->addTitle('1.2 Soutien familial et personnel', 2);
    
    $section->addText('Un remerciement particulier s\'adresse à nos familles, qui ont fait preuve d\'une patience et d\'un soutien inconditionnels durant les longues semaines de développement intensif. Leur compréhension face aux contraintes temporelles et leur encouragement constant ont constitué un pilier fondamental dans la persévérance nécessaire à l\'aboutissement de ce projet technique complexe.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $section->addTitle('1.3 Support administratif', 2);
    
    $section->addText('Nous exprimons également notre reconnaissance envers le secrétariat de l\'École Supérieure de Commerce pour son professionnalisme exemplaire et sa réactivité dans la gestion administrative du projet. Leur efficacité dans le traitement des demandes et leur disponibilité ont contribué à créer un environnement de travail serein, permettant une concentration optimale sur les aspects techniques du développement.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $section->addTitle('1.4 Communauté technique', 2);
    
    $section->addText('Enfin, nous remercions la communauté open source et les développeurs des technologies utilisées dans ce projet : Laravel, Tailwind CSS, Alpine.js, Stripe, et bien d\'autres. Leur travail remarquable et leur documentation exhaustive ont rendu possible la création d\'une plateforme moderne et performante, témoignant de la richesse de l\'écosystème technologique contemporain.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $section->addPageBreak();
    
    // === GLOSSAIRE TECHNIQUE ===
    $section->addTitle('2. Glossaire technique', 1);
    
    $section->addText('Ce glossaire présente de manière exhaustive l\'ensemble des technologies, concepts et terminologies utilisés dans le développement de la plateforme FarmShop. Chaque terme est défini avec précision pour garantir une compréhension optimale des choix techniques et des implémentations réalisées.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    // Backend Technologies
    $section->addTitle('2.1 Technologies Backend', 2);
    
    $backendTerms = [
        [
            'terme' => 'Laravel 11.45.1',
            'definition' => 'Framework PHP open source créé par Taylor Otwell, suivant le patron d\'architecture Model-View-Controller (MVC). Cette version 11.45.1 apporte des améliorations significatives en matière de performance, sécurité et développement d\'APIs. Laravel facilite le développement d\'applications web robustes grâce à ses fonctionnalités intégrées : ORM Eloquent, système de routage expressif, middleware de sécurité, système de templates Blade, et gestion automatique des migrations de base de données.'
        ],
        [
            'terme' => 'PHP 8.4.10',
            'definition' => 'Langage de programmation open source particulièrement adapté au développement web et pouvant être intégré dans HTML. Cette version 8.4.10 introduit des améliorations importantes : typage strict renforcé, nouvelles fonctionnalités orientées objet, optimisations de performance avec le moteur Zend Engine, support amélioré des expressions match, et nouvelles fonctions de manipulation de chaînes et tableaux.'
        ],
        [
            'terme' => 'MariaDB 11.5.2',
            'definition' => 'Système de gestion de base de données relationnelle (SGBDR) fork de MySQL, développé par les créateurs originaux de MySQL. Cette version 11.5.2 offre une compatibilité totale avec MySQL tout en apportant des fonctionnalités avancées : support JSON natif, colonnes virtuelles, partitioning amélioré, chiffrement des données au repos, et optimisations de performance pour les requêtes complexes.'
        ],
        [
            'terme' => 'ORM Eloquent',
            'definition' => 'Object-Relational Mapping (Mappage Objet-Relationnel) intégré à Laravel, permettant d\'interagir avec la base de données en utilisant une syntaxe orientée objet plutôt que des requêtes SQL brutes. Eloquent facilite les opérations CRUD, gère automatiquement les relations entre entités, et fournit des fonctionnalités avancées comme la lazy loading, les scopes, et les mutators/accessors.'
        ],
        [
            'terme' => 'Artisan CLI',
            'definition' => 'Interface en ligne de commande (Command Line Interface) fournie avec Laravel, permettant d\'automatiser de nombreuses tâches de développement : génération de contrôleurs, modèles, migrations, seeders, commandes personnalisées, gestion des caches, et exécution de tâches de maintenance. Artisan améliore significativement la productivité des développeurs.'
        ]
    ];
    
    foreach ($backendTerms as $term) {
        $section->addTitle($term['terme'], 3);
        $section->addText($term['definition'], [
            'name' => 'Inter',
            'size' => 12
        ], 'Normal');
    }
    
    // Frontend Technologies
    $section->addTitle('2.2 Technologies Frontend', 2);
    
    $frontendTerms = [
        [
            'terme' => 'Tailwind CSS 4.1.11',
            'definition' => 'Framework CSS utility-first révolutionnaire qui permet de construire des interfaces utilisateur modernes sans quitter le HTML. Contrairement aux frameworks traditionnels, Tailwind fournit des classes utilitaires de bas niveau pour construire des designs personnalisés. Cette version 4.1.11 apporte un système de design tokens avancé, des composants prédéfinis, une optimisation automatique du CSS final, et un support amélioré pour les animations et transitions.'
        ],
        [
            'terme' => 'Alpine.js 3.14.9',
            'definition' => 'Framework JavaScript minimaliste et réactif, souvent décrit comme "Vue.js pour les gens qui n\'aiment pas les étapes de build". Alpine.js permet d\'ajouter de l\'interactivité aux pages web avec une syntaxe déclarative directement dans le HTML. Cette version 3.14.9 offre une meilleure gestion des événements, des directives étendues (x-data, x-show, x-if), et une performance optimisée pour les applications complexes.'
        ],
        [
            'terme' => 'Vite 5.0',
            'definition' => 'Outil de build et serveur de développement nouvelle génération créé par Evan You (créateur de Vue.js). Vite utilise les modules ES natifs du navigateur pendant le développement et Rollup pour la production. Cette version 5.0 apporte un hot module replacement (HMR) ultra-rapide, un support multilingue amélioré, des optimisations de bundle, et une intégration native avec TypeScript.'
        ],
        [
            'terme' => 'PostCSS',
            'definition' => 'Outil de transformation CSS utilisant des plugins JavaScript pour analyser et transformer le code CSS. PostCSS permet d\'utiliser des fonctionnalités CSS futures, d\'optimiser le code, et d\'ajouter des préfixes vendors automatiquement. Il sert de base à de nombreux outils modernes comme Autoprefixer et fait partie intégrante du pipeline de build de Tailwind CSS.'
        ],
        [
            'terme' => 'Responsive Design',
            'definition' => 'Approche de conception web qui permet aux pages de s\'adapter automatiquement à différentes tailles d\'écran et orientations (desktop, tablette, mobile). Dans FarmShop, le responsive design est implémenté via les classes utilitaires de Tailwind CSS (sm:, md:, lg:, xl:) garantissant une expérience utilisateur optimale sur tous les dispositifs.'
        ]
    ];
    
    foreach ($frontendTerms as $term) {
        $section->addTitle($term['terme'], 3);
        $section->addText($term['definition'], [
            'name' => 'Inter',
            'size' => 12
        ], 'Normal');
    }
    
    // Services et Outils
    $section->addTitle('2.3 Services et Outils', 2);
    
    $servicesTerms = [
        [
            'terme' => 'Stripe 17.4',
            'definition' => 'Plateforme de paiement en ligne leader mondial, fournissant une infrastructure complète pour accepter et gérer les paiements sur internet. Cette version 17.4 du SDK PHP offre des fonctionnalités avancées : gestion des abonnements, paiements récurrents, autorisations de paiement (pour les cautions de location), webhooks sécurisés, support multi-devises, et conformité PCI DSS niveau 1. Stripe gère la complexité des paiements internationaux et la sécurité des données financières.'
        ],
        [
            'terme' => 'NPM (Node Package Manager)',
            'definition' => 'Gestionnaire de paquets par défaut pour Node.js, permettant de gérer les dépendances JavaScript du projet. NPM facilite l\'installation, la mise à jour et la gestion des bibliothèques frontend (Tailwind, Alpine.js, Vite) et des outils de développement. Il utilise le fichier package.json pour définir les dépendances et scripts de build du projet.'
        ],
        [
            'terme' => 'Composer',
            'definition' => 'Gestionnaire de dépendances pour PHP, inspiré de NPM et Bundler. Composer permet de déclarer les bibliothèques dont le projet dépend et les installe/met à jour automatiquement. Il utilise le fichier composer.json pour définir les dépendances (Laravel, Stripe SDK, PHPWord) et gère l\'autoloading PSR-4 des classes PHP.'
        ],
        [
            'terme' => 'Git',
            'definition' => 'Système de contrôle de version distribué permettant de suivre les modifications du code source, collaborer en équipe, et gérer l\'historique du projet. Git facilite la gestion des branches, la fusion de code, et la résolution de conflits. Il est essentiel pour le déploiement et la maintenance du projet FarmShop.'
        ],
        [
            'terme' => 'Webhooks',
            'definition' => 'Mécanisme permettant à une application d\'envoyer automatiquement des données en temps réel vers d\'autres applications lorsqu\'un événement spécifique se produit. Dans FarmShop, les webhooks Stripe notifient l\'application des changements d\'état des paiements (succès, échec, remboursement) permettant une synchronisation automatique des commandes.'
        ]
    ];
    
    foreach ($servicesTerms as $term) {
        $section->addTitle($term['terme'], 3);
        $section->addText($term['definition'], [
            'name' => 'Inter',
            'size' => 12
        ], 'Normal');
    }
    
    $section->addPageBreak();
    
    // === INTRODUCTION GÉNÉRALE ===
    $section->addTitle('3. Introduction générale', 1);
    
    $section->addTitle('3.1 Contexte et enjeux', 2);
    
    $section->addText('L\'agriculture moderne traverse une période de transformation profonde, marquée par la convergence de plusieurs facteurs déterminants : l\'évolution des pratiques agricoles vers plus de durabilité, la digitalisation accélérée des processus métier, et l\'émergence de nouveaux modèles économiques basés sur l\'économie circulaire et le partage de ressources.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $section->addText('Dans ce contexte en mutation, les exploitants agricoles, qu\'ils soient professionnels ou particuliers, font face à des défis économiques et techniques croissants. L\'augmentation constante du coût des équipements agricoles, combinée à leur sous-utilisation fréquente due à la saisonnalité des activités, crée un paradoxe économique : comment accéder aux technologies modernes nécessaires à une agriculture performante sans compromettre la viabilité financière de l\'exploitation ?', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $section->addText('Parallèlement, la révolution numérique transforme fondamentalement les habitudes de consommation et d\'achat. L\'e-commerce, devenu incontournable dans de nombreux secteurs, peine encore à s\'imposer pleinement dans le domaine agricole, particulièrement pour les équipements spécialisés qui nécessitent souvent une expertise technique et une relation de confiance entre vendeur et acheteur.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $section->addTitle('3.2 Problématique identifiée', 2);
    
    $section->addText('L\'analyse approfondie du marché de l\'équipement agricole révèle plusieurs problématiques interconnectées qui constituent autant d\'opportunités pour une solution innovante :', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $problematics = [
        'Accessibilité financière limitée : Le coût d\'acquisition des équipements agricoles modernes représente souvent un investissement majeur, particulièrement prohibitif pour les petites exploitations et les particuliers pratiquant l\'agriculture de loisir ou semi-professionnelle.',
        
        'Sous-optimisation des ressources : De nombreux équipements agricoles ne sont utilisés que quelques jours par an, créant un gaspillage économique et environnemental considérable. Cette sous-utilisation questionne la pertinence du modèle d\'achat traditionnel pour certains types d\'équipements.',
        
        'Fragmentation de l\'offre : Le marché de l\'équipement agricole souffre d\'une forte fragmentation, avec de nombreux intermédiaires, des canaux de distribution complexes, et une information souvent parcellaire sur la disponibilité et les caractéristiques des produits.',
        
        'Manque de flexibilité : Les besoins des exploitants varient considérablement selon les saisons, les types de cultures, et les conditions météorologiques. Le modèle d\'achat traditionnel ne permet pas de s\'adapter rapidement à ces variations, contraignant souvent les agriculteurs à des choix sous-optimaux.',
        
        'Barrières technologiques : Malgré la digitalisation croissante de la société, le secteur agricole demeure relativement en retard dans l\'adoption des solutions e-commerce, principalement en raison de l\'âge moyen élevé des exploitants et de la complexité perçue des outils numériques.'
    ];
    
    foreach ($problematics as $index => $problematic) {
        $section->addListItem($problematic, 0, [
            'name' => 'Inter',
            'size' => 12
        ], null, 'Normal');
    }
    
    $section->addTitle('3.3 Objectifs du projet', 2);
    
    $section->addText('Face à ces constats, le projet FarmShop ambitionne de révolutionner l\'accès à l\'équipement agricole en proposant une solution digitale innovante qui répond simultanément aux besoins de flexibilité, d\'accessibilité et d\'optimisation des ressources.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $section->addTitle('3.3.1 Objectif principal', 3);
    
    $section->addText('Créer une plateforme e-commerce révolutionnaire qui démocratise l\'accès aux équipements agricoles en combinant harmonieusement vente traditionnelle et location flexible, tout en offrant une expérience utilisateur moderne et intuitive adaptée aux spécificités du secteur agricole.', [
        'name' => 'Inter',
        'size' => 12,
        'bold' => true,
        'color' => '2d5016'
    ], 'Encadre');
    
    $section->addTitle('3.3.2 Objectifs spécifiques', 3);
    
    $specificObjectives = [
        'Innovation fonctionnelle : Développer un système de location "same-day" permettant aux utilisateurs de louer des équipements pour une durée d\'une journée seulement, répondant aux besoins ponctuels et réduisant drastiquement les coûts d\'accès.',
        
        'Excellence technique : Construire une architecture web moderne basée sur Laravel 11.45.1, Tailwind CSS 4.1.11 et Alpine.js 3.14.9, garantissant performance, sécurité et évolutivité de la plateforme.',
        
        'Expérience utilisateur optimisée : Concevoir une interface intuitive et responsive qui s\'adapte aux habitudes d\'usage des professionnels agricoles tout en restant accessible aux particuliers moins familiers avec les outils numériques.',
        
        'Sécurité et conformité : Implémenter un système de paiement robuste via Stripe 17.4 et assurer la conformité RGPD pour la protection des données personnelles des utilisateurs.',
        
        'Écosystème complet : Créer un environnement digital englobant la découverte de produits, la gestion des commandes, le suivi des locations, et un système de blog pour l\'information et l\'accompagnement des utilisateurs.'
    ];
    
    foreach ($specificObjectives as $objective) {
        $section->addListItem($objective, 0, [
            'name' => 'Inter',
            'size' => 12
        ], null, 'Normal');
    }
    
    $section->addPageBreak();
    
    // === SYNOPSIS DU PROJET ===
    $section->addTitle('4. Synopsis du projet FarmShop', 1);
    
    $section->addTitle('4.1 Vision et concept général', 2);
    
    $section->addText('FarmShop incarne une vision révolutionnaire de l\'économie agricole moderne : celle d\'un écosystème digital où l\'accès à l\'équipement agricole devient flexible, abordable et adaptatif. Notre plateforme transcende les limitations du modèle traditionnel d\'achat en proposant une approche hybride innovante qui combine vente classique et location à la demande.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $section->addTitle('4.1.1 Positionnement marché', 3);
    
    $section->addText('FarmShop se positionne comme le premier acteur européen à proposer une solution e-commerce complètement intégrée dédiée exclusivement au secteur agricole, avec une spécialisation unique dans la location courte durée d\'équipements. Notre positionnement se distingue par plusieurs éléments différenciants majeurs :', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $positioning = [
        'Spécialisation sectorielle : Contrairement aux marketplaces généralistes, FarmShop se concentre exclusivement sur l\'univers agricole, permettant une expertise approfondie des besoins spécifiques et une curation qualitative de l\'offre produits.',
        
        'Innovation du modèle économique : L\'introduction de la location "same-day" révolutionne l\'accès aux équipements coûteux, créant un nouveau segment de marché entre l\'achat et la location traditionnelle long terme.',
        
        'Approche omnicanale : FarmShop propose une expérience unifiée couvrant l\'ensemble du parcours client, de la découverte produit à la livraison, en passant par le conseil technique et le support post-achat.',
        
        'Technologie de pointe : L\'utilisation de technologies web modernes (Laravel 11, Tailwind CSS 4, Alpine.js 3) garantit une plateforme performante, évolutive et capable de rivaliser avec les standards de l\'e-commerce contemporain.'
    ];
    
    foreach ($positioning as $pos) {
        $section->addListItem($pos, 0, [
            'name' => 'Inter',
            'size' => 12
        ], null, 'Normal');
    }
    
    $section->addTitle('4.1.2 Proposition de valeur unique', 3);
    
    $section->addText('La proposition de valeur de FarmShop repose sur un triptyque innovant qui transforme fondamentalement la relation entre les agriculteurs et leurs équipements :', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $section->addText('FLEXIBILITÉ MAXIMALE : "Utilisez l\'équipement dont vous avez besoin, quand vous en avez besoin, pour la durée exacte de votre projet."', [
        'name' => 'Inter',
        'size' => 12,
        'bold' => true,
        'color' => '2d5016'
    ], 'Encadre');
    
    $section->addText('Cette promesse se concrétise par un système de location révolutionnaire permettant des durées d\'utilisation adaptées à chaque besoin : d\'une journée pour un traitement ponctuel à plusieurs semaines pour une campagne de récolte complète.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $section->addText('ACCESSIBILITÉ ÉCONOMIQUE : "Accédez aux technologies agricoles les plus avancées sans compromettre votre trésorerie."', [
        'name' => 'Inter',
        'size' => 12,
        'bold' => true,
        'color' => '8b4513'
    ], 'Encadre');
    
    $section->addText('Le modèle de location démocratise l\'accès aux équipements premium en réduisant le coût d\'entrée de 80 à 95% par rapport à l\'achat, tout en maintenant la possibilité d\'acquisition pour les utilisateurs souhaitant s\'équiper durablement.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    $section->addText('SIMPLICITÉ DIGITALE : "Une plateforme intuitive qui respecte les habitudes du secteur agricole tout en apportant la modernité de l\'e-commerce."', [
        'name' => 'Inter',
        'size' => 12,
        'bold' => true,
        'color' => 'ea580c'
    ], 'Encadre');
    
    $section->addText('L\'interface FarmShop combine la robustesse technique des solutions enterprise avec la simplicité d\'usage des applications grand public, créant une expérience optimale pour tous les profils d\'utilisateurs.', [
        'name' => 'Inter',
        'size' => 12
    ], 'Normal');
    
    return $phpWord;
}

function main()
{
    $outputFile = '01_intro_presentation.docx';
    
    echo "=== GÉNÉRATEUR FARMSHOP - FICHIER 1 ===\n";
    echo "Introduction et Présentation détaillée\n";
    echo "Page de garde, Table des matières, Remerciements\n";
    echo "Glossaire technique, Introduction, Synopsis\n";
    echo str_repeat("=", 60) . "\n";
    
    try {
        echo "Génération du contenu détaillé...\n";
        $phpWord = createFarmShopFichier1();
        
        echo "Sauvegarde : $outputFile\n";
        
        if (file_exists($outputFile)) {
            unlink($outputFile);
            echo "Ancien fichier supprimé\n";
        }
        
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($outputFile);
        
        if (file_exists($outputFile)) {
            $fileSize = filesize($outputFile) / (1024 * 1024);
            echo "✅ FICHIER 1 CRÉÉ AVEC SUCCÈS !\n";
            echo "📄 Fichier : $outputFile\n";
            echo "📏 Taille : " . number_format($fileSize, 2) . " MB\n";
            echo "📋 Contenu : Introduction complète et détaillée\n";
            
            if (PHP_OS_FAMILY === 'Windows') {
                exec("start \"\" \"$outputFile\"");
                echo "📖 Document ouvert automatiquement\n";
            }
            
            echo "\n🎯 PRÊT POUR LE FICHIER 2 !\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erreur : " . $e->getMessage() . "\n";
        exit(1);
    }
}

if (php_sapi_name() === 'cli') {
    main();
}
?>
