<?php

namespace App\Http\Controllers;

use App\Models\CartLocation;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Rules\RentalDateValidation;

class CartLocationController extends Controller
{
    /**
     * Afficher le panier de location de l'utilisateur connecté
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentification requise',
                'data' => null
            ], 401);
        }
        
        $cartLocation = $user->getOrCreateActiveCartLocation();
        
        $cartLocation->load(['items.product.category', 'items.product.rentalCategory']);
        
        return response()->json([
            'success' => true,
            'data' => [
                'cart' => $cartLocation,
                'summary' => $cartLocation->getSummary()
            ]
        ]);
    }

    /**
     * Ajouter un produit au panier de location
     */
    public function addProduct(Request $request, $productSlug): JsonResponse
    {
        // Debug: log du paramètre reçu
        \Log::info('Paramètre productSlug reçu', [
            'productSlug' => $productSlug,
            'request_url' => $request->fullUrl(),
            'user_id' => Auth::id()
        ]);
        
        // Debug: récupérer le produit par slug
        $product = Product::where('slug', $productSlug)->first();
        
        if (!$product) {
            \Log::error('Produit non trouvé pour ajout panier location', [
                'product_slug' => $productSlug,
                'user_id' => Auth::id()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Produit non trouvé'
            ], 404);
        }
        
        \Log::info('Tentative ajout produit au panier location', [
            'product_slug' => $productSlug,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'user_id' => Auth::id(),
            'is_rentable' => $product->isRentable()
        ]);

        $user = Auth::user();
        $cartLocation = $user->getOrCreateActiveCartLocation();

        // Vérifier que le produit est louable
        if (!$product->isRentable()) {
            \Log::warning('Produit non louable', [
                'product_slug' => $productSlug,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'type' => $product->type,
                'is_rental_available' => $product->is_rental_available,
                'rental_stock' => $product->rental_stock
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est pas disponible à la location'
            ], 400);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'start_date' => [
                'required',
                'date',
                new RentalDateValidation($product, null, null, 'start')
            ],
            'end_date' => [
                'required', 
                'date',
                function ($attribute, $value, $fail) use ($request, $product) {
                    $startDate = Carbon::parse($request->start_date);
                    $endDate = Carbon::parse($value);
                    $rule = new RentalDateValidation($product, $startDate, $endDate, 'end');
                    $rule->validate($attribute, $value, $fail);
                }
            ],
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);

            // Validation supplémentaire de la période complète
            $validation = $product->validateRentalPeriod($startDate, $endDate);
            if (!$validation['valid']) {
                throw ValidationException::withMessages([
                    'rental_period' => $validation['errors']
                ]);
            }

            $cartItem = $cartLocation->addProduct(
                $product,
                $validated['quantity'],
                $startDate,
                $endDate,
                $validated['notes'] ?? null
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier de location avec succès',
                'data' => [
                    'cart_item' => $cartItem->toDisplayArray(),
                    'cart_summary' => $cartLocation->fresh()->getSummary(),
                    'rental_info' => [
                        'duration_days' => $validation['duration_days']
                    ]
                ]
            ], 201);

        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Mettre à jour la quantité d'un produit
     */
    public function updateQuantity(Request $request, $productSlug): JsonResponse
    {
        $product = Product::where('slug', $productSlug)->first();
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produit non trouvé'], 404);
        }

        $user = Auth::user();
        $cartLocation = $user->activeCartLocation;

        if (!$cartLocation) {
            return response()->json([
                'success' => false,
                'message' => 'Panier de location non trouvé'
            ], 404);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $cartItem = $cartLocation->updateProductQuantity($product, $validated['quantity']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Quantité mise à jour avec succès',
                'data' => [
                    'cart_item' => $cartItem->toDisplayArray(),
                    'cart_summary' => $cartLocation->fresh()->getSummary()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Mettre à jour les dates de location d'un produit
     */
    public function updateDates(Request $request, $productSlug): JsonResponse
    {
        $product = Product::where('slug', $productSlug)->first();
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produit non trouvé'], 404);
        }

        $user = Auth::user();
        $cartLocation = $user->activeCartLocation;

        if (!$cartLocation) {
            return response()->json([
                'success' => false,
                'message' => 'Panier de location non trouvé'
            ], 404);
        }

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date'
        ]);

        try {
            DB::beginTransaction();

            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);

            $cartItem = $cartLocation->updateProductDates($product, $startDate, $endDate);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dates de location mises à jour avec succès',
                'data' => [
                    'cart_item' => $cartItem->toDisplayArray(),
                    'cart_summary' => $cartLocation->fresh()->getSummary()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Supprimer un produit du panier de location
     */
    public function removeProduct($productSlug): JsonResponse
    {
        $product = Product::where('slug', $productSlug)->first();
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produit non trouvé'], 404);
        }

        $user = Auth::user();
        $cartLocation = $user->activeCartLocation;

        if (!$cartLocation) {
            return response()->json([
                'success' => false,
                'message' => 'Panier de location non trouvé'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $removed = $cartLocation->removeProduct($product);

            if (!$removed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produit non trouvé dans le panier de location'
                ], 404);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Produit '{$product->name}' supprimé du panier de location",
                'data' => [
                    'cart_summary' => $cartLocation->fresh()->getSummary()
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
     * Vider le panier de location
     */
    public function clear(): JsonResponse
    {
        $user = Auth::user();
        $cartLocation = $user->activeCartLocation;

        if (!$cartLocation) {
            return response()->json([
                'success' => false,
                'message' => 'Panier de location non trouvé'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $cartLocation->clear();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Panier de location vidé avec succès',
                'data' => [
                    'cart_summary' => $cartLocation->fresh()->getSummary()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du vidage: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vérifier la disponibilité de tous les produits du panier
     */
    public function checkAvailability(): JsonResponse
    {
        $user = Auth::user();
        $cartLocation = $user->activeCartLocation;

        if (!$cartLocation) {
            return response()->json([
                'success' => false,
                'message' => 'Panier de location non trouvé'
            ], 404);
        }

        $availability = $cartLocation->checkAvailability();

        return response()->json([
            'success' => true,
            'data' => $availability
        ]);
    }

    /**
     * Obtenir un résumé du panier de location
     */
    public function summary(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentification requise',
                'data' => [
                    'total_amount' => 0,
                    'total_deposit' => 0,
                    'total_tva' => 0,
                    'total_with_tax' => 0,
                    'total_items' => 0,
                    'total_quantity' => 0,
                    'items_count' => 0,
                    'is_empty' => true
                ]
            ], 401);
        }
        
        $cartLocation = $user->activeCartLocation;

        if (!$cartLocation) {
            return response()->json([
                'success' => true,
                'data' => [
                    'total_amount' => 0,
                    'total_deposit' => 0,
                    'total_tva' => 0,
                    'total_with_tax' => 0,
                    'total_items' => 0,
                    'total_quantity' => 0,
                    'items_count' => 0,
                    'is_empty' => true
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $cartLocation->getSummary()
        ]);
    }

    /**
     * Mettre à jour les dates par défaut du panier
     */
    public function updateDefaultDates(Request $request): JsonResponse
    {
        $user = Auth::user();
        $cartLocation = $user->getOrCreateActiveCartLocation();

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date'
        ]);

        try {
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);

            $cartLocation->updateDefaultDates($startDate, $endDate);

            return response()->json([
                'success' => true,
                'message' => 'Dates par défaut mises à jour avec succès',
                'data' => [
                    'default_start_date' => $startDate->format('Y-m-d'),
                    'default_end_date' => $endDate->format('Y-m-d'),
                    'default_duration_days' => $cartLocation->default_duration_days
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Préparer le panier pour la commande
     */
    public function prepareForCheckout(): JsonResponse
    {
        $user = Auth::user();
        $cartLocation = $user->activeCartLocation;

        if (!$cartLocation) {
            return response()->json([
                'success' => false,
                'message' => 'Panier de location non trouvé'
            ], 404);
        }

        try {
            $checkoutData = $cartLocation->prepareForCheckout();

            return response()->json([
                'success' => true,
                'message' => 'Panier prêt pour la commande',
                'data' => $checkoutData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // ==================== MÉTHODES ADMIN ====================

    /**
     * [ADMIN] Voir tous les paniers de location
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $query = CartLocation::with(['user', 'items.product']);

        // Filtres optionnels
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        if ($request->has('min_amount')) {
            $query->where('total_with_tax', '>=', $request->min_amount);
        }

        $cartLocations = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $cartLocations->map(function ($cart) {
                $data = $cart->toArray();
                $data['user'] = [
                    'id' => $cart->user->id,
                    'name' => $cart->user->name,
                    'email' => $cart->user->email
                ];
                return $data;
            }),
            'pagination' => [
                'current_page' => $cartLocations->currentPage(),
                'last_page' => $cartLocations->lastPage(),
                'per_page' => $cartLocations->perPage(),
                'total' => $cartLocations->total()
            ]
        ]);
    }

    /**
     * [ADMIN] Statistiques des paniers de location
     */
    public function adminStats(): JsonResponse
    {
        $stats = [
            'total_carts' => CartLocation::count(),
            'total_value' => CartLocation::sum('total_with_tax'),
            'total_deposits' => CartLocation::sum('total_deposit'),
            'average_cart_value' => CartLocation::avg('total_with_tax'),
            'carts_with_items' => CartLocation::where('total_items', '>', 0)->count(),
            'empty_carts' => CartLocation::where('total_items', 0)->count(),
            'carts_by_month' => CartLocation::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_with_tax) as total_value'),
                DB::raw('SUM(total_deposit) as total_deposits')
            )
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->limit(12)
                ->get(),
            'top_users_by_cart_value' => CartLocation::select('user_id', DB::raw('SUM(total_with_tax) as total_spent'))
                ->with('user:id,name,email')
                ->groupBy('user_id')
                ->orderBy('total_spent', 'desc')
                ->limit(10)
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
