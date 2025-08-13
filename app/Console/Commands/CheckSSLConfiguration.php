<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckSSLConfiguration extends Command
{
    protected $signature = 'app:check-ssl-config';
    protected $description = 'Vérifier la configuration SSL et HTTPS';

    public function handle()
    {
        $this->info('=== VÉRIFICATION CONFIGURATION SSL ===');

        // 1. Vérifier les variables d'environnement
        $this->info('1. Variables d\'environnement:');
        $this->info('APP_URL: ' . env('APP_URL', 'non défini'));
        $this->info('APP_ENV: ' . env('APP_ENV', 'non défini'));
        $this->info('FORCE_HTTPS: ' . env('FORCE_HTTPS', 'non défini'));
        $this->info('SESSION_SECURE_COOKIE: ' . env('SESSION_SECURE_COOKIE', 'non défini'));

        // 2. Vérifier la configuration Laravel
        $this->info('2. Configuration Laravel:');
        $this->info('config(app.url): ' . config('app.url'));
        $this->info('config(session.secure): ' . (config('session.secure') ? 'true' : 'false'));
        $this->info('config(session.same_site): ' . config('session.same_site'));

        // 3. Vérifier si on est en HTTPS
        $this->info('3. État HTTPS:');
        $isHttps = request()->isSecure();
        $this->info('Request is secure: ' . ($isHttps ? 'OUI' : 'NON'));
        
        if (isset($_SERVER['HTTPS'])) {
            $this->info('$_SERVER[HTTPS]: ' . $_SERVER['HTTPS']);
        }
        
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            $this->info('X-Forwarded-Proto: ' . $_SERVER['HTTP_X_FORWARDED_PROTO']);
        }

        // 4. Test de génération d'URL
        $this->info('4. Génération d\'URLs:');
        $this->info('url("/"): ' . url('/'));
        $this->info('secure_url("/"): ' . secure_url('/'));
        $this->info('route("rentals.index"): ' . route('rentals.index'));

        // 5. Vérifications de sécurité
        $this->info('5. Headers de sécurité:');
        $headers = getallheaders();
        if (isset($headers['X-Forwarded-Proto'])) {
            $this->info('X-Forwarded-Proto présent: ' . $headers['X-Forwarded-Proto']);
        }

        $this->info('=== RECOMMANDATIONS ===');
        
        if (!$isHttps) {
            $this->warn('⚠️  HTTPS non détecté - Configuration nécessaire');
        } else {
            $this->info('✅ HTTPS détecté');
        }

        if (config('app.url') && !str_starts_with(config('app.url'), 'https://')) {
            $this->warn('⚠️  APP_URL devrait commencer par https://');
        }

        if (!config('session.secure')) {
            $this->warn('⚠️  SESSION_SECURE_COOKIE devrait être à true en production');
        }

        $this->info('=== FIN VÉRIFICATION ===');
    }
}
