<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class AutoSetupRailway
{
    /**
     * Middleware pour auto-setup Railway (migrations + seeders)
     */
    public function handle(Request $request, Closure $next)
    {
        // Seulement en production Railway
        if (env('APP_ENV') === 'production' && env('RAILWAY_ENVIRONMENT_NAME')) {
            try {
                // Vérifier si les migrations ont déjà été faites
                if (!Schema::hasTable('users')) {
                    Log::info('🚀 Auto-setup Railway démarré...');
                    
                    // Lancer les migrations
                    Log::info('📊 Lancement des migrations...');
                    Artisan::call('migrate', ['--force' => true]);
                    Log::info('✅ Migrations terminées');
                    
                    // Lancer les seeders
                    Log::info('🌱 Lancement des seeders...');
                    Artisan::call('db:seed', ['--force' => true]);
                    Log::info('✅ Seeders terminés');
                    
                    Log::info('🎉 Auto-setup Railway terminé avec succès !');
                }
            } catch (\Exception $e) {
                Log::error('❌ Erreur auto-setup Railway : ' . $e->getMessage());
            }
        }
        
        return $next($request);
    }
}
