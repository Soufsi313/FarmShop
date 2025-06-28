<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\Product;
use App\Models\User;
use App\Notifications\RentalConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class RentalController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of rentals.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Auth::user()->rentals()->with(['items.product', 'penalties']);
        
        // Filtres
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->date_from) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }
        
        $rentals = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $rentals,
            'statuses' => Rental::getAllStatuses()
        ]);
    }

    /**
     * Show the form for creating a new rental.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rentableProducts = Product::where('is_rentable', true)
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->with('images')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => [
                'products' => $rentableProducts,
                'user' => Auth::user()
            ]
        ]);
    }

    /**
     * Store a newly created rental.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'billing_address' => 'required|array',
            'billing_address.street' => 'required|string|max:255',
            'billing_address.city' => 'required|string|max:255',
            'billing_address.postal_code' => 'required|string|max:10',
            'billing_address.country' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Vérifier la disponibilité des produits
            $unavailableItems = [];
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                
                if (!$product || !$product->is_rentable || !$product->is_active) {
                    $unavailableItems[] = "Le produit ID {$item['product_id']} n'est pas disponible à la location";
                    continue;
                }
                
                if ($product->quantity < $item['quantity']) {
                    $unavailableItems[] = "Stock insuffisant pour {$product->name} (demandé: {$item['quantity']}, disponible: {$product->quantity})";
                }
                
                // Vérifier les contraintes de location
                $duration = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1;
                
                if ($product->min_rental_days && $duration < $product->min_rental_days) {
                    $unavailableItems[] = "Durée minimum pour {$product->name}: {$product->min_rental_days} jours";
                }
                
                if ($product->max_rental_days && $duration > $product->max_rental_days) {
                    $unavailableItems[] = "Durée maximum pour {$product->name}: {$product->max_rental_days} jours";
                }
            }

            if (!empty($unavailableItems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certains produits ne sont pas disponibles',
                    'errors' => $unavailableItems
                ], 422);
            }

            // Créer la location
            $rental = Rental::create([
                'user_id' => Auth::id(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'billing_address' => $request->billing_address,
                'notes' => $request->notes,
                'status' => Rental::STATUS_PENDING
            ]);

            // Ajouter les articles
            $totalRentalAmount = 0;
            $totalDepositAmount = 0;
            
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $duration = $rental->duration_in_days;
                
                $rentalItem = $rental->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'rental_price_per_day' => $product->rental_price_per_day,
                    'deposit_amount_per_item' => $product->deposit_amount,
                    'total_rental_amount' => $item['quantity'] * $product->rental_price_per_day * $duration,
                    'total_deposit_amount' => $item['quantity'] * $product->deposit_amount
                ]);
                
                $totalRentalAmount += $rentalItem->total_rental_amount;
                $totalDepositAmount += $rentalItem->total_deposit_amount;
                
                // Réserver le stock
                $product->decrement('quantity', $item['quantity']);
            }

            // Mettre à jour les totaux
            $rental->update([
                'total_rental_amount' => $totalRentalAmount,
                'total_deposit_amount' => $totalDepositAmount
            ]);

            DB::commit();

            $rental->load(['items.product', 'user']);

            // Envoyer la notification de confirmation
            $rental->user->notify(new RentalConfirmation($rental));

            return response()->json([
                'success' => true,
                'message' => 'Location créée avec succès',
                'data' => $rental
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rental = Auth::user()->rentals()
            ->with(['items.product.images', 'penalties'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $rental
        ]);
    }

    /**
     * Update the specified rental.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rental = Auth::user()->rentals()->findOrFail($id);

        if (!$rental->canBeModified()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette location ne peut plus être modifiée'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'start_date' => 'sometimes|date|after_or_equal:today',
            'end_date' => 'sometimes|date|after:start_date',
            'billing_address' => 'sometimes|array',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $rental->update($request->only([
                'start_date', 'end_date', 'billing_address', 'notes'
            ]));

            // Recalculer les totaux si les dates ont changé
            if ($request->has('start_date') || $request->has('end_date')) {
                foreach ($rental->items as $item) {
                    $item->calculateTotals();
                    $item->save();
                }
                $rental->calculateTotals();
            }

            DB::commit();

            $rental->load(['items.product', 'penalties']);

            return response()->json([
                'success' => true,
                'message' => 'Location mise à jour avec succès',
                'data' => $rental
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel the specified rental.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request, $id)
    {
        $rental = Auth::user()->rentals()->findOrFail($id);

        if (!$rental->can_be_cancelled) {
            return response()->json([
                'success' => false,
                'message' => 'Cette location ne peut plus être annulée'
            ], 422);
        }

        $reason = $request->input('reason', 'Annulée par le client');

        if ($rental->cancel($reason)) {
            return response()->json([
                'success' => true,
                'message' => 'Location annulée avec succès'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible d\'annuler cette location'
        ], 422);
    }

    /**
     * Process the return of rental items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processReturn(Request $request, $id)
    {
        $rental = Auth::user()->rentals()->findOrFail($id);

        if (!$rental->can_be_returned) {
            return response()->json([
                'success' => false,
                'message' => 'Cette location n\'est pas éligible au retour'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:rental_items,id',
            'items.*.returned_quantity' => 'required|integer|min:0',
            'items.*.condition_at_return' => 'required|in:excellent,good,fair,poor',
            'items.*.damage_notes' => 'nullable|string|max:500',
            'return_notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $returnData = [];
            foreach ($request->items as $itemData) {
                $returnData[$itemData['item_id']] = [
                    'returned_quantity' => $itemData['returned_quantity'],
                    'condition_at_return' => $itemData['condition_at_return'],
                    'damage_notes' => $itemData['damage_notes'] ?? null
                ];
            }

            $rental->processReturn($returnData);
            
            if ($request->return_notes) {
                $rental->update(['return_notes' => $request->return_notes]);
            }

            DB::commit();

            $rental->load(['items.product', 'penalties']);

            return response()->json([
                'success' => true,
                'message' => 'Retour traité avec succès',
                'data' => $rental
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement du retour',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate rental invoice PDF.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generateInvoice($id)
    {
        $rental = Auth::user()->rentals()
            ->with(['items.product', 'penalties', 'user'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('rentals.invoice', compact('rental'));
        
        return $pdf->download("facture-location-{$rental->rental_number}.pdf");
    }

    /**
     * Get rental statistics for the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        $user = Auth::user();
        
        $stats = [
            'total_rentals' => $user->rentals()->count(),
            'active_rentals' => $user->rentals()->where('status', Rental::STATUS_ACTIVE)->count(),
            'completed_rentals' => $user->rentals()->where('status', Rental::STATUS_COMPLETED)->count(),
            'overdue_rentals' => $user->rentals()->where('status', Rental::STATUS_OVERDUE)->count(),
            'total_spent' => $user->rentals()->sum('total_rental_amount'),
            'total_penalties' => $user->rentals()->join('rental_penalties', 'rentals.id', '=', 'rental_penalties.rental_id')
                                              ->where('rental_penalties.payment_status', '!=', 'waived')
                                              ->sum('rental_penalties.amount'),
            'recent_rentals' => $user->rentals()->with(['items.product'])
                                               ->orderBy('created_at', 'desc')
                                               ->take(5)
                                               ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Check product availability for rental.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::findOrFail($request->product_id);
        
        if (!$product->is_rentable || !$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est pas disponible à la location'
            ]);
        }

        // Vérifier le stock disponible
        $duration = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1;
        $isAvailable = $product->quantity >= $request->quantity;
        
        // Vérifier les contraintes de durée
        $durationValid = true;
        $durationMessage = '';
        
        if ($product->min_rental_days && $duration < $product->min_rental_days) {
            $durationValid = false;
            $durationMessage = "Durée minimum: {$product->min_rental_days} jours";
        }
        
        if ($product->max_rental_days && $duration > $product->max_rental_days) {
            $durationValid = false;
            $durationMessage = "Durée maximum: {$product->max_rental_days} jours";
        }

        // Calculer le coût
        $rentalCost = $request->quantity * $product->rental_price_per_day * $duration;
        $depositCost = $request->quantity * $product->deposit_amount;
        $totalCost = $rentalCost + $depositCost;

        return response()->json([
            'success' => true,
            'data' => [
                'available' => $isAvailable && $durationValid,
                'stock_available' => $product->quantity,
                'duration_valid' => $durationValid,
                'duration_message' => $durationMessage,
                'costs' => [
                    'rental_cost' => $rentalCost,
                    'deposit_cost' => $depositCost,
                    'total_cost' => $totalCost,
                    'duration_days' => $duration
                ],
                'product' => [
                    'name' => $product->name,
                    'rental_price_per_day' => $product->rental_price_per_day,
                    'deposit_amount' => $product->deposit_amount,
                    'min_rental_days' => $product->min_rental_days,
                    'max_rental_days' => $product->max_rental_days
                ]
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rental = Auth::user()->rentals()->findOrFail($id);

        if (!$rental->can_be_cancelled) {
            return response()->json([
                'success' => false,
                'message' => 'Cette location ne peut pas être supprimée'
            ], 422);
        }

        $rental->cancel('Supprimée par le client');

        return response()->json([
            'success' => true,
            'message' => 'Location supprimée avec succès'
        ]);
    }
}
