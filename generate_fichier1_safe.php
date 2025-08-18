<?php
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;

function sanitizeText($text)
{
    if (!is_string($text)) return $text;
    $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
    $text = preg_replace('/[^\x{9}\x{A}\x{D}\x{20}-\x{D7FF}\x{E000}-\x{FFFD}]/u', '', $text);
    $text = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $text);
    $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $text);
    return $text;
}

function safeAddText($section, $text, $style = null, $paragraphStyle = null)
{
    $text = sanitizeText($text);
    return $section->addText($text, $style, $paragraphStyle);
}

function safeAddTitle($section, $text, $depth)
{
    $text = sanitizeText($text);
    return $section->addTitle($text, $depth);
}

function createFichier1Safe()
{
    $phpWord = new PhpWord();
    $phpWord->getDocInfo()->setCreator('Soufiane MEFTAH');
    $phpWord->getDocInfo()->setTitle('FarmShop - Rapport Final - Partie 1 - SAFE');
    $phpWord->setDefaultFontName('Times New Roman');
    $phpWord->setDefaultFontSize(12);

    $phpWord->addTitleStyle(1, ['size' => 18, 'bold' => true], ['spaceAfter' => Converter::cmToTwip(0.5)]);
    $phpWord->addTitleStyle(2, ['size' => 16, 'bold' => true], ['spaceAfter' => Converter::cmToTwip(0.3)]);
    $phpWord->addTitleStyle(3, ['size' => 14, 'bold' => true], ['spaceAfter' => Converter::cmToTwip(0.2)]);

    $section = $phpWord->addSection(['marginTop' => Converter::cmToTwip(3), 'marginBottom' => Converter::cmToTwip(3), 'marginLeft' => Converter::cmToTwip(2.5), 'marginRight' => Converter::cmToTwip(2.5)]);

    $section->addTextBreak(3);
    safeAddText($section, 'RAPPORT FINAL', ['size' => 24, 'bold' => true], 'center');
    $section->addTextBreak(1);
    safeAddText($section, 'FARMSHOP', ['size' => 32, 'bold' => true], 'center');
    $section->addTextBreak(1);
    safeAddText($section, "L'agriculture flexible, de l'achat a la location en un clic", ['size' => 14, 'italic' => true], 'center');
    $section->addPageBreak();

    safeAddTitle($section, 'TABLE DES MATIERES', 1);
    $section->addTextBreak(1);
    $toc = [
        '1. REMERCIEMENTS ............................................. 3',
        '2. GLOSSAIRE TECHNIQUE ........................................ 4',
        '3. INTRODUCTION ............................................... 8',
        '4. SYNOPSIS DU PROJET ......................................... 11'
    ];
    foreach ($toc as $line) safeAddText($section, $line);
    $section->addPageBreak();

    safeAddTitle($section, '1. REMERCIEMENTS', 1);
    safeAddText($section, "Au terme de ce travail de fin d'etudes consacre au developpement de la plateforme FarmShop, je tiens a exprimer ma profonde gratitude.");
    safeAddTitle($section, '1.1 Equipe pedagogique', 2);
    $remerciements = [
        'Monsieur RUTH', 'Madame VANCRAYENST', 'Monsieur VERBIST', 'Monsieur VANDOOREN', 'Monsieur CIULLO'
    ];
    foreach ($remerciements as $r) safeAddText($section, '- ' . $r);

    safeAddTitle($section, '1.2 Soutien familial', 2);
    safeAddText($section, 'Remerciements a la famille pour leur soutien.');

    safeAddTitle($section, '1.3 Support administratif', 2);
    safeAddText($section, 'Remerciements au secretariat pour son professionnalisme.');

    $section->addPageBreak();

    safeAddTitle($section, '2. GLOSSAIRE TECHNIQUE', 1);
    safeAddText($section, 'Glossaire resumant les technologies principales utilisees: Laravel, PHP, MariaDB, Tailwind, Alpine.js, Redis, Stripe, Git, Composer.');
    $section->addPageBreak();

    safeAddTitle($section, '3. INTRODUCTION', 1);
    safeAddText($section, "L'agriculture moderne traverse une periode de transformation profonde... (texte redacte en francais). This document is a safe Word export.");

    return $phpWord;
}

function main()
{
    $out = '01_intro_presentation_safe.docx';
    echo "Generation safe du Fichier 1...\n";
    $phpWord = createFichier1Safe();
    if (file_exists($out)) unlink($out);
    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save($out);
    $size = filesize($out) / 1024;
    echo "Fichier cree: $out (" . number_format($size,2) . " KB)\n";
    if (PHP_OS_FAMILY === 'Windows') exec("start \"\" \"$out\"");
}

if (php_sapi_name() === 'cli') main();
