<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Liste des catégories",
     *     description="Récupère la liste des catégories. Les administrateurs voient toutes les catégories, les autres utilisateurs ne voient que les catégories actives.",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Numéro de page pour la pagination",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d'éléments par page",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des catégories récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Liste des catégories récupérée avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/PaginatedResponse")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
     * Afficher la liste des catégories
     */
    public function index()
    {
        // Les visiteurs et users peuvent voir les catégories actives
        // Les admins peuvent voir toutes les catégories (y compris supprimées)
        if (Auth::user()?->isAdmin()) {
            $categories = Category::withTrashed()->paginate(15);
        } else {
            $categories = Category::active()->paginate(15);
        }

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Liste des catégories récupérée avec succès'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/categories",
     *     tags={"Admin", "Categories"},
     *     summary="Créer une nouvelle catégorie",
     *     description="Crée une nouvelle catégorie. Accès réservé aux administrateurs.",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Outils de jardinage", description="Nom de la catégorie"),
     *             @OA\Property(property="description", type="string", example="Tous les outils pour le jardinage", description="Description de la catégorie"),
     *             @OA\Property(property="is_active", type="boolean", example=true, description="Statut actif de la catégorie")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Catégorie créée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Catégorie créée avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/Category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès refusé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreurs de validation",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
     * Créer une nouvelle catégorie (Admin seulement)
     */
    public function store(Request $request)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()?->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Seuls les administrateurs peuvent créer des catégories.'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Catégorie créée avec succès'
        ], 201);
    }

    /**
     * Afficher une catégorie spécifique
     */
    public function show($identifier)
    {
        // Recherche par ID ou slug
        if (is_numeric($identifier)) {
            $category = Category::findOrFail($identifier);
        } else {
            $category = Category::where('slug', $identifier)->firstOrFail();
        }

        // Les non-admins ne peuvent voir que les catégories actives
        if (!Auth::user()?->isAdmin() && !$category->is_active) {
            abort(404, 'Catégorie non trouvée');
        }

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Catégorie récupérée avec succès'
        ]);
    }

    /**
     * Mettre à jour une catégorie (Admin seulement)
     */
    public function update(Request $request, Category $category)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()?->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Seuls les administrateurs peuvent modifier des catégories.'
            ], 403);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'description' => 'sometimes|nullable|string|max:1000',
            'is_active' => 'sometimes|boolean'
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'data' => $category->fresh(),
            'message' => 'Catégorie mise à jour avec succès'
        ]);
    }

    /**
     * Supprimer une catégorie (Admin seulement)
     */
    public function destroy(Category $category)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()?->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Seuls les administrateurs peuvent supprimer des catégories.'
            ], 403);
        }

        // Vérifier si la catégorie a des produits
        if ($category->hasProducts()) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer une catégorie qui contient des produits.'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catégorie supprimée avec succès'
        ]);
    }

    /**
     * Restaurer une catégorie supprimée (Admin seulement)
     */
    public function restore($id)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()?->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Seuls les administrateurs peuvent restaurer des catégories.'
            ], 403);
        }

        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Catégorie restaurée avec succès'
        ]);
    }

    /**
     * Activer/Désactiver une catégorie (Admin seulement)
     */
    public function toggleStatus(Category $category)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()?->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Seuls les administrateurs peuvent modifier le statut des catégories.'
            ], 403);
        }

        if ($category->is_active) {
            $category->deactivate();
            $message = 'Catégorie désactivée avec succès';
        } else {
            $category->activate();
            $message = 'Catégorie activée avec succès';
        }

        return response()->json([
            'success' => true,
            'data' => $category->fresh(),
            'message' => $message
        ]);
    }

    /**
     * Récupérer les catégories actives (pour les formulaires)
     */
    public function active()
    {
        $categories = Category::active()->select('id', 'name', 'slug')->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Catégories actives récupérées avec succès'
        ]);
    }

    /**
     * Statistiques des catégories (Admin seulement)
     */
    public function adminStats()
    {
        // Statistiques générales
        $totalCategories = Category::count();
        $activeCategories = Category::where('is_active', true)->count();
        $inactiveCategories = Category::where('is_active', false)->count();
        $deletedCategories = Category::onlyTrashed()->count();

        // Catégories avec le plus de produits
        $categoriesWithProducts = Category::withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('products_count', 'desc')
            ->limit(10)
            ->get();

        // Catégories avec le plus de likes (via leurs produits)
        $categoriesWithLikes = Category::withCount(['products as total_likes' => function ($query) {
                $query->join('product_likes', 'products.id', '=', 'product_likes.product_id')
                      ->where('products.is_active', true);
            }])
            ->orderBy('total_likes', 'desc')
            ->limit(10)
            ->get();

        // Évolution des créations de catégories par mois (6 derniers mois)
        $categoriesPerMonth = Category::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Catégories sans produits
        $emptyCategoriesCount = Category::whereDoesntHave('products')
            ->where('is_active', true)
            ->count();

        // Catégories récemment créées
        $recentCategories = Category::with(['products' => function ($query) {
                $query->where('is_active', true)->limit(3);
            }])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Statistiques de revenus par catégorie (approximatif basé sur les prix des produits)
        $revenueByCategory = Category::withSum(['products as total_value' => function ($query) {
                $query->where('is_active', true)
                      ->selectRaw('SUM(price * quantity)');
            }], 'price')
            ->orderBy('total_value', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Statistiques des catégories récupérées',
            'data' => [
                'overview' => [
                    'total_categories' => $totalCategories,
                    'active_categories' => $activeCategories,
                    'inactive_categories' => $inactiveCategories,
                    'deleted_categories' => $deletedCategories,
                    'empty_categories' => $emptyCategoriesCount
                ],
                'top_categories_by_products' => $categoriesWithProducts,
                'top_categories_by_likes' => $categoriesWithLikes,
                'categories_per_month' => $categoriesPerMonth,
                'recent_categories' => $recentCategories,
                'revenue_by_category' => $revenueByCategory
            ]
        ]);
    }
}
