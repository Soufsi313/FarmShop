<?php

namespace App\Http\Controllers;

use App\Models\RentalCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RentalCategoryController extends Controller
{
    /**
     * Afficher la liste des catégories de location
     */
    public function index()
    {
        // Les visiteurs et users peuvent voir les catégories de location actives
        // Les admins peuvent voir toutes les catégories de location (y compris supprimées)
        if (Auth::user()?->isAdmin()) {
            $rentalCategories = RentalCategory::withTrashed()->paginate(15);
        } else {
            $rentalCategories = RentalCategory::active()->paginate(15);
        }

        return response()->json([
            'success' => true,
            'data' => $rentalCategories,
            'message' => 'Liste des catégories de location récupérée avec succès'
        ]);
    }

    /**
     * Créer une nouvelle catégorie de location (Admin seulement)
     */
    public function store(Request $request)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()?->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Seuls les administrateurs peuvent créer des catégories de location.'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:rental_categories,name',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        $rentalCategory = RentalCategory::create($validated);

        return response()->json([
            'success' => true,
            'data' => $rentalCategory,
            'message' => 'Catégorie de location créée avec succès'
        ], 201);
    }

    /**
     * Afficher une catégorie de location spécifique
     */
    public function show($identifier)
    {
        // Recherche par ID ou slug
        if (is_numeric($identifier)) {
            $rentalCategory = RentalCategory::findOrFail($identifier);
        } else {
            $rentalCategory = RentalCategory::where('slug', $identifier)->firstOrFail();
        }

        // Les non-admins ne peuvent voir que les catégories de location actives
        if (!Auth::user()?->isAdmin() && !$rentalCategory->is_active) {
            abort(404, 'Catégorie de location non trouvée');
        }

        return response()->json([
            'success' => true,
            'data' => $rentalCategory,
            'message' => 'Catégorie de location récupérée avec succès'
        ]);
    }

    /**
     * Mettre à jour une catégorie de location (Admin seulement)
     */
    public function update(Request $request, RentalCategory $rentalCategory)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()?->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Seuls les administrateurs peuvent modifier des catégories de location.'
            ], 403);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('rental_categories')->ignore($rentalCategory->id)],
            'description' => 'sometimes|nullable|string|max:1000',
            'is_active' => 'sometimes|boolean'
        ]);

        $rentalCategory->update($validated);

        return response()->json([
            'success' => true,
            'data' => $rentalCategory->fresh(),
            'message' => 'Catégorie de location mise à jour avec succès'
        ]);
    }

    /**
     * Supprimer une catégorie de location (Admin seulement)
     */
    public function destroy(RentalCategory $rentalCategory)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()?->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Seuls les administrateurs peuvent supprimer des catégories de location.'
            ], 403);
        }

        // Vérifier si la catégorie de location a des produits
        if ($rentalCategory->hasRentalProducts()) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer une catégorie de location qui contient des produits.'
            ], 422);
        }

        $rentalCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catégorie de location supprimée avec succès'
        ]);
    }

    /**
     * Restaurer une catégorie de location supprimée (Admin seulement)
     */
    public function restore($id)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()?->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Seuls les administrateurs peuvent restaurer des catégories de location.'
            ], 403);
        }

        $rentalCategory = RentalCategory::withTrashed()->findOrFail($id);
        $rentalCategory->restore();

        return response()->json([
            'success' => true,
            'data' => $rentalCategory,
            'message' => 'Catégorie de location restaurée avec succès'
        ]);
    }

    /**
     * Activer/Désactiver une catégorie de location (Admin seulement)
     */
    public function toggleStatus(RentalCategory $rentalCategory)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()?->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Seuls les administrateurs peuvent modifier le statut des catégories de location.'
            ], 403);
        }

        if ($rentalCategory->is_active) {
            $rentalCategory->deactivate();
            $message = 'Catégorie de location désactivée avec succès';
        } else {
            $rentalCategory->activate();
            $message = 'Catégorie de location activée avec succès';
        }

        return response()->json([
            'success' => true,
            'data' => $rentalCategory->fresh(),
            'message' => $message
        ]);
    }

    /**
     * Récupérer les catégories de location actives (pour les formulaires)
     */
    public function active()
    {
        $rentalCategories = RentalCategory::active()->select('id', 'name', 'slug')->get();

        return response()->json([
            'success' => true,
            'data' => $rentalCategories,
            'message' => 'Catégories de location actives récupérées avec succès'
        ]);
    }

    /**
     * Statistiques des catégories de location (Admin seulement)
     */
    public function adminStats()
    {
        // Statistiques générales
        $totalRentalCategories = RentalCategory::count();
        $activeRentalCategories = RentalCategory::where('is_active', true)->count();
        $inactiveRentalCategories = RentalCategory::where('is_active', false)->count();
        $deletedRentalCategories = RentalCategory::onlyTrashed()->count();

        // Catégories avec le plus de produits de location
        $categoriesWithRentalProducts = RentalCategory::withCount(['products' => function ($query) {
                $query->where('is_active', true)
                      ->whereIn('type', ['rental', 'both']);
            }])
            ->orderBy('products_count', 'desc')
            ->limit(10)
            ->get();

        // Catégories avec le plus de likes (via leurs produits de location)
        $categoriesWithLikes = RentalCategory::withCount(['products as total_likes' => function ($query) {
                $query->join('product_likes', 'products.id', '=', 'product_likes.product_id')
                      ->where('products.is_active', true)
                      ->whereIn('products.type', ['rental', 'both']);
            }])
            ->orderBy('total_likes', 'desc')
            ->limit(10)
            ->get();

        // Évolution des créations de catégories par mois (6 derniers mois)
        $categoriesPerMonth = RentalCategory::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Catégories sans produits de location
        $emptyRentalCategoriesCount = RentalCategory::whereDoesntHave('products', function ($query) {
                $query->whereIn('type', ['rental', 'both']);
            })
            ->where('is_active', true)
            ->count();

        // Catégories récemment créées
        $recentRentalCategories = RentalCategory::with(['products' => function ($query) {
                $query->where('is_active', true)
                      ->whereIn('type', ['rental', 'both'])
                      ->limit(3);
            }])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Statistiques de revenus par catégorie de location (basé sur les prix de location)
        $revenueByRentalCategory = RentalCategory::withSum(['products as total_rental_value' => function ($query) {
                $query->where('is_active', true)
                      ->whereIn('type', ['rental', 'both'])
                      ->whereNotNull('rental_price_per_day')
                      ->selectRaw('SUM(rental_price_per_day * quantity * 30)'); // Estimation sur 30 jours
            }], 'rental_price_per_day')
            ->orderBy('total_rental_value', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Statistiques des catégories de location récupérées',
            'data' => [
                'overview' => [
                    'total_rental_categories' => $totalRentalCategories,
                    'active_rental_categories' => $activeRentalCategories,
                    'inactive_rental_categories' => $inactiveRentalCategories,
                    'deleted_rental_categories' => $deletedRentalCategories,
                    'empty_rental_categories' => $emptyRentalCategoriesCount
                ],
                'top_categories_by_rental_products' => $categoriesWithRentalProducts,
                'top_categories_by_likes' => $categoriesWithLikes,
                'categories_per_month' => $categoriesPerMonth,
                'recent_rental_categories' => $recentRentalCategories,
                'revenue_by_rental_category' => $revenueByRentalCategory
            ]
        ]);
    }
}
