<?php

namespace App\Http\Controllers;

use App\Models\SpecialOffer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class SpecialOfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('admin')->except(['index', 'show', 'getProductOffers']);
    }

    /**
     * Afficher la liste des offres spéciales
     */
    public function index(Request $request): JsonResponse
    {
        $query = SpecialOffer::with(['product']);

        // Filtres pour les non-admin
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            $query->valid()->available();
        }

        // Recherche
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('product', function ($productQuery) use ($request) {
                      $productQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filtrage par produit
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filtrage par statut (admin seulement)
        if ($request->filled('status') && Auth::user()?->isAdmin()) {
            switch ($request->status) {
                case 'active':
                    $query->valid()->available();
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'expired':
                    $query->where('end_date', '<', now());
                    break;
                case 'upcoming':
                    $query->where('start_date', '>', now());
                    break;
                case 'limit_reached':
                    $query->whereNotNull('usage_limit')
                          ->whereColumn('usage_count', '>=', 'usage_limit');
                    break;
            }
        }

        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        $allowedSorts = ['name', 'discount_percentage', 'minimum_quantity', 'start_date', 'end_date', 'usage_count', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $specialOffers = $query->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $specialOffers,
            'message' => 'Liste des offres spéciales récupérée avec succès'
        ]);
    }

    /**
     * Créer une nouvelle offre spéciale (Admin seulement)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'product_id' => 'required|exists:products,id',
            'minimum_quantity' => 'required|integer|min:1',
            'discount_percentage' => 'required|numeric|min:0.01|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'usage_limit' => 'nullable|integer|min:1'
        ]);

        // Vérifier qu'il n'y a pas de conflit avec d'autres offres pour le même produit
        $conflictingOffer = SpecialOffer::where('product_id', $validated['product_id'])
            ->where('is_active', true)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhere(function ($q) use ($validated) {
                          $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                      });
            })
            ->first();

        if ($conflictingOffer) {
            return response()->json([
                'success' => false,
                'message' => 'Une offre spéciale active existe déjà pour ce produit durant cette période.',
                'conflicting_offer' => $conflictingOffer->name
            ], 422);
        }

        $specialOffer = SpecialOffer::create($validated);

        return response()->json([
            'success' => true,
            'data' => $specialOffer->load('product'),
            'message' => 'Offre spéciale créée avec succès'
        ], 201);
    }

    /**
     * Afficher une offre spéciale spécifique
     */
    public function show(SpecialOffer $specialOffer): JsonResponse
    {
        $specialOffer->load('product');

        return response()->json([
            'success' => true,
            'data' => $specialOffer,
            'message' => 'Offre spéciale récupérée avec succès'
        ]);
    }

    /**
     * Mettre à jour une offre spéciale (Admin seulement)
     */
    public function update(Request $request, SpecialOffer $specialOffer): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string|max:1000',
            'product_id' => 'sometimes|exists:products,id',
            'minimum_quantity' => 'sometimes|integer|min:1',
            'discount_percentage' => 'sometimes|numeric|min:0.01|max:100',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'is_active' => 'sometimes|boolean',
            'usage_limit' => 'sometimes|nullable|integer|min:1'
        ]);

        // Si on modifie les dates ou le produit, vérifier les conflits
        if (isset($validated['product_id']) || isset($validated['start_date']) || isset($validated['end_date'])) {
            $productId = $validated['product_id'] ?? $specialOffer->product_id;
            $startDate = $validated['start_date'] ?? $specialOffer->start_date;
            $endDate = $validated['end_date'] ?? $specialOffer->end_date;

            $conflictingOffer = SpecialOffer::where('product_id', $productId)
                ->where('id', '!=', $specialOffer->id)
                ->where('is_active', true)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate])
                          ->orWhere(function ($q) use ($startDate, $endDate) {
                              $q->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                          });
                })
                ->first();

            if ($conflictingOffer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une offre spéciale active existe déjà pour ce produit durant cette période.',
                    'conflicting_offer' => $conflictingOffer->name
                ], 422);
            }
        }

        $specialOffer->update($validated);

        return response()->json([
            'success' => true,
            'data' => $specialOffer->fresh(['product']),
            'message' => 'Offre spéciale mise à jour avec succès'
        ]);
    }

    /**
     * Supprimer une offre spéciale (Admin seulement)
     */
    public function destroy(SpecialOffer $specialOffer): JsonResponse
    {
        $specialOffer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Offre spéciale supprimée avec succès'
        ]);
    }

    /**
     * Activer/Désactiver une offre spéciale (Admin seulement)
     */
    public function toggleStatus(SpecialOffer $specialOffer): JsonResponse
    {
        $specialOffer->update([
            'is_active' => !$specialOffer->is_active
        ]);

        $status = $specialOffer->is_active ? 'activée' : 'désactivée';

        return response()->json([
            'success' => true,
            'data' => $specialOffer->fresh(['product']),
            'message' => "Offre spéciale {$status} avec succès"
        ]);
    }

    /**
     * Obtenir les offres spéciales pour un produit spécifique
     */
    public function getProductOffers(Product $product, Request $request): JsonResponse
    {
        $quantity = $request->get('quantity', 1);
        
        $offers = SpecialOffer::getValidOffersForProduct($product->id, $quantity);
        
        $bestOffer = $offers->first();
        $discountInfo = null;
        
        if ($bestOffer) {
            $discountInfo = $bestOffer->calculateDiscount($quantity, $product->price);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'product' => $product,
                'quantity' => $quantity,
                'available_offers' => $offers,
                'best_offer' => $bestOffer,
                'discount_calculation' => $discountInfo
            ],
            'message' => 'Offres pour le produit récupérées avec succès'
        ]);
    }

    /**
     * Calculer la réduction pour un produit et une quantité
     */
    public function calculateDiscount(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $quantity = $validated['quantity'];

        $bestOffer = SpecialOffer::getBestOfferForProduct($product->id, $quantity);
        
        if (!$bestOffer) {
            return response()->json([
                'success' => true,
                'data' => [
                    'applicable' => false,
                    'original_total' => $quantity * $product->price,
                    'discounted_total' => $quantity * $product->price,
                    'savings' => 0,
                    'message' => 'Aucune offre spéciale applicable'
                ]
            ]);
        }

        $discountInfo = $bestOffer->calculateDiscount($quantity, $product->price);

        return response()->json([
            'success' => true,
            'data' => $discountInfo
        ]);
    }

    /**
     * Statistiques des offres spéciales (Admin seulement)
     */
    public function adminStats(): JsonResponse
    {
        $totalOffers = SpecialOffer::count();
        $activeOffers = SpecialOffer::valid()->available()->count();
        $expiredOffers = SpecialOffer::where('end_date', '<', now())->count();
        $upcomingOffers = SpecialOffer::where('start_date', '>', now())->count();
        
        // Offres les plus utilisées
        $mostUsedOffers = SpecialOffer::with('product')
            ->orderBy('usage_count', 'desc')
            ->limit(10)
            ->get();

        // Offres avec le meilleur taux de réduction
        $bestDiscountOffers = SpecialOffer::with('product')
            ->where('is_active', true)
            ->orderBy('discount_percentage', 'desc')
            ->limit(10)
            ->get();

        // Statistiques par mois (6 derniers mois)
        $monthlyStats = SpecialOffer::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as offers_created'),
                DB::raw('SUM(usage_count) as total_usage')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Total des économies générées
        $totalSavings = SpecialOffer::with('product')
            ->get()
            ->sum(function ($offer) {
                return ($offer->product->price * $offer->usage_count * $offer->discount_percentage) / 100;
            });

        return response()->json([
            'success' => true,
            'data' => [
                'overview' => [
                    'total_offers' => $totalOffers,
                    'active_offers' => $activeOffers,
                    'expired_offers' => $expiredOffers,
                    'upcoming_offers' => $upcomingOffers,
                    'total_savings_generated' => round($totalSavings, 2)
                ],
                'most_used_offers' => $mostUsedOffers,
                'best_discount_offers' => $bestDiscountOffers,
                'monthly_stats' => $monthlyStats
            ]
        ]);
    }
}
