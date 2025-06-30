<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
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
        // Le middleware auth est appliqué au niveau des routes
    }

    /**
     * Afficher le contenu du panier de l'utilisateur connecté.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Récupérer ou créer le panier actif de l'utilisateur
        $cart = Cart::getActiveCartForUser($user->id);
        
        // Récupérer les articles du panier
        $cartItems = $cart->items()
            ->with(['product.category', 'product.images'])
            ->get();

        // Valider le panier (vérifier stock et produits actifs)
        $cartValidation = $cart->validateItems();

        // Calculer les totaux avec TVA dynamique
        $subtotal = $cartItems->sum('total_price');
        $itemCount = $cartItems->sum('quantity');
        
        // Calculer la TVA par catégorie
        $taxBreakdown = [];
        $totalTaxAmount = 0;
        $subtotalHT = 0;
        
        foreach ($cartItems as $item) {
            $category = $item->product->category;
            $taxRate = $category->getTaxRate();
            
            // Si le prix stocké est TTC (cas habituel), calculer le HT et la TVA
            $priceTTC = $item->total_price;
            $priceHT = $priceTTC / (1 + $taxRate);
            $itemTaxAmount = $priceTTC - $priceHT;
            
            $subtotalHT += $priceHT;
            $totalTaxAmount += $itemTaxAmount;
            
            // Grouper par taux de TVA pour l'affichage
            $taxRatePercent = round($taxRate * 100);
            if (!isset($taxBreakdown[$taxRatePercent])) {            $taxBreakdown[$taxRatePercent] = [
                'rate' => $taxRate,
                'rate_percent' => $taxRatePercent,
                'subtotal_ht' => 0,
                'subtotal_ttc' => 0,
                'tax_amount' => 0,
                'categories' => []
            ];
        }
        
        $taxBreakdown[$taxRatePercent]['subtotal_ht'] += $priceHT;
        $taxBreakdown[$taxRatePercent]['subtotal_ttc'] += $priceTTC;
        $taxBreakdown[$taxRatePercent]['tax_amount'] += $itemTaxAmount;
            
            if (!in_array($category->name, $taxBreakdown[$taxRatePercent]['categories'])) {
                $taxBreakdown[$taxRatePercent]['categories'][] = $category->name;
            }
        }
        
        // Informations pour la commande
        $cartSummary = [
            'items' => $cartItems,
            'item_count' => $itemCount,
            'subtotal' => $subtotal, // TTC pour compatibilité
            'subtotal_ht' => $subtotalHT,
            'subtotal_ttc' => $subtotal,
            'tax_breakdown' => $taxBreakdown,
            'total_tax_amount' => $totalTaxAmount,
            'total' => $subtotal, // Le total est déjà TTC
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
        
        // Log pour diagnostiquer
        \Log::info('CartController::store - Début', [
            'user_id' => $user ? $user->id : null,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'expects_json' => $request->expectsJson(),
            'headers' => $request->headers->all()
        ]);
        
        try {
            DB::beginTransaction();
            
            // Récupérer ou créer le panier actif
            $cart = Cart::getActiveCartForUser($user->id);
            
            // Ajouter l'article au panier
            $cartItem = $cart->addItem($request->product_id, $request->quantity);

            DB::commit();

            $response = [
                'success' => true,
                'message' => 'Produit ajouté au panier avec succès.',
                'cart_item' => $cartItem->load('product'),
                'cart_count' => $cart->getTotalItems(),
                'cart_total' => $cart->getTotalPrice()
            ];

            \Log::info('CartController::store - Succès', $response);

            // Toujours retourner du JSON pour les tests
            return response()->json($response);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('CartController::store - Erreur', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Toujours retourner du JSON pour les tests
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Mettre à jour la quantité d'un élément du panier.
     */
    public function update(UpdateCartRequest $request, CartItem $cartItem)
    {
        // Log pour diagnostiquer
        \Log::info('CartController::update - Début', [
            'cart_item_id' => $cartItem->id,
            'user_id' => auth()->id(),
            'cart_item_user_id' => $cartItem->user_id,
            'quantity' => $request->quantity,
            'expects_json' => $request->expectsJson()
        ]);

        // Vérifier que l'élément appartient à l'utilisateur connecté
        if ($cartItem->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé à cet élément du panier.');
        }

        try {
            $cartItem->updateQuantity($request->quantity);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Quantité mise à jour avec succès.',
                    'cart_item' => $cartItem->fresh()->load('product'),
                    'cart_count' => $cartItem->cart->getTotalItems(),
                    'cart_total' => $cartItem->cart->getTotalPrice()
                ]);
            }

            return redirect()->route('cart.index')
                ->with('success', 'Quantité mise à jour avec succès.');

        } catch (\Exception $e) {
            \Log::error('CartController::update - Erreur', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
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
    public function destroy(Request $request, CartItem $cartItem)
    {
        // Log pour diagnostiquer
        \Log::info('CartController::destroy - Début', [
            'cart_item_id' => $cartItem->id,
            'user_id' => auth()->id(),
            'cart_item_user_id' => $cartItem->user_id,
            'expects_json' => $request->expectsJson(),
            'content_type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept'),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        // Vérifier que l'élément appartient à l'utilisateur connecté
        if ($cartItem->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé à cet élément du panier.');
        }

        $productName = $cartItem->product->name;
        $cart = $cartItem->cart;
        $cartItem->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Produit '{$productName}' retiré du panier.",
                'cart_count' => $cart->getTotalItems(),
                'cart_total' => $cart->getTotalPrice()
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
        // Log pour diagnostiquer
        \Log::info('CartController::clear - Début', [
            'user_id' => auth()->id(),
            'expects_json' => $request->expectsJson(),
            'content_type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept'),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        $user = auth()->user();
        $cart = Cart::getActiveCartForUser($user->id);
        $itemCount = $cart->getTotalItems();
        
        $cart->clear();

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
        $cart = Cart::getActiveCartForUser(auth()->id());
        $count = $cart->getTotalItems();
        
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
        $cart = Cart::getActiveCartForUser(auth()->id());
        $total = $cart->getTotalPrice();
        
        return response()->json([
            'success' => true,
            'total' => $total
        ]);
    }

    /**
     * Valider le panier avant commande.
     */
    public function validateCart(Request $request)
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

    /**
     * Méthode de debug pour les routes du panier
     */
    public function debug(Request $request)
    {
        $user = auth()->user();
        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
        
        return response()->json([
            'success' => true,
            'debug' => 'Route cart fonctionnelle',
            'user_id' => $user->id,
            'cart_items_count' => $cartItems->count(),
            'cart_items' => $cartItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->product->name ?? 'Produit introuvable',
                    'quantity' => $item->quantity,
                    'user_id' => $item->user_id
                ];
            })
        ]);
    }
}
