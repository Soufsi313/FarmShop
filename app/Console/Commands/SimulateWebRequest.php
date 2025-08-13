<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class SimulateWebRequest extends Command
{
    protected $signature = 'app:simulate-web-request';
    protected $description = 'Simuler une vraie requête web avec middleware pour /rentals';

    public function handle()
    {
        $this->info('=== SIMULATION REQUÊTE WEB /RENTALS ===');

        try {
            // Simuler une vraie requête HTTP avec tous les middleware
            $this->info('1. Création d\'une requête HTTP complète...');
            
            $request = Request::create(
                '/rentals',
                'GET',
                [], // Paramètres GET
                [ // Cookies (simuler les cookies du navigateur)
                    'XSRF-TOKEN' => 'test-token',
                    'laravel_session' => 'test-session'
                ],
                [], // Files
                [ // Server variables
                    'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'HTTP_ACCEPT_LANGUAGE' => 'fr,fr-FR;q=0.8,en-US;q=0.5,en;q=0.3',
                    'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:141.0) Gecko/20100101 Firefox/141.0',
                    'HTTP_HOST' => 'farmshop-production.up.railway.app',
                    'HTTPS' => 'on',
                    'SERVER_PORT' => '443'
                ]
            );

            $this->info('2. Test via le kernel Laravel (avec middleware)...');
            
            // Utiliser le kernel pour traiter la requête comme en production
            $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
            $response = $kernel->handle($request);
            
            $this->info('✓ Requête traitée par le kernel');
            $this->info('Code de statut: ' . $response->getStatusCode());
            $this->info('Type de contenu: ' . $response->headers->get('content-type'));
            $this->info('Taille de la réponse: ' . strlen($response->getContent()) . ' bytes');
            
            if ($response->getStatusCode() === 500) {
                $this->error('=== ERREUR 500 DÉTECTÉE ===');
                $content = $response->getContent();
                
                // Extraire le message d'erreur de la page d'erreur Laravel
                if (strpos($content, 'Whoops') !== false || strpos($content, 'Exception') !== false) {
                    $this->error('Page d\'erreur Laravel détectée');
                    
                    // Chercher le message d'erreur principal
                    if (preg_match('/<h1[^>]*>(.*?)<\/h1>/s', $content, $matches)) {
                        $this->error('Titre d\'erreur: ' . strip_tags($matches[1]));
                    }
                    
                    if (preg_match('/<pre[^>]*>(.*?)<\/pre>/s', $content, $matches)) {
                        $errorDetail = strip_tags($matches[1]);
                        $this->error('Détail: ' . substr($errorDetail, 0, 500) . '...');
                    }
                    
                    // Sauvegarder la réponse complète pour analyse
                    file_put_contents(storage_path('logs/error_500_response.html'), $content);
                    $this->info('Réponse complète sauvée dans storage/logs/error_500_response.html');
                }
            } else {
                $this->info('✅ Réponse OK');
            }

        } catch (\Throwable $e) {
            $this->error('=== EXCEPTION CAPTURÉE ===');
            $this->error('Type: ' . get_class($e));
            $this->error('Message: ' . $e->getMessage());
            $this->error('Fichier: ' . $e->getFile() . ':' . $e->getLine());
            
            // Première partie de la stack trace
            $trace = explode("\n", $e->getTraceAsString());
            $this->error('Stack trace (10 premières lignes):');
            for ($i = 0; $i < min(10, count($trace)); $i++) {
                $this->error('  ' . $trace[$i]);
            }
        }

        $this->info('=== FIN SIMULATION ===');
    }
}
