<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ForceSecurityHeaders extends Command
{
    protected $signature = 'app:force-security-headers';
    protected $description = 'Forcer l\'application des headers de sécurité';

    public function handle()
    {
        $this->info('=== ACTIVATION FORCÉE DES HEADERS DE SÉCURITÉ ===');

        // 1. Vérifier et corriger le middleware existant
        $this->info('1. Mise à jour du middleware ForceHTTPS...');
        
        $middlewareContent = '<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHTTPS
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Forcer HTTPS en production
        if (!$request->isSecure() && app()->environment(\'production\')) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        // Traiter la requête
        $response = $next($request);
        
        // Ajouter les headers de sécurité pour tous les types de réponse
        if ($response instanceof \Illuminate\Http\Response || 
            $response instanceof \Illuminate\Http\JsonResponse || 
            method_exists($response, \'header\')) {
            
            // HSTS - Forcer HTTPS pour 1 an
            $response->headers->set(\'Strict-Transport-Security\', \'max-age=31536000; includeSubDomains; preload\');
            
            // Protection contre le clickjacking
            $response->headers->set(\'X-Frame-Options\', \'DENY\');
            
            // Empêcher la détection automatique du type MIME
            $response->headers->set(\'X-Content-Type-Options\', \'nosniff\');
            
            // Protection XSS (bien que dépréciée, toujours utile pour les anciens navigateurs)
            $response->headers->set(\'X-XSS-Protection\', \'1; mode=block\');
            
            // Politique de référent stricte
            $response->headers->set(\'Referrer-Policy\', \'strict-origin-when-cross-origin\');
            
            // Content Security Policy basique
            $response->headers->set(\'Content-Security-Policy\', "default-src \'self\'; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\' https://unpkg.com https://cdn.jsdelivr.net; style-src \'self\' \'unsafe-inline\' https://fonts.googleapis.com; font-src \'self\' https://fonts.gstatic.com; img-src \'self\' data: https:; connect-src \'self\'");
            
            // Permissions Policy
            $response->headers->set(\'Permissions-Policy\', \'geolocation=(), microphone=(), camera=()\');
        }

        return $response;
    }
}';

        $middlewarePath = app_path('Http/Middleware/ForceHTTPS.php');
        file_put_contents($middlewarePath, $middlewareContent);
        $this->info('✅ Middleware ForceHTTPS mis à jour avec headers renforcés');

        // 2. Vérifier l'enregistrement dans bootstrap/app.php
        $this->info('2. Vérification de l\'enregistrement...');
        $appPath = base_path('bootstrap/app.php');
        $content = file_get_contents($appPath);
        
        if (strpos($content, 'ForceHTTPS') !== false) {
            $this->info('✅ Middleware enregistré dans bootstrap/app.php');
        } else {
            $this->warn('⚠️  Middleware non trouvé, ajout en cours...');
            
            // Ajouter le middleware
            $pattern = '/->withMiddleware\(function \(Middleware \$middleware\) \{([^}]+)\}\)/s';
            if (preg_match($pattern, $content, $matches)) {
                $middlewareConfig = $matches[1];
                $newMiddlewareConfig = $middlewareConfig . '
        
        // Middleware de sécurité HTTPS
        $middleware->web(append: [
            \App\Http\Middleware\ForceHTTPS::class,
        ]);';
                
                $newContent = str_replace($matches[0], 
                    '->withMiddleware(function (Middleware $middleware) {' . $newMiddlewareConfig . '
    })', 
                    $content
                );
                
                file_put_contents($appPath, $newContent);
                $this->info('✅ Middleware ajouté à bootstrap/app.php');
            }
        }

        // 3. Créer un test de headers en temps réel
        $this->info('3. Test des headers en temps réel...');
        
        // Simuler une requête avec le middleware
        $request = \Illuminate\Http\Request::create('/', 'GET');
        $middleware = new \App\Http\Middleware\ForceHTTPS();
        
        $response = $middleware->handle($request, function ($request) {
            return response('Test headers');
        });
        
        // Vérifier les headers
        $headers = $response->headers->all();
        
        $securityHeaders = [
            'strict-transport-security' => 'HSTS',
            'x-frame-options' => 'Protection Clickjacking',
            'x-content-type-options' => 'Protection MIME',
            'x-xss-protection' => 'Protection XSS',
            'referrer-policy' => 'Politique de referent',
            'content-security-policy' => 'CSP',
            'permissions-policy' => 'Permissions Policy'
        ];
        
        $this->info('Headers de sécurité ajoutés:');
        foreach ($securityHeaders as $header => $description) {
            if (isset($headers[$header])) {
                $value = is_array($headers[$header]) ? $headers[$header][0] : $headers[$header];
                $this->info("✅ {$description}: {$value}");
            } else {
                $this->error("❌ {$description} manquant");
            }
        }

        // 4. Nettoyer tous les caches
        $this->info('4. Nettoyage des caches...');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        
        if (function_exists('opcache_reset')) {
            opcache_reset();
            $this->info('✅ Cache OPCache nettoyé');
        }
        
        $this->info('✅ Tous les caches nettoyés');

        $this->info('=== ACTIVATION TERMINÉE ===');
        $this->info('');
        $this->info('🔒 Headers de sécurité renforcés appliqués !');
        $this->info('📋 Prochaines étapes:');
        $this->info('1. Le middleware sera actif pour toutes les nouvelles requêtes');
        $this->info('2. Testez avec: railway run php artisan app:test-ssl-security');
        $this->info('3. Vérifiez sur https://securityheaders.com');
    }
}
