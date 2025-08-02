<?php

namespace App\Http\Controllers;

use App\Models\CartLocation;
use App\Models\CartItemLocation;
use App\Models\Product;
use App\Rules\RentalDateValidation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class CartItemLocationController extends Controller
{
    /**
     * Afficher tous les éléments du panier de location de l'utilisateur connecté
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $cartLocation = $user->activeCartLocation;
        
        if (!$cartLocation) {
            return response()->json([
                'success' => true,
                'message' => 'Panier de location vide',
                'data' => []
            ]);
        }

        $items = $cartLocation->items()->with(['product.category', 'product.rentalCategory'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $items->map(function ($item) {
                return $item->toDisplayArray();
            })
        ]);
    }

    /**
     * Afficher un élément spécifique du panier de location
     */
    public function show(CartItemLocation $cartItemLocation): JsonResponse
    {
        $user = Auth::user();
        
        // Vérifier que l'élément appartient au panier de l'utilisateur
        if ($cartItemLocation->cartLocation->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Élément non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $cartItemLocation->toDisplayArray()
        ]);
    }

    /**
     * Mettre à jour la quantité d'un élément du panier de location
     */
    public function updateQuantity(Request $request, CartItemLocation $cartItemLocation): JsonResponse
    {
        $user = Auth::user();
        
        // Vérifier que l'élément appartient au panier de l'utilisateur
        if ($cartItemLocation->cartLocation->user_id !== $user->id) {
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
            $cartItemLocation->cartLocation->checkProductAvailability(
                $cartItemLocation->product,
                $quantity,
                Carbon::parse($cartItemLocation->start_date),
                Carbon::parse($cartItemLocation->end_date),
                $cartItemLocation->id
            );

            // Mettre à jour la quantité
            $cartItemLocation->updateQuantity($quantity);
            
            // Recalculer le total du panier
            $cartItemLocation->cartLocation->recalculateTotal();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Quantité mise à jour avec succès',
                'data' => [
                    'cart_item' => $cartItemLocation->fresh()->toDisplayArray(),
                    'cart_summary' => $cartItemLocation->cartLocation->fresh()->getSummary()
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
     * Mettre à jour les dates de location d'un élément
     */
    public function updateDates(Request $request, CartItemLocation $cartItemLocation): JsonResponse
    {
        $user = Auth::user();
        
        // Vérifier que l'élément appartient au panier de l'utilisateur
        if ($cartItemLocation->cartLocation->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Élément non trouvé'
            ], 404);
        }

        $validated = $request->validate([
            'start_date' => [
                'required',
                'date',
                new RentalDateValidation($cartItemLocation->product, null, null, 'start')
            ],
            'end_date' => [
                'required', 
                'date',
                'after:start_date',
                new RentalDateValidation($cartItemLocation->product, 
                    $request->start_date ? Carbon::parse($request->start_date) : null, 
                    null, 
                    'end')
            ]
        ]);

        try {
            DB::beginTransaction();

            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);

            // Validation supplémentaire avec les contraintes du produit
            $validation = $cartItemLocation->product->validateRentalPeriod($startDate, $endDate);
            
            if (!$validation['valid']) {
                throw ValidationException::withMessages([
                    'rental_period' => $validation['errors']
                ]);
            }

            // Vérifier la disponibilité pour les nouvelles dates
            $cartItemLocation->cartLocation->checkProductAvailability(
                $cartItemLocation->product,
                $cartItemLocation->quantity,
                $startDate,
                $endDate,
                $cartItemLocation->id
            );

            // Mettre à jour les dates
            $cartItemLocation->updateDates($startDate, $endDate);
            
            // Recalculer le total du panier
            $cartItemLocation->cartLocation->recalculateTotal();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dates de location mises à jour avec succès',
                'data' => [
                    'cart_item' => $cartItemLocation->fresh()->toDisplayArray(),
                    'cart_summary' => $cartItemLocation->cartLocation->fresh()->getSummary()
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
     * Supprimer un élément du panier de location
     */
    public function destroy(CartItemLocation $cartItemLocation): JsonResponse
    {
        $user = Auth::user();
        
        // Vérifier que l'élément appartient au panier de l'utilisateur
        if ($cartItemLocation->cartLocation->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Élément non trouvé'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $cartLocation = $cartItemLocation->cartLocation;
            $productName = $cartItemLocation->product_name;
            
            $cartItemLocation->delete();
            
            // Recalculer le total du panier
            $cartLocation->recalculateTotal();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Produit '{$productName}' supprimé du panier de location",
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
     * Vérifier la disponibilité d'un élément du panier de location
     */
    public function checkAvailability(CartItemLocation $cartItemLocation): JsonResponse
    {
        $user = Auth::user();
        
        // Vérifier que l'élément appartient au panier de l'utilisateur
        if ($cartItemLocation->cartLocation->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Élément non trouvé'
            ], 404);
        }

        $availability = $cartItemLocation->getAvailabilityInfo();
        
        return response()->json([
            'success' => true,
            'data' => $availability
        ]);
    }

    /**
     * Dupliquer un élément du panier (augmenter sa quantité de 1)
     */
    public function duplicate(CartItemLocation $cartItemLocation): JsonResponse
    {
        $user = Auth::user();
        
        // Vérifier que l'élément appartient au panier de l'utilisateur
        if ($cartItemLocation->cartLocation->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Élément non trouvé'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $newQuantity = $cartItemLocation->quantity + 1;
            
            // Vérifier la disponibilité
            $cartItemLocation->cartLocation->checkProductAvailability(
                $cartItemLocation->product,
                $newQuantity,
                Carbon::parse($cartItemLocation->start_date),
                Carbon::parse($cartItemLocation->end_date),
                $cartItemLocation->id
            );

            $cartItemLocation->updateQuantity($newQuantity);
            $cartItemLocation->cartLocation->recalculateTotal();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Quantité augmentée avec succès',
                'data' => [
                    'cart_item' => $cartItemLocation->fresh()->toDisplayArray(),
                    'cart_summary' => $cartItemLocation->cartLocation->fresh()->getSummary()
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
    public function updateDetails(Request $request, CartItemLocation $cartItemLocation): JsonResponse
    {
        $user = Auth::user();
        
        // Vérifier que l'élément appartient au panier de l'utilisateur
        if ($cartItemLocation->cartLocation->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Élément non trouvé'
            ], 404);
        }

        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $cartItemLocation->update([
                'notes' => $request->input('notes')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Détails mis à jour avec succès',
                'data' => $cartItemLocation->fresh()->toDisplayArray()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir des suggestions de dates optimales
     */
    public function suggestOptimalDates(Request $request, CartItemLocation $cartItemLocation): JsonResponse
    {
        $user = Auth::user();
        
        // Vérifier que l'élément appartient au panier de l'utilisateur
        if ($cartItemLocation->cartLocation->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Élément non trouvé'
            ], 404);
        }

        $validated = $request->validate([
            'preferred_start_date' => 'nullable|date|after_or_equal:today',
            'duration_days' => 'nullable|integer|min:1|max:365'
        ]);

        try {
            $product = $cartItemLocation->product;
            $preferredStart = $validated['preferred_start_date'] ? 
                Carbon::parse($validated['preferred_start_date']) : Carbon::today();
            $duration = $validated['duration_days'] ?? $cartItemLocation->duration_days;

            $suggestions = [];
            
            // Chercher des créneaux disponibles sur les 30 prochains jours
            for ($i = 0; $i < 30; $i++) {
                $testStart = $preferredStart->copy()->addDays($i);
                $testEnd = $testStart->copy()->addDays($duration - 1);
                
                try {
                    $cartItemLocation->cartLocation->checkProductAvailability(
                        $product,
                        $cartItemLocation->quantity,
                        $testStart,
                        $testEnd,
                        $cartItemLocation->id
                    );
                    
                    $suggestions[] = [
                        'start_date' => $testStart->format('Y-m-d'),
                        'end_date' => $testEnd->format('Y-m-d'),
                        'duration_days' => $duration,
                        'total_cost' => $cartItemLocation->unit_price_per_day * $cartItemLocation->quantity * $duration * 1.20 // avec TVA
                    ];
                    
                    if (count($suggestions) >= 5) break; // Limiter à 5 suggestions
                } catch (\Exception $e) {
                    // Cette période n'est pas disponible, continuer
                    continue;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Suggestions de dates disponibles',
                'data' => [
                    'current_dates' => [
                        'start_date' => $cartItemLocation->start_date->format('Y-m-d'),
                        'end_date' => $cartItemLocation->end_date->format('Y-m-d'),
                        'duration_days' => $cartItemLocation->duration_days
                    ],
                    'suggestions' => $suggestions
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== MÉTHODES ADMIN ====================

    /**
     * [ADMIN] Voir tous les éléments de tous les paniers de location
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $query = CartItemLocation::with(['cartLocation.user', 'product.category', 'product.rentalCategory']);

        // Filtres optionnels
        if ($request->has('user_id')) {
            $query->whereHas('cartLocation', function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('start_date_from')) {
            $query->where('start_date', '>=', $request->start_date_from);
        }

        if ($request->has('start_date_to')) {
            $query->where('start_date', '<=', $request->start_date_to);
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $items->map(function ($item) {
                $data = $item->toDisplayArray();
                $data['user'] = [
                    'id' => $item->cartLocation->user->id,
                    'name' => $item->cartLocation->user->name,
                    'email' => $item->cartLocation->user->email
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
     * [ADMIN] Statistiques des éléments de panier de location
     */
    public function adminStats(): JsonResponse
    {
        $stats = [
            'total_items' => CartItemLocation::count(),
            'total_quantity' => CartItemLocation::sum('quantity'),
            'total_value' => CartItemLocation::sum('total_amount'),
            'total_deposits' => CartItemLocation::sum('subtotal_deposit'),
            'average_duration' => CartItemLocation::avg('duration_days'),
            'average_quantity_per_item' => CartItemLocation::avg('quantity'),
            'most_rented_products' => CartItemLocation::select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('COUNT(*) as rental_count'))
                ->with('product:id,name')
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->limit(10)
                ->get(),
            'rental_periods_distribution' => CartItemLocation::select(
                DB::raw('CASE 
                    WHEN duration_days <= 1 THEN "1 jour"
                    WHEN duration_days <= 3 THEN "2-3 jours"
                    WHEN duration_days <= 7 THEN "4-7 jours"
                    WHEN duration_days <= 14 THEN "1-2 semaines"
                    WHEN duration_days <= 30 THEN "2-4 semaines"
                    ELSE "Plus d\'un mois"
                END as period_range'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as total_value')
            )
                ->groupBy('period_range')
                ->orderByRaw('MIN(duration_days)')
                ->get(),
            'items_by_month' => CartItemLocation::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total_amount) as total_value')
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
