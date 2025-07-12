<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
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
}
