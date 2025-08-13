<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateSecurityTestRoute extends Command
{
    protected $signature = 'app:create-security-test-route';
    protected $description = 'Créer une route de test pour vérifier les headers de sécurité';

    public function handle()
    {
        $this->info('=== CRÉATION ROUTE DE TEST SÉCURITÉ ===');

        // 1. Créer un contrôleur de test
        $this->info('1. Création du contrôleur de test...');
        
        $controllerContent = '<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SecurityTestController extends Controller
{
    public function testHeaders(Request $request)
    {
        // Créer une réponse avec informations sur les headers
        $response = response()->json([
            "message" => "Test des headers de sécurité",
            "timestamp" => now()->toISOString(),
            "request_secure" => $request->isSecure(),
            "app_env" => app()->environment(),
            "headers_info" => "Vérifiez les headers de réponse dans les outils de développement"
        ]);

        // Forcer l\'ajout des headers de sécurité
        $securityHeaders = [
            "Strict-Transport-Security" => "max-age=31536000; includeSubDomains; preload",
            "X-Frame-Options" => "DENY",
            "X-Content-Type-Options" => "nosniff",
            "X-XSS-Protection" => "1; mode=block",
            "Referrer-Policy" => "strict-origin-when-cross-origin",
            "Content-Security-Policy" => "default-src \'self\'; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\' https://unpkg.com https://cdn.jsdelivr.net; style-src \'self\' \'unsafe-inline\' https://fonts.googleapis.com; font-src \'self\' https://fonts.gstatic.com; img-src \'self\' data: https:; connect-src \'self\'",
            "Permissions-Policy" => "geolocation=(), microphone=(), camera=()"
        ];

        foreach ($securityHeaders as $name => $value) {
            $response->header($name, $value);
        }

        return $response;
    }

    public function testPage()
    {
        $html = \'<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Sécurité SSL</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .header { background: #10b981; color: white; padding: 20px; border-radius: 8px; }
        .test-item { background: #f3f4f6; padding: 15px; margin: 10px 0; border-radius: 6px; }
        .success { color: #10b981; }
        .warning { color: #f59e0b; }
        .error { color: #ef4444; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔒 Test de Sécurité SSL - FarmShop</h1>
        <p>Cette page teste les headers de sécurité de votre application</p>
    </div>
    
    <div class="test-item">
        <h3>📊 Informations de Sécurité</h3>
        <p><strong>Protocole:</strong> \' . (request()->isSecure() ? \'<span class="success">HTTPS ✅</span>\' : \'<span class="error">HTTP ❌</span>\') . \'</p>
        <p><strong>Environnement:</strong> \' . app()->environment() . \'</p>
        <p><strong>Timestamp:</strong> \' . now()->format(\'Y-m-d H:i:s\') . \'</p>
    </div>
    
    <div class="test-item">
        <h3>🛡️ Headers de Sécurité</h3>
        <p>Ouvrez les outils de développement de votre navigateur (F12) et vérifiez l\'onglet <strong>Network</strong> pour voir les headers de réponse suivants :</p>
        <ul>
            <li><strong>Strict-Transport-Security:</strong> Force HTTPS</li>
            <li><strong>X-Frame-Options:</strong> Protection contre le clickjacking</li>
            <li><strong>X-Content-Type-Options:</strong> Protection MIME</li>
            <li><strong>X-XSS-Protection:</strong> Protection XSS</li>
            <li><strong>Referrer-Policy:</strong> Politique de référent</li>
            <li><strong>Content-Security-Policy:</strong> Politique de sécurité du contenu</li>
            <li><strong>Permissions-Policy:</strong> Politique des permissions</li>
        </ul>
    </div>
    
    <div class="test-item">
        <h3>🔍 Test Externe</h3>
        <p>Pour un test complet, utilisez ces outils externes :</p>
        <ul>
            <li><a href="https://securityheaders.com/?q=farmshop-production.up.railway.app" target="_blank">SecurityHeaders.com</a></li>
            <li><a href="https://www.ssllabs.com/ssltest/analyze.html?d=farmshop-production.up.railway.app" target="_blank">SSL Labs Test</a></li>
        </ul>
    </div>
    
    <script>
        console.log("🔒 Page de test sécurité chargée");
        console.log("Protocol:", window.location.protocol);
        console.log("Host:", window.location.host);
    </script>
</body>
</html>\';

        $response = response($html);
        
        // Ajouter les headers de sécurité
        $securityHeaders = [
            "Strict-Transport-Security" => "max-age=31536000; includeSubDomains; preload",
            "X-Frame-Options" => "DENY", 
            "X-Content-Type-Options" => "nosniff",
            "X-XSS-Protection" => "1; mode=block",
            "Referrer-Policy" => "strict-origin-when-cross-origin",
            "Content-Security-Policy" => "default-src \'self\'; script-src \'self\' \'unsafe-inline\'; style-src \'self\' \'unsafe-inline\';",
            "Permissions-Policy" => "geolocation=(), microphone=(), camera=()"
        ];

        foreach ($securityHeaders as $name => $value) {
            $response->header($name, $value);
        }

        return $response;
    }
}';

        $controllerPath = app_path('Http/Controllers/SecurityTestController.php');
        file_put_contents($controllerPath, $controllerContent);
        $this->info('✅ SecurityTestController créé');

        // 2. Ajouter les routes
        $this->info('2. Ajout des routes de test...');
        
        $routesPath = base_path('routes/web.php');
        $routesContent = file_get_contents($routesPath);
        
        if (strpos($routesContent, 'security-test') === false) {
            $newRoutes = "\n\n// Routes de test sécurité\nRoute::get('/security-test', [App\\Http\\Controllers\\SecurityTestController::class, 'testPage'])->name('security.test.page');\nRoute::get('/security-headers', [App\\Http\\Controllers\\SecurityTestController::class, 'testHeaders'])->name('security.test.headers');";
            
            file_put_contents($routesPath, $routesContent . $newRoutes);
            $this->info('✅ Routes de test ajoutées');
        } else {
            $this->info('✅ Routes de test déjà présentes');
        }

        // 3. Nettoyer les caches
        $this->info('3. Nettoyage des caches...');
        \Artisan::call('route:clear');
        \Artisan::call('config:clear');
        $this->info('✅ Caches nettoyés');

        $this->info('=== ROUTES DE TEST CRÉÉES ===');
        $this->info('');
        $this->info('🔗 URLs de test disponibles :');
        $this->info('📄 Page de test: https://farmshop-production.up.railway.app/security-test');
        $this->info('🔧 API headers: https://farmshop-production.up.railway.app/security-headers');
        $this->info('');
        $this->info('📋 Instructions :');
        $this->info('1. Visitez la page de test dans votre navigateur');
        $this->info('2. Ouvrez les outils de développement (F12)');
        $this->info('3. Allez dans l\'onglet Network');
        $this->info('4. Rechargez la page');
        $this->info('5. Cliquez sur la requête et vérifiez les Response Headers');
    }
}
