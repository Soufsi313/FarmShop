<?php
/**
 * Script pour générer le Manuel d'Utilisation en PDF
 * Usage: php generate_manual_pdf.php
 */

require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Configuration
$markdownFile = 'MANUEL_UTILISATION.md';
$outputFile = 'Manuel_Utilisation_FarmShop.pdf';

if (!file_exists($markdownFile)) {
    die("Erreur: Fichier Markdown non trouvé: $markdownFile\n");
}

// Lire le contenu Markdown
$markdownContent = file_get_contents($markdownFile);

// Convertir Markdown en HTML
$html = convertMarkdownToHtml($markdownContent);

// Configurer DOMPDF
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Sauvegarder le PDF
file_put_contents($outputFile, $dompdf->output());

echo "✅ Manuel d'utilisation PDF généré: $outputFile\n";
echo "📄 Taille: " . formatBytes(filesize($outputFile)) . "\n";
echo "📚 Pages: ~" . estimatePages($markdownContent) . "\n";

function convertMarkdownToHtml($markdown) {
    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manuel d\'Utilisation - FarmShop</title>
    <style>
        body { 
            font-family: "Arial", sans-serif; 
            margin: 20px; 
            line-height: 1.6;
            color: #333;
        }
        h1 { 
            color: #2c3e50; 
            border-bottom: 3px solid #3498db; 
            padding-bottom: 10px;
            page-break-before: always;
        }
        h1:first-of-type {
            page-break-before: avoid;
        }
        h2 { 
            color: #34495e; 
            margin-top: 30px;
            border-bottom: 2px solid #bdc3c7;
            padding-bottom: 5px;
        }
        h3 { 
            color: #7f8c8d; 
            margin-top: 20px;
        }
        h4 {
            color: #95a5a6;
            margin-top: 15px;
        }
        .toc {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            page-break-inside: avoid;
        }
        .highlight {
            background: #e8f4f8;
            padding: 15px;
            border-left: 4px solid #3498db;
            margin: 15px 0;
            border-radius: 0 5px 5px 0;
        }
        .warning {
            background: #fdf2e9;
            padding: 15px;
            border-left: 4px solid #e67e22;
            margin: 15px 0;
            border-radius: 0 5px 5px 0;
        }
        .success {
            background: #eafaf1;
            padding: 15px;
            border-left: 4px solid #27ae60;
            margin: 15px 0;
            border-radius: 0 5px 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        code {
            background: #f4f4f4;
            padding: 2px 4px;
            border-radius: 3px;
            font-family: "Courier New", monospace;
            font-size: 0.9em;
        }
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-family: "Courier New", monospace;
            font-size: 0.9em;
        }
        .url {
            color: #3498db;
            font-family: "Courier New", monospace;
            font-size: 0.9em;
        }
        .section-break {
            page-break-before: always;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 10px;
            color: #666;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            border-radius: 10px;
        }
        .emoji {
            font-size: 1.2em;
        }
        ul, ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        li {
            margin: 5px 0;
        }
        .contact-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>';

    // Convertir le markdown en HTML basique
    $lines = explode("\n", $markdown);
    $inCodeBlock = false;
    $inTable = false;
    $tableHeaders = [];
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        // Skip empty lines
        if (empty($line)) {
            $html .= "<br>\n";
            continue;
        }
        
        // Code blocks
        if (strpos($line, '```') === 0) {
            if (!$inCodeBlock) {
                $html .= '<pre>';
                $inCodeBlock = true;
            } else {
                $html .= '</pre>';
                $inCodeBlock = false;
            }
            continue;
        }
        
        if ($inCodeBlock) {
            $html .= htmlspecialchars($line) . "\n";
            continue;
        }
        
        // Headers
        if (preg_match('/^(#{1,6})\s+(.+)$/', $line, $matches)) {
            $level = strlen($matches[1]);
            $text = $matches[2];
            $id = strtolower(preg_replace('/[^a-z0-9]+/', '-', $text));
            $html .= "<h$level id=\"$id\">$text</h$level>\n";
            continue;
        }
        
        // Tables
        if (preg_match('/^\|(.+)\|$/', $line)) {
            if (!$inTable) {
                $html .= '<table>';
                $inTable = true;
                $isHeader = true;
            } else {
                $isHeader = false;
            }
            
            $cells = array_map('trim', explode('|', trim($line, '|')));
            $html .= '<tr>';
            foreach ($cells as $cell) {
                $tag = $isHeader ? 'th' : 'td';
                $html .= "<$tag>" . processInlineMarkdown($cell) . "</$tag>";
            }
            $html .= '</tr>';
            continue;
        } elseif ($inTable && strpos($line, '|') === false) {
            $html .= '</table>';
            $inTable = false;
        }
        
        // Table separator line
        if (preg_match('/^\|[\s\-\|:]+\|$/', $line)) {
            continue;
        }
        
        // Horizontal rule
        if ($line === '---') {
            $html .= '<hr>';
            continue;
        }
        
        // Lists
        if (preg_match('/^[\s]*[-\*\+]\s+(.+)$/', $line, $matches)) {
            $html .= '<ul><li>' . processInlineMarkdown($matches[1]) . '</li></ul>';
            continue;
        }
        
        if (preg_match('/^[\s]*\d+\.\s+(.+)$/', $line, $matches)) {
            $html .= '<ol><li>' . processInlineMarkdown($matches[1]) . '</li></ol>';
            continue;
        }
        
        // Regular paragraphs
        $html .= '<p>' . processInlineMarkdown($line) . '</p>';
    }
    
    // Close any open table
    if ($inTable) {
        $html .= '</table>';
    }
    
    $html .= '</body></html>';
    return $html;
}

function processInlineMarkdown($text) {
    // Bold
    $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
    // Italic
    $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text);
    // Code
    $text = preg_replace('/`(.+?)`/', '<code>$1</code>', $text);
    // Links
    $text = preg_replace('/\[(.+?)\]\((.+?)\)/', '<a href="$2">$1</a>', $text);
    // URLs
    $text = preg_replace('/(https?:\/\/[^\s]+)/', '<span class="url">$1</span>', $text);
    // Emojis as text
    $text = preg_replace('/([🔍📋📊📈🚨📄🌐✅❌💾🗑️✏️🧮📧📱🔔⭐🏷️📅💬📞📚🔧💻])/', '<span class="emoji">$1</span>', $text);
    
    return $text;
}

function estimatePages($content) {
    $wordCount = str_word_count($content);
    $wordsPerPage = 400; // Estimation
    return ceil($wordCount / $wordsPerPage);
}

function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}
