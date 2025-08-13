<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;

class FixEncodingIssues extends Command
{
    protected $signature = 'fix:encoding';
    protected $description = 'Corriger les problèmes d\'encodage des produits et catégories';

    private $encodingFixes = [
        // Caractères avec problèmes d'encodage
        'é' => ['Ã©', 'Ã©', '&eacute;'],
        'è' => ['Ã¨', 'Ã¨', '&egrave;'],
        'à' => ['Ã ', 'Ã ', '&agrave;'],
        'ô' => ['Ã´', 'Ã´', '&ocirc;'],
        'ê' => ['Ãª', 'Ãª', '&ecirc;'],
        'ç' => ['Ã§', 'Ã§', '&ccedil;'],
        'É' => ['Ã‰', 'Ã‰', '&Eacute;'],
        'È' => ['Ãˆ', 'Ãˆ', '&Egrave;'],
        'À' => ['Ã€', 'Ã€', '&Agrave;'],
        'Ô' => ['Ã"', 'Ã"', '&Ocirc;'],
        'Ê' => ['ÃŠ', 'ÃŠ', '&Ecirc;'],
        'Ç' => ['Ã‡', 'Ã‡', '&Ccedil;'],
        'ü' => ['Ã¼', 'Ã¼', '&uuml;'],
        'î' => ['Ã®', 'Ã®', '&icirc;'],
        'ï' => ['Ã¯', 'Ã¯', '&iuml;'],
        'ù' => ['Ã¹', 'Ã¹', '&ugrave;'],
        'â' => ['Ã¢', 'Ã¢', '&acirc;'],
        'û' => ['Ã»', 'Ã»', '&ucirc;'],
        'ö' => ['Ã¶', 'Ã¶', '&ouml;'],
        'ä' => ['Ã¤', 'Ã¤', '&auml;'],
        'ñ' => ['Ã±', 'Ã±', '&ntilde;'],
        'è' => ['éè', 'Ã©è', 'éÃ¨'],
        'é' => ['ée', 'Ã©e', 'éÃ©']
    ];

    public function handle()
    {
        $this->info('=== CORRECTION DES PROBLEMES D\'ENCODAGE ===');
        $this->newLine();

        // 1. Corriger les catégories
        $this->info('1. CORRECTION DES CATEGORIES:');
        $categories = Category::all();
        $categoriesFixed = 0;

        foreach ($categories as $category) {
            $originalName = $category->name;
            $originalDescription = $category->description;
            
            $fixedName = $this->fixEncoding($originalName);
            $fixedDescription = $this->fixEncoding($originalDescription);
            
            if ($fixedName !== $originalName || $fixedDescription !== $originalDescription) {
                $category->update([
                    'name' => $fixedName,
                    'description' => $fixedDescription
                ]);
                
                $this->line("OK Categorie: '{$originalName}' -> '{$fixedName}'");
                $categoriesFixed++;
            }
        }
        
        $this->line("   Categories corrigees: {$categoriesFixed}");
        $this->newLine();

        // 2. Corriger les produits
        $this->info('2. CORRECTION DES PRODUITS:');
        $products = Product::all();
        $productsFixed = 0;

        foreach ($products as $product) {
            $originalName = $product->name;
            $originalDescription = $product->description;
            $originalShortDescription = $product->short_description;
            
            $fixedName = $this->fixEncoding($originalName);
            $fixedDescription = $this->fixEncoding($originalDescription);
            $fixedShortDescription = $this->fixEncoding($originalShortDescription);
            
            if ($fixedName !== $originalName || 
                $fixedDescription !== $originalDescription || 
                $fixedShortDescription !== $originalShortDescription) {
                
                $product->update([
                    'name' => $fixedName,
                    'description' => $fixedDescription,
                    'short_description' => $fixedShortDescription
                ]);
                
                if ($fixedName !== $originalName) {
                    $this->line("OK Produit: '{$originalName}' -> '{$fixedName}'");
                }
                $productsFixed++;
            }
        }
        
        $this->line("   Produits corriges: {$productsFixed}");
        $this->newLine();

        $this->info('=== CORRECTION TERMINEE ===');
        $this->line("OK Categories: {$categoriesFixed} corrigees");
        $this->line("OK Produits: {$productsFixed} corriges");
    }

    private function fixEncoding($text)
    {
        if (!$text) return $text;

        $fixed = $text;
        
        // Appliquer les corrections d'encodage
        foreach ($this->encodingFixes as $correct => $broken) {
            foreach ($broken as $brokenChar) {
                $fixed = str_replace($brokenChar, $correct, $fixed);
            }
        }

        // Nettoyer les caractères de contrôle
        $fixed = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $fixed);
        
        // Convertir en UTF-8 si nécessaire
        if (!mb_check_encoding($fixed, 'UTF-8')) {
            $fixed = mb_convert_encoding($fixed, 'UTF-8', 'auto');
        }

        return trim($fixed);
    }
}
