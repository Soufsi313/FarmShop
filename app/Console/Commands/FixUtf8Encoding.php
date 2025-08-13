<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixUtf8Encoding extends Command
{
    protected $signature = 'app:fix-utf8-encoding';
    protected $description = 'Assure que l\'encodage UTF-8 est correct pour tous les textes';

    public function handle()
    {
        $this->info('=== CORRECTION ENCODAGE UTF-8 ===');

        try {
            // 1. Définir l'encodage de la connexion
            $this->info('1. Configuration UTF-8 de la base de données...');
            
            DB::statement('SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci');
            DB::statement('SET CHARACTER SET utf8mb4');
            
            $this->info('✅ Encodage UTF-8 configuré');

            // 2. Corrections spécifiques pour les caractères français
            $this->info('2. Correction des caractères français...');
            
            $frenchCorrections = [
                // Céréales
                "UPDATE categories SET name = 'céréales' WHERE name REGEXP 'c[^a-zA-Z0-9]r[^a-zA-Z0-9]ales' OR name LIKE '%cereal%'",
                
                // Légumes
                "UPDATE categories SET name = 'légumes' WHERE name REGEXP 'l[^a-zA-Z0-9]gumes' OR name LIKE '%legume%'",
                
                // Féculents
                "UPDATE categories SET name = 'féculents' WHERE name REGEXP 'f[^a-zA-Z0-9]culents' OR name LIKE '%feculent%'",
                
                // Protéines
                "UPDATE categories SET name = 'protéines' WHERE name REGEXP 'prot[^a-zA-Z0-9]ines' OR name LIKE '%protein%'",
                
                // Équipement
                "UPDATE categories SET name = 'Équipement' WHERE name REGEXP '[^a-zA-Z0-9]quipement' OR name LIKE '%quipement'",
                
                // Matériel
                "UPDATE categories SET name = 'Matériel' WHERE name REGEXP 'Mat[^a-zA-Z0-9]riel' OR name LIKE '%materiel%'"
            ];
            
            $totalCorrected = 0;
            foreach ($frenchCorrections as $correction) {
                $affected = DB::update($correction);
                $totalCorrected += $affected;
                if ($affected > 0) {
                    $this->info("✅ {$affected} correction(s) appliquée(s)");
                }
            }

            // 3. Nettoyer les caractères non imprimables
            $this->info('3. Nettoyage des caractères non imprimables...');
            
            $cleanupQueries = [
                // Nettoyer les caractères de contrôle dans les catégories
                "UPDATE categories SET name = TRIM(REPLACE(REPLACE(REPLACE(name, CHAR(13), ''), CHAR(10), ''), CHAR(9), ''))",
                
                // Nettoyer les caractères de contrôle dans les produits
                "UPDATE products SET name = TRIM(REPLACE(REPLACE(REPLACE(name, CHAR(13), ''), CHAR(10), ''), CHAR(9), ''))",
                "UPDATE products SET description = TRIM(REPLACE(REPLACE(REPLACE(description, CHAR(13), ''), CHAR(10), ' '), CHAR(9), ' '))",
                "UPDATE products SET short_description = TRIM(REPLACE(REPLACE(REPLACE(short_description, CHAR(13), ''), CHAR(10), ' '), CHAR(9), ' '))"
            ];
            
            foreach ($cleanupQueries as $query) {
                $affected = DB::update($query);
                if ($affected > 0) {
                    $this->info("✅ {$affected} nettoyage(s) effectué(s)");
                }
            }

            // 4. Corriger les encodages doubles
            $this->info('4. Correction des encodages doubles...');
            
            $doubleEncodingFixes = [
                "UPDATE categories SET name = REPLACE(name, 'Ã©', 'é')",
                "UPDATE categories SET name = REPLACE(name, 'Ã¨', 'è')",
                "UPDATE categories SET name = REPLACE(name, 'Ã ', 'à')",
                "UPDATE categories SET name = REPLACE(name, 'Ã§', 'ç')",
                "UPDATE categories SET name = REPLACE(name, 'Ã´', 'ô')",
                "UPDATE categories SET name = REPLACE(name, 'Ã«', 'ë')",
                "UPDATE categories SET name = REPLACE(name, 'Ãª', 'ê')",
                
                "UPDATE products SET name = REPLACE(name, 'Ã©', 'é')",
                "UPDATE products SET name = REPLACE(name, 'Ã¨', 'è')",
                "UPDATE products SET name = REPLACE(name, 'Ã ', 'à')",
                "UPDATE products SET name = REPLACE(name, 'Ã§', 'ç')",
                "UPDATE products SET name = REPLACE(name, 'Ã´', 'ô')",
                "UPDATE products SET name = REPLACE(name, 'Ã«', 'ë')",
                "UPDATE products SET name = REPLACE(name, 'Ãª', 'ê')"
            ];
            
            $doubleEncodingCorrected = 0;
            foreach ($doubleEncodingFixes as $fix) {
                $affected = DB::update($fix);
                $doubleEncodingCorrected += $affected;
            }
            
            if ($doubleEncodingCorrected > 0) {
                $this->info("✅ {$doubleEncodingCorrected} correction(s) d'encodage double");
            }

            // 5. Afficher les résultats
            $this->info('=== RÉSULTATS ===');
            
            $categories = DB::table('categories')->orderBy('name')->get();
            $this->info('📋 Catégories corrigées:');
            foreach ($categories as $category) {
                $this->info("  • {$category->name}");
            }
            
            $this->info("✅ Total corrections: {$totalCorrected}");
            $this->info("✅ Corrections d'encodage double: {$doubleEncodingCorrected}");
            
            $this->info('🎉 Encodage UTF-8 finalisé!');
            $this->info('Testez maintenant:');
            $this->info('• https://farmshop-production.up.railway.app/products');
            $this->info('• https://farmshop-production.up.railway.app/rentals');
            $this->info('• https://farmshop-production.up.railway.app/blog');

        } catch (\Exception $e) {
            $this->error('Erreur UTF-8:');
            $this->error($e->getMessage());
        }
    }
}
