<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RentalCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    /**
     * Afficher la page des locations avec tous les produits louables
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'rentalCategory'])
            ->where('is_active', true)
            ->where('is_rental_available', true)
            ->whereIn('type', ['rental', 'both'])
            ->where('rental_stock', '>', 0);

        // Filtrage par catégorie de location
        if ($request->filled('rental_category')) {
            $query->where('rental_category_id', $request->rental_category);
        }

        // Filtrage par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filtrage par prix
        if ($request->filled('min_price')) {
            $query->where('rental_price_per_day', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('rental_price_per_day', '<=', $request->max_price);
        }

        // Tri
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');

        switch ($sortBy) {
            case 'price':
                $query->orderBy('rental_price_per_day', $sortOrder);
                break;
            case 'popularity':
                $query->orderBy('views_count', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', $sortOrder);
                break;
        }

        $products = $query->paginate(12);

        // Récupérer les catégories de location pour les filtres
        $rentalCategories = RentalCategory::active()->get();

        // Calculer les statistiques des prix pour les filtres
        $priceStats = Product::whereIn('type', ['rental', 'both'])
            ->where('is_active', true)
            ->selectRaw('MIN(rental_price_per_day) as min_price, MAX(rental_price_per_day) as max_price')
            ->first();

        return view('web.rentals.index', compact('products', 'rentalCategories', 'priceStats'));
    }

    /**
     * Afficher les détails d'un produit de location
     */
    public function show(Product $product)
    {
        // Vérifier que le produit est louable
        if (!$product->isRentable()) {
            abort(404, 'Ce produit n\'est pas disponible à la location');
        }

        // Charger les relations
        $product->load(['category', 'rentalCategory']);

        // Incrémenter le compteur de vues
        $product->increment('views_count');

        // Récupérer les produits similaires
        $similarProducts = Product::with(['category', 'rentalCategory'])
            ->where('id', '!=', $product->id)
            ->where('rental_category_id', $product->rental_category_id)
            ->where('is_active', true)
            ->whereIn('type', ['rental', 'both'])
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('web.rentals.show', compact('product', 'similarProducts'));
    }

    /**
     * API pour obtenir les contraintes de location d'un produit
     */
    public function getProductConstraints(Product $product)
    {
        if (!$product->isRentable()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est pas disponible à la location'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'constraints' => [
                    'min_rental_days' => $product->min_rental_days ?? 1,
                    'max_rental_days' => $product->max_rental_days ?? 30,
                    'available_days' => $product->available_days ?? [1, 2, 3, 4, 5, 6, 7],
                ],
                'pricing' => [
                    'daily_price' => $product->rental_price_per_day,
                    'deposit_amount' => $product->deposit_amount,
                    'currency' => 'EUR'
                ],
                'availability' => [
                    'current_stock' => $product->quantity,
                    'is_available' => $product->quantity > 0 && !$product->is_out_of_stock
                ]
            ]
        ]);
    }

    /**
     * API pour calculer le coût de location
     */
    public function calculateRentalCost(Request $request, Product $product)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'quantity' => 'required|integer|min:1'
        ]);

        if (!$product->isRentable()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est pas disponible à la location'
            ], 400);
        }

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $quantity = $validated['quantity'];

        // Calculer le nombre de jours
        $days = $startDate->diffInDays($endDate) + 1;

        // Vérifier les contraintes
        if ($days < $product->min_rental_days) {
            return response()->json([
                'success' => false,
                'message' => "Durée minimale de location : {$product->min_rental_days} jour(s)"
            ], 400);
        }

        if ($days > $product->max_rental_days) {
            return response()->json([
                'success' => false,
                'message' => "Durée maximale de location : {$product->max_rental_days} jour(s)"
            ], 400);
        }

        // Calculer les coûts
        $dailyPrice = $product->rental_price_per_day;
        $depositAmount = $product->deposit_amount;
        
        $subtotal = $dailyPrice * $quantity * $days;
        $deposit = $depositAmount * $quantity;
        $tax = $subtotal * ($product->getTaxRate() / 100); // TVA basée sur le produit
        $total = $subtotal + $tax;

        return response()->json([
            'success' => true,
            'data' => [
                'rental_period' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'days' => $days
                ],
                'quantity' => $quantity,
                'pricing' => [
                    'daily_price' => $dailyPrice,
                    'subtotal' => $subtotal,
                    'tax_amount' => $tax,
                    'total' => $total,
                    'deposit_amount' => $deposit,
                    'currency' => 'EUR'
                ],
                'breakdown' => [
                    'unit_price_per_day' => $dailyPrice,
                    'total_days' => $days,
                    'quantity' => $quantity,
                    'unit_deposit' => $depositAmount
                ]
            ]
        ]);
    }
}
