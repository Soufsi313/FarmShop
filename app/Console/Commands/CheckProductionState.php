<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckProductionState extends Command
{
    protected $signature = 'app:check-production-state';
    protected $description = 'Vérifier l\'état exact en production';

    public function handle()
    {
        $this->info('=== ÉTAT PRODUCTION ACTUEL ===');

        // 1. Vérifier les fichiers problématiques
        $this->info('1. Fichiers potentiellement problématiques:');
        
        $files = [
            'app/Http/Controllers/SecurityTestController.php',
            'app/Http/Middleware/ForceHTTPS.php',
        ];
        
        foreach ($files as $file) {
            $fullPath = base_path($file);
            if (file_exists($fullPath)) {
                $this->error("❌ TROUVÉ: {$file}");
                // Supprimer immédiatement
                unlink($fullPath);
                $this->info("✅ SUPPRIMÉ: {$file}");
            } else {
                $this->info("✅ OK: {$file} n'existe pas");
            }
        }

        // 2. Test real-time de la page
        $this->info('2. Test temps réel de /rentals...');
        
        try {
            // Simuler une vraie requête HTTP
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://farmshop-production.up.railway.app/rentals');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            $this->info("Code HTTP: {$httpCode}");
            
            if ($httpCode === 200) {
                $this->info('🎉 SUCCESS! Page fonctionne en production');
            } else {
                $this->error("❌ Erreur {$httpCode} en production");
                if ($error) {
                    $this->error("Erreur cURL: {$error}");
                }
            }
            
        } catch (\Exception $e) {
            $this->error('Erreur test: ' . $e->getMessage());
        }

        // 3. Clear cache final
        $this->info('3. Nettoyage final...');
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $this->info('✅ Cache nettoyé');

        // 4. Test interne Laravel
        $this->info('4. Test interne Laravel...');
        try {
            $request = \Illuminate\Http\Request::create('/rentals', 'GET');
            $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
            $response = $kernel->handle($request);
            $statusCode = $response->getStatusCode();
            
            if ($statusCode === 200) {
                $this->info('✅ Test interne OK (200)');
            } else {
                $this->error("❌ Test interne erreur ({$statusCode})");
                // Afficher le début de l'erreur
                $content = $response->getContent();
                $this->error('Erreur: ' . substr(strip_tags($content), 0, 200));
            }
            
        } catch (\Throwable $e) {
            $this->error('Exception interne: ' . $e->getMessage());
            $this->error('Fichier: ' . $e->getFile() . ':' . $e->getLine());
        }

        $this->info('=== FIN VÉRIFICATION ===');
    }
}
