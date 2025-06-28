<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    /**
     * Constructor - require authentication for all methods.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user's wishlist.
     */
    public function index(Request $request)
    {
        $query = Wishlist::where('user_id', Auth::id())
            ->with(['product' => function ($q) {
                $q->select('id', 'name', 'slug', 'price', 'main_image', 'is_active', 'quantity')
                  ->where('is_active', true);
            }])
            ->latest();

        // Filter by search term
        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->expectsJson()) {
            $wishlists = $query->paginate(20);
            return response()->json([
                'success' => true,
                'data' => $wishlists,
                'message' => 'Wishlist récupérée avec succès'
            ]);
        }

        $wishlists = $query->paginate(20);
        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Add a product to the wishlist.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $productId = $request->product_id;
        $userId = Auth::id();

        // Check if product is already in wishlist
        if (Wishlist::isInWishlist($userId, $productId)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce produit est déjà dans votre wishlist'
                ], 409);
            }

            return back()->with('error', 'Ce produit est déjà dans votre wishlist');
        }

        // Check if product is active
        $product = Product::where('id', $productId)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produit non disponible'
                ], 404);
            }

            return back()->with('error', 'Produit non disponible');
        }

        try {
            $wishlist = Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $wishlist->load('product'),
                    'message' => 'Produit ajouté à votre wishlist'
                ], 201);
            }

            return back()->with('success', 'Produit ajouté à votre wishlist');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'ajout à la wishlist'
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'ajout à la wishlist');
        }
    }

    /**
     * Remove a product from the wishlist.
     */
    public function destroy(Request $request, $productId)
    {
        $userId = Auth::id();

        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if (!$wishlist) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produit non trouvé dans votre wishlist'
                ], 404);
            }

            return back()->with('error', 'Produit non trouvé dans votre wishlist');
        }

        try {
            $wishlist->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produit retiré de votre wishlist'
                ]);
            }

            return back()->with('success', 'Produit retiré de votre wishlist');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression'
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suppression');
        }
    }

    /**
     * Clear the entire wishlist for the authenticated user.
     */
    public function clear(Request $request)
    {
        $userId = Auth::id();

        try {
            $deletedCount = Wishlist::where('user_id', $userId)->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $deletedCount . ' produit(s) retiré(s) de votre wishlist'
                ]);
            }

            return redirect()->route('wishlist.index')
                ->with('success', $deletedCount . ' produit(s) retiré(s) de votre wishlist');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de la wishlist'
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suppression de la wishlist');
        }
    }

    /**
     * Get the count of items in the user's wishlist.
     */
    public function getCount(Request $request)
    {
        $count = Wishlist::where('user_id', Auth::id())->count();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => ['count' => $count],
                'message' => 'Nombre d\'articles dans la wishlist'
            ]);
        }

        return response()->json(['count' => $count]);
    }

    /**
     * Toggle a product in the wishlist (add if not present, remove if present).
     */
    public function toggle(Request $request, $productId)
    {
        $request->validate([
            'product_id' => 'sometimes|integer|exists:products,id'
        ]);

        // Use productId from route parameter or request
        $productId = $productId ?? $request->product_id;
        $userId = Auth::id();

        // Check if product exists and is active
        $product = Product::where('id', $productId)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produit non disponible'
                ], 404);
            }

            return back()->with('error', 'Produit non disponible');
        }

        try {
            $wishlist = Wishlist::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($wishlist) {
                // Remove from wishlist
                $wishlist->delete();
                $action = 'removed';
                $message = 'Produit retiré de votre wishlist';
            } else {
                // Add to wishlist
                Wishlist::create([
                    'user_id' => $userId,
                    'product_id' => $productId
                ]);
                $action = 'added';
                $message = 'Produit ajouté à votre wishlist';
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'action' => $action,
                        'in_wishlist' => $action === 'added',
                        'count' => Wishlist::where('user_id', $userId)->count()
                    ],
                    'message' => $message
                ]);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la modification de la wishlist'
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la modification de la wishlist');
        }
    }

    /**
     * Check if a product is in the user's wishlist.
     */
    public function check(Request $request, $productId)
    {
        $inWishlist = Wishlist::isInWishlist(Auth::id(), $productId);

        return response()->json([
            'success' => true,
            'data' => ['in_wishlist' => $inWishlist],
            'message' => 'Statut de la wishlist vérifié'
        ]);
    }

    /**
     * Move all wishlist items to cart.
     */
    public function moveToCart(Request $request)
    {
        $userId = Auth::id();
        $wishlistItems = Wishlist::where('user_id', $userId)
            ->with('product')
            ->get();

        if ($wishlistItems->isEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Votre wishlist est vide'
                ], 400);
            }

            return back()->with('error', 'Votre wishlist est vide');
        }

        try {
            DB::beginTransaction();

            $addedCount = 0;
            $skippedCount = 0;

            foreach ($wishlistItems as $wishlistItem) {
                $product = $wishlistItem->product;

                // Check if product is still available and in stock
                if ($product && $product->is_active && $product->quantity > 0) {
                    // Check if already in cart
                    $existingCart = \App\Models\Cart::where('user_id', $userId)
                        ->where('product_id', $product->id)
                        ->first();

                    if ($existingCart) {
                        // Update quantity
                        $existingCart->increment('quantity');
                    } else {
                        // Add to cart
                        \App\Models\Cart::create([
                            'user_id' => $userId,
                            'product_id' => $product->id,
                            'quantity' => 1,
                            'price' => $product->price
                        ]);
                    }

                    $addedCount++;
                } else {
                    $skippedCount++;
                }

                // Remove from wishlist
                $wishlistItem->delete();
            }

            DB::commit();

            $message = $addedCount . ' produit(s) ajouté(s) au panier';
            if ($skippedCount > 0) {
                $message .= ', ' . $skippedCount . ' produit(s) non disponible(s) ignoré(s)';
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'added_count' => $addedCount,
                        'skipped_count' => $skippedCount
                    ],
                    'message' => $message
                ]);
            }

            return redirect()->route('cart.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du transfert vers le panier'
                ], 500);
            }

            return back()->with('error', 'Erreur lors du transfert vers le panier');
        }
    }
}
