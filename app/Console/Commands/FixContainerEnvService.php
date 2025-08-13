<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixContainerEnvService extends Command
{
    protected $signature = 'fix:container-env';
    protected $description = 'Supprime le service env problématique du container';

    public function handle()
    {
        $this->info('=== CORRECTION SERVICE ENV CONTAINER ===');

        try {
            $container = app();
            
            // Vérifier si le service env existe
            if ($container->bound('env')) {
                $this->info('❌ Service "env" trouvé dans le container');
                
                // Supprimer le binding problématique
                $reflection = new \ReflectionClass($container);
                $bindingsProperty = $reflection->getProperty('bindings');
                $bindingsProperty->setAccessible(true);
                $bindings = $bindingsProperty->getValue($container);
                
                if (isset($bindings['env'])) {
                    unset($bindings['env']);
                    $bindingsProperty->setValue($container, $bindings);
                    $this->info('✅ Service "env" supprimé du container');
                }
                
                // Vérifier aussi les instances
                $instancesProperty = $reflection->getProperty('instances');
                $instancesProperty->setAccessible(true);
                $instances = $instancesProperty->getValue($container);
                
                if (isset($instances['env'])) {
                    unset($instances['env']);
                    $instancesProperty->setValue($container, $instances);
                    $this->info('✅ Instance "env" supprimée du container');
                }
                
                // Vérifier aussi les aliases
                $aliasesProperty = $reflection->getProperty('aliases');
                $aliasesProperty->setAccessible(true);
                $aliases = $aliasesProperty->getValue($container);
                
                if (isset($aliases['env'])) {
                    unset($aliases['env']);
                    $aliasesProperty->setValue($container, $aliases);
                    $this->info('✅ Alias "env" supprimé du container');
                }
                
            } else {
                $this->info('✅ Aucun service "env" trouvé');
            }
            
            // Test final
            $this->info('🧪 Test final...');
            $this->info('Environment: ' . app()->environment());
            $this->info('✅ Container corrigé avec succès!');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur: ' . $e->getMessage());
        }
    }
}
