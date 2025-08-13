<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DiagnoseReflectionError extends Command
{
    protected $signature = 'diagnose:reflection-error';
    protected $description = 'Diagnostique l\'erreur ReflectionException en détail';

    public function handle()
    {
        $this->info('=== DIAGNOSTIC ERREUR REFLECTION ===');

        try {
            // 1. Tester l'environnement Laravel actuel
            $this->info('✅ Laravel fonctionne (nous sommes dans une commande)');
            $this->info('Environment actuel: ' . app()->environment());
            $this->info('Config app.env: ' . config('app.env'));

            // 2. Lister les services problématiques
            $this->info('📊 Services dans le container:');
            $container = app();
            $bindings = $container->getBindings();
            
            foreach ($bindings as $abstract => $binding) {
                if (str_contains($abstract, 'env')) {
                    $this->info("  - Service suspect: {$abstract}");
                }
            }

            // 3. Chercher les alias problématiques
            $this->info('🔍 Recherche du service "env" problématique...');
            
            if ($container->bound('env')) {
                $this->error('❌ PROBLÈME TROUVÉ: Service "env" existe dans le container!');
                
                try {
                    $envService = $container->make('env');
                    $this->info('Type du service: ' . get_class($envService));
                } catch (\Exception $e) {
                    $this->error('Erreur lors de la résolution: ' . $e->getMessage());
                }
            } else {
                $this->info('✅ Pas de service "env" trouvé');
            }

            // 4. Test simple de création d'une nouvelle instance
            $this->info('🔧 Test de création d\'application...');
            
            $this->info('✅ Diagnostic terminé sans erreur');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur détectée:');
            $this->error('Message: ' . $e->getMessage());
            $this->error('Fichier: ' . $e->getFile());
            $this->error('Ligne: ' . $e->getLine());
            $this->error('Trace: ' . $e->getTraceAsString());
        }
    }
}
