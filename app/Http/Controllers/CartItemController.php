<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartItemController extends Controller
{
    /**
     * Afficher tous les éléments du panier de l'utilisateur connecté
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $cart = $user->cart;
        
        if (!$cart) {
            return response()->json([
                'success' => true,
                'message' => 'Panier vide',
                'data' => []
            ]);
        }

        $items = $cart->items()->with(['product.category', 'product.rentalCategory'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $items->map(function ($item) {
                return $item->toDisplayArray();
            })
        ]);
    }

    /**
     * Afficher un élément spécifique du panier
     */
    public function show(CartItem $cartItem): JsonResponse
    {
        $user = Auth::user();
        
        // Vérifier que l'élément appartient au panier de l'utilisateur
        if ($cartItem->cart->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Élément non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $cartItem->toDisplayArray()
        ]);
    }

    /**
     * Mettre à jour la quantité d'un élément du panier
     */
    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        $user = Auth::user();
        
        // Vérifier que l'élément appartient au panier de l'utilisateur
        if ($cartItem->cart->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Élément non trouvé'
            ], 404);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $quantity = $request->input('quantity');
        
        try {
            DB::beginTransaction();

            // Vérifier la disponibilité
            $product = $cartItem->product;
            if ($quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuffisant. Stock disponible: {$product->stock}"
                ], 400);
            }

            // Mettre à jour la quantité
            $cartItem->updateQuantity($quantity);
            
            // Recalculer le total du panier
            $cartItem->cart->recalculateTotal();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Quantité mise à jour avec succès',
                'data' => [
                    'cart_item' => $cartItem->fresh()->toDisplayArray(),
                    'cart_total' => $cartItem->cart->fresh()->total_amount,
                    'cart_total_tva' => $cartItem->cart->fresh()->total_tva
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un élément du panier
     */
    public function destroy(CartItem $cartItem): JsonResponse
    {
        $user = Auth::user();
        
        // Vérifier que l'élément appartient au panier de l'utilisateur
        if ($cartItem->cart->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Élément non trouvé'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $cart = $cartItem->cart;
            $productName = $cartItem->product->name;
            
            $cartItem->delete();
            
            // Recalculer le total du panier
            $cart->recalculateTotal();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Produit '{$productName}' supprimé du panier",
                'data' => [
                    'cart_total' => $cart->fresh()->total_amount,
                    'cart_total_tva' => $cart->fresh()->total_tva,
                    'items_count' => $cart->items()->count()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vérifier la disponibilité d'un élément du panier
     */
    public function checkAvailability(CartItem $cartItem): JsonResponse
    {
        $user = Auth::user();
        
        // Vérifier que l'élément appartient au panier de l'utilisateur
        if ($cartItem->cart->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Élément non trouvé'
            ], 404);
        }

        $availability = $cartItem->getAvailabilityInfo();
        
        return response()->json([
            'success' => true,
            'data' => $availability
        ]);
    }

    /**
     * Dupliquer un élément du panier (augmenter sa quantité de 1)
     */
    public function duplicate(CartItem $cartItem): JsonResponse
    {
        $user = Auth::user();
        
        // Vérifier que l'élément appartient au panier de l'utilisateur
        if ($cartItem->cart->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Élément non trouvé'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $newQuantity = $cartItem->quantity + 1;
            
            // Vérifier la disponibilité
            if ($newQuantity > $cartItem->product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuffisant. Stock disponible: {$cartItem->product->stock}"
                ], 400);
            }

            $cartItem->updateQuantity($newQuantity);
            $cartItem->cart->recalculateTotal();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Quantité augmentée avec succès',
                'data' => [
                    'cart_item' => $cartItem->fresh()->toDisplayArray(),
                    'cart_total' => $cartItem->cart->fresh()->total_amount,
                    'cart_total_tva' => $cartItem->cart->fresh()->total_tva
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la duplication: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour les détails d'un élément (notes, options spéciales)
     */
    public function updateDetails(Request $request, CartItem $cartItem): JsonResponse
    {
        $user = Auth::user();
        
        // Vérifier que l'élément appartient au panier de l'utilisateur
        if ($cartItem->cart->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Élément non trouvé'
            ], 404);
        }

        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $cartItem->update([
                'notes' => $request->input('notes')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Détails mis à jour avec succès',
                'data' => $cartItem->fresh()->toDisplayArray()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * [ADMIN] Voir tous les éléments de tous les paniers
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $query = CartItem::with(['cart.user', 'product.category', 'product.rentalCategory']);

        // Filtres optionnels
        if ($request->has('user_id')) {
            $query->whereHas('cart', function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $items->map(function ($item) {
                $data = $item->toDisplayArray();
                $data['user'] = [
                    'id' => $item->cart->user->id,
                    'name' => $item->cart->user->name,
                    'email' => $item->cart->user->email
                ];
                return $data;
            }),
            'pagination' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total()
            ]
        ]);
    }

    /**
     * [ADMIN] Statistiques des éléments de panier
     */
    public function adminStats(): JsonResponse
    {
        $stats = [
            'total_items' => CartItem::count(),
            'total_quantity' => CartItem::sum('quantity'),
            'total_value' => CartItem::sum('total_amount'),
            'average_quantity_per_item' => CartItem::avg('quantity'),
            'most_added_products' => CartItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->with('product:id,name')
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->limit(10)
                ->get(),
            'items_by_month' => CartItem::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(quantity) as total_quantity')
            )
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->limit(12)
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
