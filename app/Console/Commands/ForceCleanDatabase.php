<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ForceCleanDatabase extends Command
{
    protected $signature = 'force:clean-db';
    protected $description = 'Force le nettoyage de la base avec reconstruction manuelle';

    public function handle()
    {
        $this->info('=== RECONSTRUCTION MANUELLE DES DONNÉES ===');

        try {
            // 1. Lire toutes les catégories
            $this->info('📊 Analyse des catégories corrompues...');
            $categories = DB::table('categories')->select('id', 'name')->get();
            
            $fixes = [];
            foreach ($categories as $cat) {
                $cleanName = $this->cleanCategoryName($cat->name);
                if ($cleanName !== $cat->name) {
                    $fixes[] = [
                        'id' => $cat->id,
                        'original' => $cat->name,
                        'clean' => $cleanName
                    ];
                }
            }

            // 2. Appliquer les corrections
            foreach ($fixes as $fix) {
                DB::table('categories')
                    ->where('id', $fix['id'])
                    ->update(['name' => $fix['clean']]);
                    
                $this->info("✅ ID {$fix['id']}: [{$fix['original']}] → [{$fix['clean']}]");
            }

            // 3. Nettoyer les produits avec la même logique
            $this->info('🧹 Nettoyage des produits...');
            
            $products = DB::table('products')->select('id', 'name')->whereRaw("name REGEXP '[^a-zA-Z0-9 À-ÿ.-]'")->get();
            
            $productsFixes = 0;
            foreach ($products as $product) {
                $cleanName = $this->cleanProductName($product->name);
                if ($cleanName !== $product->name) {
                    DB::table('products')
                        ->where('id', $product->id)
                        ->update(['name' => $cleanName]);
                    $productsFixes++;
                }
            }

            // 4. Résultats finaux
            $this->info('=== RÉSULTATS FINAUX ===');
            $this->info("✅ Catégories corrigées: " . count($fixes));
            $this->info("✅ Produits corrigés: {$productsFixes}");
            
            $this->info('📋 Catégories finales:');
            $finalCategories = DB::table('categories')->orderBy('name')->get(['name']);
            foreach ($finalCategories as $cat) {
                $this->info("  • {$cat->name}");
            }

        } catch (\Exception $e) {
            $this->error('Erreur: ' . $e->getMessage());
        }
    }

    private function cleanCategoryName($name)
    {
        // Suppression de tous les caractères non-alphanumériques français
        $clean = preg_replace('/[^\p{L}\p{N}\s\-]/u', '', $name);
        $clean = trim($clean);
        
        // Reconstructions spécifiques basées sur les patterns
        $patterns = [
            '/^.*quipement.*$/i' => 'Équipement',
            '/^.*rales.*$/i' => 'céréales',
            '/^.*gumes.*$/i' => 'légumes', 
            '/^.*culents.*$/i' => 'féculents',
            '/^.*teines.*$/i' => 'protéines',
            '/^.*grais.*$/i' => 'Engrais',
            '/^.*achines.*$/i' => 'Machines',
            '/^.*utils.*$/i' => 'Outils agricoles',
            '/^.*rrigation.*$/i' => 'Irrigation',
            '/^.*rotections.*$/i' => 'Protections',
            '/^.*emences.*$/i' => 'Semences',
            '/^.*roduits.*aitiers.*$/i' => 'Produits Laitiers',
            '/^.*ruits.*$/i' => 'Fruits',
        ];

        foreach ($patterns as $pattern => $replacement) {
            if (preg_match($pattern, $clean)) {
                return $replacement;
            }
        }

        return $clean;
    }

    private function cleanProductName($name)
    {
        // Simple nettoyage des caractères bizarres
        $clean = preg_replace('/[^\p{L}\p{N}\s\-\.]/u', '', $name);
        return trim($clean);
    }
}
