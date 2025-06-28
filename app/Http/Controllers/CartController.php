<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Seuls les utilisateurs connectés peuvent accéder au panier
    }

    /**
     * Afficher le contenu du panier de l'utilisateur connecté.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $cartItems = Cart::forUser($user->id)
            ->with(['product.category', 'product.images'])
            ->get();

        // Valider le panier (vérifier stock et produits actifs)
        $cartValidation = Cart::validateCart($user->id);
        
        // Calculer les totaux
        $subtotal = $cartItems->sum('total_price');
        $itemCount = $cartItems->sum('quantity');
        
        // Informations pour la commande
        $cartSummary = [
            'items' => $cartItems,
            'item_count' => $itemCount,
            'subtotal' => $subtotal,
            'tax_rate' => 0.20, // 20% TVA (configurable)
            'tax_amount' => $subtotal * 0.20,
            'total' => $subtotal * 1.20,
            'validation_issues' => $cartValidation,
            'has_issues' => !empty($cartValidation),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'cart' => $cartSummary
            ]);
        }

        return view('cart.index', compact('cartSummary'));
    }

    /**
     * Ajouter un produit au panier.
     */
    public function store(AddToCartRequest $request)
    {
        $user = auth()->user();
        
        try {
            DB::beginTransaction();
            
            $cartItem = Cart::addToCart(
                $user->id, 
                $request->product_id, 
                $request->quantity
            );

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produit ajouté au panier avec succès.',
                    'cart_item' => $cartItem->load('product'),
                    'cart_count' => Cart::getCartItemCount($user->id),
                    'cart_total' => Cart::getCartTotal($user->id)
                ]);
            }

            return redirect()->route('cart.index')
                ->with('success', 'Produit ajouté au panier avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }

            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Mettre à jour la quantité d'un élément du panier.
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        // Vérifier que l'élément appartient à l'utilisateur connecté
        if ($cart->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé à cet élément du panier.');
        }

        try {
            $cart->updateQuantity($request->quantity);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Quantité mise à jour avec succès.',
                    'cart_item' => $cart->fresh()->load('product'),
                    'cart_count' => Cart::getCartItemCount(auth()->id()),
                    'cart_total' => Cart::getCartTotal(auth()->id())
                ]);
            }

            return redirect()->route('cart.index')
                ->with('success', 'Quantité mise à jour avec succès.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }

            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Supprimer un élément du panier.
     */
    public function destroy(Request $request, Cart $cart)
    {
        // Vérifier que l'élément appartient à l'utilisateur connecté
        if ($cart->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé à cet élément du panier.');
        }

        $productName = $cart->product->name;
        $cart->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Produit '{$productName}' retiré du panier.",
                'cart_count' => Cart::getCartItemCount(auth()->id()),
                'cart_total' => Cart::getCartTotal(auth()->id())
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', "Produit '{$productName}' retiré du panier.");
    }

    /**
     * Vider complètement le panier.
     */
    public function clear(Request $request)
    {
        $user = auth()->user();
        $itemCount = Cart::getCartItemCount($user->id);
        
        Cart::clearCart($user->id);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Panier vidé ({$itemCount} articles supprimés).",
                'cart_count' => 0,
                'cart_total' => 0
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', "Panier vidé ({$itemCount} articles supprimés).");
    }

    /**
     * Obtenir le nombre d'articles dans le panier (pour l'affichage dans le header).
     */
    public function getCartCount()
    {
        $count = Cart::getCartItemCount(auth()->id());
        
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Obtenir le total du panier.
     */
    public function getCartTotal()
    {
        $total = Cart::getCartTotal(auth()->id());
        
        return response()->json([
            'success' => true,
            'total' => $total
        ]);
    }

    /**
     * Valider le panier avant commande.
     */
    public function validate(Request $request)
    {
        $user = auth()->user();
        $issues = Cart::validateCart($user->id);
        
        if (!empty($issues)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Des problèmes ont été détectés dans votre panier.',
                    'issues' => $issues
                ], 400);
            }

            return redirect()->route('cart.index')
                ->with('error', 'Des problèmes ont été détectés dans votre panier.')
                ->with('issues', $issues);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Panier valide, prêt pour la commande.'
            ]);
        }

        return redirect()->route('checkout.index'); // Redirection vers la page de commande
    }

    /**
     * Ajouter rapidement un produit au panier (AJAX).
     */
    public function quickAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides.',
                'errors' => $validator->errors()
            ], 400);
        }

        $user = auth()->user();
        $quantity = $request->quantity ?? 1;

        try {
            $cartItem = Cart::addToCart($user->id, $request->product_id, $quantity);

            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier.',
                'cart_count' => Cart::getCartItemCount($user->id),
                'cart_total' => Cart::getCartTotal($user->id)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Synchroniser le panier (nettoyer les produits inactifs ou en rupture).
     */
    public function sync(Request $request)
    {
        $user = auth()->user();
        $removedItems = [];

        // Supprimer les produits inactifs
        $inactiveItems = Cart::forUser($user->id)
            ->whereHas('product', function($q) {
                $q->where('is_active', false);
            })
            ->with('product')
            ->get();

        foreach ($inactiveItems as $item) {
            $removedItems[] = "{$item->product->name} (produit désactivé)";
            $item->delete();
        }

        // Ajuster les quantités si stock insuffisant
        $cartItems = Cart::forUser($user->id)->with('product')->get();
        $adjustedItems = [];

        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->quantity) {
                $oldQuantity = $item->quantity;
                if ($item->product->quantity > 0) {
                    $item->updateQuantity($item->product->quantity);
                    $adjustedItems[] = "{$item->product->name} (quantité réduite de {$oldQuantity} à {$item->product->quantity})";
                } else {
                    $removedItems[] = "{$item->product->name} (rupture de stock)";
                    $item->delete();
                }
            }
        }

        $message = "Panier synchronisé.";
        if (!empty($removedItems)) {
            $message .= " Articles supprimés: " . implode(', ', $removedItems) . ".";
        }
        if (!empty($adjustedItems)) {
            $message .= " Quantités ajustées: " . implode(', ', $adjustedItems) . ".";
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'removed_items' => $removedItems,
                'adjusted_items' => $adjustedItems,
                'cart_count' => Cart::getCartItemCount($user->id),
                'cart_total' => Cart::getCartTotal($user->id)
            ]);
        }

        return redirect()->route('cart.index')->with('info', $message);
    }
}
