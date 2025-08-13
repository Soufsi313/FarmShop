<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanSecurityReferences extends Command
{
    protected $signature = 'app:clean-security-references';
    protected $description = 'Nettoyer toutes les références aux contrôleurs de sécurité';

    public function handle()
    {
        $this->info('=== NETTOYAGE RÉFÉRENCES SÉCURITÉ ===');

        // Nettoyer routes/web.php
        $webRoutesPath = base_path('routes/web.php');
        if (file_exists($webRoutesPath)) {
            $content = file_get_contents($webRoutesPath);
            $originalContent = $content;
            
            // Supprimer toutes les lignes contenant SecurityTestController
            $lines = explode("\n", $content);
            $cleanLines = array_filter($lines, function($line) {
                return strpos($line, 'SecurityTestController') === false;
            });
            
            $content = implode("\n", $cleanLines);
            
            if ($content !== $originalContent) {
                file_put_contents($webRoutesPath, $content);
                $this->info('✅ Références supprimées de routes/web.php');
            }
        }

        // Nettoyer routes/api.php si nécessaire
        $apiRoutesPath = base_path('routes/api.php');
        if (file_exists($apiRoutesPath)) {
            $content = file_get_contents($apiRoutesPath);
            $originalContent = $content;
            
            $lines = explode("\n", $content);
            $cleanLines = array_filter($lines, function($line) {
                return strpos($line, 'SecurityTestController') === false;
            });
            
            $content = implode("\n", $cleanLines);
            
            if ($content !== $originalContent) {
                file_put_contents($apiRoutesPath, $content);
                $this->info('✅ Références supprimées de routes/api.php');
            }
        }

        // Clear cache complet
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $this->info('✅ Cache nettoyé');

        // Test final
        $this->info('Test final des routes...');
        try {
            $output = \Illuminate\Support\Facades\Artisan::call('route:list', ['--name' => 'rentals']);
            $this->info('✅ SUCCESS! Routes fonctionnent');
        } catch (\Exception $e) {
            $this->error('❌ Erreur persistante: ' . $e->getMessage());
        }

        $this->info('=== NETTOYAGE TERMINÉ ===');
    }
}
