<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\Jc;

// Configuration pour Office 365 et encodage UTF-8
Settings::setOutputEscapingEnabled(true);
Settings::setCompatibility(true);

// Créer un nouveau document Word
$phpWord = new PhpWord();

// Configuration de la langue et police par défaut
$phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('fr-BE'));
$phpWord->setDefaultFontName('Inter');
$phpWord->setDefaultFontSize(11);

// Styles pour document académique
$phpWord->addTitleStyle(1, [
    'name' => 'Inter', 
    'size' => 18, 
    'bold' => true, 
    'color' => '000000'
]);

$phpWord->addTitleStyle(2, [
    'name' => 'Inter', 
    'size' => 16, 
    'bold' => true, 
    'color' => '000000'
]);

$phpWord->addTitleStyle(3, [
    'name' => 'Inter', 
    'size' => 14, 
    'bold' => true, 
    'color' => '000000'
]);

$phpWord->addTitleStyle(4, [
    'name' => 'Inter', 
    'size' => 12, 
    'bold' => true, 
    'color' => '000000'
]);

// Styles de contenu
$contentStyle = ['name' => 'Inter', 'size' => 11, 'lang' => 'fr-BE'];
$strongStyle = ['name' => 'Inter', 'size' => 11, 'bold' => true, 'lang' => 'fr-BE'];
$italicStyle = ['name' => 'Inter', 'size' => 11, 'italic' => true, 'lang' => 'fr-BE'];

// Section avec marges standards
$section = $phpWord->addSection([
    'marginTop' => 1440,
    'marginBottom' => 1440,
    'marginLeft' => 1440,
    'marginRight' => 1440
]);

// PAGE DE GARDE ACADEMIQUE
$section->addText('COMMUNAUTE FRANCAISE DE BELGIQUE', [
    'name' => 'Inter', 'size' => 14, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('Institut des Carrieres Commerciales', [
    'name' => 'Inter', 'size' => 13, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('Ville de Bruxelles', [
    'name' => 'Inter', 'size' => 12, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('Rue de la Fontaine 4 - 1000 BRUXELLES', [
    'name' => 'Inter', 'size' => 11, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addTextBreak(8);

$section->addText('LIVRABLE 13', [
    'name' => 'Inter', 'size' => 20, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('RECHERCHE DE PROGICIELS ET SOLUTIONS TECHNIQUES', [
    'name' => 'Inter', 'size' => 16, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addTextBreak(3);

$section->addText('Epreuve integree realisee en vue de l\'obtention du titre de', [
    'name' => 'Inter', 'size' => 11, 'italic' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('Bachelier en Informatique de gestion', [
    'name' => 'Inter', 'size' => 12, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('Orientation developpement d\'applications', [
    'name' => 'Inter', 'size' => 11, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addTextBreak(5);

$section->addText('MEFTAH Soufiane', [
    'name' => 'Inter', 'size' => 16, 'bold' => true, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('Annee academique 2024-2025', [
    'name' => 'Inter', 'size' => 12, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

// Nouvelle page
$section->addPageBreak();

// TABLE DES MATIERES
$section->addTitle('Table des matieres', 1);
$section->addTextBreak();

$section->addText('Introduction', $contentStyle);
$section->addText('1. Philosophie RAD et approche technique', $contentStyle);
$section->addText('2. Framework principal et ecosysteme Laravel', $contentStyle);
$section->addText('2.1 Laravel 11 LTS - Framework de base', $contentStyle);
$section->addText('2.2 Packages Laravel officiels', $contentStyle);
$section->addText('2.3 Packages tiers Laravel communautaires', $contentStyle);
$section->addText('3. Solutions frontend et interface utilisateur', $contentStyle);
$section->addText('3.1 Frameworks CSS et design systems', $contentStyle);
$section->addText('3.2 Librairies JavaScript et interactivite', $contentStyle);
$section->addText('3.3 Outils de build et bundling', $contentStyle);
$section->addText('4. Gestion des donnees et base de donnees', $contentStyle);
$section->addText('4.1 Systemes de gestion de base de donnees', $contentStyle);
$section->addText('4.2 ORM et outils de migration', $contentStyle);
$section->addText('4.3 Cache et optimisation performances', $contentStyle);
$section->addText('5. APIs et services externes', $contentStyle);
$section->addText('5.1 APIs de paiement', $contentStyle);
$section->addText('5.2 Services de messagerie et notifications', $contentStyle);
$section->addText('5.3 Services de stockage et CDN', $contentStyle);
$section->addText('6. Outils de developpement et productivite', $contentStyle);
$section->addText('6.1 Environnement de developpement', $contentStyle);
$section->addText('6.2 Outils de testing et qualite code', $contentStyle);
$section->addText('6.3 Deployment et DevOps', $contentStyle);
$section->addText('7. Solutions specifiques e-commerce', $contentStyle);
$section->addText('7.1 Packages e-commerce Laravel', $contentStyle);
$section->addText('7.2 Gestion des stocks et inventaire', $contentStyle);
$section->addText('7.3 Systeme de location et cautions', $contentStyle);
$section->addText('8. Analyse comparative et selection', $contentStyle);
$section->addText('8.1 Criteres de selection', $contentStyle);
$section->addText('8.2 Matrice de decision technique', $contentStyle);
$section->addText('8.3 Recommandations finales', $contentStyle);
$section->addText('Conclusion', $contentStyle);
$section->addText('Bibliographie', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// INTRODUCTION
$section->addTitle('Introduction', 1);
$section->addTextBreak();

$section->addText('Dans le cadre du developpement de FarmShop, une plateforme e-commerce specialisee dans la vente et location de produits agricoles, il est essentiel d\'adopter une approche Rapid Application Development (RAD) pour optimiser les delais de livraison tout en maintenant une qualite technique elevee.', $contentStyle);

$section->addTextBreak();

$section->addText('Cette recherche de progiciels et solutions techniques vise a identifier les librairies, frameworks, composants logiciels tiers et outils permettant de realiser efficacement les fonctionnalites definies dans le cahier des charges, sans reinventer la roue.', $contentStyle);

$section->addTextBreak();

$section->addText('L\'objectif est de constituer un ecosysteme technologique coherent, performant et maintenable, en tirant parti des meilleures solutions disponibles sur le marche, particulierement dans l\'ecosysteme Laravel 11 LTS et les technologies web modernes.', $contentStyle);

$section->addTextBreak(2);

// 1. PHILOSOPHIE RAD
$section->addTitle('1. Philosophie RAD et approche technique', 1);
$section->addTextBreak();

$section->addTitle('Principes du Rapid Application Development', 2);
$section->addText('Le RAD preconise l\'utilisation maximale de composants existants et eprouves pour accelerer le developpement. Cette approche presente plusieurs avantages :', $contentStyle);

$section->addTextBreak();
$section->addText('Reduction significative du temps de developpement', $contentStyle);
$section->addText('Fiabilite accrue grace a des composants testes par la communaute', $contentStyle);
$section->addText('Maintenance facilitee par des solutions standardisees', $contentStyle);
$section->addText('Documentation et support communautaire disponibles', $contentStyle);
$section->addText('Evolutivite et compatibilite avec les nouvelles versions', $contentStyle);

$section->addTextBreak();

$section->addTitle('Criteres de selection des solutions', 2);
$section->addText('Pour chaque composant technique, les criteres suivants sont evalues :', $contentStyle);

$section->addTextBreak();
$section->addText('Maturite et stabilite de la solution', $contentStyle);
$section->addText('Taille et activite de la communaute', $contentStyle);
$section->addText('Qualite de la documentation', $contentStyle);
$section->addText('Compatibilite avec Laravel 11 LTS', $contentStyle);
$section->addText('Performance et optimisation', $contentStyle);
$section->addText('Facilite d\'integration et d\'utilisation', $contentStyle);
$section->addText('Licence et cout d\'utilisation', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 2. FRAMEWORK PRINCIPAL
$section->addTitle('2. Framework principal et ecosysteme Laravel', 1);
$section->addTextBreak();

$section->addTitle('2.1 Laravel 11 LTS - Framework de base', 2);
$section->addTextBreak();

$section->addText('Choix du framework principal :', $strongStyle);
$section->addText('Laravel 11 LTS (Long Term Support)', $contentStyle);
$section->addText('Version : 11.x', $contentStyle);
$section->addText('Support : Jusqu\'en 2027', $contentStyle);
$section->addText('Licence : MIT License (Open Source)', $contentStyle);

$section->addTextBreak();

$section->addText('Justification du choix :', $strongStyle);
$section->addText('Framework PHP le plus populaire et mature', $contentStyle);
$section->addText('Ecosysteme riche avec de nombreux packages', $contentStyle);
$section->addText('Architecture MVC claire et maintenable', $contentStyle);
$section->addText('ORM Eloquent puissant et intuitif', $contentStyle);
$section->addText('Systeme de routing moderne et flexible', $contentStyle);
$section->addText('Support natif pour API REST et GraphQL', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.2 Packages Laravel officiels', 2);
$section->addTextBreak();

// Tableau des packages officiels
$officialTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$officialTable->addRow();
$officialTable->addCell(3000)->addText('Package', $strongStyle);
$officialTable->addCell(2000)->addText('Version', $strongStyle);
$officialTable->addCell(4000)->addText('Utilisation dans FarmShop', $strongStyle);

$officialTable->addRow();
$officialTable->addCell(3000)->addText('Laravel Sanctum', $contentStyle);
$officialTable->addCell(2000)->addText('4.x', $contentStyle);
$officialTable->addCell(4000)->addText('Authentification API et SPA', $contentStyle);

$officialTable->addRow();
$officialTable->addCell(3000)->addText('Laravel Horizon', $contentStyle);
$officialTable->addCell(2000)->addText('5.x', $contentStyle);
$officialTable->addCell(4000)->addText('Gestion des queues et jobs', $contentStyle);

$officialTable->addRow();
$officialTable->addCell(3000)->addText('Laravel Scout', $contentStyle);
$officialTable->addCell(2000)->addText('10.x', $contentStyle);
$officialTable->addCell(4000)->addText('Recherche full-text produits', $contentStyle);

$officialTable->addRow();
$officialTable->addCell(3000)->addText('Laravel Cashier', $contentStyle);
$officialTable->addCell(2000)->addText('15.x', $contentStyle);
$officialTable->addCell(4000)->addText('Integration Stripe pour paiements', $contentStyle);

$officialTable->addRow();
$officialTable->addCell(3000)->addText('Laravel Telescope', $contentStyle);
$officialTable->addCell(2000)->addText('5.x', $contentStyle);
$officialTable->addCell(4000)->addText('Debug et monitoring developpement', $contentStyle);

$section->addTextBreak();

$section->addTitle('2.3 Packages tiers Laravel communautaires', 2);
$section->addTextBreak();

// Tableau des packages tiers
$thirdPartyTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$thirdPartyTable->addRow();
$thirdPartyTable->addCell(3000)->addText('Package', $strongStyle);
$thirdPartyTable->addCell(2000)->addText('Editeur', $strongStyle);
$thirdPartyTable->addCell(4000)->addText('Fonctionnalite', $strongStyle);

$thirdPartyTable->addRow();
$thirdPartyTable->addCell(3000)->addText('Spatie Laravel-Permission', $contentStyle);
$thirdPartyTable->addCell(2000)->addText('Spatie', $contentStyle);
$thirdPartyTable->addCell(4000)->addText('Gestion roles et permissions', $contentStyle);

$thirdPartyTable->addRow();
$thirdPartyTable->addCell(3000)->addText('Laravel Filament', $contentStyle);
$thirdPartyTable->addCell(2000)->addText('Filament', $contentStyle);
$thirdPartyTable->addCell(4000)->addText('Panel admin moderne et rapide', $contentStyle);

$thirdPartyTable->addRow();
$thirdPartyTable->addCell(3000)->addText('Intervention Image', $contentStyle);
$thirdPartyTable->addCell(2000)->addText('Intervention', $contentStyle);
$thirdPartyTable->addCell(4000)->addText('Manipulation images produits', $contentStyle);

$thirdPartyTable->addRow();
$thirdPartyTable->addCell(3000)->addText('Laravel Excel', $contentStyle);
$thirdPartyTable->addCell(2000)->addText('SpartnerNL', $contentStyle);
$thirdPartyTable->addCell(4000)->addText('Import/Export donnees Excel', $contentStyle);

$thirdPartyTable->addRow();
$thirdPartyTable->addCell(3000)->addText('Laravel Backup', $contentStyle);
$thirdPartyTable->addCell(2000)->addText('Spatie', $contentStyle);
$thirdPartyTable->addCell(4000)->addText('Sauvegarde automatique BDD/fichiers', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 3. SOLUTIONS FRONTEND
$section->addTitle('3. Solutions frontend et interface utilisateur', 1);
$section->addTextBreak();

$section->addTitle('3.1 Frameworks CSS et design systems', 2);
$section->addTextBreak();

$section->addText('Solution principale retenue :', $strongStyle);
$section->addText('Tailwind CSS v3.4', $contentStyle);
$section->addText('Licence : MIT License', $contentStyle);
$section->addText('Communaute : Plus de 2M d\'utilisateurs', $contentStyle);

$section->addTextBreak();

$section->addText('Justification du choix :', $strongStyle);
$section->addText('Approche utility-first pour un design personnalise', $contentStyle);
$section->addText('Excellent support responsive design', $contentStyle);
$section->addText('Configuration personnalisable (couleurs farm-green/brown)', $contentStyle);
$section->addText('Performance optimisee avec purging CSS automatique', $contentStyle);
$section->addText('Integration native avec Vite et Laravel Mix', $contentStyle);

$section->addTextBreak();

$section->addText('Alternatives evaluees :', $strongStyle);
$section->addText('Bootstrap 5 : Rejete pour manque de flexibilite design', $contentStyle);
$section->addText('Bulma : Rejete pour ecosysteme plus restreint', $contentStyle);
$section->addText('Foundation : Rejete pour complexite excessive', $contentStyle);

$section->addTextBreak();

$section->addTitle('3.2 Librairies JavaScript et interactivite', 2);
$section->addTextBreak();

// Tableau JavaScript
$jsTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$jsTable->addRow();
$jsTable->addCell(3000)->addText('Librairie', $strongStyle);
$jsTable->addCell(2000)->addText('Version', $strongStyle);
$jsTable->addCell(4000)->addText('Usage specifique', $strongStyle);

$jsTable->addRow();
$jsTable->addCell(3000)->addText('Alpine.js', $contentStyle);
$jsTable->addCell(2000)->addText('3.x', $contentStyle);
$jsTable->addCell(4000)->addText('Interactivite legere (modals, dropdowns)', $contentStyle);

$jsTable->addRow();
$jsTable->addCell(3000)->addText('Chart.js', $contentStyle);
$jsTable->addCell(2000)->addText('4.x', $contentStyle);
$jsTable->addCell(4000)->addText('Graphiques dashboard admin', $contentStyle);

$jsTable->addRow();
$jsTable->addCell(3000)->addText('Swiper.js', $contentStyle);
$jsTable->addCell(2000)->addText('11.x', $contentStyle);
$jsTable->addCell(4000)->addText('Carrousel images produits', $contentStyle);

$jsTable->addRow();
$jsTable->addCell(3000)->addText('Choices.js', $contentStyle);
$jsTable->addCell(2000)->addText('10.x', $contentStyle);
$jsTable->addCell(4000)->addText('Select boxes ameliores', $contentStyle);

$jsTable->addRow();
$jsTable->addCell(3000)->addText('Flatpickr', $contentStyle);
$jsTable->addCell(2000)->addText('4.x', $contentStyle);
$jsTable->addCell(4000)->addText('Date picker pour locations', $contentStyle);

$section->addTextBreak();

$section->addTitle('3.3 Outils de build et bundling', 2);
$section->addTextBreak();

$section->addText('Solution retenue :', $strongStyle);
$section->addText('Vite.js 5.x avec Laravel Vite Plugin', $contentStyle);

$section->addTextBreak();

$section->addText('Avantages de Vite.js :', $strongStyle);
$section->addText('Hot Module Replacement (HMR) ultra-rapide', $contentStyle);
$section->addText('Build de production optimise', $contentStyle);
$section->addText('Support natif TypeScript et PostCSS', $contentStyle);
$section->addText('Integration transparente avec Laravel 11', $contentStyle);
$section->addText('Tree-shaking automatique pour reduire bundle size', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 4. GESTION DES DONNEES
$section->addTitle('4. Gestion des donnees et base de donnees', 1);
$section->addTextBreak();

$section->addTitle('4.1 Systemes de gestion de base de donnees', 2);
$section->addTextBreak();

$section->addText('Base de donnees principale :', $strongStyle);
$section->addText('MySQL 8.0 Community Edition', $contentStyle);
$section->addText('Licence : GPL v2 (Open Source)', $contentStyle);

$section->addTextBreak();

$section->addText('Justification :', $strongStyle);
$section->addText('Compatibilite native avec Laravel Eloquent', $contentStyle);
$section->addText('Performance excellente pour applications e-commerce', $contentStyle);
$section->addText('Support JSON natif pour donnees semi-structurees', $contentStyle);
$section->addText('Replication et clustering pour scalabilite', $contentStyle);
$section->addText('Outils d\'administration matures (phpMyAdmin, Adminer)', $contentStyle);

$section->addTextBreak();

$section->addTitle('4.2 ORM et outils de migration', 2);
$section->addTextBreak();

$section->addText('Solutions integrees Laravel :', $strongStyle);
$section->addText('Eloquent ORM : Mapping objet-relationnel natif Laravel', $contentStyle);
$section->addText('Laravel Migrations : Versioning schema base de donnees', $contentStyle);
$section->addText('Laravel Seeder : Population donnees de test', $contentStyle);
$section->addText('Laravel Factory : Generation donnees factices', $contentStyle);

$section->addTextBreak();

$section->addText('Packages complementaires :', $strongStyle);
$section->addText('Laravel Model Caching : Cache automatique des modeles', $contentStyle);
$section->addText('Laravel Sluggable : Generation automatique de slugs SEO', $contentStyle);
$section->addText('Laravel Soft Deletes : Suppression logique des donnees', $contentStyle);

$section->addTextBreak();

$section->addTitle('4.3 Cache et optimisation performances', 2);
$section->addTextBreak();

// Tableau cache
$cacheTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$cacheTable->addRow();
$cacheTable->addCell(3000)->addText('Solution', $strongStyle);
$cacheTable->addCell(2000)->addText('Type', $strongStyle);
$cacheTable->addCell(4000)->addText('Usage FarmShop', $strongStyle);

$cacheTable->addRow();
$cacheTable->addCell(3000)->addText('Redis 7.x', $contentStyle);
$cacheTable->addCell(2000)->addText('In-memory', $contentStyle);
$cacheTable->addCell(4000)->addText('Cache sessions, queues, rate limiting', $contentStyle);

$cacheTable->addRow();
$cacheTable->addCell(3000)->addText('Laravel Cache', $contentStyle);
$cacheTable->addCell(2000)->addText('Framework', $contentStyle);
$cacheTable->addCell(4000)->addText('Cache application et requetes DB', $contentStyle);

$cacheTable->addRow();
$cacheTable->addCell(3000)->addText('OPcache', $contentStyle);
$cacheTable->addCell(2000)->addText('PHP', $contentStyle);
$cacheTable->addCell(4000)->addText('Cache bytecode PHP optimise', $contentStyle);

$cacheTable->addRow();
$cacheTable->addCell(3000)->addText('Cloudflare CDN', $contentStyle);
$cacheTable->addCell(2000)->addText('CDN', $contentStyle);
$cacheTable->addCell(4000)->addText('Cache assets statiques (images, CSS, JS)', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 5. APIS ET SERVICES EXTERNES
$section->addTitle('5. APIs et services externes', 1);
$section->addTextBreak();

$section->addTitle('5.1 APIs de paiement', 2);
$section->addTextBreak();

$section->addText('Solution principale :', $strongStyle);
$section->addText('Stripe Payment Platform', $contentStyle);
$section->addText('Integration : Laravel Cashier + Stripe PHP SDK', $contentStyle);
$section->addText('Tarification : 2.9% + 0.25€ par transaction reussie', $contentStyle);

$section->addTextBreak();

$section->addText('Fonctionnalites supportees :', $strongStyle);
$section->addText('Paiements uniques (achats)', $contentStyle);
$section->addText('Preauthorisations (cautions locations)', $contentStyle);
$section->addText('Abonnements recurents (premium memberships)', $contentStyle);
$section->addText('Webhooks pour suivi temps reel', $contentStyle);
$section->addText('3D Secure et Strong Customer Authentication', $contentStyle);
$section->addText('Support multi-devises (EUR, USD, GBP)', $contentStyle);

$section->addTextBreak();

$section->addText('Alternative evaluee :', $strongStyle);
$section->addText('PayPal : Rejete pour integration moins elegante avec Laravel', $contentStyle);
$section->addText('Mollie : Option valable pour marche europeen uniquement', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.2 Services de messagerie et notifications', 2);
$section->addTextBreak();

// Tableau messaging
$messagingTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$messagingTable->addRow();
$messagingTable->addCell(3000)->addText('Service', $strongStyle);
$messagingTable->addCell(2000)->addText('Type', $strongStyle);
$messagingTable->addCell(4000)->addText('Usage', $strongStyle);

$messagingTable->addRow();
$messagingTable->addCell(3000)->addText('SendGrid', $contentStyle);
$messagingTable->addCell(2000)->addText('Email', $contentStyle);
$messagingTable->addCell(4000)->addText('Emails transactionnels et newsletter', $contentStyle);

$messagingTable->addRow();
$messagingTable->addCell(3000)->addText('Laravel Notifications', $contentStyle);
$messagingTable->addCell(2000)->addText('Framework', $contentStyle);
$messagingTable->addCell(4000)->addText('Systeme unifie notifications multi-canal', $contentStyle);

$messagingTable->addRow();
$messagingTable->addCell(3000)->addText('Pusher Channels', $contentStyle);
$messagingTable->addCell(2000)->addText('WebSocket', $contentStyle);
$messagingTable->addCell(4000)->addText('Notifications temps reel (statut commandes)', $contentStyle);

$messagingTable->addRow();
$messagingTable->addCell(3000)->addText('Firebase Cloud Messaging', $contentStyle);
$messagingTable->addCell(2000)->addText('Push', $contentStyle);
$messagingTable->addCell(4000)->addText('Notifications push mobile (PWA)', $contentStyle);

$section->addTextBreak();

$section->addTitle('5.3 Services de stockage et CDN', 2);
$section->addTextBreak();

$section->addText('Stockage fichiers :', $strongStyle);
$section->addText('Amazon S3 avec Laravel Filesystem', $contentStyle);
$section->addText('Usage : Images produits, documents PDF factures', $contentStyle);
$section->addText('Avantages : Scalabilite, fiabilite, integration Laravel native', $contentStyle);

$section->addTextBreak();

$section->addText('CDN (Content Delivery Network) :', $strongStyle);
$section->addText('Cloudflare Pro Plan', $contentStyle);
$section->addText('Fonctionnalites : Cache global, optimisation images, protection DDoS', $contentStyle);
$section->addText('Performance : Reduction latence 50-70% selon localisation', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 6. OUTILS DE DEVELOPPEMENT
$section->addTitle('6. Outils de developpement et productivite', 1);
$section->addTextBreak();

$section->addTitle('6.1 Environnement de developpement', 2);
$section->addTextBreak();

$section->addText('Solution conteneurisee :', $strongStyle);
$section->addText('Laravel Sail (Docker)', $contentStyle);
$section->addText('Avantages : Environnement reproductible, isolation dependances', $contentStyle);

$section->addTextBreak();

$section->addText('Stack technique Sail :', $strongStyle);
$section->addText('PHP 8.3 avec extensions Laravel recommandees', $contentStyle);
$section->addText('MySQL 8.0 pour base de donnees', $contentStyle);
$section->addText('Redis 7.x pour cache et queues', $contentStyle);
$section->addText('Mailpit pour test emails en local', $contentStyle);
$section->addText('Node.js 20.x LTS pour outils frontend', $contentStyle);

$section->addTextBreak();

$section->addTitle('6.2 Outils de testing et qualite code', 2);
$section->addTextBreak();

// Tableau testing
$testingTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$testingTable->addRow();
$testingTable->addCell(3000)->addText('Outil', $strongStyle);
$testingTable->addCell(2000)->addText('Type', $strongStyle);
$testingTable->addCell(4000)->addText('Utilisation', $strongStyle);

$testingTable->addRow();
$testingTable->addCell(3000)->addText('PHPUnit', $contentStyle);
$testingTable->addCell(2000)->addText('Testing', $contentStyle);
$testingTable->addCell(4000)->addText('Tests unitaires et fonctionnels', $contentStyle);

$testingTable->addRow();
$testingTable->addCell(3000)->addText('Laravel Dusk', $contentStyle);
$testingTable->addCell(2000)->addText('E2E Testing', $contentStyle);
$testingTable->addCell(4000)->addText('Tests navigateur automatises', $contentStyle);

$testingTable->addRow();
$testingTable->addCell(3000)->addText('PHP_CodeSniffer', $contentStyle);
$testingTable->addCell(2000)->addText('Code Style', $contentStyle);
$testingTable->addCell(4000)->addText('Respect standards PSR-12', $contentStyle);

$testingTable->addRow();
$testingTable->addCell(3000)->addText('PHPStan', $contentStyle);
$testingTable->addCell(2000)->addText('Static Analysis', $contentStyle);
$testingTable->addCell(4000)->addText('Detection erreurs potentielles', $contentStyle);

$testingTable->addRow();
$testingTable->addCell(3000)->addText('Laravel Pint', $contentStyle);
$testingTable->addCell(2000)->addText('Code Formatting', $contentStyle);
$testingTable->addCell(4000)->addText('Formatage automatique code PHP', $contentStyle);

$section->addTextBreak();

$section->addTitle('6.3 Deployment et DevOps', 2);
$section->addTextBreak();

$section->addText('Solution d\'hebergement :', $strongStyle);
$section->addText('Laravel Forge + DigitalOcean Droplets', $contentStyle);
$section->addText('Avantages : Deployment automatise, scaling facile, monitoring integre', $contentStyle);

$section->addTextBreak();

$section->addText('Pipeline CI/CD :', $strongStyle);
$section->addText('GitHub Actions pour integration continue', $contentStyle);
$section->addText('Tests automatises sur chaque pull request', $contentStyle);
$section->addText('Deployment automatique vers staging/production', $contentStyle);
$section->addText('Notifications Slack/Discord pour equipe', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 7. SOLUTIONS E-COMMERCE
$section->addTitle('7. Solutions specifiques e-commerce', 1);
$section->addTextBreak();

$section->addTitle('7.1 Packages e-commerce Laravel', 2);
$section->addTextBreak();

$section->addText('Package principal evalué :', $strongStyle);
$section->addText('Bagisto E-commerce Package', $contentStyle);
$section->addText('Decision : Rejete pour complexite excessive par rapport aux besoins', $contentStyle);

$section->addTextBreak();

$section->addText('Approche retenue :', $strongStyle);
$section->addText('Developpement custom base sur Laravel 11 LTS', $contentStyle);
$section->addText('Justification : Controle total, optimisation performances, flexibilite maximale', $contentStyle);

$section->addTextBreak();

$section->addText('Composants e-commerce reutilises :', $strongStyle);
$section->addText('Shopping Cart : Package hardevine/shoppingcart', $contentStyle);
$section->addText('Product Variants : Package genetsis/product-variants', $contentStyle);
$section->addText('Price Calculator : Package moneyphp/money pour devises', $contentStyle);
$section->addText('Tax Management : Package mpociot/vat-calculator', $contentStyle);

$section->addTextBreak();

$section->addTitle('7.2 Gestion des stocks et inventaire', 2);
$section->addTextBreak();

// Tableau inventaire
$inventoryTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$inventoryTable->addRow();
$inventoryTable->addCell(3000)->addText('Fonctionnalite', $strongStyle);
$inventoryTable->addCell(2000)->addText('Solution', $strongStyle);
$inventoryTable->addCell(4000)->addText('Implementation', $strongStyle);

$inventoryTable->addRow();
$inventoryTable->addCell(3000)->addText('Stock tracking', $contentStyle);
$inventoryTable->addCell(2000)->addText('Custom Laravel', $contentStyle);
$inventoryTable->addCell(4000)->addText('Models Product, Stock, StockMovement', $contentStyle);

$inventoryTable->addRow();
$inventoryTable->addCell(3000)->addText('Low stock alerts', $contentStyle);
$inventoryTable->addCell(2000)->addText('Laravel Jobs', $contentStyle);
$inventoryTable->addCell(4000)->addText('Queue jobs + notifications automatiques', $contentStyle);

$inventoryTable->addRow();
$inventoryTable->addCell(3000)->addText('Barcode generation', $contentStyle);
$inventoryTable->addCell(2000)->addText('Package tiers', $contentStyle);
$inventoryTable->addCell(4000)->addText('milon/barcode pour codes-barres EAN-13', $contentStyle);

$inventoryTable->addRow();
$inventoryTable->addCell(3000)->addText('Inventory reports', $contentStyle);
$inventoryTable->addCell(2000)->addText('Laravel Excel', $contentStyle);
$inventoryTable->addCell(4000)->addText('Export automatise Excel/PDF', $contentStyle);

$section->addTextBreak();

$section->addTitle('7.3 Systeme de location et cautions', 2);
$section->addTextBreak();

$section->addText('Specificite FarmShop :', $strongStyle);
$section->addText('Systeme dual achat/location necessite une approche custom', $contentStyle);

$section->addTextBreak();

$section->addText('Composants techniques :', $strongStyle);
$section->addText('Carbon : Package nesbot/carbon pour gestion dates locations', $contentStyle);
$section->addText('Laravel Scheduler : Taches automatisees (rappels, penalites)', $contentStyle);
$section->addText('Stripe Preauth : Preauthorisation cautions via Laravel Cashier', $contentStyle);
$section->addText('PDF Generator : Package barryvdh/laravel-dompdf pour contrats', $contentStyle);

$section->addTextBreak();

$section->addText('Workflow location :', $strongStyle);
$section->addText('1. Selection produit + dates (Flatpickr calendar)', $contentStyle);
$section->addText('2. Calcul prix + caution automatique', $contentStyle);
$section->addText('3. Preauthorisation Stripe pour caution', $contentStyle);
$section->addText('4. Generation contrat PDF automatique', $contentStyle);
$section->addText('5. Notifications rappel retour (Laravel Scheduler)', $contentStyle);
$section->addText('6. Gestion penalites retard/dommages', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// 8. ANALYSE COMPARATIVE
$section->addTitle('8. Analyse comparative et selection', 1);
$section->addTextBreak();

$section->addTitle('8.1 Criteres de selection', 2);
$section->addTextBreak();

$section->addText('Criteres techniques :', $strongStyle);
$section->addText('Compatibilite Laravel 11 LTS (score 0-5)', $contentStyle);
$section->addText('Performance et optimisation (score 0-5)', $contentStyle);
$section->addText('Qualite documentation (score 0-5)', $contentStyle);
$section->addText('Stabilite et maturite (score 0-5)', $contentStyle);
$section->addText('Communaute et support (score 0-5)', $contentStyle);

$section->addTextBreak();

$section->addText('Criteres business :', $strongStyle);
$section->addText('Cout d\'acquisition et licensing', $contentStyle);
$section->addText('Temps d\'integration estime', $contentStyle);
$section->addText('Maintenance et evolutivite', $contentStyle);
$section->addText('Vendor lock-in et dependances', $contentStyle);

$section->addTextBreak();

$section->addTitle('8.2 Matrice de decision technique', 2);
$section->addTextBreak();

// Tableau matrice
$matrixTable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
$matrixTable->addRow();
$matrixTable->addCell(2500)->addText('Categorie', $strongStyle);
$matrixTable->addCell(3000)->addText('Solution retenue', $strongStyle);
$matrixTable->addCell(1000)->addText('Score', $strongStyle);
$matrixTable->addCell(2500)->addText('Alternative', $strongStyle);

$matrixTable->addRow();
$matrixTable->addCell(2500)->addText('Framework PHP', $contentStyle);
$matrixTable->addCell(3000)->addText('Laravel 11 LTS', $contentStyle);
$matrixTable->addCell(1000)->addText('5/5', $contentStyle);
$matrixTable->addCell(2500)->addText('Symfony, CodeIgniter', $contentStyle);

$matrixTable->addRow();
$matrixTable->addCell(2500)->addText('CSS Framework', $contentStyle);
$matrixTable->addCell(3000)->addText('Tailwind CSS 3.4', $contentStyle);
$matrixTable->addCell(1000)->addText('5/5', $contentStyle);
$matrixTable->addCell(2500)->addText('Bootstrap, Bulma', $contentStyle);

$matrixTable->addRow();
$matrixTable->addCell(2500)->addText('JavaScript', $contentStyle);
$matrixTable->addCell(3000)->addText('Alpine.js', $contentStyle);
$matrixTable->addCell(1000)->addText('4/5', $contentStyle);
$matrixTable->addCell(2500)->addText('Vue.js, React', $contentStyle);

$matrixTable->addRow();
$matrixTable->addCell(2500)->addText('Base de donnees', $contentStyle);
$matrixTable->addCell(3000)->addText('MySQL 8.0', $contentStyle);
$matrixTable->addCell(1000)->addText('5/5', $contentStyle);
$matrixTable->addCell(2500)->addText('PostgreSQL, SQLite', $contentStyle);

$matrixTable->addRow();
$matrixTable->addCell(2500)->addText('Cache', $contentStyle);
$matrixTable->addCell(3000)->addText('Redis 7.x', $contentStyle);
$matrixTable->addCell(1000)->addText('5/5', $contentStyle);
$matrixTable->addCell(2500)->addText('Memcached, File', $contentStyle);

$matrixTable->addRow();
$matrixTable->addCell(2500)->addText('Paiement', $contentStyle);
$matrixTable->addCell(3000)->addText('Stripe + Cashier', $contentStyle);
$matrixTable->addCell(1000)->addText('5/5', $contentStyle);
$matrixTable->addCell(2500)->addText('PayPal, Mollie', $contentStyle);

$matrixTable->addRow();
$matrixTable->addCell(2500)->addText('Admin Panel', $contentStyle);
$matrixTable->addCell(3000)->addText('Laravel Filament', $contentStyle);
$matrixTable->addCell(1000)->addText('5/5', $contentStyle);
$matrixTable->addCell(2500)->addText('Laravel Nova, Custom', $contentStyle);

$section->addTextBreak();

$section->addTitle('8.3 Recommandations finales', 2);
$section->addTextBreak();

$section->addText('Stack technique recommande :', $strongStyle);
$section->addTextBreak();

$section->addText('Backend :', $strongStyle);
$section->addText('Laravel 11 LTS + MySQL 8.0 + Redis 7.x', $contentStyle);
$section->addText('Packages : Sanctum, Horizon, Scout, Cashier, Filament', $contentStyle);

$section->addTextBreak();

$section->addText('Frontend :', $strongStyle);
$section->addText('Tailwind CSS 3.4 + Alpine.js + Vite.js 5.x', $contentStyle);
$section->addText('Librairies : Chart.js, Swiper.js, Flatpickr', $contentStyle);

$section->addTextBreak();

$section->addText('Services externes :', $strongStyle);
$section->addText('Stripe (paiements) + SendGrid (emails) + Cloudflare (CDN)', $contentStyle);
$section->addText('AWS S3 (stockage) + Laravel Forge (deployment)', $contentStyle);

$section->addTextBreak();

$section->addText('Justification globale :', $strongStyle);
$section->addText('Cette stack offre le meilleur equilibre entre rapidite de developpement, performance, maintenabilite et cout total de possession pour le projet FarmShop.', $contentStyle);

// Nouvelle page
$section->addPageBreak();

// CONCLUSION
$section->addTitle('Conclusion', 1);
$section->addTextBreak();

$section->addText('Cette recherche de progiciels et solutions techniques a permis d\'identifier un ecosysteme coherent et performant pour le developpement de FarmShop selon les principes RAD.', $contentStyle);

$section->addTextBreak();

$section->addText('La selection privilegie des solutions matures, bien documentees et largement adoptees par la communaute, garantissant ainsi la perennite et la maintenabilite du projet.', $contentStyle);

$section->addTextBreak();

$section->addText('L\'approche hybride - combinant packages existants pour les fonctionnalites generiques et developpement custom pour les specificites metier - permet d\'optimiser le time-to-market tout en conservant la flexibilite necessaire aux besoins specifiques de FarmShop.', $contentStyle);

$section->addTextBreak();

$section->addText('La stack technique retenue (Laravel 11 LTS + Tailwind CSS + Alpine.js + MySQL + Redis + Stripe) constitue une base solide pour le developpement d\'une plateforme e-commerce moderne, performante et evolutive.', $contentStyle);

$section->addTextBreak(2);

// BIBLIOGRAPHIE
$section->addTitle('Bibliographie', 1);
$section->addTextBreak();

$section->addText('LARAVEL. S.d. Laravel 11 Documentation. Site web sur INTERNET. <laravel.com/docs/11.x>. Derniere consultation : le 16/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('TAILWIND CSS. S.d. Tailwind CSS Documentation. Site web sur INTERNET. <tailwindcss.com/docs>. Derniere consultation : le 16/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('ALPINE.JS. S.d. Alpine.js Documentation. Site web sur INTERNET. <alpinejs.dev/start-here>. Derniere consultation : le 16/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('STRIPE. S.d. Stripe API Documentation. Site web sur INTERNET. <stripe.com/docs/api>. Derniere consultation : le 16/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('PACKAGIST. S.d. The PHP Package Repository. Site web sur INTERNET. <packagist.org>. Derniere consultation : le 16/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('SPATIE. S.d. Laravel Permission Package. Site web sur INTERNET. <spatie.be/docs/laravel-permission>. Derniere consultation : le 16/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('FILAMENT. S.d. Filament Admin Panel for Laravel. Site web sur INTERNET. <filamentphp.com/docs>. Derniere consultation : le 16/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('VITE.JS. S.d. Vite Next Generation Frontend Tooling. Site web sur INTERNET. <vitejs.dev>. Derniere consultation : le 16/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('REDIS. S.d. Redis Documentation. Site web sur INTERNET. <redis.io/documentation>. Derniere consultation : le 16/07-2025.', $contentStyle);
$section->addTextBreak();

$section->addText('MYSQL. S.d. MySQL 8.0 Reference Manual. Site web sur INTERNET. <dev.mysql.com/doc/refman/8.0>. Derniere consultation : le 16/07-2025.', $contentStyle);

$section->addTextBreak(4);

// Footer academique
$section->addText('Document genere le 16 juillet 2025', [
    'name' => 'Inter', 'size' => 10, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('MEFTAH Soufiane - Bachelier en Informatique de gestion', [
    'name' => 'Inter', 'size' => 10, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

$section->addText('Institut des Carrieres Commerciales - Bruxelles', [
    'name' => 'Inter', 'size' => 10, 'lang' => 'fr-BE'
], ['alignment' => Jc::CENTER]);

// Sauvegarder avec encodage UTF-8 pour Office 365
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save(__DIR__ . '/13_Recherche_Progiciels_Solutions.docx');

echo "Livrable 13 - Recherche de progiciels et solutions techniques cree avec succes !\n";
echo "Emplacement : " . __DIR__ . "/13_Recherche_Progiciels_Solutions.docx\n";
echo "Document RAD avec analyse complete des solutions techniques Laravel 11 LTS !\n";

?>
