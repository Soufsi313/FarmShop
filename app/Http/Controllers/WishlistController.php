<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Afficher la wishlist de l'utilisateur connecté
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $wishlist = $user->wishlists()
            ->with(['product' => function ($query) {
                $query->with('category')
                      ->where('is_active', true);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        // Filtrer les produits qui ne sont plus actifs
        $wishlist->getCollection()->transform(function ($wishlistItem) {
            if (!$wishlistItem->product || !$wishlistItem->product->is_active) {
                return null;
            }
            return $wishlistItem;
        })->filter();

        return response()->json([
            'success' => true,
            'message' => 'Wishlist récupérée avec succès',
            'data' => $wishlist,
            'total_items' => $user->wishlists()->count()
        ]);
    }

    /**
     * Ajouter un produit à la wishlist
     */
    public function store(Product $product): JsonResponse
    {
        $user = Auth::user();

        // Vérifier que le produit est actif
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est plus disponible'
            ], 400);
        }

        // Vérifier si le produit est déjà dans la wishlist
        $existingWishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingWishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit est déjà dans votre wishlist'
            ], 409);
        }

        // Ajouter le produit à la wishlist
        $wishlistItem = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        $wishlistItem->load(['product.category']);

        return response()->json([
            'success' => true,
            'message' => 'Produit ajouté à la wishlist avec succès',
            'data' => $wishlistItem,
            'total_items' => $user->wishlists()->count()
        ], 201);
    }

    /**
     * Retirer un produit de la wishlist
     */
    public function destroy(Product $product): JsonResponse
    {
        $user = Auth::user();

        // Chercher l'élément dans la wishlist
        $wishlistItem = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$wishlistItem) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est pas dans votre wishlist'
            ], 404);
        }

        // Supprimer l'élément de la wishlist
        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produit retiré de la wishlist avec succès',
            'total_items' => $user->wishlists()->count()
        ]);
    }

    /**
     * Toggle - Ajouter ou retirer un produit de la wishlist
     */
    public function toggle(Product $product): JsonResponse
    {
        $user = Auth::user();

        // Vérifier que le produit est actif
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est plus disponible'
            ], 400);
        }

        // Chercher l'élément dans la wishlist
        $existingWishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingWishlist) {
            // Retirer de la wishlist
            $existingWishlist->delete();
            $message = 'Produit retiré de la wishlist';
            $inWishlist = false;
            $wishlistItem = null;
        } else {
            // Ajouter à la wishlist
            $wishlistItem = Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id
            ]);
            $wishlistItem->load(['product.category']);
            $message = 'Produit ajouté à la wishlist';
            $inWishlist = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'in_wishlist' => $inWishlist,
                'wishlist_item' => $wishlistItem,
                'total_items' => $user->wishlists()->count()
            ]
        ]);
    }

    /**
     * Vider complètement la wishlist
     */
    public function clear(): JsonResponse
    {
        $user = Auth::user();
        
        $deletedCount = $user->wishlists()->delete();

        return response()->json([
            'success' => true,
            'message' => "Wishlist vidée avec succès ({$deletedCount} produits supprimés)",
            'deleted_count' => $deletedCount,
            'total_items' => 0
        ]);
    }

    /**
     * Obtenir le nombre d'éléments dans la wishlist
     */
    public function count(): JsonResponse
    {
        $user = Auth::user();
        $count = $user->wishlists()->count();

        return response()->json([
            'success' => true,
            'message' => 'Nombre d\'éléments dans la wishlist',
            'data' => [
                'count' => $count
            ]
        ]);
    }

    /**
     * Vérifier si un produit spécifique est dans la wishlist
     */
    public function check(Product $product): JsonResponse
    {
        $user = Auth::user();
        
        $inWishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        return response()->json([
            'success' => true,
            'message' => 'Statut du produit dans la wishlist',
            'data' => [
                'in_wishlist' => $inWishlist,
                'product_id' => $product->id
            ]
        ]);
    }
}
