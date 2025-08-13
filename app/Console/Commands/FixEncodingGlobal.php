<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;
use App\Models\BlogPost;
use App\Models\BlogCategory;

class FixEncodingGlobal extends Command
{
    protected $signature = 'app:fix-encoding-global';
    protected $description = 'Correction globale des problèmes d\'encodage sur toutes les pages';

    public function handle()
    {
        $this->info('=== CORRECTION ENCODAGE GLOBAL ===');

        try {
            // 1. Diagnostiquer les problèmes d'encodage
            $this->info('1. Diagnostic des problèmes d\'encodage...');
            
            // Vérifier les catégories avec problèmes
            $categories = Category::all();
            $problematicCategories = [];
            
            foreach ($categories as $category) {
                if (preg_match('/[^\x20-\x7E\x{00A0}-\x{00FF}]/u', $category->name) || 
                    strpos($category->name, '?') !== false ||
                    strpos($category->name, '+') !== false) {
                    $problematicCategories[] = $category;
                    $this->warn("Catégorie problématique: ID {$category->id} - '{$category->name}'");
                }
            }

            // Vérifier les produits avec problèmes
            $products = Product::whereRaw("name LIKE '%?%' OR name LIKE '%+%' OR description LIKE '%?%'")->get();
            $this->info("Produits avec problèmes d'encodage: " . $products->count());

            // 2. Correction des catégories
            $this->info('2. Correction des catégories...');
            
            // Corrections spécifiques pour les catégories les plus courantes
            $categoryFixes = [
                'cereales' => 'céréales',
                'legumes' => 'légumes', 
                'feculents' => 'féculents',
                'proteines' => 'protéines',
                'elevage' => 'élevage',
                'materiel' => 'matériel',
                'semences' => 'semences'
            ];

            $categoriesFixed = 0;
            foreach ($categories as $category) {
                $originalName = $category->name;
                $originalDescription = $category->description;
                $fixed = false;

                // Corriger le nom
                foreach ($categoryFixes as $broken => $correct) {
                    if (strpos($category->name, $broken) !== false) {
                        $category->name = str_replace($broken, $correct, $category->name);
                        $fixed = true;
                    }
                }

                // Correction générale UTF-8
                $cleanName = $this->fixUTF8($category->name);
                if ($cleanName !== $category->name) {
                    $category->name = $cleanName;
                    $fixed = true;
                }

                // Corriger la description si elle existe
                if ($category->description) {
                    $cleanDescription = $this->fixUTF8($category->description);
                    if ($cleanDescription !== $category->description) {
                        $category->description = $cleanDescription;
                        $fixed = true;
                    }
                }

                if ($fixed) {
                    $category->save();
                    $categoriesFixed++;
                    $this->info("✅ Catégorie corrigée: '{$originalName}' → '{$category->name}'");
                }
            }

            // 3. Correction des produits
            $this->info('3. Correction des produits...');
            
            $productsFixed = 0;
            $allProducts = Product::all();
            
            foreach ($allProducts as $product) {
                $originalName = $product->name;
                $originalDescription = $product->description;
                $fixed = false;

                // Corriger le nom du produit
                $cleanName = $this->fixUTF8($product->name);
                if ($cleanName !== $product->name) {
                    $product->name = $cleanName;
                    $fixed = true;
                }

                // Corriger la description
                if ($product->description) {
                    $cleanDescription = $this->fixUTF8($product->description);
                    if ($cleanDescription !== $product->description) {
                        $product->description = $cleanDescription;
                        $fixed = true;
                    }
                }

                // Corriger la description courte
                if ($product->short_description) {
                    $cleanShortDescription = $this->fixUTF8($product->short_description);
                    if ($cleanShortDescription !== $product->short_description) {
                        $product->short_description = $cleanShortDescription;
                        $fixed = true;
                    }
                }

                if ($fixed) {
                    $product->save();
                    $productsFixed++;
                    if ($productsFixed <= 10) { // Afficher seulement les 10 premiers
                        $this->info("✅ Produit corrigé: '{$originalName}' → '{$product->name}'");
                    }
                }
            }

            // 4. Correction des blogs
            $this->info('4. Correction des blogs...');
            
            $blogsFixed = 0;
            
            // Corriger les catégories de blog
            if (class_exists(BlogCategory::class)) {
                $blogCategories = BlogCategory::all();
                foreach ($blogCategories as $blogCategory) {
                    $originalName = $blogCategory->name;
                    $cleanName = $this->fixUTF8($blogCategory->name);
                    
                    if ($cleanName !== $blogCategory->name) {
                        $blogCategory->name = $cleanName;
                        $blogCategory->save();
                        $this->info("✅ Catégorie blog corrigée: '{$originalName}' → '{$cleanName}'");
                    }
                }
            }

            // Corriger les articles de blog
            if (class_exists(BlogPost::class)) {
                $blogPosts = BlogPost::all();
                foreach ($blogPosts as $blogPost) {
                    $fixed = false;
                    $originalTitle = $blogPost->title;

                    $cleanTitle = $this->fixUTF8($blogPost->title);
                    if ($cleanTitle !== $blogPost->title) {
                        $blogPost->title = $cleanTitle;
                        $fixed = true;
                    }

                    if ($blogPost->content) {
                        $cleanContent = $this->fixUTF8($blogPost->content);
                        if ($cleanContent !== $blogPost->content) {
                            $blogPost->content = $cleanContent;
                            $fixed = true;
                        }
                    }

                    if ($fixed) {
                        $blogPost->save();
                        $blogsFixed++;
                        $this->info("✅ Article blog corrigé: '{$originalTitle}' → '{$blogPost->title}'");
                    }
                }
            }

            // 5. Résultats
            $this->info('=== RÉSULTATS ===');
            $this->info("✅ Catégories corrigées: {$categoriesFixed}");
            $this->info("✅ Produits corrigés: {$productsFixed}");
            $this->info("✅ Articles blog corrigés: {$blogsFixed}");
            $this->info('');
            $this->info('🎉 Correction encodage terminée!');
            $this->info('Les pages suivantes sont maintenant corrigées:');
            $this->info('• /products - Catégories et produits');
            $this->info('• /rentals - Produits de location');
            $this->info('• /blog - Articles et catégories de blog');

        } catch (\Exception $e) {
            $this->error('Erreur lors de la correction:');
            $this->error($e->getMessage());
            $this->error('Ligne: ' . $e->getLine());
        }
    }

    private function fixUTF8($text)
    {
        if (!$text) return $text;

        // Corrections spécifiques communes
        $fixes = [
            // Caractères mal encodés courants  
            'cereales' => 'céréales',
            'legumes' => 'légumes',
            'feculents' => 'féculents', 
            'proteines' => 'protéines',
            'elevage' => 'élevage',
            'materiel' => 'matériel',
            
            // Autres caractères problématiques courants
            'a€™' => "'",
            'a€œ' => '"',
            'a€' => '"',
        ];

        $cleaned = $text;
        foreach ($fixes as $broken => $correct) {
            $cleaned = str_replace($broken, $correct, $cleaned);
        }

        // Nettoyer les caractères de contrôle
        $cleaned = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $cleaned);
        
        // Convertir vers UTF-8 propre
        $cleaned = mb_convert_encoding($cleaned, 'UTF-8', 'UTF-8');
        
        return trim($cleaned);
    }
}
