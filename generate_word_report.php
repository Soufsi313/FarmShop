<?php
/**
 * Générateur de rapport Word FarmShop
 * Crée directement un document Word professionnel sans conversion HTML
 */

require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\Language;

class FarmShopReportGenerator
{
    private $phpWord;
    private $section;
    
    public function __construct()
    {
        $this->phpWord = new PhpWord();
        $this->setupDocument();
        $this->setupStyles();
    }
    
    private function setupDocument()
    {
        // Propriétés du document
        $properties = $this->phpWord->getDocInfo();
        $properties->setCreator('Équipe FarmShop');
        $properties->setCompany('FarmShop');
        $properties->setTitle('Rapport Final - Projet FarmShop');
        $properties->setDescription('Rapport final complet du projet FarmShop - Plateforme e-commerce agricole avec système de location');
        $properties->setCategory('Rapport académique');
        $properties->setLastModifiedBy('Équipe FarmShop');
        $properties->setCreated(time());
        $properties->setModified(time());
        $properties->setSubject('Développement d\'une plateforme e-commerce agricole');
        $properties->setKeywords('FarmShop, Laravel 11.45.1, e-commerce, agriculture, location, Tailwind CSS, Alpine.js');
        
        // Configuration par défaut
        $this->phpWord->getSettings()->setThemeFontLang(new Language(Language::FR_FR));
        $this->phpWord->setDefaultFontName('Times New Roman');
        $this->phpWord->setDefaultFontSize(12);
    }
    
    private function setupStyles()
    {
        // Style pour page de garde
        $this->phpWord->addFontStyle('TitlePage', [
            'name' => 'Times New Roman',
            'size' => 24,
            'bold' => true,
            'color' => '2d5016'
        ]);
        
        $this->phpWord->addParagraphStyle('TitlePageCenter', [
            'alignment' => 'center',
            'spaceAfter' => Converter::cmToTwip(1)
        ]);
        
        // Styles pour titres
        $this->phpWord->addTitleStyle(1, [
            'name' => 'Times New Roman',
            'size' => 18,
            'bold' => true,
            'color' => '2d5016'
        ], [
            'spaceAfter' => Converter::cmToTwip(0.5),
            'spaceBefore' => Converter::cmToTwip(1),
            'keepNext' => true
        ]);
        
        $this->phpWord->addTitleStyle(2, [
            'name' => 'Times New Roman',
            'size' => 16,
            'bold' => true,
            'color' => '8b4513'
        ], [
            'spaceAfter' => Converter::cmToTwip(0.3),
            'spaceBefore' => Converter::cmToTwip(0.8)
        ]);
        
        $this->phpWord->addTitleStyle(3, [
            'name' => 'Times New Roman',
            'size' => 14,
            'bold' => true,
            'color' => 'ea580c'
        ], [
            'spaceAfter' => Converter::cmToTwip(0.2),
            'spaceBefore' => Converter::cmToTwip(0.5)
        ]);
        
        // Style paragraphe normal
        $this->phpWord->addParagraphStyle('Normal', [
            'alignment' => 'both',
            'spaceAfter' => Converter::cmToTwip(0.3),
            'lineHeight' => 1.15,
            'indent' => 0
        ]);
        
        // Style pour citations et encadrés
        $this->phpWord->addParagraphStyle('Encadre', [
            'alignment' => 'both',
            'spaceAfter' => Converter::cmToTwip(0.5),
            'spaceBefore' => Converter::cmToTwip(0.5),
            'lineHeight' => 1.1,
            'borderTopSize' => 6,
            'borderTopColor' => '2d5016',
            'borderBottomSize' => 6,
            'borderBottomColor' => '2d5016',
            'borderLeftSize' => 2,
            'borderLeftColor' => 'cccccc',
            'borderRightSize' => 2,
            'borderRightColor' => 'cccccc',
            'indent' => Converter::cmToTwip(0.5),
            'hangingIndent' => 0
        ]);
        
        // Style pour listes
        $this->phpWord->addNumberingStyle('listNumber', [
            'type' => 'hybridMultilevel',
            'levels' => [
                ['format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360],
                ['format' => 'lowerLetter', 'text' => '%2)', 'left' => 720, 'hanging' => 360, 'tabPos' => 720],
                ['format' => 'lowerRoman', 'text' => '%3.', 'left' => 1080, 'hanging' => 360, 'tabPos' => 1080]
            ]
        ]);
        
        // Style tableau
        $this->phpWord->addTableStyle('TableStyle', [
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80,
            'alignment' => 'center'
        ]);
    }
    
    private function createCoverPage()
    {
        $section = $this->phpWord->addSection([
            'marginTop' => Converter::cmToTwip(3),
            'marginBottom' => Converter::cmToTwip(3),
            'marginLeft' => Converter::cmToTwip(2.5),
            'marginRight' => Converter::cmToTwip(2.5)
        ]);
        
        // Espacement haut
        $section->addTextBreak(3);
        
        // Titre principal
        $section->addText('RAPPORT FINAL', 'TitlePage', 'TitlePageCenter');
        $section->addTextBreak(1);
        
        // Nom du projet
        $section->addText('FARMSHOP', [
            'name' => 'Times New Roman',
            'size' => 28,
            'bold' => true,
            'color' => '2d5016'
        ], 'TitlePageCenter');
        
        $section->addTextBreak(1);
        
        // Sous-titre
        $section->addText('L\'agriculture flexible, de l\'achat à la location en un clic', [
            'name' => 'Times New Roman',
            'size' => 16,
            'italic' => true,
            'color' => '8b4513'
        ], 'TitlePageCenter');
        
        $section->addTextBreak(3);
        
        // Description
        $section->addText('Développement d\'une plateforme e-commerce agricole', [
            'name' => 'Times New Roman',
            'size' => 14,
            'bold' => true
        ], 'TitlePageCenter');
        
        $section->addText('avec système de location intégré', [
            'name' => 'Times New Roman',
            'size' => 14,
            'bold' => true
        ], 'TitlePageCenter');
        
        $section->addTextBreak(4);
        
        // Technologies
        $section->addText('Technologies utilisées :', [
            'name' => 'Times New Roman',
            'size' => 12,
            'bold' => true
        ], 'TitlePageCenter');
        
        $section->addText('Laravel 11.45.1 • PHP 8.4.10 • MariaDB 11.5.2', [
            'name' => 'Times New Roman',
            'size' => 11
        ], 'TitlePageCenter');
        
        $section->addText('Tailwind CSS 4.1.11 • Alpine.js 3.14.9 • Stripe 17.4', [
            'name' => 'Times New Roman',
            'size' => 11
        ], 'TitlePageCenter');
        
        $section->addTextBreak(4);
        
        // Date et auteur
        $section->addText('Rapport final - Version 1.0', [
            'name' => 'Times New Roman',
            'size' => 12,
            'bold' => true
        ], 'TitlePageCenter');
        
        $section->addText('17 août 2025', [
            'name' => 'Times New Roman',
            'size' => 12
        ], 'TitlePageCenter');
        
        $section->addPageBreak();
    }
    
    private function createTableOfContents()
    {
        $this->section = $this->phpWord->addSection([
            'marginTop' => Converter::cmToTwip(2.5),
            'marginBottom' => Converter::cmToTwip(2.5),
            'marginLeft' => Converter::cmToTwip(2.5),
            'marginRight' => Converter::cmToTwip(2.5)
        ]);
        
        $this->section->addTitle('Table des matières', 1);
        
        $contents = [
            ['1. Présentation du projet', 3],
            ['2. Analyse du marché agricole', 5],
            ['3. Étude de faisabilité', 8],
            ['4. Spécifications techniques', 12],
            ['5. Architecture système', 16],
            ['6. Modélisation de la base de données', 20],
            ['7. Interface utilisateur et expérience', 25],
            ['8. Système de gestion des commandes', 30],
            ['9. Module de location d\'équipements', 35],
            ['10. Système de paiement intégré', 40],
            ['11. Gestion des utilisateurs', 45],
            ['12. Blog et gestion de contenu', 50],
            ['13. Sécurité et protection des données', 55],
            ['14. Tests et validation', 60],
            ['15. Plan de déploiement', 65],
            ['16. Business plan et projections financières', 70],
            ['17. Design graphique et charte', 75],
            ['18. Aspects juridiques et conformité', 80],
            ['19. Annexes techniques', 85],
            ['20. Bibliographie et sources', 90]
        ];
        
        foreach ($contents as $item) {
            $this->section->addText($item[0] . str_repeat('.', 50 - strlen($item[0])) . $item[1], [
                'name' => 'Times New Roman',
                'size' => 11
            ], 'Normal');
        }
        
        $this->section->addPageBreak();
    }
    
    private function addChapter($title, $content)
    {
        $this->section->addTitle($title, 1);
        
        foreach ($content as $element) {
            switch ($element['type']) {
                case 'paragraph':
                    $this->section->addText($element['text'], [
                        'name' => 'Times New Roman',
                        'size' => 12
                    ], 'Normal');
                    break;
                    
                case 'subtitle':
                    $this->section->addTitle($element['text'], 2);
                    break;
                    
                case 'subsubtitle':
                    $this->section->addTitle($element['text'], 3);
                    break;
                    
                case 'list':
                    foreach ($element['items'] as $item) {
                        $this->section->addListItem($item, 0, [
                            'name' => 'Times New Roman',
                            'size' => 12
                        ], 'listNumber', 'Normal');
                    }
                    break;
                    
                case 'encadre':
                    $this->section->addText($element['text'], [
                        'name' => 'Times New Roman',
                        'size' => 11,
                        'bold' => true,
                        'color' => '2d5016'
                    ], 'Encadre');
                    break;
                    
                case 'table':
                    $this->addTable($element);
                    break;
            ]
        }
        
        $this->section->addPageBreak();
    }
    
    private function addTable($tableData)
    {
        $table = $this->section->addTable('TableStyle');
        
        // En-tête
        if (isset($tableData['headers'])) {
            $table->addRow();
            foreach ($tableData['headers'] as $header) {
                $table->addCell(2000)->addText($header, [
                    'name' => 'Times New Roman',
                    'size' => 11,
                    'bold' => true
                ]);
            }
        }
        
        // Données
        foreach ($tableData['rows'] as $row) {
            $table->addRow();
            foreach ($row as $cell) {
                $table->addCell(2000)->addText($cell, [
                    'name' => 'Times New Roman',
                    'size' => 10
                ]);
            }
        }
        
        $this->section->addTextBreak();
    }
    
    public function generateReport()
    {
        echo "Génération du rapport FarmShop...\n";
        
        // Page de garde
        $this->createCoverPage();
        
        // Table des matières
        $this->createTableOfContents();
        
        // Chapitre 1: Présentation du projet
        $this->addChapter('1. Présentation du projet', [
            [
                'type' => 'paragraph',
                'text' => 'FarmShop représente une innovation majeure dans le secteur de l\'e-commerce agricole français. Cette plateforme digitale révolutionnaire combine la vente traditionnelle d\'équipements agricoles avec un système de location flexible, répondant ainsi aux besoins diversifiés des exploitants agricoles modernes.'
            ],
            [
                'type' => 'subtitle',
                'text' => '1.1 Contexte et problématique'
            ],
            [
                'type' => 'paragraph',
                'text' => 'Le secteur agricole français traverse une période de transformation digitale accélérée. Les exploitants font face à des défis économiques croissants : augmentation des coûts d\'équipement, nécessité de modernisation technologique, et pression sur les marges bénéficiaires. Dans ce contexte, l\'accès à l\'équipement agricole devient un enjeu stratégique majeur.'
            ],
            [
                'type' => 'encadre',
                'text' => 'Problématique centrale : Comment démocratiser l\'accès aux équipements agricoles modernes tout en proposant une expérience d\'achat digitale optimale ?'
            ],
            [
                'type' => 'subtitle',
                'text' => '1.2 Vision et objectifs'
            ],
            [
                'type' => 'paragraph',
                'text' => 'FarmShop ambitionne de devenir la référence française de l\'e-commerce agricole en proposant une solution complète et flexible. Notre vision s\'articule autour de trois piliers fondamentaux :'
            ],
            [
                'type' => 'list',
                'items' => [
                    'Accessibilité : Démocratiser l\'accès aux équipements agricoles grâce à des solutions de location flexibles',
                    'Innovation : Intégrer les dernières technologies web pour offrir une expérience utilisateur exceptionnelle',
                    'Durabilité : Promouvoir l\'économie circulaire dans le secteur agricole par la mutualisation des équipements'
                ]
            ],
            [
                'type' => 'subtitle',
                'text' => '1.3 Innovation technologique'
            ],
            [
                'type' => 'paragraph',
                'text' => 'FarmShop se distingue par son architecture technique moderne, basée sur Laravel 11.45.1 et une stack technologique de pointe. L\'innovation majeure réside dans le système de location same-day, permettant des locations d\'équipement pour une journée seulement, révolutionnant ainsi l\'accès aux outils agricoles spécialisés.'
            ]
        ]);
        
        // Chapitre 2: Analyse du marché
        $this->addChapter('2. Analyse du marché agricole', [
            [
                'type' => 'paragraph',
                'text' => 'Le marché français de l\'équipement agricole représente un secteur économique majeur, générant un chiffre d\'affaires annuel de plusieurs milliards d\'euros. Cette analyse approfondie examine les tendances, opportunités et défis de ce marché en mutation.'
            ],
            [
                'type' => 'subtitle',
                'text' => '2.1 Dimensions du marché'
            ],
            [
                'type' => 'paragraph',
                'text' => 'Selon les données de FranceAgriMer et de l\'INSEE, le secteur agricole français compte plus de 400 000 exploitations actives. Le parc d\'équipements agricoles représente une valeur estimée à 45 milliards d\'euros, avec un taux de renouvellement annuel de 8 à 12%.'
            ],
            [
                'type' => 'table',
                'headers' => ['Segment', 'Valeur marché (M€)', 'Croissance annuelle', 'Potentiel digital'],
                'rows' => [
                    ['Tracteurs et automoteurs', '2 850', '+3.2%', 'Élevé'],
                    ['Outils de travail du sol', '980', '+2.8%', 'Moyen'],
                    ['Équipements de récolte', '1 450', '+4.1%', 'Élevé'],
                    ['Matériel d\'élevage', '720', '+2.1%', 'Moyen'],
                    ['Équipements spécialisés', '650', '+5.5%', 'Très élevé']
                ]
            ],
            [
                'type' => 'subtitle',
                'text' => '2.2 Tendances de digitalisation'
            ],
            [
                'type' => 'paragraph',
                'text' => 'La transformation numérique du secteur agricole s\'accélère. Une étude Accenture 2024 révèle que 67% des exploitants agricoles utilisent désormais des outils numériques pour leurs achats d\'équipement, contre 34% en 2020.'
            ],
            [
                'type' => 'encadre',
                'text' => 'Opportunité stratégique : Le marché de la location d\'équipements agricoles, estimé à 680 millions d\'euros en 2024, présente un potentiel de croissance de 15% annuel.'
            ]
        ]);
        
        // Chapitre 18: Aspects juridiques (modèle de référence)
        $this->addChapter('18. Aspects juridiques et conformité', [
        ]);
        
        // Chapitre 18: Aspects juridiques (modèle de référence)
        $this->addChapter('18. Aspects juridiques et conformité', [
            [
                'type' => 'paragraph',
                'text' => 'La conformité juridique de FarmShop constitue un pilier fondamental du projet, garantissant la sécurité juridique de l\'entreprise et la protection des utilisateurs. Cette section détaille l\'ensemble des aspects réglementaires et légaux intégrés dans la plateforme.'
            ],
            [
                'type' => 'subtitle',
                'text' => '18.1 Conformité RGPD et protection des données'
            ],
            [
                'type' => 'paragraph',
                'text' => 'FarmShop applique rigoureusement le Règlement Général sur la Protection des Données (RGPD) dans toutes ses dimensions. La protection des données personnelles des utilisateurs constitue une priorité absolue, intégrée dès la conception de la plateforme (Privacy by Design).'
            ],
            [
                'type' => 'subsubtitle',
                'text' => '18.1.1 Principes fondamentaux appliqués'
            ],
            [
                'type' => 'list',
                'items' => [
                    'Licéité du traitement : Consentement explicite et bases légales clairement identifiées',
                    'Minimisation des données : Collecte limitée aux données strictement nécessaires',
                    'Exactitude : Procédures de mise à jour et de correction des données',
                    'Limitation de la conservation : Durées de conservation définies et respectées',
                    'Sécurité : Chiffrement, pseudonymisation et mesures de sécurité techniques'
                ]
            ],
            [
                'type' => 'paragraph',
                'text' => 'Cette approche juridique complète garantit la conformité réglementaire de FarmShop et minimise les risques juridiques, créant un environnement sécurisé pour toutes les parties prenantes.'
            ]
        ]);
        
        return true;
    }
    
    public function saveDocument($filename)
    {
        echo "Sauvegarde du document : $filename\n";
        
        if (file_exists($filename)) {
            unlink($filename);
            echo "Ancien fichier supprimé\n";
        }
        
        try {
            $writer = IOFactory::createWriter($this->phpWord, 'Word2007');
            $writer->save($filename);
            
            if (file_exists($filename)) {
                $fileSize = filesize($filename) / (1024 * 1024);
                echo "✅ Document créé avec succès !\n";
                echo "📄 Fichier : $filename\n";
                echo "📏 Taille : " . number_format($fileSize, 2) . " MB\n";
                return true;
            }
        } catch (Exception $e) {
            echo "❌ Erreur lors de la sauvegarde : " . $e->getMessage() . "\n";
            return false;
        }
        
        return false;
    }
}

// Exécution
function main()
{
    $outputFile = 'FarmShop_Rapport_Final.docx';
    
    echo "=== GÉNÉRATEUR DE RAPPORT FARMSHOP ===\n";
    echo "Création d'un document Word professionnel\n";
    echo "Style académique - Format rapport final\n";
    echo str_repeat("=", 50) . "\n";
    
    try {
        $generator = new FarmShopReportGenerator();
        
        if ($generator->generateReport()) {
            if ($generator->saveDocument($outputFile)) {
                echo "🎉 Rapport généré avec succès !\n";
                
                // Ouvrir automatiquement
                if (PHP_OS_FAMILY === 'Windows') {
                    exec("start \"\" \"$outputFile\"");
                    echo "📖 Document ouvert automatiquement\n";
                }
            }
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
