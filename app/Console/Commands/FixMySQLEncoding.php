<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixMySQLEncoding extends Command
{
    protected $signature = 'fix:mysql-encoding';
    protected $description = 'Corrige l\'encodage MySQL en production';

    public function handle()
    {
        $this->info('=== CORRECTION ENCODAGE MYSQL PRODUCTION ===');

        try {
            // 1. Forcer l'encodage UTF-8 pour la connexion
            DB::statement('SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci');
            DB::statement('SET CHARACTER SET utf8mb4');
            DB::statement('SET character_set_client = utf8mb4');
            DB::statement('SET character_set_results = utf8mb4');
            DB::statement('SET character_set_connection = utf8mb4');
            
            $this->info('✅ Configuration UTF-8 appliquée');

            // 2. Diagnostiquer l'état actuel
            $this->info('📊 État actuel des catégories:');
            $categories = DB::table('categories')->select('id', 'name')->get();
            foreach ($categories as $cat) {
                $this->info("  ID {$cat->id}: {$cat->name}");
            }

            // 3. Corrections directes des caractères corrompus
            $fixes = [
                // Corrections des caractères français
                "UPDATE categories SET name = 'céréales' WHERE name REGEXP 'c[^a-zA-Z0-9]*r[^a-zA-Z0-9]*ales' OR name LIKE '%cereal%' OR name LIKE '%c+?+?ales%'",
                "UPDATE categories SET name = 'légumes' WHERE name REGEXP 'l[^a-zA-Z0-9]*gumes' OR name LIKE '%legume%' OR name LIKE '%l+?+?gumes%'",
                "UPDATE categories SET name = 'féculents' WHERE name REGEXP 'f[^a-zA-Z0-9]*culents' OR name LIKE '%feculent%' OR name LIKE '%f+?+?culents%'",
                "UPDATE categories SET name = 'protéines' WHERE name REGEXP 'prot[^a-zA-Z0-9]*ines' OR name LIKE '%protein%' OR name LIKE '%prot+?+?ines%'",
                "UPDATE categories SET name = 'Équipement' WHERE name REGEXP '[^a-zA-Z0-9]*quipement' OR name LIKE '%quipement%'",
                
                // Nettoyer les caractères parasites
                "UPDATE categories SET name = REPLACE(name, '?', '')",
                "UPDATE categories SET name = REPLACE(name, '+', '')",
                "UPDATE categories SET name = REPLACE(name, '�', '')",
                
                // Corrections d'encodage double
                "UPDATE categories SET name = REPLACE(name, 'Ã©', 'é')",
                "UPDATE categories SET name = REPLACE(name, 'Ã¨', 'è')",
                "UPDATE categories SET name = REPLACE(name, 'Ã ', 'à')",
                "UPDATE categories SET name = REPLACE(name, 'Ã§', 'ç')",
                "UPDATE categories SET name = REPLACE(name, 'Ã´', 'ô')",
                "UPDATE categories SET name = REPLACE(name, 'Ã«', 'ë')",
                "UPDATE categories SET name = REPLACE(name, 'Ãª', 'ê')",
            ];

            $totalFixed = 0;
            foreach ($fixes as $fix) {
                $affected = DB::update($fix);
                $totalFixed += $affected;
                if ($affected > 0) {
                    $this->info("✅ {$affected} correction(s)");
                }
            }

            // 4. Corriger également les produits
            $this->info('🔧 Correction des produits...');
            
            $productFixes = [
                "UPDATE products SET name = REPLACE(name, '?', '') WHERE name LIKE '%?%'",
                "UPDATE products SET name = REPLACE(name, '+', '') WHERE name LIKE '%+%'",
                "UPDATE products SET name = REPLACE(name, '�', '') WHERE name LIKE '%�%'",
                "UPDATE products SET description = REPLACE(description, '?', '') WHERE description LIKE '%?%'",
                "UPDATE products SET description = REPLACE(description, '+', '') WHERE description LIKE '%+%'",
                "UPDATE products SET description = REPLACE(description, '�', '') WHERE description LIKE '%�%'",
                
                // Corrections d'encodage double sur les produits
                "UPDATE products SET name = REPLACE(name, 'Ã©', 'é')",
                "UPDATE products SET name = REPLACE(name, 'Ã¨', 'è')",
                "UPDATE products SET name = REPLACE(name, 'Ã ', 'à')",
                "UPDATE products SET description = REPLACE(description, 'Ã©', 'é')",
                "UPDATE products SET description = REPLACE(description, 'Ã¨', 'è')",
                "UPDATE products SET description = REPLACE(description, 'Ã ', 'à')",
            ];

            $productsFixed = 0;
            foreach ($productFixes as $fix) {
                $affected = DB::update($fix);
                $productsFixed += $affected;
            }

            // 5. Résultats finaux
            $this->info('=== RÉSULTATS FINAUX ===');
            $this->info("✅ Catégories corrigées: {$totalFixed}");
            $this->info("✅ Produits corrigés: {$productsFixed}");
            
            $this->info('📋 Catégories après correction:');
            $newCategories = DB::table('categories')->select('name')->orderBy('name')->get();
            foreach ($newCategories as $cat) {
                $this->info("  • {$cat->name}");
            }

            $this->info('🎉 Encodage MySQL corrigé!');

        } catch (\Exception $e) {
            $this->error('Erreur lors de la correction:');
            $this->error($e->getMessage());
            $this->error('Ligne: ' . $e->getLine());
        }
    }
}
