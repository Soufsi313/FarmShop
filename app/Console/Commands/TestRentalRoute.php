<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RentalController;
use Illuminate\Http\Request;

class TestRentalRoute extends Command
{
    protected $signature = 'app:test-rental-route';
    protected $description = 'Tester directement la route /rentals pour identifier l\'erreur 500';

    public function handle()
    {
        $this->info('=== TEST DE LA ROUTE /RENTALS ===');

        try {
            // Test 1: Vérifier que la route existe
            $this->info('1. Vérification de l\'existence de la route...');
            $routes = Route::getRoutes();
            $rentalRoute = null;
            foreach ($routes as $route) {
                if ($route->uri() === 'rentals' && in_array('GET', $route->methods())) {
                    $rentalRoute = $route;
                    break;
                }
            }

            if ($rentalRoute) {
                $this->info('✓ Route GET /rentals trouvée');
                $this->info("Action: {$rentalRoute->getActionName()}");
            } else {
                $this->error('✗ Route GET /rentals non trouvée');
                return;
            }

            // Test 2: Créer une fausse requête et tester le contrôleur
            $this->info('2. Test du contrôleur RentalController...');
            $controller = new RentalController();
            $request = new Request();

            // Test avec différents paramètres
            $this->info('2a. Test sans paramètres...');
            $response = $controller->index($request);
            $this->info('✓ Contrôleur sans paramètres OK');

            // Test avec paramètres de filtrage
            $this->info('2b. Test avec paramètres de filtrage...');
            $request->merge(['search' => 'test']);
            $response = $controller->index($request);
            $this->info('✓ Contrôleur avec recherche OK');

            $this->info('2c. Test avec filtrage par catégorie...');
            $request = new Request();
            $request->merge(['category' => '1']);
            $response = $controller->index($request);
            $this->info('✓ Contrôleur avec filtrage par catégorie OK');

            $this->info('2d. Test avec filtrage par prix...');
            $request = new Request();
            $request->merge(['min_price' => '10', 'max_price' => '50']);
            $response = $controller->index($request);
            $this->info('✓ Contrôleur avec filtrage par prix OK');

            // Test 3: Vérifier le type de réponse
            $this->info('3. Vérification du type de réponse...');
            if ($response instanceof \Illuminate\View\View) {
                $this->info('✓ Réponse est une vue');
                $this->info("Vue: {$response->name()}");
                
                // Vérifier les données passées à la vue
                $data = $response->getData();
                $this->info('Variables passées à la vue:');
                foreach (array_keys($data) as $key) {
                    $this->info("  - {$key}");
                }
            } else {
                $this->info("Type de réponse: " . get_class($response));
            }

            $this->info('=== TEST TERMINÉ AVEC SUCCÈS ===');

        } catch (\Exception $e) {
            $this->error('ERREUR DÉTECTÉE:');
            $this->error("Message: {$e->getMessage()}");
            $this->error("Fichier: {$e->getFile()}:{$e->getLine()}");
            $this->error("Trace:");
            $this->error($e->getTraceAsString());
        }
    }
}
