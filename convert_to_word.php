<?php

/**
 * Script de conversion HTML vers Word pour FarmShop
 * Convertit le rapport final HTML en document Word .docx
 */

require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;

class HtmlToWordConverter
{
    private $phpWord;
    private $section;
    
    public function __construct()
    {
        $this->phpWord = new PhpWord();
        
        // Configuration du document
        $this->phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('fr-FR'));
        
        // Styles de base
        $this->setupStyles();
        
        // Propriétés du document
        $properties = $this->phpWord->getDocInfo();
        $properties->setCreator('FarmShop Team');
        $properties->setCompany('FarmShop');
        $properties->setTitle('Rapport Final - FarmShop');
        $properties->setDescription('Rapport complet du projet FarmShop - E-commerce agricole');
        $properties->setCategory('Rapport de projet');
        $properties->setLastModifiedBy('FarmShop Team');
        $properties->setCreated(time());
        $properties->setModified(time());
        $properties->setSubject('Développement application e-commerce');
        $properties->setKeywords('FarmShop, Agriculture, E-commerce, Laravel, Location');
        
        // Créer la section principale
        $this->section = $this->phpWord->addSection([
            'marginTop' => 1440,    // 2.5cm
            'marginBottom' => 1440, // 2.5cm
            'marginLeft' => 1440,   // 2.5cm
            'marginRight' => 1440,  // 2.5cm
            'headerHeight' => 720,  // 1.25cm
            'footerHeight' => 720,  // 1.25cm
        ]);
    }
    
    private function setupStyles()
    {
        // Style titre principal
        $this->phpWord->addTitleStyle(1, [
            'name' => 'Arial',
            'size' => 24,
            'bold' => true,
            'color' => '2d5016'
        ], [
            'spaceAfter' => 480,
            'keepNext' => true,
            'keepLines' => true
        ]);
        
        // Style titre niveau 2
        $this->phpWord->addTitleStyle(2, [
            'name' => 'Arial',
            'size' => 18,
            'bold' => true,
            'color' => '8b4513'
        ], [
            'spaceAfter' => 320,
            'spaceBefore' => 320,
            'keepNext' => true
        ]);
        
        // Style titre niveau 3
        $this->phpWord->addTitleStyle(3, [
            'name' => 'Arial',
            'size' => 14,
            'bold' => true,
            'color' => '374151'
        ], [
            'spaceAfter' => 240,
            'spaceBefore' => 240
        ]);
        
        // Style paragraphe normal
        $this->phpWord->addParagraphStyle('Normal', [
            'spaceAfter' => 200,
            'lineHeight' => 1.5,
            'alignment' => 'justify'
        ]);
        
        // Style code
        $this->phpWord->addFontStyle('Code', [
            'name' => 'Courier New',
            'size' => 9,
            'color' => '1f2937'
        ]);
        
        // Style highlight
        $this->phpWord->addParagraphStyle('Highlight', [
            'bgColor' => 'f0fdf4',
            'borderTopSize' => 6,
            'borderTopColor' => '22c55e',
            'borderLeftSize' => 6,
            'borderLeftColor' => '22c55e',
            'borderRightSize' => 6,
            'borderRightColor' => '22c55e',
            'borderBottomSize' => 6,
            'borderBottomColor' => '22c55e',
            'spaceAfter' => 240,
            'spaceBefore' => 240
        ]);
    }
    
    public function convertHtmlFile($htmlFilePath, $outputPath)
    {
        if (!file_exists($htmlFilePath)) {
            throw new Exception("Fichier HTML introuvable : $htmlFilePath");
        }
        
        echo "📖 Lecture du fichier HTML...\n";
        $htmlContent = file_get_contents($htmlFilePath);
        
        echo "🔄 Conversion du contenu...\n";
        $this->convertHtmlContent($htmlContent);
        
        echo "💾 Sauvegarde du document Word...\n";
        $this->saveDocument($outputPath);
        
        echo "✅ Conversion terminée : $outputPath\n";
    }
    
    private function convertHtmlContent($htmlContent)
    {
        // Nettoyer le HTML et extraire le contenu principal
        $htmlContent = $this->cleanHtml($htmlContent);
        
        // Parser le HTML
        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        // Ajouter page de titre
        $this->addTitlePage();
        
        // Convertir le contenu
        $this->processNode($dom->documentElement);
        
        // Ajouter table des matières
        $this->addTableOfContents();
    }
    
    private function cleanHtml($html)
    {
        // Supprimer les styles inline complexes
        $html = preg_replace('/style="[^"]*"/', '', $html);
        
        // Supprimer les classes CSS
        $html = preg_replace('/class="[^"]*"/', '', $html);
        
        // Garder uniquement le contenu du body
        if (preg_match('/<body[^>]*>(.*?)<\/body>/s', $html, $matches)) {
            $html = $matches[1];
        }
        
        return $html;
    }
    
    private function addTitlePage()
    {
        // Logo ou titre principal
        $titleRun = $this->section->addTextRun(['alignment' => 'center']);
        $titleRun->addText('FARMSHOP', [
            'name' => 'Arial',
            'size' => 36,
            'bold' => true,
            'color' => '2d5016'
        ]);
        
        $this->section->addTextBreak(2);
        
        // Sous-titre
        $subtitleRun = $this->section->addTextRun(['alignment' => 'center']);
        $subtitleRun->addText('L\'agriculture flexible, de l\'achat à la location en un clic', [
            'name' => 'Arial',
            'size' => 18,
            'italic' => true,
            'color' => '8b4513'
        ]);
        
        $this->section->addTextBreak(4);
        
        // Titre du rapport
        $reportTitleRun = $this->section->addTextRun(['alignment' => 'center']);
        $reportTitleRun->addText('RAPPORT FINAL DE PROJET', [
            'name' => 'Arial',
            'size' => 24,
            'bold' => true,
            'color' => '374151'
        ]);
        
        $this->section->addTextBreak(6);
        
        // Informations du projet
        $infoTable = $this->section->addTable([
            'borderSize' => 6,
            'borderColor' => 'cccccc',
            'cellMargin' => 80,
            'alignment' => 'center'
        ]);
        
        $infoTable->addRow();
        $infoTable->addCell(3000)->addText('Projet :', ['bold' => true]);
        $infoTable->addCell(6000)->addText('Plateforme E-commerce FarmShop');
        
        $infoTable->addRow();
        $infoTable->addCell(3000)->addText('Technologies :', ['bold' => true]);
        $infoTable->addCell(6000)->addText('Laravel 11.45.1, PHP 8.4.10, MariaDB 11.5.2');
        
        $infoTable->addRow();
        $infoTable->addCell(3000)->addText('Frontend :', ['bold' => true]);
        $infoTable->addCell(6000)->addText('Tailwind CSS 4.1.11, Alpine.js 3.14.9');
        
        $infoTable->addRow();
        $infoTable->addCell(3000)->addText('Date :', ['bold' => true]);
        $infoTable->addCell(6000)->addText('Août 2025');
        
        $this->section->addPageBreak();
    }
    
    private function addTableOfContents()
    {
        $this->section->addTitle('Table des matières', 1);
        $this->section->addTOC(['tabLeader' => '.']);
        $this->section->addPageBreak();
    }
    
    private function processNode($node)
    {
        if ($node->nodeType === XML_TEXT_NODE) {
            $text = trim($node->textContent);
            if (!empty($text)) {
                $this->section->addText($text, null, 'Normal');
            }
            return;
        }
        
        if ($node->nodeType !== XML_ELEMENT_NODE) {
            return;
        }
        
        $tagName = strtolower($node->tagName);
        
        switch ($tagName) {
            case 'h1':
                $this->section->addTitle($node->textContent, 1);
                break;
                
            case 'h2':
                $this->section->addTitle($node->textContent, 2);
                break;
                
            case 'h3':
                $this->section->addTitle($node->textContent, 3);
                break;
                
            case 'p':
                if (trim($node->textContent)) {
                    $this->section->addText($node->textContent, null, 'Normal');
                }
                break;
                
            case 'ul':
            case 'ol':
                $this->processList($node, $tagName === 'ol');
                break;
                
            case 'table':
                $this->processTable($node);
                break;
                
            case 'pre':
            case 'code':
                $this->processCode($node);
                break;
                
            case 'div':
                if (strpos($node->getAttribute('class'), 'highlight-box') !== false) {
                    $this->processHighlightBox($node);
                } else {
                    $this->processChildren($node);
                }
                break;
                
            case 'br':
                $this->section->addTextBreak();
                break;
                
            default:
                $this->processChildren($node);
                break;
        }
    }
    
    private function processChildren($node)
    {
        foreach ($node->childNodes as $child) {
            $this->processNode($child);
        }
    }
    
    private function processList($listNode, $isOrdered = false)
    {
        $items = $listNode->getElementsByTagName('li');
        foreach ($items as $item) {
            $text = $isOrdered ? '1. ' : '• ';
            $text .= trim($item->textContent);
            $this->section->addText($text, null, 'Normal');
        }
        $this->section->addTextBreak();
    }
    
    private function processTable($tableNode)
    {
        $table = $this->section->addTable([
            'borderSize' => 6,
            'borderColor' => 'cccccc',
            'cellMargin' => 80
        ]);
        
        $rows = $tableNode->getElementsByTagName('tr');
        foreach ($rows as $rowIndex => $row) {
            $tableRow = $table->addRow();
            $cells = $row->getElementsByTagName($rowIndex === 0 ? 'th' : 'td');
            
            foreach ($cells as $cell) {
                $cellWidth = 2000; // Largeur par défaut
                $cellObj = $tableRow->addCell($cellWidth);
                
                $cellText = trim($cell->textContent);
                $fontStyle = $rowIndex === 0 ? ['bold' => true] : [];
                
                $cellObj->addText($cellText, $fontStyle);
            }
        }
        
        $this->section->addTextBreak();
    }
    
    private function processCode($codeNode)
    {
        $codeText = $codeNode->textContent;
        $lines = explode("\n", $codeText);
        
        foreach ($lines as $line) {
            if (trim($line)) {
                $this->section->addText($line, 'Code');
            } else {
                $this->section->addTextBreak();
            }
        }
        
        $this->section->addTextBreak();
    }
    
    private function processHighlightBox($boxNode)
    {
        $textRun = $this->section->addTextRun('Highlight');
        $textRun->addText($boxNode->textContent);
        $this->section->addTextBreak();
    }
    
    private function saveDocument($outputPath)
    {
        $objWriter = IOFactory::createWriter($this->phpWord, 'Word2007');
        $objWriter->save($outputPath);
    }
}

// Exécution du script
try {
    echo "🚀 Démarrage de la conversion HTML vers Word...\n\n";
    
    $converter = new HtmlToWordConverter();
    
    $htmlFile = 'rapport_final_farmshop.html';
    $wordFile = 'rapport_final_farmshop.docx';
    
    if (!file_exists($htmlFile)) {
        throw new Exception("Fichier HTML introuvable : $htmlFile");
    }
    
    echo "📄 Fichier source : $htmlFile\n";
    echo "📄 Fichier destination : $wordFile\n\n";
    
    $converter->convertHtmlFile($htmlFile, $wordFile);
    
    echo "\n🎉 Conversion réussie !\n";
    echo "📁 Document Word créé : $wordFile\n";
    echo "📊 Taille du fichier : " . round(filesize($wordFile) / 1024, 2) . " KB\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la conversion : " . $e->getMessage() . "\n";
    exit(1);
}

?>
