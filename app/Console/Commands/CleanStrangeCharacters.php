<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanStrangeCharacters extends Command
{
    protected $signature = 'clean:strange-chars';
    protected $description = 'Nettoie aggressivement les caractères bizarres comme +?';

    public function handle()
    {
        $this->info('=== NETTOYAGE AGRESSIF DES CARACTÈRES BIZARRES ===');

        try {
            // 1. Configuration UTF-8
            DB::statement('SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci');
            
            // 2. État actuel
            $this->info('📊 Catégories AVANT nettoyage:');
            $categories = DB::table('categories')->select('id', 'name')->get();
            foreach ($categories as $cat) {
                $this->info("  ID {$cat->id}: [{$cat->name}]");
            }

            // 3. Nettoyage BRUTAL des caractères parasites
            $cleanups = [
                // Supprimer tous les caractères +?
                "UPDATE categories SET name = REPLACE(name, '+?', '')",
                "UPDATE categories SET name = REPLACE(name, '?+', '')",
                "UPDATE categories SET name = REPLACE(name, '+', '')",
                "UPDATE categories SET name = REPLACE(name, '?', '')",
                
                // Supprimer les caractères de contrôle
                "UPDATE categories SET name = REPLACE(name, CHAR(127), '')",
                "UPDATE categories SET name = REPLACE(name, CHAR(129), '')",
                "UPDATE categories SET name = REPLACE(name, CHAR(141), '')",
                "UPDATE categories SET name = REPLACE(name, CHAR(143), '')",
                "UPDATE categories SET name = REPLACE(name, CHAR(144), '')",
                "UPDATE categories SET name = REPLACE(name, CHAR(157), '')",
                
                // Nettoyer les espaces bizarres
                "UPDATE categories SET name = TRIM(name)",
                "UPDATE categories SET name = REPLACE(name, '  ', ' ')",
            ];

            foreach ($cleanups as $cleanup) {
                $affected = DB::update($cleanup);
                if ($affected > 0) {
                    $this->info("✅ {$affected} nettoyage(s)");
                }
            }

            // 4. Reconstructions manuelles des mots connus
            $reconstructions = [
                "UPDATE categories SET name = 'Équipement' WHERE name LIKE '%quipement%' OR name LIKE '%quip%'",
                "UPDATE categories SET name = 'céréales' WHERE name LIKE '%rales%' OR name LIKE '%real%' OR name LIKE 'C%r%ales'",
                "UPDATE categories SET name = 'légumes' WHERE name LIKE '%gumes%' OR name LIKE 'L%gumes'",
                "UPDATE categories SET name = 'féculents' WHERE name LIKE '%culents%' OR name LIKE 'F%culents'",
                "UPDATE categories SET name = 'protéines' WHERE name LIKE '%teines%' OR name LIKE '%otines%'",
            ];

            foreach ($reconstructions as $reconstruction) {
                $affected = DB::update($reconstruction);
                if ($affected > 0) {
                    $this->info("🔧 {$affected} reconstruction(s)");
                }
            }

            // 5. Même traitement pour les produits
            $this->info('🧹 Nettoyage des produits...');
            
            $productCleanups = [
                "UPDATE products SET name = REPLACE(name, '+?', '') WHERE name LIKE '%+?%'",
                "UPDATE products SET name = REPLACE(name, '?+', '') WHERE name LIKE '%?+%'",
                "UPDATE products SET name = REPLACE(name, '+', '') WHERE name LIKE '%+%'",
                "UPDATE products SET name = REPLACE(name, '?', '') WHERE name LIKE '%?%'",
                "UPDATE products SET name = TRIM(name)",
                
                "UPDATE products SET description = REPLACE(description, '+?', '') WHERE description LIKE '%+?%'",
                "UPDATE products SET description = REPLACE(description, '?+', '') WHERE description LIKE '%?+%'",
                "UPDATE products SET description = REPLACE(description, '+', '') WHERE description LIKE '%+%'",
                "UPDATE products SET description = REPLACE(description, '?', '') WHERE description LIKE '%?%'",
                "UPDATE products SET description = TRIM(description)",
            ];

            $productsFixed = 0;
            foreach ($productCleanups as $cleanup) {
                $affected = DB::update($cleanup);
                $productsFixed += $affected;
            }

            // 6. État APRÈS nettoyage
            $this->info('📊 Catégories APRÈS nettoyage:');
            $newCategories = DB::table('categories')->select('id', 'name')->get();
            foreach ($newCategories as $cat) {
                $this->info("  ID {$cat->id}: [{$cat->name}]");
            }

            $this->info('=== RÉSULTATS ===');
            $this->info("✅ Produits nettoyés: {$productsFixed}");
            $this->info('🎉 Nettoyage terminé!');

        } catch (\Exception $e) {
            $this->error('Erreur: ' . $e->getMessage());
        }
    }
}
