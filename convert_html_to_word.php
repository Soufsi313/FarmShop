<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// --- Helper: sanitize text for Word XML ---
function sanitizeText($text) {
    $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
    // Remove invalid XML chars
    $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);
    // Remove 4-byte UTF-8 chars (emojis, surrogates)
    $text = preg_replace('/[\xF0-\xF7][\x80-\xBF]{3}/u', '', $text);
    return $text;
}

// --- Helper: add sanitized text ---
function safeAddText($section, $text, $style = null, $paragraphStyle = null) {
    $section->addText(sanitizeText($text), $style, $paragraphStyle);
}

// --- Main conversion ---
function convertHtmlToWord($htmlFile, $outputFile) {
    $phpWord = new PhpWord();
    $section = $phpWord->addSection();

    $html = file_get_contents($htmlFile);

    // --- Extract all sections dynamically ---
    if (preg_match_all('/<div class="page-break">(.*?)<\/div>/s', $html, $matches)) {
        foreach ($matches[1] as $block) {
            // Extract H1 as section title
            if (preg_match('/<h1>(.*?)<\/h1>/', $block, $titleMatch)) {
                $title = strip_tags($titleMatch[1]);
                safeAddText($section, $title, ['size' => 18, 'bold' => true, 'color' => '2c5aa0']);
                $section->addTextBreak(1);
            }
            // Extract H2/H3/H4
            if (preg_match_all('/<h([2-4])>(.*?)<\/h\1>/', $block, $subMatches)) {
                foreach ($subMatches[2] as $i => $subTitle) {
                    $depth = (int)$subMatches[1][$i];
                    $size = $depth === 2 ? 16 : ($depth === 3 ? 14 : 12);
                    safeAddText($section, strip_tags($subTitle), ['size' => $size, 'bold' => true]);
                }
            }
            // Extract paragraphs
            if (preg_match_all('/<p>(.*?)<\/p>/s', $block, $pMatches)) {
                foreach ($pMatches[1] as $para) {
                    safeAddText($section, strip_tags($para), ['size' => 12]);
                }
            }
            // Extract bullet lists
            if (preg_match_all('/<ul>(.*?)<\/ul>/s', $block, $ulMatches)) {
                foreach ($ulMatches[1] as $ul) {
                    if (preg_match_all('/<li>(.*?)<\/li>/', $ul, $liMatches)) {
                        foreach ($liMatches[1] as $li) {
                            safeAddText($section, "• " . strip_tags($li), ['size' => 12]);
                        }
                    }
                }
            }
            // Extract tables
            if (preg_match_all('/<table>(.*?)<\/table>/s', $block, $tableMatches)) {
                foreach ($tableMatches[1] as $tableHtml) {
                    // Simple table parser
                    $rows = [];
                    if (preg_match_all('/<tr>(.*?)<\/tr>/s', $tableHtml, $rowMatches)) {
                        foreach ($rowMatches[1] as $rowHtml) {
                            $cells = [];
                            if (preg_match_all('/<(td|th)[^>]*>(.*?)<\/(td|th)>/s', $rowHtml, $cellMatches)) {
                                foreach ($cellMatches[2] as $cell) {
                                    $cells[] = strip_tags($cell);
                                }
                            }
                            $rows[] = $cells;
                        }
                    }
                    if (count($rows) > 0) {
                        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
                        foreach ($rows as $row) {
                            $tableRow = $table->addRow();
                            foreach ($row as $cell) {
                                $tableRow->addCell(3000)->addText(sanitizeText($cell), ['size' => 11]);
                            }
                        }
                        $section->addTextBreak(1);
                    }
                }
            }
            // Add page break after each section
            $section->addPageBreak();
        }
    }

    // --- Save ---
    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save($outputFile);
}

// --- Run ---
convertHtmlToWord('rapport_final_farmshop.html', 'rapport_final_farmshop.docx');
echo "Fichier Word généré : rapport_final_farmshop.docx\n";
