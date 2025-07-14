<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Afficher la page publique des produits
     */
    public function index(Request $request): View
    {
        $query = Product::with(['category'])
            ->where('is_active', true)
            ->whereIn('type', ['sale', 'both']); // Exclure les produits "rental" uniquement

        // Filtrage par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('short_description', 'like', '%' . $search . '%');
            });
        }

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtrage par prix
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Tri
        $sortBy = $request->get('sort', 'created_at');

        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->appends($request->all());
        
        // Récupérer les catégories pour les filtres
        $categories = Category::orderBy('name')->get();

        // Récupérer les prix min et max pour les filtres
        $priceRange = Product::where('is_active', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        return view('web.products.index', compact('products', 'categories', 'priceRange'));
    }

    /**
     * Afficher le détail d'un produit
     */
    public function show(Product $product): View
    {
        // Vérifier que le produit est actif
        if (!$product->is_active) {
            abort(404);
        }

        // Charger les relations nécessaires
        $product->load(['category']);

        // Produits similaires (même catégorie, excluant le produit actuel)
        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        // Vérifier si l'utilisateur a liké le produit (si connecté)
        $isLiked = false;
        $isInWishlist = false;
        
        if (auth()->check()) {
            $isLiked = $product->likes()->where('user_id', auth()->id())->exists();
            $isInWishlist = $product->wishlists()->where('user_id', auth()->id())->exists();
        }

        return view('web.products.show', compact('product', 'similarProducts', 'isLiked', 'isInWishlist'));
    }

    /**
     * Achat direct (ajout au panier + redirection)
     */
    public function buyNow(Request $request, Product $product)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour acheter');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->quantity
        ]);

        $user = auth()->user();
        $quantity = $request->quantity ?? 1;

        // Vérifier la disponibilité du stock
        if ($product->quantity < $quantity) {
            return back()->with('error', 'Stock insuffisant');
        }

        // Ajouter au panier
        $cartItem = $user->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $product->quantity) {
                return back()->with('error', 'Quantité totale demandée supérieure au stock disponible');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $user->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }

        // Rediriger vers le panier
        return redirect()->route('cart.index')->with('success', 'Produit ajouté au panier');
    }
}
