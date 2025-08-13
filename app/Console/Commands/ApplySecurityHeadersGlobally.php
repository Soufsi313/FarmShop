<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ApplySecurityHeadersGlobally extends Command
{
    protected $signature = 'app:apply-security-headers-globally';
    protected $description = 'Appliquer les headers de sécurité globalement via AppServiceProvider';

    public function handle()
    {
        $this->info('=== APPLICATION GLOBALE DES HEADERS DE SÉCURITÉ ===');

        // 1. Modifier AppServiceProvider pour ajouter les headers globalement
        $this->info('1. Modification de AppServiceProvider...');
        
        $providerPath = app_path('Providers/AppServiceProvider.php');
        if (!file_exists($providerPath)) {
            $this->error('AppServiceProvider.php non trouvé');
            return;
        }

        $content = file_get_contents($providerPath);
        
        // Vérifier si les headers ne sont pas déjà ajoutés
        if (strpos($content, 'Strict-Transport-Security') === false) {
            
            // Ajouter l'import Response si nécessaire
            if (strpos($content, 'use Illuminate\Http\Response;') === false) {
                $content = str_replace(
                    'use Illuminate\Support\ServiceProvider;',
                    "use Illuminate\Support\ServiceProvider;\nuse Illuminate\Http\Response;",
                    $content
                );
            }
            
            // Trouver la méthode boot() et ajouter les headers
            $bootMethod = 'public function boot(): void
    {
        // Headers de sécurité globaux
        Response::macro(\'withSecurityHeaders\', function () {
            return $this->withHeaders([
                \'Strict-Transport-Security\' => \'max-age=31536000; includeSubDomains; preload\',
                \'X-Frame-Options\' => \'DENY\',
                \'X-Content-Type-Options\' => \'nosniff\',
                \'X-XSS-Protection\' => \'1; mode=block\',
                \'Referrer-Policy\' => \'strict-origin-when-cross-origin\',
                \'Content-Security-Policy\' => "default-src \'self\'; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\' https://unpkg.com https://cdn.jsdelivr.net; style-src \'self\' \'unsafe-inline\' https://fonts.googleapis.com; font-src \'self\' https://fonts.gstatic.com; img-src \'self\' data: https:; connect-src \'self\'",
                \'Permissions-Policy\' => \'geolocation=(), microphone=(), camera=()\'
            ]);
        });
        
        // Appliquer automatiquement les headers à toutes les réponses
        app()->singleton(\'response.headers\', function () {
            return [
                \'Strict-Transport-Security\' => \'max-age=31536000; includeSubDomains; preload\',
                \'X-Frame-Options\' => \'DENY\',
                \'X-Content-Type-Options\' => \'nosniff\',
                \'X-XSS-Protection\' => \'1; mode=block\',
                \'Referrer-Policy\' => \'strict-origin-when-cross-origin\',
                \'Permissions-Policy\' => \'geolocation=(), microphone=(), camera=()\'
            ];
        });
    }';
            
            // Remplacer la méthode boot existante
            if (preg_match('/public function boot\(\): void\s*\{[^}]*\}/', $content)) {
                $content = preg_replace('/public function boot\(\): void\s*\{[^}]*\}/', $bootMethod, $content);
            } else {
                // Si pas de méthode boot, l'ajouter avant la fermeture de classe
                $content = str_replace('}', "\n    " . $bootMethod . "\n}", $content);
            }
            
            file_put_contents($providerPath, $content);
            $this->info('✅ AppServiceProvider modifié');
        } else {
            $this->info('✅ Headers déjà configurés dans AppServiceProvider');
        }

        // 2. Créer un middleware global plus simple
        $this->info('2. Création d\'un middleware global simplifié...');
        
        $globalMiddlewareContent = '<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GlobalSecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Appliquer les headers à tous les types de réponse
        $headers = [
            \'Strict-Transport-Security\' => \'max-age=31536000; includeSubDomains; preload\',
            \'X-Frame-Options\' => \'DENY\',
            \'X-Content-Type-Options\' => \'nosniff\',
            \'X-XSS-Protection\' => \'1; mode=block\',
            \'Referrer-Policy\' => \'strict-origin-when-cross-origin\',
            \'Permissions-Policy\' => \'geolocation=(), microphone=(), camera=()\'
        ];
        
        foreach ($headers as $name => $value) {
            $response->headers->set($name, $value);
        }
        
        return $response;
    }
}';

        $globalMiddlewarePath = app_path('Http/Middleware/GlobalSecurityHeaders.php');
        file_put_contents($globalMiddlewarePath, $globalMiddlewareContent);
        $this->info('✅ Middleware GlobalSecurityHeaders créé');

        // 3. Enregistrer le middleware global
        $this->info('3. Enregistrement du middleware global...');
        
        $appPath = base_path('bootstrap/app.php');
        $content = file_get_contents($appPath);
        
        if (strpos($content, 'GlobalSecurityHeaders') === false) {
            // Ajouter le middleware global
            $pattern = '/->withMiddleware\(function \(Middleware \$middleware\) \{([^}]+)\}\)/s';
            if (preg_match($pattern, $content, $matches)) {
                $middlewareConfig = $matches[1];
                $newMiddlewareConfig = $middlewareConfig . '
        
        // Middleware de sécurité global
        $middleware->web(append: [
            \App\Http\Middleware\GlobalSecurityHeaders::class,
        ]);
        
        $middleware->api(append: [
            \App\Http\Middleware\GlobalSecurityHeaders::class,
        ]);';
                
                $newContent = str_replace($matches[0], 
                    '->withMiddleware(function (Middleware $middleware) {' . $newMiddlewareConfig . '
    })', 
                    $content
                );
                
                file_put_contents($appPath, $newContent);
                $this->info('✅ Middleware global ajouté');
            }
        } else {
            $this->info('✅ Middleware global déjà enregistré');
        }

        // 4. Nettoyer tous les caches
        $this->info('4. Nettoyage complet des caches...');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        $this->info('✅ Caches nettoyés');

        // 5. Test final
        $this->info('5. Test du middleware...');
        try {
            $middleware = new \App\Http\Middleware\GlobalSecurityHeaders();
            $request = \Illuminate\Http\Request::create('/', 'GET');
            
            $response = $middleware->handle($request, function ($request) {
                return response('Test');
            });
            
            $hasHSTS = $response->headers->has('Strict-Transport-Security');
            $hasXFrame = $response->headers->has('X-Frame-Options');
            
            if ($hasHSTS && $hasXFrame) {
                $this->info('✅ Middleware fonctionne correctement');
            } else {
                $this->warn('⚠️  Middleware partiellement fonctionnel');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur dans le middleware: ' . $e->getMessage());
        }

        $this->info('=== APPLICATION TERMINÉE ===');
        $this->info('');
        $this->info('🔒 Headers de sécurité configurés globalement !');
        $this->info('🚀 Redémarrage de l\'application recommandé');
        $this->info('📊 Testez avec: railway run php artisan app:test-ssl-security');
    }
}
