<?php
/**
 * Générateur de rapport Word FarmShop - Version simplifiée
 * Crée directement un document Word professionnel
 */

require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\Language;

function createFarmShopReport()
{
    $phpWord = new PhpWord();
    
    // Configuration du document
    $properties = $phpWord->getDocInfo();
    $properties->setCreator('Équipe FarmShop');
    $properties->setTitle('Rapport Final - Projet FarmShop');
    $properties->setDescription('Plateforme e-commerce agricole avec système de location');
    $properties->setSubject('Développement d\'une plateforme e-commerce agricole');
    $properties->setKeywords('FarmShop, Laravel, e-commerce, agriculture, location');
    
    $phpWord->setDefaultFontName('Times New Roman');
    $phpWord->setDefaultFontSize(12);
    
    // Styles
    $phpWord->addTitleStyle(1, [
        'name' => 'Times New Roman',
        'size' => 18,
        'bold' => true,
        'color' => '2d5016'
    ], [
        'spaceAfter' => Converter::cmToTwip(0.5),
        'spaceBefore' => Converter::cmToTwip(1)
    ]);
    
    $phpWord->addTitleStyle(2, [
        'name' => 'Times New Roman', 
        'size' => 16,
        'bold' => true,
        'color' => '8b4513'
    ], [
        'spaceAfter' => Converter::cmToTwip(0.3),
        'spaceBefore' => Converter::cmToTwip(0.8)
    ]);
    
    $phpWord->addParagraphStyle('Normal', [
        'alignment' => 'both',
        'spaceAfter' => Converter::cmToTwip(0.3),
        'lineHeight' => 1.15
    ]);
    
    $phpWord->addParagraphStyle('Centered', [
        'alignment' => 'center',
        'spaceAfter' => Converter::cmToTwip(0.5)
    ]);
    
    // Page de garde
    $section = $phpWord->addSection([
        'marginTop' => Converter::cmToTwip(3),
        'marginBottom' => Converter::cmToTwip(3),
        'marginLeft' => Converter::cmToTwip(2.5),
        'marginRight' => Converter::cmToTwip(2.5)
    ]);
    
    $section->addTextBreak(3);
    
    $section->addText('RAPPORT FINAL', [
        'name' => 'Times New Roman',
        'size' => 24,
        'bold' => true,
        'color' => '2d5016'
    ], 'Centered');
    
    $section->addTextBreak(1);
    
    $section->addText('FARMSHOP', [
        'name' => 'Times New Roman',
        'size' => 28,
        'bold' => true,
        'color' => '2d5016'
    ], 'Centered');
    
    $section->addTextBreak(1);
    
    $section->addText('L\'agriculture flexible, de l\'achat à la location en un clic', [
        'name' => 'Times New Roman',
        'size' => 16,
        'italic' => true,
        'color' => '8b4513'
    ], 'Centered');
    
    $section->addTextBreak(3);
    
    $section->addText('Développement d\'une plateforme e-commerce agricole', [
        'name' => 'Times New Roman',
        'size' => 14,
        'bold' => true
    ], 'Centered');
    
    $section->addText('avec système de location intégré', [
        'name' => 'Times New Roman',
        'size' => 14,
        'bold' => true
    ], 'Centered');
    
    $section->addTextBreak(4);
    
    $section->addText('Technologies utilisées :', [
        'name' => 'Times New Roman',
        'size' => 12,
        'bold' => true
    ], 'Centered');
    
    $section->addText('Laravel 11.45.1 • PHP 8.4.10 • MariaDB 11.5.2', [
        'name' => 'Times New Roman',
        'size' => 11
    ], 'Centered');
    
    $section->addText('Tailwind CSS 4.1.11 • Alpine.js 3.14.9 • Stripe 17.4', [
        'name' => 'Times New Roman',
        'size' => 11
    ], 'Centered');
    
    $section->addTextBreak(4);
    
    $section->addText('Rapport final - Version 1.0', [
        'name' => 'Times New Roman',
        'size' => 12,
        'bold' => true
    ], 'Centered');
    
    $section->addText('17 août 2025', [
        'name' => 'Times New Roman',
        'size' => 12
    ], 'Centered');
    
    $section->addPageBreak();
    
    // Table des matières
    $section->addTitle('Table des matières', 1);
    
    $contents = [
        '1. Présentation du projet ................................. 3',
        '2. Analyse du marché agricole ............................. 5', 
        '3. Étude de faisabilité ................................... 8',
        '4. Spécifications techniques .............................. 12',
        '5. Architecture système ................................... 16',
        '6. Modélisation de la base de données ..................... 20',
        '7. Interface utilisateur et expérience ................... 25',
        '8. Système de gestion des commandes ....................... 30',
        '9. Module de location d\'équipements ........................ 35',
        '10. Système de paiement intégré ........................... 40',
        '11. Gestion des utilisateurs .............................. 45',
        '12. Blog et gestion de contenu ............................ 50',
        '13. Sécurité et protection des données .................... 55',
        '14. Tests et validation .................................... 60',
        '15. Plan de déploiement .................................... 65',
        '16. Business plan et projections financières .............. 70',
        '17. Design graphique et charte ............................. 75',
        '18. Aspects juridiques et conformité ....................... 80',
        '19. Annexes techniques ..................................... 85',
        '20. Bibliographie et sources ............................... 90'
    ];
    
    foreach ($contents as $item) {
        $section->addText($item, [
            'name' => 'Times New Roman',
            'size' => 11
        ], 'Normal');
    }
    
    $section->addPageBreak();
    
    // Chapitre 1: Présentation du projet
    $section->addTitle('1. Présentation du projet', 1);
    
    $section->addText('FarmShop représente une innovation majeure dans le secteur de l\'e-commerce agricole français. Cette plateforme digitale révolutionnaire combine la vente traditionnelle d\'équipements agricoles avec un système de location flexible, répondant ainsi aux besoins diversifiés des exploitants agricoles modernes.', [
        'name' => 'Times New Roman',
        'size' => 12
    ], 'Normal');
    
    $section->addTitle('1.1 Contexte et problématique', 2);
    
    $section->addText('Le secteur agricole français traverse une période de transformation digitale accélérée. Les exploitants font face à des défis économiques croissants : augmentation des coûts d\'équipement, nécessité de modernisation technologique, et pression sur les marges bénéficiaires. Dans ce contexte, l\'accès à l\'équipement agricole devient un enjeu stratégique majeur.', [
        'name' => 'Times New Roman',
        'size' => 12
    ], 'Normal');
    
    $section->addText('Problématique centrale : Comment démocratiser l\'accès aux équipements agricoles modernes tout en proposant une expérience d\'achat digitale optimale ?', [
        'name' => 'Times New Roman',
        'size' => 11,
        'bold' => true,
        'color' => '2d5016'
    ], [
        'alignment' => 'both',
        'spaceAfter' => Converter::cmToTwip(0.5),
        'spaceBefore' => Converter::cmToTwip(0.5),
        'borderTopSize' => 6,
        'borderTopColor' => '2d5016',
        'borderBottomSize' => 6,
        'borderBottomColor' => '2d5016'
    ]);
    
    $section->addTitle('1.2 Vision et objectifs', 2);
    
    $section->addText('FarmShop ambitionne de devenir la référence française de l\'e-commerce agricole en proposant une solution complète et flexible. Notre vision s\'articule autour de trois piliers fondamentaux :', [
        'name' => 'Times New Roman',
        'size' => 12
    ], 'Normal');
    
    $section->addListItem('Accessibilité : Démocratiser l\'accès aux équipements agricoles grâce à des solutions de location flexibles', 0, [
        'name' => 'Times New Roman',
        'size' => 12
    ]);
    
    $section->addListItem('Innovation : Intégrer les dernières technologies web pour offrir une expérience utilisateur exceptionnelle', 0, [
        'name' => 'Times New Roman',
        'size' => 12
    ]);
    
    $section->addListItem('Durabilité : Promouvoir l\'économie circulaire dans le secteur agricole par la mutualisation des équipements', 0, [
        'name' => 'Times New Roman',
        'size' => 12
    ]);
    
    $section->addPageBreak();
    
    // Chapitre 18: Aspects juridiques (comme demandé en référence)
    $section->addTitle('18. Aspects juridiques et conformité', 1);
    
    $section->addText('La conformité juridique de FarmShop constitue un pilier fondamental du projet, garantissant la sécurité juridique de l\'entreprise et la protection des utilisateurs. Cette section détaille l\'ensemble des aspects réglementaires et légaux intégrés dans la plateforme.', [
        'name' => 'Times New Roman',
        'size' => 12
    ], 'Normal');
    
    $section->addTitle('18.1 Conformité RGPD et protection des données', 2);
    
    $section->addText('FarmShop applique rigoureusement le Règlement Général sur la Protection des Données (RGPD) dans toutes ses dimensions. La protection des données personnelles des utilisateurs constitue une priorité absolue, intégrée dès la conception de la plateforme (Privacy by Design).', [
        'name' => 'Times New Roman',
        'size' => 12
    ], 'Normal');
    
    $section->addTitle('18.1.1 Principes fondamentaux appliqués', 3);
    
    $principles = [
        'Licéité du traitement : Consentement explicite et bases légales clairement identifiées',
        'Minimisation des données : Collecte limitée aux données strictement nécessaires', 
        'Exactitude : Procédures de mise à jour et de correction des données',
        'Limitation de la conservation : Durées de conservation définies et respectées',
        'Sécurité : Chiffrement, pseudonymisation et mesures de sécurité techniques'
    ];
    
    foreach ($principles as $principle) {
        $section->addListItem($principle, 0, [
            'name' => 'Times New Roman',
            'size' => 12
        ]);
    }
    
    $section->addTitle('18.2 Droit du commerce électronique', 2);
    
    $section->addText('FarmShop respecte intégralement la réglementation française et européenne du commerce électronique, notamment la Loi pour la Confiance dans l\'Économie Numérique (LCEN) et la directive européenne sur le commerce électronique.', [
        'name' => 'Times New Roman',
        'size' => 12
    ], 'Normal');
    
    $section->addTitle('18.2.1 Obligations d\'information', 3);
    
    $obligations = [
        'Identification de l\'entreprise : Dénomination sociale, adresse, RCS, TVA',
        'Conditions générales de vente : Accessibles et acceptées explicitement',
        'Prix et modalités de paiement : Affichage TTC, frais de livraison',
        'Délais de livraison : Information précise et mise à jour temps réel',
        'Droit de rétractation : Formulaire type et procédure simplifiée'
    ];
    
    foreach ($obligations as $obligation) {
        $section->addListItem($obligation, 0, [
            'name' => 'Times New Roman', 
            'size' => 12
        ]);
    }
    
    $section->addText('Spécificité location : Le système de location FarmShop applique un régime juridique mixte combinant vente (équipements) et prestation de services (location), avec des CGV adaptées à chaque modalité.', [
        'name' => 'Times New Roman',
        'size' => 11,
        'bold' => true,
        'color' => '2d5016'
    ], [
        'alignment' => 'both',
        'spaceAfter' => Converter::cmToTwip(0.5),
        'spaceBefore' => Converter::cmToTwip(0.5),
        'borderTopSize' => 6,
        'borderTopColor' => '2d5016',
        'borderBottomSize' => 6,
        'borderBottomColor' => '2d5016'
    ]);
    
    $section->addTitle('18.3 Responsabilité et assurances', 2);
    
    $section->addText('La structure juridique de FarmShop intègre une répartition claire des responsabilités entre la plateforme, les vendeurs et les utilisateurs finaux. Cette approche garantit une couverture optimale des risques tout en préservant l\'agilité opérationnelle.', [
        'name' => 'Times New Roman',
        'size' => 12
    ], 'Normal');
    
    $section->addTitle('18.3.1 Couverture assurantielle', 3);
    
    $assurances = [
        'Responsabilité civile professionnelle : Couverture globale 5M€',
        'Cyber-assurance : Protection contre les incidents de sécurité',
        'Assurance produits : Couverture des équipements en location',
        'Protection juridique : Assistance en cas de contentieux',
        'Assurance crédit : Couverture des impayés professionnels'
    ];
    
    foreach ($assurances as $assurance) {
        $section->addListItem($assurance, 0, [
            'name' => 'Times New Roman',
            'size' => 12
        ]);
    }
    
    $section->addTitle('18.4 Propriété intellectuelle', 2);
    
    $section->addText('La protection de la propriété intellectuelle de FarmShop s\'articule autour d\'une stratégie globale couvrant tous les actifs immatériels de l\'entreprise.', [
        'name' => 'Times New Roman',
        'size' => 12
    ], 'Normal');
    
    $propriete = [
        'Marque FarmShop : Dépôt INPI classes 9, 35, 42',
        'Droits d\'auteur : Protection du code source et des contenus',
        'Brevets logiciels : Protection des innovations techniques',
        'Designs : Protection de l\'interface utilisateur',
        'Noms de domaine : Portefeuille sécurisé multi-extensions'
    ];
    
    foreach ($propriete as $item) {
        $section->addListItem($item, 0, [
            'name' => 'Times New Roman',
            'size' => 12
        ]);
    }
    
    $section->addText('Cette approche juridique complète garantit la conformité réglementaire de FarmShop et minimise les risques juridiques, créant un environnement sécurisé pour toutes les parties prenantes.', [
        'name' => 'Times New Roman',
        'size' => 12
    ], 'Normal');
    
    return $phpWord;
}

function main()
{
    $outputFile = 'FarmShop_Rapport_Final.docx';
    
    echo "=== GÉNÉRATEUR DE RAPPORT FARMSHOP ===\n";
    echo "Création d'un document Word professionnel\n";
    echo "Style académique - Format rapport final\n";
    echo str_repeat("=", 50) . "\n";
    
    try {
        echo "Génération du rapport FarmShop...\n";
        $phpWord = createFarmShopReport();
        
        echo "Sauvegarde du document : $outputFile\n";
        
        if (file_exists($outputFile)) {
            unlink($outputFile);
            echo "Ancien fichier supprimé\n";
        }
        
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($outputFile);
        
        if (file_exists($outputFile)) {
            $fileSize = filesize($outputFile) / (1024 * 1024);
            echo "Document créé avec succès !\n";
            echo "Fichier : $outputFile\n";
            echo "Taille : " . number_format($fileSize, 2) . " MB\n";
            echo "Rapport généré avec succès !\n";
            
            // Ouvrir automatiquement
            if (PHP_OS_FAMILY === 'Windows') {
                exec("start \"\" \"$outputFile\"");
                echo "Document ouvert automatiquement\n";
            }
        }
        
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage() . "\n";
        exit(1);
    }
}

if (php_sapi_name() === 'cli') {
    main();
}
?>
