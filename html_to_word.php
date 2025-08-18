<?php
/**
 * Script de conversion HTML vers Word pour FarmShop
 * Convertit rapport_final_farmshop.html en rapport_final_farmshop.docx
 */

require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Element\Section;

class FarmShopWordConverter
{
    private $phpWord;
    private $section;
    
    public function __construct()
    {
        $this->phpWord = new PhpWord();
        $this->setupDocumentProperties();
        $this->setupStyles();
        $this->section = $this->phpWord->addSection();
    }
    
    private function setupDocumentProperties()
    {
        $properties = $this->phpWord->getDocInfo();
        $properties->setCreator('Équipe FarmShop');
        $properties->setCompany('FarmShop');
        $properties->setTitle('Rapport Final FarmShop');
        $properties->setDescription('Rapport final complet du projet FarmShop - Plateforme e-commerce agricole');
        $properties->setCategory('Rapport de projet');
        $properties->setLastModifiedBy('Équipe FarmShop');
        $properties->setCreated(time());
        $properties->setModified(time());
        $properties->setSubject('Projet e-commerce agricole avec système de location');
        $properties->setKeywords('FarmShop, Laravel, e-commerce, agriculture, location');
    }
    
    private function setupStyles()
    {
        // Style pour titre principal
        $this->phpWord->addTitleStyle(1, [
            'name' => 'Arial',
            'size' => 18,
            'bold' => true,
            'color' => '2d5016'
        ], [
            'spaceAfter' => 240,
            'spaceBefore' => 480
        ]);
        
        // Style pour titre niveau 2
        $this->phpWord->addTitleStyle(2, [
            'name' => 'Arial',
            'size' => 16,
            'bold' => true,
            'color' => '8b4513'
        ], [
            'spaceAfter' => 120,
            'spaceBefore' => 360
        ]);
        
        // Style pour titre niveau 3
        $this->phpWord->addTitleStyle(3, [
            'name' => 'Arial',
            'size' => 14,
            'bold' => true,
            'color' => 'ea580c'
        ], [
            'spaceAfter' => 120,
            'spaceBefore' => 240
        ]);
        
        // Style paragraphe normal
        $this->phpWord->addParagraphStyle('Normal', [
            'spaceAfter' => 120,
            'lineHeight' => 1.15
        ]);
        
        // Style pour les boîtes en surbrillance
        $this->phpWord->addParagraphStyle('HighlightBox', [
            'spaceAfter' => 240,
            'spaceBefore' => 240,
            'borderTopSize' => 6,
            'borderTopColor' => '2d5016',
            'borderBottomSize' => 6,
            'borderBottomColor' => '2d5016'
        ]);
    }
    
    private function cleanText($text)
    {
        // Nettoyer le texte
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Remplacer les caractères spéciaux problématiques
        $text = str_replace(['🔄', '📝', '💾', '✅', '📄', '📏', '📅', '🎉', '❌', '🗑️'], 
                           ['[En cours]', '[Conversion]', '[Sauvegarde]', '[OK]', '[Fichier]', '[Taille]', '[Date]', '[Succès]', '[Erreur]', '[Supprimé]'], 
                           $text);
        
        // Remplacer tous les autres émojis et caractères unicode problématiques
        $text = preg_replace('/[\x{1F600}-\x{1F64F}]|[\x{1F300}-\x{1F5FF}]|[\x{1F680}-\x{1F6FF}]|[\x{1F1E0}-\x{1F1FF}]|[\x{2600}-\x{26FF}]|[\x{2700}-\x{27BF}]/u', '[*]', $text);
        
        // Nettoyer les espaces multiples
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        // S'assurer que le texte est en UTF-8 valide
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'auto');
        }
        
        return $text;
    }
    
    private function addPageBreak()
    {
        $this->section->addPageBreak();
    }
    
    private function processTable($tableHtml)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($tableHtml, 'HTML-ENTITIES', 'UTF-8'));
        
        $rows = $dom->getElementsByTagName('tr');
        if ($rows->length == 0) return;
        
        // Compter les colonnes
        $maxCols = 0;
        foreach ($rows as $row) {
            $cells = $row->getElementsByTagName('td');
            $headers = $row->getElementsByTagName('th');
            $colCount = $cells->length + $headers->length;
            $maxCols = max($maxCols, $colCount);
        }
        
        if ($maxCols == 0) return;
        
        // Créer le tableau
        $table = $this->section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80
        ]);
        
        foreach ($rows as $row) {
            $table->addRow();
            
            $cells = [];
            foreach ($row->getElementsByTagName('th') as $th) {
                $cells[] = ['text' => $this->cleanText($th->textContent), 'header' => true];
            }
            foreach ($row->getElementsByTagName('td') as $td) {
                $cells[] = ['text' => $this->cleanText($td->textContent), 'header' => false];
            }
            
            for ($i = 0; $i < $maxCols; $i++) {
                $cellText = isset($cells[$i]) ? $cells[$i]['text'] : '';
                $isHeader = isset($cells[$i]) ? $cells[$i]['header'] : false;
                
                $cell = $table->addCell(2000);
                
                if ($isHeader) {
                    $cell->addText($cellText, [
                        'bold' => true,
                        'name' => 'Arial',
                        'size' => 10
                    ]);
                } else {
                    $cell->addText($cellText, [
                        'name' => 'Arial',
                        'size' => 10
                    ]);
                }
            }
        }
        
        $this->section->addTextBreak();
    }
    
    private function processList($listHtml, $ordered = false)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($listHtml, 'HTML-ENTITIES', 'UTF-8'));
        
        $items = $dom->getElementsByTagName('li');
        $counter = 1;
        
        foreach ($items as $item) {
            $text = $this->cleanText($item->textContent);
            if (!empty($text)) {
                if ($ordered) {
                    $this->section->addText($counter . '. ' . $text, [
                        'name' => 'Arial',
                        'size' => 11
                    ], 'Normal');
                    $counter++;
                } else {
                    $this->section->addText('• ' . $text, [
                        'name' => 'Arial',
                        'size' => 11
                    ], 'Normal');
                }
            }
        }
    }
    
    public function convertHtmlFile($inputFile, $outputFile)
    {
        echo "Lecture du fichier HTML : $inputFile\n";
        
        if (!file_exists($inputFile)) {
            throw new Exception("Le fichier $inputFile n'existe pas");
        }
        
        // Lire et nettoyer le HTML
        $html = file_get_contents($inputFile);
        
        // Nettoyer le HTML des caractères problématiques avant parsing
        $html = mb_convert_encoding($html, 'UTF-8', 'auto');
        
        // Remplacer les émojis dans le HTML avant parsing
        $html = preg_replace('/[\x{1F600}-\x{1F64F}]|[\x{1F300}-\x{1F5FF}]|[\x{1F680}-\x{1F6FF}]|[\x{1F1E0}-\x{1F1FF}]|[\x{2600}-\x{26FF}]|[\x{2700}-\x{27BF}]/u', '[*]', $html);
        
        // Parser le HTML avec gestion d'erreur améliorée
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->encoding = 'UTF-8';
        
        // Désactiver les erreurs libxml temporairement
        $prevValue = libxml_use_internal_errors(true);
        
        // Charger le HTML avec des options plus permissives
        $success = $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        // Restaurer la gestion d'erreur libxml
        libxml_use_internal_errors($prevValue);
        
        if (!$success) {
            echo "Attention: Erreurs lors du parsing HTML, tentative de continuation...\n";
        }
        
        echo "Conversion du contenu...\n";
        
        // Traiter les éléments
        $xpath = new DOMXPath($dom);
        
        // Ajouter une page de titre simplifiée
        $this->section->addTitle('RAPPORT FINAL', 1);
        $this->section->addTitle('FarmShop', 1);
        $this->section->addText('L\'agriculture flexible, de l\'achat à la location en un clic', [
            'name' => 'Arial',
            'size' => 14,
            'italic' => true
        ], [
            'alignment' => 'center',
            'spaceAfter' => 480
        ]);
        
        $this->section->addText('Rapport final complet', [
            'name' => 'Arial',
            'size' => 12
        ], [
            'alignment' => 'center'
        ]);
        
        $this->section->addText('17 août 2025', [
            'name' => 'Arial',
            'size' => 12
        ], [
            'alignment' => 'center',
            'spaceAfter' => 480
        ]);
        
        $this->addPageBreak();
        
        // Traiter le contenu principal avec gestion d'erreur
        try {
            $elements = $xpath->query('//h1 | //h2 | //h3 | //p | //ul | //ol | //table | //div[contains(@class, "highlight-box")]');
        } catch (Exception $e) {
            echo "Erreur XPath, utilisation d'une méthode alternative...\n";
            $elements = $dom->getElementsByTagName('*');
        }
        
        $pageBreakNext = false;
        $processedElements = 0;
        
        foreach ($elements as $element) {
            try {
                if ($pageBreakNext) {
                    $this->addPageBreak();
                    $pageBreakNext = false;
                }
                
                switch ($element->nodeName) {
                    case 'h1':
                        $text = $this->cleanText($element->textContent);
                        if (!empty($text) && strlen($text) > 1) {
                            $this->section->addTitle($text, 1);
                            $processedElements++;
                            
                            // Vérifier si c'est un élément avec page-break
                            $class = $element->getAttribute('class');
                            if ($class && strpos($class, 'page-break') !== false) {
                                $pageBreakNext = true;
                            }
                        }
                        break;
                        
                    case 'h2':
                        $text = $this->cleanText($element->textContent);
                        if (!empty($text) && strlen($text) > 1) {
                            $this->section->addTitle($text, 2);
                            $processedElements++;
                        }
                        break;
                        
                    case 'h3':
                        $text = $this->cleanText($element->textContent);
                        if (!empty($text) && strlen($text) > 1) {
                            $this->section->addTitle($text, 3);
                            $processedElements++;
                        }
                        break;
                        
                    case 'p':
                        $text = $this->cleanText($element->textContent);
                        if (!empty($text) && strlen($text) > 5) {
                            $this->section->addText($text, [
                                'name' => 'Arial',
                                'size' => 11
                            ], 'Normal');
                            $processedElements++;
                        }
                        break;
                        
                    case 'ul':
                        $this->processList($dom->saveHTML($element), false);
                        $processedElements++;
                        break;
                        
                    case 'ol':
                        $this->processList($dom->saveHTML($element), true);
                        $processedElements++;
                        break;
                        
                    case 'table':
                        $this->processTable($dom->saveHTML($element));
                        $processedElements++;
                        break;
                        
                    case 'div':
                        $class = $element->getAttribute('class');
                        if ($class && strpos($class, 'highlight-box') !== false) {
                            $text = $this->cleanText($element->textContent);
                            if (!empty($text) && strlen($text) > 5) {
                                $this->section->addText($text, [
                                    'name' => 'Arial',
                                    'size' => 11,
                                    'bold' => true,
                                    'color' => '2d5016'
                                ], 'HighlightBox');
                                $processedElements++;
                            }
                        }
                        break;
                }
            } catch (Exception $e) {
                echo "Erreur lors du traitement d'un élément: " . $e->getMessage() . "\n";
                continue;
            }
        }
        
        echo "Elements traités: $processedElements\n";
        echo "Sauvegarde du document Word : $outputFile\n";
        
        // Supprimer l'ancien fichier s'il existe
        if (file_exists($outputFile)) {
            unlink($outputFile);
            echo "Ancien fichier supprimé\n";
        }
        
        // Sauvegarder avec gestion d'erreur
        try {
            $writer = IOFactory::createWriter($this->phpWord, 'Word2007');
            $writer->save($outputFile);
        } catch (Exception $e) {
            echo "Erreur lors de la sauvegarde: " . $e->getMessage() . "\n";
            throw $e;
        }
        
        if (file_exists($outputFile)) {
            $fileSize = filesize($outputFile) / (1024 * 1024);
            echo "Conversion réussie !\n";
            echo "Fichier créé : $outputFile\n";
            echo "Taille : " . number_format($fileSize, 2) . " MB\n";
            echo "Date : " . date('d/m/Y H:i:s') . "\n";
            return true;
        } else {
            echo "Échec de la création du fichier\n";
            return false;
        }
    }
}

// Fonction principale
function main()
{
    $inputFile = 'rapport_final_farmshop.html';
    $outputFile = 'rapport_final_farmshop.docx';
    
    echo "Conversion HTML vers Word - FarmShop\n";
    echo str_repeat("=", 50) . "\n";
    
    try {
        $converter = new FarmShopWordConverter();
        $success = $converter->convertHtmlFile($inputFile, $outputFile);
        
        if ($success) {
            echo "Conversion terminée avec succès !\n";
            
            // Ouvrir automatiquement le fichier
            if (PHP_OS_FAMILY === 'Windows') {
                exec("start \"\" \"$outputFile\"");
                echo "Fichier Word ouvert automatiquement.\n";
            } else {
                echo "Fichier Word créé : $outputFile\n";
            }
        } else {
            echo "Échec de la conversion\n";
            exit(1);
        }
        
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage() . "\n";
        exit(1);
    }
}

// Exécuter le script
if (php_sapi_name() === 'cli') {
    main();
}
?>
