<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixEncodingDatabase extends Command
{
    protected $signature = 'app:fix-encoding-database';
    protected $description = 'Correction d\'encodage directement en base de données';

    public function handle()
    {
        $this->info('=== CORRECTION ENCODAGE BASE DE DONNÉES ===');

        try {
            // 1. Corriger les catégories
            $this->info('1. Correction des catégories...');
            
            $categoriesUpdated = 0;
            
            // Corrections directes en SQL pour éviter les problèmes de caractères
            $categoryUpdates = [
                "UPDATE categories SET name = 'céréales' WHERE name LIKE '%cereal%' OR name LIKE '%ereale%' OR name REGEXP '[^a-zA-Z0-9]reale'",
                "UPDATE categories SET name = 'légumes' WHERE name LIKE '%legume%' OR name LIKE '%gume%' OR name REGEXP 'l[^a-zA-Z0-9]gume'",
                "UPDATE categories SET name = 'féculents' WHERE name LIKE '%feculent%' OR name LIKE '%culent%' OR name REGEXP 'f[^a-zA-Z0-9]culent'",
                "UPDATE categories SET name = 'protéines' WHERE name LIKE '%protein%' OR name LIKE '%oteine%' OR name REGEXP 'prot[^a-zA-Z0-9]ine'",
                "UPDATE categories SET name = 'élevage' WHERE name LIKE '%elevage%' OR name LIKE '%levage%' OR name REGEXP '[^a-zA-Z0-9]levage'",
                "UPDATE categories SET name = 'matériel' WHERE name LIKE '%materiel%' OR name LIKE '%teriel%' OR name REGEXP 'mat[^a-zA-Z0-9]riel'"
            ];
            
            foreach ($categoryUpdates as $update) {
                $affected = DB::update($update);
                $categoriesUpdated += $affected;
                if ($affected > 0) {
                    $this->info("✅ {$affected} catégorie(s) mise(s) à jour");
                }
            }

            // 2. Corriger les produits
            $this->info('2. Correction des produits...');
            
            $productsUpdated = 0;
            
            // Nettoyer les caractères problématiques dans les noms de produits
            $productUpdates = [
                "UPDATE products SET name = REPLACE(name, '?', '') WHERE name LIKE '%?%'",
                "UPDATE products SET name = REPLACE(name, '+', '') WHERE name LIKE '%+%'",
                "UPDATE products SET description = REPLACE(description, '?', '') WHERE description LIKE '%?%'",
                "UPDATE products SET description = REPLACE(description, '+', '') WHERE description LIKE '%+%'",
                "UPDATE products SET short_description = REPLACE(short_description, '?', '') WHERE short_description LIKE '%?%'",
                "UPDATE products SET short_description = REPLACE(short_description, '+', '') WHERE short_description LIKE '%+%'"
            ];
            
            foreach ($productUpdates as $update) {
                $affected = DB::update($update);
                $productsUpdated += $affected;
                if ($affected > 0) {
                    $this->info("✅ {$affected} produit(s) mis à jour");
                }
            }

            // 3. Vérifier les blogs si la table existe
            $this->info('3. Correction des blogs...');
            
            $blogsUpdated = 0;
            
            try {
                // Vérifier si les tables blog existent
                $blogTables = ['blog_posts', 'blog_categories'];
                
                foreach ($blogTables as $table) {
                    if (DB::getSchemaBuilder()->hasTable($table)) {
                        $updates = [
                            "UPDATE {$table} SET name = REPLACE(name, '?', '') WHERE name LIKE '%?%'",
                            "UPDATE {$table} SET name = REPLACE(name, '+', '') WHERE name LIKE '%+%'"
                        ];
                        
                        if ($table === 'blog_posts') {
                            $updates[] = "UPDATE {$table} SET title = REPLACE(title, '?', '') WHERE title LIKE '%?%'";
                            $updates[] = "UPDATE {$table} SET title = REPLACE(title, '+', '') WHERE title LIKE '%+%'";
                            $updates[] = "UPDATE {$table} SET content = REPLACE(content, '?', '') WHERE content LIKE '%?%'";
                            $updates[] = "UPDATE {$table} SET content = REPLACE(content, '+', '') WHERE content LIKE '%+%'";
                        }
                        
                        foreach ($updates as $update) {
                            $affected = DB::update($update);
                            $blogsUpdated += $affected;
                        }
                    }
                }
                
                if ($blogsUpdated > 0) {
                    $this->info("✅ {$blogsUpdated} élément(s) de blog mis à jour");
                }
                
            } catch (\Exception $e) {
                $this->warn('Tables blog non trouvées ou erreur: ' . $e->getMessage());
            }

            // 4. Correction spécifique pour les catégories connues
            $this->info('4. Correction spécifique des catégories...');
            
            $specificFixes = [
                ['pattern' => '%c%reale%', 'replacement' => 'céréales'],
                ['pattern' => '%l%gume%', 'replacement' => 'légumes'],
                ['pattern' => '%f%culent%', 'replacement' => 'féculents'],
                ['pattern' => '%prot%ine%', 'replacement' => 'protéines'],
                ['pattern' => '%levage%', 'replacement' => 'élevage'],
                ['pattern' => '%mat%riel%', 'replacement' => 'matériel']
            ];
            
            foreach ($specificFixes as $fix) {
                $sql = "UPDATE categories SET name = ? WHERE name LIKE ?";
                $affected = DB::update($sql, [$fix['replacement'], $fix['pattern']]);
                if ($affected > 0) {
                    $this->info("✅ Catégorie '{$fix['replacement']}': {$affected} mise(s) à jour");
                }
            }

            // 5. Statistiques finales
            $this->info('=== RÉSULTATS FINAUX ===');
            
            // Compter les éléments
            $totalCategories = DB::table('categories')->count();
            $totalProducts = DB::table('products')->count();
            
            $this->info("📊 Total catégories: {$totalCategories}");
            $this->info("📊 Total produits: {$totalProducts}");
            $this->info("✅ Catégories mises à jour: {$categoriesUpdated}");
            $this->info("✅ Produits mis à jour: {$productsUpdated}");
            $this->info("✅ Éléments blog mis à jour: {$blogsUpdated}");
            
            // Afficher quelques exemples de catégories
            $this->info('📋 Catégories actuelles:');
            $categories = DB::table('categories')->select('name')->limit(10)->get();
            foreach ($categories as $category) {
                $this->info("  • {$category->name}");
            }

            $this->info('🎉 Correction encodage terminée!');
            $this->info('Les pages sont maintenant corrigées pour:');
            $this->info('• https://farmshop-production.up.railway.app/products');
            $this->info('• https://farmshop-production.up.railway.app/rentals');
            $this->info('• https://farmshop-production.up.railway.app/blog');

        } catch (\Exception $e) {
            $this->error('Erreur lors de la correction:');
            $this->error($e->getMessage());
            $this->error('Ligne: ' . $e->getLine());
        }
    }
}
