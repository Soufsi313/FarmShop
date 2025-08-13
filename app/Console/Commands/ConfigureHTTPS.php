<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConfigureHTTPS extends Command
{
    protected $signature = 'app:configure-https';
    protected $description = 'Configurer HTTPS et sécuriser l\'application';

    public function handle()
    {
        $this->info('=== CONFIGURATION HTTPS ET SÉCURITÉ ===');

        try {
            // 1. Mettre à jour la configuration de session pour HTTPS
            $this->info('1. Configuration des sessions sécurisées...');
            
            $sessionConfigPath = config_path('session.php');
            if (file_exists($sessionConfigPath)) {
                $sessionConfig = file_get_contents($sessionConfigPath);
                
                // Mettre 'secure' à true pour HTTPS
                if (strpos($sessionConfig, "'secure' => false") !== false) {
                    $sessionConfig = str_replace(
                        "'secure' => false",
                        "'secure' => env('SESSION_SECURE_COOKIE', true)",
                        $sessionConfig
                    );
                    file_put_contents($sessionConfigPath, $sessionConfig);
                    $this->info('✅ Configuration session.secure mise à jour');
                }

                // Mettre same_site à 'strict' pour plus de sécurité
                if (strpos($sessionConfig, "'same_site' => 'lax'") !== false) {
                    $sessionConfig = str_replace(
                        "'same_site' => 'lax'",
                        "'same_site' => env('SESSION_SAME_SITE_COOKIE', 'strict')",
                        $sessionConfig
                    );
                    file_put_contents($sessionConfigPath, $sessionConfig);
                    $this->info('✅ Configuration session.same_site mise à jour');
                }
            }

            // 2. Créer un middleware pour forcer HTTPS
            $this->info('2. Création du middleware ForceHTTPS...');
            
            $middlewareContent = '<?php

namespace App\\Http\\Middleware;

use Closure;
use Illuminate\\Http\\Request;

class ForceHTTPS
{
    public function handle(Request $request, Closure $next)
    {
        // Forcer HTTPS en production
        if (!$request->isSecure() && app()->environment(\'production\')) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        // Ajouter des headers de sécurité
        $response = $next($request);
        
        if (method_exists($response, \'header\')) {
            // Forcer HTTPS pour 1 an
            $response->header(\'Strict-Transport-Security\', \'max-age=31536000; includeSubDomains\');
            
            // Empêcher le chargement dans des iframes (protection contre clickjacking)
            $response->header(\'X-Frame-Options\', \'DENY\');
            
            // Empêcher la détection du type MIME
            $response->header(\'X-Content-Type-Options\', \'nosniff\');
            
            // Protection XSS
            $response->header(\'X-XSS-Protection\', \'1; mode=block\');
            
            // Politique de référent
            $response->header(\'Referrer-Policy\', \'strict-origin-when-cross-origin\');
        }

        return $response;
    }
}';

            $middlewarePath = app_path('Http/Middleware/ForceHTTPS.php');
            file_put_contents($middlewarePath, $middlewareContent);
            $this->info('✅ Middleware ForceHTTPS créé');

            // 3. Enregistrer le middleware
            $this->info('3. Enregistrement du middleware...');
            
            $kernelPath = app_path('Http/Kernel.php');
            if (file_exists($kernelPath)) {
                $kernelContent = file_get_contents($kernelPath);
                
                // Ajouter le middleware dans la liste web
                if (strpos($kernelContent, 'ForceHTTPS') === false) {
                    $webMiddleware = "'web' => [";
                    if (strpos($kernelContent, $webMiddleware) !== false) {
                        $replacement = $webMiddleware . "
            \\App\\Http\\Middleware\\ForceHTTPS::class,";
                        $kernelContent = str_replace($webMiddleware, $replacement, $kernelContent);
                        file_put_contents($kernelPath, $kernelContent);
                        $this->info('✅ Middleware ajouté au groupe web');
                    }
                }
            }

            // 4. Mettre à jour le fichier .env pour les nouvelles variables
            $this->info('4. Variables d\'environnement recommandées...');
            $this->info('Ajoutez ces variables à votre configuration Railway:');
            $this->info('SESSION_SECURE_COOKIE=true');
            $this->info('SESSION_SAME_SITE_COOKIE=strict');
            $this->info('FORCE_HTTPS=true');

            // 5. Créer une route de test SSL
            $this->info('5. Test de la configuration...');
            $testUrl = url('/');
            $this->info('URL de test: ' . $testUrl);
            
            if (str_starts_with($testUrl, 'https://')) {
                $this->info('✅ URLs générées en HTTPS');
            } else {
                $this->warn('⚠️  URLs non générées en HTTPS');
            }

            $this->info('=== CONFIGURATION TERMINÉE ===');
            $this->info('');
            $this->info('📋 ACTIONS SUIVANTES:');
            $this->info('1. Redéployer l\'application pour appliquer les changements');
            $this->info('2. Vérifier que le site fonctionne en HTTPS');
            $this->info('3. Tester la redirection HTTP -> HTTPS');
            $this->info('4. Vérifier les headers de sécurité');

        } catch (\Exception $e) {
            $this->error('Erreur lors de la configuration:');
            $this->error($e->getMessage());
        }
    }
}
