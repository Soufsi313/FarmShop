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

function createFichier1Step1()
{
    $phpWord = new PhpWord();
    $phpWord->getDocInfo()->setCreator('Soufiane MEFTAH');
    $phpWord->getDocInfo()->setTitle('FarmShop - Partie 1 - Step1');
    // Step1: set default font to Inter only
    $phpWord->setDefaultFontName('Inter');
    $phpWord->setDefaultFontSize(12);

    $section = $phpWord->addSection(['marginTop' => Converter::cmToTwip(3), 'marginBottom' => Converter::cmToTwip(3), 'marginLeft' => Converter::cmToTwip(2.5), 'marginRight' => Converter::cmToTwip(2.5)]);

    $section->addTextBreak(3);
    safeAddText($section, 'RAPPORT FINAL', ['name' => 'Inter', 'size' => 24, 'bold' => true], 'Centered');
    $section->addTextBreak(1);
    safeAddText($section, 'FARMSHOP', ['name' => 'Inter', 'size' => 32, 'bold' => true], 'Centered');
    $section->addTextBreak(1);
    safeAddText($section, "L'agriculture flexible, de l'achat a la location en un clic", ['name' => 'Inter', 'size' => 16, 'italic' => true], 'Centered');
    $section->addPageBreak();

    safeAddTitle($section, 'TABLE DES MATIERES', 1);
    safeAddText($section, '1. REMERCIEMENTS ............................................. 3', ['name' => 'Inter', 'size' => 11], 'Normal');
    $section->addPageBreak();

    safeAddTitle($section, '1. REMERCIEMENTS', 1);
    safeAddText($section, 'Au terme de ce travail de fin d\'etudes...', ['name' => 'Inter', 'size' => 12], 'Normal');

    $section->addPageBreak();
    safeAddTitle($section, '2. GLOSSAIRE TECHNIQUE', 1);
    safeAddText($section, 'Laravel, PHP, MariaDB, Tailwind, Alpine.js, Stripe, Redis, Git, Composer', ['name' => 'Inter', 'size' => 12], 'Normal');

    return $phpWord;
}

function main()
{
    $out = '01_intro_presentation_step1.docx';
    echo "Generation step1...\n";
    $phpWord = createFichier1Step1();
    if (file_exists($out)) unlink($out);
    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save($out);
    $size = filesize($out) / 1024;
    echo "Fichier cree: $out (" . number_format($size,2) . " KB)\n";
    if (PHP_OS_FAMILY === 'Windows') exec("start \"\" \"$out\"");
}

if (php_sapi_name() === 'cli') main();
