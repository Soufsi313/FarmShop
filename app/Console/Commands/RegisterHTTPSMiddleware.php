<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RegisterHTTPSMiddleware extends Command
{
    protected $signature = 'app:register-https-middleware';
    protected $description = 'Enregistrer le middleware HTTPS dans Laravel 11';

    public function handle()
    {
        $this->info('=== ENREGISTREMENT MIDDLEWARE HTTPS ===');

        $appPath = base_path('bootstrap/app.php');
        
        if (!file_exists($appPath)) {
            $this->error('Fichier bootstrap/app.php non trouvé');
            return;
        }

        $content = file_get_contents($appPath);
        
        // Vérifier si le middleware n'est pas déjà ajouté
        if (strpos($content, 'ForceHTTPS') !== false) {
            $this->info('✅ Middleware HTTPS déjà enregistré');
            return;
        }

        // Ajouter le middleware dans le groupe web
        $pattern = '/->withMiddleware\(function \(Middleware \$middleware\) \{([^}]+)\}\)/s';
        
        if (preg_match($pattern, $content, $matches)) {
            $middlewareConfig = $matches[1];
            
            // Ajouter notre middleware ForceHTTPS au groupe web
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
            $this->info('✅ Middleware HTTPS ajouté à bootstrap/app.php');
            
        } else {
            $this->error('❌ Structure withMiddleware non trouvée');
        }

        // Vérifier le résultat
        $this->info('2. Vérification de l\'enregistrement...');
        $updatedContent = file_get_contents($appPath);
        if (strpos($updatedContent, 'ForceHTTPS') !== false) {
            $this->info('✅ Middleware correctement ajouté');
        } else {
            $this->error('❌ Échec de l\'ajout du middleware');
        }

        $this->info('=== ENREGISTREMENT TERMINÉ ===');
        $this->info('Le middleware sera actif après redéploiement');
    }
}
