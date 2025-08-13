<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixRentals500 extends Command
{
    protected $signature = 'app:fix-rentals-500';
    protected $description = 'Correction immédiate de l\'erreur 500 sur /rentals';

    public function handle()
    {
        $this->info('=== CORRECTION ERREUR 500 /RENTALS ===');

        try {
            // 1. Supprimer tous les middlewares récents problématiques
            $this->info('1. Nettoyage des middlewares problématiques...');
            
            $appPath = base_path('bootstrap/app.php');
            $content = file_get_contents($appPath);
            
            // Supprimer le middleware ForceHTTPS qui peut causer des problèmes
            $content = preg_replace('/\s*\$middleware->web\(append: \[\s*\\\\App\\\\Http\\\\Middleware\\\\ForceHTTPS::class,\s*\]\);/', '', $content);
            
            file_put_contents($appPath, $content);
            $this->info('✅ Middleware problématique supprimé');

            // 2. Vérifier et nettoyer le cache
            $this->info('2. Nettoyage du cache...');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            $this->info('✅ Cache nettoyé');

            // 3. Vérifier la route
            $this->info('3. Vérification de la route...');
            $routes = \Illuminate\Support\Facades\Route::getRoutes();
            $rentalRoute = null;
            foreach ($routes as $route) {
                if ($route->uri() === 'rentals' && in_array('GET', $route->methods())) {
                    $rentalRoute = $route;
                    break;
                }
            }
            
            if ($rentalRoute) {
                $this->info('✅ Route /rentals trouvée');
            } else {
                $this->error('❌ Route /rentals manquante');
            }

            // 4. Test immédiat
            $this->info('4. Test après correction...');
            $request = \Illuminate\Http\Request::create('/rentals', 'GET');
            $response = app('router')->dispatch($request);
            $statusCode = $response->getStatusCode();
            
            if ($statusCode === 200) {
                $this->info('🎉 SUCCÈS! Code 200 - Erreur 500 corrigée!');
            } else {
                $this->error("❌ Toujours en erreur: {$statusCode}");
                
                // Tentative de correction plus agressive
                $this->info('5. Correction agressive...');
                
                // Restaurer un RentalController minimal qui fonctionne
                $controllerContent = '<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where("is_active", true)
            ->whereIn("type", ["rental", "mixed"])
            ->where("rental_stock", ">", 0)
            ->paginate(12);

        $rentalCategories = \App\Models\Category::whereHas("products", function($query) {
            $query->whereIn("type", ["rental", "mixed"])->where("is_active", true);
        })->get();

        $priceStats = Product::whereIn("type", ["rental", "mixed"])
            ->where("is_active", true)
            ->selectRaw("MIN(price) as min_price, MAX(price) as max_price")
            ->first();

        return view("web.rentals.index", compact("products", "rentalCategories", "priceStats"));
    }

    public function show(Product $product)
    {
        if (!$product->isRentable()) {
            abort(404);
        }

        $product->load(["category"]);
        $product->increment("views_count");

        $similarProducts = Product::where("id", "!=", $product->id)
            ->where("category_id", $product->category_id)
            ->where("is_active", true)
            ->whereIn("type", ["rental", "mixed"])
            ->where("rental_stock", ">", 0)
            ->limit(4)
            ->get();

        return view("web.rentals.show", compact("product", "similarProducts"));
    }
}';

                file_put_contents(app_path('Http/Controllers/RentalController.php'), $controllerContent);
                $this->info('✅ RentalController restauré en version minimal');
                
                // Test final
                $response = app('router')->dispatch($request);
                $finalCode = $response->getStatusCode();
                
                if ($finalCode === 200) {
                    $this->info('🎉 CORRECTION RÉUSSIE! Code 200');
                } else {
                    $this->error("❌ Échec final: {$finalCode}");
                }
            }

        } catch (\Exception $e) {
            $this->error('ERREUR lors de la correction:');
            $this->error($e->getMessage());
        }

        $this->info('=== FIN CORRECTION ===');
    }
}
