<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemoveBrokenFiles extends Command
{
    protected $signature = 'app:remove-broken-files';
    protected $description = 'Supprimer les fichiers cassés qui bloquent les routes';

    public function handle()
    {
        $this->info('=== SUPPRESSION FICHIERS CASSÉS ===');

        // Supprimer SecurityTestController.php qui a une erreur de syntaxe
        $securityController = app_path('Http/Controllers/SecurityTestController.php');
        if (file_exists($securityController)) {
            unlink($securityController);
            $this->info('✅ SecurityTestController.php supprimé');
        }

        // Supprimer tous les middlewares de sécurité créés récemment
        $forceHttpsMiddleware = app_path('Http/Middleware/ForceHTTPS.php');
        if (file_exists($forceHttpsMiddleware)) {
            unlink($forceHttpsMiddleware);
            $this->info('✅ ForceHTTPS middleware supprimé');
        }

        // Nettoyer bootstrap/app.php des références aux middlewares cassés
        $appPath = base_path('bootstrap/app.php');
        if (file_exists($appPath)) {
            $content = file_get_contents($appPath);
            $originalContent = $content;
            
            // Supprimer toutes les références à ForceHTTPS
            $content = preg_replace('/.*ForceHTTPS.*\n?/', '', $content);
            
            if ($content !== $originalContent) {
                file_put_contents($appPath, $content);
                $this->info('✅ Références middleware supprimées de bootstrap/app.php');
            }
        }

        // Clear tous les caches
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $this->info('✅ Cache nettoyé');

        // Test immédiat
        $this->info('Test des routes après nettoyage...');
        try {
            \Illuminate\Support\Facades\Artisan::call('route:list', ['--name' => 'rentals']);
            $this->info('✅ Routes accessibles');
        } catch (\Exception $e) {
            $this->error('❌ Toujours en erreur: ' . $e->getMessage());
        }

        $this->info('=== NETTOYAGE TERMINÉ ===');
    }
}
