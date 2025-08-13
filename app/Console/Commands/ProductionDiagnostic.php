<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProductionDiagnostic extends Command
{
    protected $signature = 'app:production-diagnostic';
    protected $description = 'Diagnostic production pour Railway';

    public function handle()
    {
        $this->info('=== DIAGNOSTIC PRODUCTION RAILWAY ===');

        try {
            // 1. Test base de données
            $this->info('1. Test connexion base de données...');
            $dbConnection = DB::connection()->getPdo();
            $this->info('✅ Base de données connectée: ' . $dbConnection->getAttribute(\PDO::ATTR_SERVER_VERSION));

            // 2. Test encodage base
            $this->info('2. Test encodage base de données...');
            $charset = DB::select("SHOW VARIABLES LIKE 'character_set_database'")[0];
            $collation = DB::select("SHOW VARIABLES LIKE 'collation_database'")[0];
            $this->info("✅ Charset: {$charset->Value}, Collation: {$collation->Value}");

            // 3. Test catégories
            $this->info('3. Test catégories...');
            $categoriesCount = DB::table('categories')->count();
            $this->info("✅ {$categoriesCount} catégories trouvées");
            
            $categories = DB::table('categories')->orderBy('name')->take(5)->get(['name']);
            foreach ($categories as $category) {
                $this->info("  • {$category->name}");
            }

            // 4. Test produits
            $this->info('4. Test produits...');
            $productsCount = DB::table('products')->count();
            $this->info("✅ {$productsCount} produits trouvés");

            // 5. Test environment
            $this->info('5. Test environment...');
            $this->info('✅ Environment: ' . app()->environment());
            $this->info('✅ Laravel version: ' . app()->version());
            
            // 6. Test cache
            $this->info('6. Test cache...');
            Cache::put('diagnostic_test', 'OK', 60);
            $cacheTest = Cache::get('diagnostic_test');
            $this->info('✅ Cache: ' . ($cacheTest === 'OK' ? 'Fonctionnel' : 'Problème'));

            $this->info('🎉 Diagnostic terminé - Tout semble fonctionnel');

        } catch (\Exception $e) {
            $this->error('❌ Erreur diagnostic:');
            $this->error($e->getMessage());
            $this->error('Ligne: ' . $e->getLine());
            $this->error('Fichier: ' . $e->getFile());
        }
    }
}
