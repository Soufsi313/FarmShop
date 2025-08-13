<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FixEnvContainerService
{
    public function handle(Request $request, Closure $next)
    {
        // Corriger le problème du service 'env' dans le container
        $container = app();
        
        if ($container->bound('env')) {
            try {
                $reflection = new \ReflectionClass($container);
                
                // Supprimer le binding problématique
                $bindingsProperty = $reflection->getProperty('bindings');
                $bindingsProperty->setAccessible(true);
                $bindings = $bindingsProperty->getValue($container);
                
                if (isset($bindings['env'])) {
                    unset($bindings['env']);
                    $bindingsProperty->setValue($container, $bindings);
                }
                
                // Supprimer aussi les instances
                $instancesProperty = $reflection->getProperty('instances');
                $instancesProperty->setAccessible(true);
                $instances = $instancesProperty->getValue($container);
                
                if (isset($instances['env'])) {
                    unset($instances['env']);
                    $instancesProperty->setValue($container, $instances);
                }
                
            } catch (\Exception $e) {
                // Si ça échoue, on continue quand même
                error_log('FixEnvContainerService failed: ' . $e->getMessage());
            }
        }
        
        return $next($request);
    }
}
