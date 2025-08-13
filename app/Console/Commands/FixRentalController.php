<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixRentalController extends Command
{
    protected $signature = 'app:fix-rental-controller';
    protected $description = 'Corriger le RentalController avec une syntaxe propre';

    public function handle()
    {
        $controllerPath = app_path('Http/Controllers/RentalController.php');
        
        $content = '<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RentalCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    /**
     * Afficher la page des locations avec tous les produits louables
     */
    public function index(Request $request)
    {
        $query = Product::with([\'category\'])
            ->where(\'is_active\', true)
            ->whereIn(\'type\', [\'rental\', \'mixed\'])
            ->where(\'rental_stock\', \'>\', 0);

        // Filtrage par catégorie
        if ($request->filled(\'category\')) {
            $query->where(\'category_id\', $request->category);
        }

        // Filtrage par recherche
        if ($request->filled(\'search\')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where(\'name\', \'like\', "%{$search}%")
                  ->orWhere(\'description\', \'like\', "%{$search}%")
                  ->orWhere(\'sku\', \'like\', "%{$search}%");
            });
        }

        // Filtrage par prix
        if ($request->filled(\'min_price\')) {
            $query->where(\'price\', \'>=\', $request->min_price);
        }

        if ($request->filled(\'max_price\')) {
            $query->where(\'price\', \'<=\', $request->max_price);
        }

        // Tri
        $sortBy = $request->get(\'sort\', \'name\');
        $sortOrder = $request->get(\'order\', \'asc\');

        switch ($sortBy) {
            case \'price\':
                $query->orderBy(\'price\', $sortOrder);
                break;
            case \'popularity\':
                $query->orderBy(\'views_count\', \'desc\');
                break;
            case \'newest\':
                $query->orderBy(\'created_at\', \'desc\');
                break;
            default:
                $query->orderBy(\'name\', $sortOrder);
                break;
        }

        $products = $query->paginate(12);

        // Récupérer les catégories pour les filtres
        $rentalCategories = \App\Models\Category::whereHas(\'products\', function($query) {
            $query->whereIn(\'type\', [\'rental\', \'mixed\'])->where(\'is_active\', true);
        })->get();

        // Calculer les statistiques des prix pour les filtres
        $priceStats = Product::whereIn(\'type\', [\'rental\', \'mixed\'])
            ->where(\'is_active\', true)
            ->selectRaw(\'MIN(price) as min_price, MAX(price) as max_price\')
            ->first();

        return view(\'web.rentals.index\', compact(\'products\', \'rentalCategories\', \'priceStats\'));
    }

    /**
     * Afficher les détails d\'un produit de location
     */
    public function show(Product $product)
    {
        // Vérifier que le produit est louable
        if (!$product->isRentable()) {
            abort(404, \'Ce produit n\\\'est pas disponible à la location\');
        }

        // Charger les relations
        $product->load([\'category\']);

        // Incrémenter le compteur de vues
        $product->increment(\'views_count\');

        // Récupérer les produits similaires
        $similarProducts = Product::with([\'category\'])
            ->where(\'id\', \'!=\', $product->id)
            ->where(\'category_id\', $product->category_id)
            ->where(\'is_active\', true)
            ->whereIn(\'type\', [\'rental\', \'mixed\'])
            ->where(\'rental_stock\', \'>\', 0)
            ->limit(4)
            ->get();

        return view(\'web.rentals.show\', compact(\'product\', \'similarProducts\'));
    }
}';

        file_put_contents($controllerPath, $content);
        
        $this->info('✅ RentalController corrigé avec succès!');
        $this->info('Le fichier a été écrit à: ' . $controllerPath);
        
        // Vérifier la syntaxe
        $check = shell_exec("php -l {$controllerPath}");
        if (strpos($check, 'No syntax errors') !== false) {
            $this->info('✅ Syntaxe PHP validée');
        } else {
            $this->error('❌ Erreur de syntaxe détectée:');
            $this->error($check);
        }
    }
}
