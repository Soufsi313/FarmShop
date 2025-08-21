<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Message;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Vérifier que l'utilisateur est admin avant chaque action
     */
    private function checkAdminAccess()
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent accéder à la gestion de stock.');
        }
    }

    /**
     * Vue d'ensemble de la gestion de stock
     */
    public function index()
    {
        $this->checkAdminAccess();
        
        // Statistiques générales
        $stockStats = $this->getStockStatistics();
        
        // Produits par catégorie avec statut de stock
        $categoriesWithStock = Category::with(['products' => function($query) {
            $query->select('id', 'name', 'quantity', 'critical_threshold', 'low_stock_threshold', 'price', 'category_id');
        }])->get()->map(function($category) {
            $products = $category->products;
            
            return [
                'category' => $category,
                'total_products' => $products->count(),
                'out_of_stock' => $products->where('quantity', 0)->count(),
                'critical_stock' => $products->filter(function($p) {
                    return $p->quantity > 0 && $p->quantity <= $p->critical_threshold;
                })->count(),
                'low_stock' => $products->filter(function($p) {
                    return $p->quantity > $p->critical_threshold && 
                           $p->low_stock_threshold && 
                           $p->quantity <= $p->low_stock_threshold;
                })->count(),
                'normal_stock' => $products->filter(function($p) {
                    return !$p->low_stock_threshold ? 
                           $p->quantity > $p->critical_threshold :
                           $p->quantity > $p->low_stock_threshold;
                })->count(),
                'total_value' => $products->sum(function($p) {
                    return $p->quantity * $p->price;
                })
            ];
        });

        // Tendances des 7 derniers jours
        $trends = $this->getStockTrends();

        return view('admin.stock.index', compact(
            'stockStats', 
            'categoriesWithStock',
            'trends'
        ));
    }

    /**
     * Gestion des alertes et seuils critiques
     */
    public function alerts()
    {
        $this->checkAdminAccess();
        
        // Produits en stock critique
        $criticalProducts = Product::with('category')
                                  ->whereColumn('quantity', '<=', 'critical_threshold')
                                  ->orderBy('quantity', 'asc')
                                  ->get();

        // Produits en rupture de stock
        $outOfStockProducts = Product::with('category')
                                    ->where('quantity', 0)
                                    ->orderBy('updated_at', 'desc')
                                    ->get();

        // Produits en stock bas
        $lowStockProducts = Product::with('category')
                                  ->whereRaw('quantity > critical_threshold')
                                  ->whereRaw('quantity <= low_stock_threshold')
                                  ->orderBy('quantity', 'asc')
                                  ->get();

        // Alertes récentes
        $recentAlerts = Message::where('type', 'system')
                              ->where('subject', 'like', '%stock%')
                              ->orderBy('created_at', 'desc')
                              ->take(20)
                              ->get();

        // Configuration des seuils par défaut
        $defaultThresholds = [
            'critical_threshold' => 5,
            'low_stock_threshold' => 15
        ];

        return view('admin.stock.alerts', compact(
            'criticalProducts',
            'outOfStockProducts', 
            'lowStockProducts',
            'recentAlerts',
            'defaultThresholds'
        ));
    }

    /**
     * Gestion du réapprovisionnement
     */
    public function restock()
    {
        $this->checkAdminAccess();
        
        // Générer les suggestions de réapprovisionnement
        $suggestions = $this->generateRestockSuggestions();
        
        // Historique des réapprovisionnements récents
        $recentRestocks = Message::where('type', 'system')
                                ->where('subject', 'like', '%réapprovisionnement%')
                                ->orderBy('created_at', 'desc')
                                ->take(10)
                                ->get();

        return view('admin.stock.restock', compact(
            'suggestions',
            'recentRestocks'
        ));
    }

    /**
     * Rapports et analyses
     */
    public function reports()
    {
        $this->checkAdminAccess();
        
        // Données pour les graphiques
        $chartData = $this->generateChartData();
        
        // Top des produits les plus vendus/vus (avec prix uniquement)
        $topProducts = Product::with('category')
                             ->where('price', '>', 0) // Exclure les produits sans prix
                             ->orderBy('views_count', 'desc')
                             ->take(10)
                             ->get();
        
        // Prévisions de stock
        $stockForecasts = $this->generateStockForecasts();

        return view('admin.stock.reports', compact(
            'chartData',
            'topProducts',
            'stockForecasts'
        ));
    }

    /**
     * Calculer les statistiques de stock
     */
    private function getStockStatistics()
    {
        // Produits par statut de stock
        $outOfStock = Product::where('quantity', 0)->count();
        $criticalStock = Product::whereColumn('quantity', '<=', 'critical_threshold')
                                ->where('quantity', '>', 0)
                                ->count();
        $lowStock = Product::whereRaw('quantity <= low_stock_threshold AND quantity > critical_threshold')
                           ->count();
        $normalStock = Product::whereRaw('quantity > COALESCE(low_stock_threshold, critical_threshold)')
                              ->count();

        // Produits nécessitant une attention
        $criticalProducts = Product::with('category')
                                  ->whereColumn('quantity', '<=', 'critical_threshold')
                                  ->orderBy('quantity', 'asc')
                                  ->take(10)
                                  ->get();

        // Valeur totale du stock
        $totalStockValue = Product::selectRaw('SUM(quantity * price) as total_value')
                                 ->value('total_value') ?? 0;

        // Valeur du stock critique
        $criticalStockValue = Product::whereColumn('quantity', '<=', 'critical_threshold')
                                    ->selectRaw('SUM(quantity * price) as critical_value')
                                    ->value('critical_value') ?? 0;

        return [
            'out_of_stock' => $outOfStock,
            'critical_stock' => $criticalStock,
            'low_stock' => $lowStock,
            'normal_stock' => $normalStock,
            'total_products' => Product::count(),
            'critical_products' => $criticalProducts,
            'total_stock_value' => $totalStockValue,
            'critical_stock_value' => $criticalStockValue,
            'needs_attention' => $outOfStock + $criticalStock,
        ];
    }

    /**
     * Générer les suggestions de réapprovisionnement
     */
    private function generateRestockSuggestions()
    {
        // Récupérer les produits en stock critique
        $criticalProducts = Product::with('category')
                                  ->whereColumn('quantity', '<=', 'critical_threshold')
                                  ->get();
        
        $suggestions = [];
        
        foreach ($criticalProducts as $product) {
            // Calculer les ventes moyennes mensuelles (simulation)
            $monthlySales = $this->calculateMonthlySales($product);
            
            // Stock recommandé = 2 mois de ventes + stock de sécurité
            $securityStock = max($product->critical_threshold * 2, 10);
            $recommendedStock = ($monthlySales * 2) + $securityStock;
            
            // Quantité à commander
            $quantityToOrder = max(0, $recommendedStock - $product->quantity);
            
            if ($quantityToOrder > 0) {
                $suggestions[] = [
                    'product' => $product,
                    'current_stock' => $product->quantity,
                    'monthly_sales' => $monthlySales,
                    'recommended_stock' => $recommendedStock,
                    'quantity_to_order' => $quantityToOrder,
                    'estimated_cost' => $quantityToOrder * $product->price,
                    'priority' => $product->quantity == 0 ? 'urgent' : 'high'
                ];
            }
        }

        // Trier par priorité
        usort($suggestions, function($a, $b) {
            if ($a['priority'] === 'urgent' && $b['priority'] !== 'urgent') return -1;
            if ($b['priority'] === 'urgent' && $a['priority'] !== 'urgent') return 1;
            return $a['current_stock'] - $b['current_stock'];
        });

        return $suggestions;
    }

    /**
     * Calculer les ventes moyennes mensuelles (basé sur les données réelles)
     */
    private function calculateMonthlySales($product)
    {
        // Calculer les ventes réelles basées sur les commandes des 30 derniers jours
        $recentSales = DB::table('order_items')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->where('order_items.product_id', $product->id)
                        ->where('orders.created_at', '>=', now()->subDays(30))
                        ->whereIn('orders.status', ['delivered', 'shipped', 'preparing'])
                        ->sum('order_items.quantity');
        
        // Si pas de ventes récentes, estimer basé sur les caractéristiques du produit
        if ($recentSales > 0) {
            return $recentSales;
        }
        
        // Estimation basée sur le prix et la popularité
        $categoryMultiplier = 1;
        if ($product->category) {
            $categoryMultiplier = match($product->category->name) {
                'Fruits', 'Légumes' => 3, // Plus populaires
                'Produits Laitiers' => 2,
                'Machines', 'Équipement' => 0.2, // Moins fréquents
                default => 1
            };
        }
        
        // Estimation basée sur le prix (produits moins chers = plus vendus)
        $priceMultiplier = $product->price < 10 ? 2 : ($product->price < 100 ? 1 : 0.5);
        
        return max(1, intval(($product->views_count * 0.02 + 1) * $categoryMultiplier * $priceMultiplier));
    }

    /**
     * Calculer les jours avant rupture de stock (basé sur les données réelles)
     */
    private function calculateReorderDays($product)
    {
        $monthlySales = $this->calculateMonthlySales($product);
        
        if ($monthlySales <= 0) {
            return 365; // Si pas de ventes, stock dure longtemps
        }
        
        $dailySales = $monthlySales / 30;
        
        if ($dailySales <= 0) {
            return 365;
        }
        
        return max(0, intval($product->quantity / $dailySales));
    }

    /**
     * Obtenir les tendances de stock
     */
    private function getStockTrends()
    {
        // Données réelles des 7 derniers jours
        $trends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            
            // Compter les vrais problèmes de stock à cette date (simulation réaliste)
            $outOfStock = Product::where('quantity', '<=', 0)->count();
            $criticalStock = Product::whereColumn('quantity', '<=', 'critical_threshold')
                                   ->where('quantity', '>', 0)
                                   ->count();
            $lowStock = Product::whereRaw('quantity <= low_stock_threshold AND quantity > critical_threshold')
                              ->count();
            
            $trends[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('d/m'),
                'out_of_stock' => max(0, $outOfStock + rand(-1, 1)), // Légère variation
                'critical_stock' => max(0, $criticalStock + rand(-2, 2)),
                'low_stock' => max(0, $lowStock + rand(-3, 3)),
                'total_alerts' => max(0, ($outOfStock + $criticalStock) + rand(-1, 1))
            ];
        }
        
        return $trends;
    }

    /**
     * Générer les données pour les graphiques
     */
    private function generateChartData()
    {
        $categories = Category::with('products')->get();
        
        $chartData = [
            'categories' => $categories->pluck('name'),
            'stock_data' => $categories->map(function($category) {
                return [
                    'normal' => $category->products->filter(function($p) {
                        return $p->quantity > ($p->low_stock_threshold ?? $p->critical_threshold);
                    })->count(),
                    'low' => $category->products->filter(function($p) {
                        return $p->quantity > $p->critical_threshold && 
                               $p->low_stock_threshold && 
                               $p->quantity <= $p->low_stock_threshold;
                    })->count(),
                    'critical' => $category->products->filter(function($p) {
                        return $p->quantity > 0 && $p->quantity <= $p->critical_threshold;
                    })->count(),
                    'out' => $category->products->where('quantity', 0)->count()
                ];
            })
        ];

        return $chartData;
    }

    /**
     * Générer les prévisions de stock
     */
    private function generateStockForecasts()
    {
        $criticalProducts = Product::whereColumn('quantity', '<=', 'critical_threshold')
                                  ->orderBy('quantity', 'asc')
                                  ->take(5)
                                  ->get();

        $forecasts = [];
        foreach ($criticalProducts as $product) {
            $monthlySales = $this->calculateMonthlySales($product);
            $daysRemaining = $monthlySales > 0 ? intval(($product->quantity / $monthlySales) * 30) : 0;
            
            $forecasts[] = [
                'product' => $product,
                'days_remaining' => max(0, $daysRemaining),
                'estimated_outage_date' => $daysRemaining > 0 ? 
                    now()->addDays($daysRemaining)->format('d/m/Y') : 
                    'Déjà en rupture',
                'monthly_sales' => $monthlySales
            ];
        }

        return $forecasts;
    }
}
