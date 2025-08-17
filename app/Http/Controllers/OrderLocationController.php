<?php

namespace App\Http\Controllers;

use App\Models\OrderLocation;
use App\Models\CartLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RentalOrderConfirmed;
use Carbon\Carbon;

class OrderLocationController extends Controller
{
    /**
     * Afficher la liste des commandes de location
     */
    public function index(Request $request)
    {
        $query = OrderLocation::with(['user', 'items.product']);

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Par défaut, ne PAS afficher les commandes en attente (pending)
            // Les commandes pending sont en cours de paiement et ne doivent pas apparaître dans "Mes locations"
            $query->whereNotIn('status', ['pending']);
        }

        // Filtrage par utilisateur (pour l'admin)
        if ($request->filled('user_id') && Auth::user()->isAdmin()) {
            $query->where('user_id', $request->user_id);
        }

        // Si ce n'est pas un admin, afficher seulement ses commandes
        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        // Filtrage par dates
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        // Tri par défaut par date de création décroissante
        $orderLocations = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('rental-orders.index', compact('orderLocations'));
    }

    /**
     * Afficher les détails d'une commande de location
     */
    public function show(OrderLocation $orderLocation, Request $request)
    {
        // Vérifier les permissions
        if (!Auth::user()->isAdmin() && $orderLocation->user_id !== Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Non autorisé'], 403);
            }
            abort(403, 'Commande non autorisée');
        }

        $orderLocation->load([
            'user',
            'items.product'
        ]);

        // Si c'est une requête web, retourner la vue
        if (!$request->expectsJson()) {
            return view('rental-orders.show', compact('orderLocation'));
        }

        return response()->json([
            'success' => true,
            'data' => $orderLocation
        ]);
    }

    /**
     * Afficher la page de checkout pour les locations
     */
    public function showCheckout()
    {
        $user = Auth::user();
        
        // Debug: Ajouter des logs pour diagnostiquer
        \Log::info('DEBUG Checkout - Utilisateur connecté', [
            'user_id' => $user ? $user->id : 'null',
            'user_email' => $user ? $user->email : 'null'
        ]);
        
        $cartLocation = $user->activeCartLocation;
        
        \Log::info('DEBUG Checkout - Panier récupéré', [
            'cart_location_id' => $cartLocation ? $cartLocation->id : 'null',
            'cart_items_count' => $cartLocation && $cartLocation->items ? $cartLocation->items->count() : 0,
            'all_carts_count' => \App\Models\CartLocation::where('user_id', $user->id)->count()
        ]);
        
        if (!$cartLocation || $cartLocation->items->isEmpty()) {
            \Log::warning('DEBUG Checkout - Panier vide ou inexistant', [
                'cart_location' => $cartLocation ? 'exists but empty' : 'null',
                'redirect_to' => 'cart-location.index'
            ]);
            
            return redirect()->route('cart-location.index')
                ->with('error', 'Votre panier de location est vide.');
        }
        
        // Calculer le résumé du panier
        $summary = $cartLocation->getSummary();
        
        // Récupérer les traductions des noms de produits pour la langue courante
        $locale = app()->getLocale();
        $productNames = __('app.product_names', [], $locale);
        
        \Log::info('DEBUG Checkout - Résumé calculé', [
            'summary' => $summary
        ]);
        
        return view('checkout-rental.index', compact('cartLocation', 'summary', 'productNames'));
    }

    /**
     * Créer une commande de location à partir du panier
     */
    public function store(Request $request)
    {
        $request->validate([
            'cart_location_id' => 'required|exists:cart_locations,id',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'pickup_address' => 'required|string|max:255',
            'return_address' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ]);

        $cartLocation = CartLocation::with('items.product')
            ->where('id', $request->cart_location_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($cartLocation->items->isEmpty()) {
            return response()->json(['error' => 'Le panier est vide'], 400);
        }

        DB::beginTransaction();
        try {
            // Calculer la durée de location
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $rentalDays = $startDate->diffInDays($endDate) + 1;
            
            // Calculer les totaux d'abord
            $subtotal = 0;
            $totalDeposit = 0;
            $dailyRate = 0;
            
            foreach ($cartLocation->items as $cartItem) {
                $product = $cartItem->product;
                $itemTotal = $product->rental_price_per_day * $cartItem->quantity * $rentalDays;
                $depositPerItem = $product->rental_deposit ?? 0; // Valeur par défaut si null
                $itemDeposit = $depositPerItem * $cartItem->quantity;
                $dailyRate += $product->rental_price_per_day * $cartItem->quantity;
                
                $subtotal += $itemTotal;
                $totalDeposit += $itemDeposit;
            }
            
            $taxAmount = $subtotal * 0.21;
            $totalAmount = $subtotal + $taxAmount;
            
            // Créer la commande de location
            $orderLocation = OrderLocation::create([
                'user_id' => Auth::id(),
                'order_number' => OrderLocation::generateOrderNumber(),
                'status' => 'pending',
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'rental_days' => $rentalDays,
                'daily_rate' => $dailyRate,
                'total_rental_cost' => $subtotal,
                'billing_address' => [
                    'address' => $request->pickup_address,
                    'type' => 'pickup'
                ],
                'delivery_address' => [
                    'address' => $request->return_address,
                    'type' => 'return'
                ],
                'notes' => $request->notes,
                'subtotal' => $subtotal,
                'deposit_amount' => $totalDeposit,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'tax_rate' => 21.00,
                'payment_status' => 'pending'
            ]);

            // Créer les éléments de la commande
            foreach ($cartLocation->items as $cartItem) {
                $product = $cartItem->product;
                $itemTotal = $product->rental_price_per_day * $cartItem->quantity * $rentalDays;
                $depositPerItem = $product->rental_deposit ?? 0; // Valeur par défaut si null
                $itemDeposit = $depositPerItem * $cartItem->quantity;
                $itemTaxAmount = $itemTotal * 0.21; // 21% TVA
                
                $orderLocation->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $cartItem->quantity,
                    'daily_rate' => $product->rental_price_per_day,
                    'rental_days' => $rentalDays,
                    'subtotal' => $itemTotal,
                    'deposit_per_item' => $depositPerItem,
                    'total_deposit' => $itemDeposit,
                    'tax_amount' => $itemTaxAmount,
                    'total_amount' => $itemTotal + $itemTaxAmount,
                    'product_name' => $product->name,
                    'product_description' => $product->description
                ]);
                
                // Note: Le stock sera décrémenté lors de la confirmation du paiement via webhook
            }

            // Vider le panier de location
            $cartLocation->items()->delete();

            DB::commit();

            // Si c'est une requête web, rediriger vers la page de paiement
            if (!$request->expectsJson()) {
                return redirect()->route('payment.stripe-rental', $orderLocation)
                    ->with('success', 'Votre commande de location a été créée avec succès ! Procédez au paiement.');
            }

            return response()->json([
                'success' => true,
                'message' => 'Commande de location créée avec succès',
                'data' => $orderLocation
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log complet de l'erreur pour debugging
            \Log::error('Erreur création OrderLocation:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Si c'est une requête web, rediriger avec erreur détaillée
            if (!$request->expectsJson()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Erreur lors de la création de la commande: ' . $e->getMessage() . ' (Ligne: ' . $e->getLine() . ')');
            }
            
            return response()->json([
                'error' => 'Erreur lors de la création de la commande', 
                'details' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Confirmer une commande de location (Admin seulement)
     */
    public function confirm(OrderLocation $orderLocation)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        if ($orderLocation->status !== 'pending') {
            return response()->json(['error' => 'Cette commande ne peut pas être confirmée'], 400);
        }

        // Le changement de statut déclenchera automatiquement les événements
        $orderLocation->update(['status' => 'confirmed']);

        return response()->json([
            'success' => true,
            'message' => 'Commande confirmée avec succès',
            'data' => $orderLocation->fresh()
        ]);
    }

    /**
     * Annuler une commande de location
     */
    public function cancel(OrderLocation $orderLocation, Request $request)
    {
        // Vérifier les permissions
        if (!Auth::user()->isAdmin() && $orderLocation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        if (!$orderLocation->can_be_cancelled) {
            return response()->json(['error' => 'Cette commande ne peut plus être annulée'], 400);
        }

        $oldStatus = $orderLocation->status;

        // Annuler la commande (cela va aussi restaurer le stock)
        $orderLocation->cancel($request->cancellation_reason);

        // Email d'annulation envoyé automatiquement par le listener
        \Log::info("Commande annulée: {$orderLocation->order_number} (email géré par le listener)");

        return response()->json([
            'success' => true,
            'message' => 'Commande annulée avec succès. Un email de confirmation vous a été envoyé.',
            'data' => $orderLocation->fresh()
        ]);
    }

    /**
     * Marquer une commande comme terminée (utilisateur)
     */
    public function complete(OrderLocation $orderLocation)
    {
        if ($orderLocation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        if ($orderLocation->status !== 'active') {
            return response()->json(['error' => 'Cette commande ne peut pas être marquée comme terminée'], 400);
        }

        // Le changement de statut déclenchera automatiquement les événements
        $orderLocation->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Commande marquée comme terminée. En attente de fermeture.',
            'data' => $orderLocation->fresh()
        ]);
    }

    /**
     * Fermer une commande (utilisateur)
     */
    public function close(OrderLocation $orderLocation)
    {
        if ($orderLocation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        if ($orderLocation->status !== 'completed') {
            return response()->json(['error' => 'Cette commande ne peut pas être fermée'], 400);
        }

        // Le changement de statut déclenchera automatiquement les événements
        $orderLocation->update(['status' => 'closed']);

        return response()->json([
            'success' => true,
            'message' => 'Commande fermée avec succès. En attente d\'inspection.',
            'data' => $orderLocation->fresh()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/rentals/{id}/start-inspection",
     *     tags={"Admin", "Rentals", "Inspection"},
     *     summary="Démarrer l'inspection d'une location",
     *     description="Initie le processus d'inspection d'un matériel retourné (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la commande de location",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inspection démarrée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Inspection démarrée"),
     *             @OA\Property(property="data", ref="#/components/schemas/OrderLocation")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Commande pas prête pour inspection",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Cette commande n'est pas prête pour l'inspection")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Non autorisé")
     *         )
     *     )
     * )
     * 
     * Démarrer l'inspection (Admin seulement)
     */
    public function startInspection(OrderLocation $orderLocation)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        if ($orderLocation->status !== 'closed') {
            return response()->json(['error' => 'Cette commande n\'est pas prête pour l\'inspection'], 400);
        }

        $orderLocation->startInspection();

        return response()->json([
            'success' => true,
            'message' => 'Inspection démarrée',
            'data' => $orderLocation->fresh()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/rentals/{id}/finish-inspection",
     *     tags={"Admin", "Rentals", "Inspection"},
     *     summary="Finaliser l'inspection d'une location",
     *     description="Termine le processus d'inspection avec évaluation des conditions des équipements retournés (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la commande de location",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"items"},
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"id", "return_condition"},
     *                     @OA\Property(property="id", type="integer", example=1, description="ID de l'article de location"),
     *                     @OA\Property(
     *                         property="return_condition",
     *                         type="string",
     *                         enum={"good", "damaged", "lost"},
     *                         example="good",
     *                         description="État de retour de l'équipement"
     *                     ),
     *                     @OA\Property(property="damage_description", type="string", example="Rayure légère sur le côté", description="Description des dommages si applicable"),
     *                     @OA\Property(property="penalty_amount", type="number", format="float", example=25.00, description="Montant de pénalité si applicable"),
     *                     @OA\Property(property="inspection_photos", type="array", @OA\Items(type="string"), description="Photos d'inspection")
     *                 )
     *             ),
     *             @OA\Property(property="global_notes", type="string", example="Inspection complète terminée, matériel en bon état général", description="Notes générales d'inspection"),
     *             @OA\Property(property="total_penalty", type="number", format="float", example=0.00, description="Total des pénalités")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inspection finalisée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Inspection terminée et caution mise à jour"),
     *             @OA\Property(property="data", ref="#/components/schemas/OrderLocation"),
     *             @OA\Property(
     *                 property="inspection_summary",
     *                 type="object",
     *                 @OA\Property(property="items_inspected", type="integer", example=3),
     *                 @OA\Property(property="items_in_good_condition", type="integer", example=3),
     *                 @OA\Property(property="items_damaged", type="integer", example=0),
     *                 @OA\Property(property="items_lost", type="integer", example=0),
     *                 @OA\Property(property="total_penalties", type="number", format="float", example=0.00),
     *                 @OA\Property(property="deposit_refunded", type="number", format="float", example=75.00),
     *                 @OA\Property(property="inspection_completed_at", type="string", format="date-time", example="2024-08-15T16:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Commande pas en cours d'inspection ou données invalides",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Cette commande n'est pas en cours d'inspection")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Non autorisé")
     *         )
     *     )
     * )
     * 
     * Finaliser l'inspection et terminer la commande (Admin seulement)
     */
    public function finishInspection(OrderLocation $orderLocation, Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        if ($orderLocation->status !== 'inspecting') {
            return response()->json(['error' => 'Cette commande n\'est pas en cours d\'inspection'], 400);
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:order_item_locations,id',
            'items.*.return_condition' => 'required|in:good,damaged,lost',
            'items.*.return_notes' => 'nullable|string|max:500',
            'items.*.damage_cost' => 'nullable|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $totalPenalties = 0;

            // Mettre à jour chaque item avec les résultats de l'inspection
            foreach ($request->items as $itemData) {
                $orderItem = $orderLocation->items()->findOrFail($itemData['id']);
                
                $penalties = $orderItem->finishInspection(
                    $itemData['return_condition'],
                    $itemData['return_notes'] ?? null,
                    $itemData['damage_cost'] ?? 0
                );

                $totalPenalties += $penalties;
            }

            // Finaliser la commande
            $orderLocation->finishInspection($totalPenalties);

            DB::commit();

            // Email d'inspection envoyé automatiquement par le listener
            \Log::info("Inspection terminée: {$orderLocation->order_number} (email géré par le listener)");

            return response()->json([
                'success' => true,
                'message' => 'Inspection terminée avec succès',
                'data' => $orderLocation->fresh()->load('items')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur lors de la finalisation de l\'inspection'], 500);
        }
    }

    /**
     * Statistiques des locations (Admin seulement)
     */
    public function statistics()
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $stats = [
            'total_orders' => OrderLocation::count(),
            'pending_orders' => OrderLocation::where('status', 'pending')->count(),
            'active_orders' => OrderLocation::where('status', 'active')->count(),
            'completed_orders' => OrderLocation::where('status', 'completed')->count(),
            'inspecting_orders' => OrderLocation::where('status', 'inspecting')->count(),
            'finished_orders' => OrderLocation::where('status', 'finished')->count(),
            'cancelled_orders' => OrderLocation::where('status', 'cancelled')->count(),
            'total_revenue' => OrderLocation::where('status', 'finished')->sum('subtotal'),
            'total_deposits' => OrderLocation::whereIn('status', ['confirmed', 'active', 'completed', 'closed', 'inspecting'])->sum('deposit_amount'),
            'total_penalties' => OrderLocation::where('status', 'finished')->sum('penalty_amount'),
            'orders_this_month' => OrderLocation::whereMonth('created_at', now()->month)->count(),
            'revenue_this_month' => OrderLocation::where('status', 'finished')->whereMonth('created_at', now()->month)->sum('subtotal')
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Exporter les commandes de location (Admin seulement)
     */
    public function export(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $query = OrderLocation::with(['user', 'items.product']);

        // Appliquer les filtres si fournis
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $data = $orders->map(function ($order) {
            return [
                'Numéro' => $order->order_number,
                'Client' => $order->user->name,
                'Email' => $order->user->email,
                'Statut' => $order->status,
                'Date début' => $order->start_date,
                'Date fin' => $order->end_date,
                'Sous-total' => $order->subtotal,
                'Dépôt' => $order->deposit_amount,
                'TVA' => $order->tax_amount,
                'Pénalités' => $order->penalty_amount,
                'Total' => $order->total_amount,
                'Créé le' => $order->created_at->format('Y-m-d H:i:s')
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'filename' => 'commandes_location_' . now()->format('Y-m-d') . '.csv'
        ]);
    }

    /**
     * Télécharger la facture d'une location
     */
    public function downloadInvoice(OrderLocation $orderLocation)
    {
        try {
            // Vérifier les permissions
            if (!Auth::user()->isAdmin() && $orderLocation->user_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Accès non autorisé.');
            }

            // Vérifier que la facture peut être générée
            if (!$orderLocation->canGenerateInvoice()) {
                return redirect()->back()->with('error', 'La facture ne peut pas encore être générée pour cette location.');
            }

            try {
                // Générer la facture PDF
                $orderLocation->generateInvoicePdf();
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Erreur lors de la génération de la facture.');
            }

            $filename = 'facture-location-' . $orderLocation->invoice_number . '.pdf';
            $filePath = storage_path('app/invoices/rentals/' . $filename);

            return response()->download($filePath, $filename);

        } catch (\Exception $e) {
            \Log::error('Erreur lors du téléchargement de la facture de location', [
                'order_location_id' => $orderLocation->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Erreur lors du téléchargement de la facture. Veuillez réessayer.');
        }
    }

    /**
     * Page de succès après paiement Stripe
     */
    public function paymentSuccess(OrderLocation $orderLocation)
    {
        // Vérifier les permissions
        if (!Auth::user()->isAdmin() && $orderLocation->user_id !== Auth::id()) {
            abort(403, 'Commande non autorisée');
        }

        // Charger les relations nécessaires
        $orderLocation->load(['user', 'items.product']);

        // 🎯 DÉCRÉMENTER LE STOCK lors de la confirmation frontend
        $stripeService = app(\App\Services\StripeService::class);
        $stockDecremented = $stripeService->decrementStockOnFrontendConfirmation($orderLocation);

        if (!$stockDecremented) {
            \Log::error('Échec du décrément de stock lors de la confirmation frontend', [
                'order_location_id' => $orderLocation->id,
                'order_number' => $orderLocation->order_number
            ]);
            return redirect()->back()->with('error', 'Erreur lors de la confirmation de la commande. Veuillez contacter le support.');
        }

        return view('rental-orders.payment-success', compact('orderLocation'));
    }

    /**
     * Page d'annulation/échec de paiement Stripe
     */
    public function paymentCancel(OrderLocation $orderLocation)
    {
        // Vérifier les permissions
        if (!Auth::user()->isAdmin() && $orderLocation->user_id !== Auth::id()) {
            abort(403, 'Commande non autorisée');
        }

        // Charger les relations nécessaires
        $orderLocation->load(['user', 'items.product']);

        return view('rental-orders.payment-cancel', compact('orderLocation'));
    }
}
