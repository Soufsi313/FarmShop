<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EnableHTTPSSecurely extends Command
{
    protected $signature = 'app:enable-https-securely';
    protected $description = 'Activer HTTPS de manière sécurisée sans casser l\'existant';

    public function handle()
    {
        $this->info('=== ACTIVATION HTTPS SÉCURISÉE ===');

        try {
            // 1. Vérifier l'état actuel
            $this->info('1. Vérification état actuel...');
            $this->info('APP_URL: ' . env('APP_URL'));
            $this->info('FORCE_HTTPS: ' . env('FORCE_HTTPS', 'non défini'));

            // 2. Créer un middleware HTTPS simple et fonctionnel
            $this->info('2. Création middleware HTTPS...');
            
            $middlewareContent = '<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecureHTTPS
{
    public function handle(Request $request, Closure $next)
    {
        // En production, forcer HTTPS
        if (app()->environment(\'production\') && !$request->isSecure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        $response = $next($request);
        
        // Ajouter headers de sécurité seulement si la réponse le permet
        if (method_exists($response, \'header\')) {
            $response->header(\'Strict-Transport-Security\', \'max-age=31536000; includeSubDomains\');
            $response->header(\'X-Content-Type-Options\', \'nosniff\');
            $response->header(\'X-Frame-Options\', \'DENY\');
            $response->header(\'Referrer-Policy\', \'strict-origin-when-cross-origin\');
        }

        return $response;
    }
}';

            $middlewarePath = app_path('Http/Middleware/SecureHTTPS.php');
            file_put_contents($middlewarePath, $middlewareContent);
            $this->info('✅ Middleware SecureHTTPS créé');

            // 3. Enregistrer le middleware dans bootstrap/app.php de manière sûre
            $this->info('3. Enregistrement du middleware...');
            
            $appPath = base_path('bootstrap/app.php');
            $content = file_get_contents($appPath);
            
            // Vérifier si pas déjà ajouté
            if (strpos($content, 'SecureHTTPS') === false) {
                // Chercher la section withMiddleware et ajouter à la fin
                $pattern = '/(->withMiddleware\(function \(Middleware \$middleware\) \{[^}]+)/';
                
                if (preg_match($pattern, $content, $matches)) {
                    $middlewareSection = $matches[1];
                    $newMiddlewareSection = $middlewareSection . '
        
        // Middleware HTTPS sécurisé pour production
        if (app()->environment(\'production\')) {
            $middleware->web(append: [
                \App\Http\Middleware\SecureHTTPS::class,
            ]);
        }';
                    
                    $content = str_replace($middlewareSection, $newMiddlewareSection, $content);
                    file_put_contents($appPath, $content);
                    $this->info('✅ Middleware enregistré');
                } else {
                    $this->warn('⚠️  Structure withMiddleware non trouvée');
                }
            } else {
                $this->info('✅ Middleware déjà enregistré');
            }

            // 4. Vérifier les variables d\'environnement
            $this->info('4. Variables d\'environnement...');
            
            $requiredVars = [
                'SESSION_SECURE_COOKIE' => 'true',
                'SESSION_SAME_SITE_COOKIE' => 'strict',
                'FORCE_HTTPS' => 'true'
            ];
            
            foreach ($requiredVars as $var => $value) {
                $current = env($var);
                if ($current === $value) {
                    $this->info("✅ {$var}: {$current}");
                } else {
                    $this->warn("⚠️  {$var}: {$current} (devrait être {$value})");
                }
            }

            // 5. Test de sécurité
            $this->info('5. Test de la configuration...');
            
            // Vérifier que l'APP_URL est en HTTPS
            $appUrl = env('APP_URL');
            if (str_starts_with($appUrl, 'https://')) {
                $this->info('✅ APP_URL configuré en HTTPS');
            } else {
                $this->warn('⚠️  APP_URL devrait commencer par https://');
            }

            $this->info('=== HTTPS CONFIGURÉ ===');
            $this->info('');
            $this->info('📋 Actions suivantes:');
            $this->info('1. Déployer les changements (git commit + push)');
            $this->info('2. Tester la redirection HTTP → HTTPS');
            $this->info('3. Vérifier que la page login est sécurisée');
            $this->info('');
            $this->info('🔒 Après déploiement, le site forcera HTTPS en production');

        } catch (\Exception $e) {
            $this->error('Erreur lors de la configuration:');
            $this->error($e->getMessage());
        }
    }
}
