<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\OrderReturnConfirmed;
use App\Mail\OrderRefundProcessed;

class OrderController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Afficher la page de checkout pour l'interface web
     */
    public function showCheckout()
    {
        $user = Auth::user();
        $cart = $user->getOrCreateActiveCart();
        $cart->load(['items.product.category']);

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }

        $cartSummary = $cart->getCompleteCartSummary();

        return view('checkout.index', [
            'cart' => $cart,
            'cartSummary' => $cartSummary,
            'user' => $user
        ]);
    }

    /**
     * Afficher la page des commandes utilisateur pour l'interface web
     */
    public function webIndex(Request $request)
    {
        $query = Auth::user()->orders()->with(['items.product']);

        // Filtrage par statut
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'pending':
                    $query->pending();
                    break;
                case 'confirmed':
                    $query->confirmed();
                    break;
                case 'shipped':
                    $query->shipped();
                    break;
                case 'delivered':
                    $query->delivered();
                    break;
                case 'cancelled':
                    $query->cancelled();
                    break;
            }
        }

        // Tri
        $sortBy = $request->get('sort_by', 'recent');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'total_asc':
                $query->orderBy('total_amount', 'asc');
                break;
            case 'total_desc':
                $query->orderBy('total_amount', 'desc');
                break;
            default:
                $query->latest();
        }

        $orders = $query->paginate(10);

        return view('orders.index', [
            'orders' => $orders,
            'currentStatus' => $request->get('status'),
            'currentSort' => $sortBy
        ]);
    }

    /**
     * Afficher une commande spécifique pour l'interface web
     */
    public function webShow(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé à cette commande');
        }

        $order->load([
            'items.product.category',
            'items.returns',
            'returns'
        ]);

        return view('orders.show', ['order' => $order]);
    }

        /**
     * @OA\Get(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Liste des commandes utilisateur",
     *     description="Récupère la liste des commandes de l'utilisateur connecté avec filtres et tri",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrer par statut de commande",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "confirmed", "shipped", "delivered", "cancelled"}, example="confirmed")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Trier les résultats",
     *         required=false,
     *         @OA\Schema(type="string", enum={"recent", "oldest", "total_asc", "total_desc"}, example="recent")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d'éléments par page",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des commandes récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Commandes récupérées avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/PaginatedResponse")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
     * Afficher la liste des commandes de l'utilisateur (API)
     */
    public function index(Request $request)
    {
        $query = Auth::user()->orders()->with(['items.product']);

        // Filtrage par statut
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'pending':
                    $query->pending();
                    break;
                case 'confirmed':
                    $query->confirmed();
                    break;
                case 'shipped':
                    $query->shipped();
                    break;
                case 'delivered':
                    $query->delivered();
                    break;
                case 'cancelled':
                    $query->cancelled();
                    break;
            }
        }

        // Tri
        $sortBy = $request->get('sort_by', 'recent');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'total_asc':
                $query->orderBy('total_amount', 'asc');
                break;
            case 'total_desc':
                $query->orderBy('total_amount', 'desc');
                break;
            default:
                $query->latest();
        }

        $orders = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $orders,
            'meta' => [
                'total_orders' => Auth::user()->orders()->count(),
                'total_spent' => Auth::user()->orders()->where('payment_status', 'paid')->sum('total_amount'),
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{order}",
     *     tags={"Orders"},
     *     summary="Détails d'une commande",
     *     description="Récupère les détails complets d'une commande spécifique",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         description="ID de la commande",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la commande récupérés avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/Order")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé à cette commande",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commande non trouvée",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
     * Afficher une commande spécifique de l'utilisateur
     */
    public function show(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé à cette commande');
        }

        $order->load([
            'items.product.category',
            'items.returns',
            'returns'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $order
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders", "Checkout"},
     *     summary="Créer une nouvelle commande",
     *     description="Crée une nouvelle commande à partir du panier actuel avec paiement",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"payment_method", "delivery_address"},
     *             @OA\Property(property="payment_method", type="string", example="stripe", description="Méthode de paiement"),
     *             @OA\Property(
     *                 property="delivery_address",
     *                 type="object",
     *                 required={"street", "city", "postal_code", "country"},
     *                 @OA\Property(property="street", type="string", example="123 Rue de la Paix"),
     *                 @OA\Property(property="street_2", type="string", example="Appartement 4B"),
     *                 @OA\Property(property="city", type="string", example="Paris"),
     *                 @OA\Property(property="postal_code", type="string", example="75001"),
     *                 @OA\Property(property="country", type="string", example="France"),
     *                 @OA\Property(property="phone", type="string", example="+33123456789")
     *             ),
     *             @OA\Property(property="billing_address", type="object", description="Adresse de facturation (optionnelle, utilise l'adresse de livraison par défaut)"),
     *             @OA\Property(property="notes", type="string", example="Livrer après 18h"),
     *             @OA\Property(property="stripe_payment_method_id", type="string", example="pm_1234567890", description="ID de la méthode de paiement Stripe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Commande créée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Commande créée avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/Order"),
     *             @OA\Property(
     *                 property="payment",
     *                 type="object",
     *                 @OA\Property(property="client_secret", type="string", example="pi_1234567890_secret_abc"),
     *                 @OA\Property(property="payment_intent_id", type="string", example="pi_1234567890")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur de validation ou panier vide",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Données de validation invalides",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
     * Créer une nouvelle commande à partir du panier
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'billing_address' => 'required|array',
            'billing_address.name' => 'required|string|max:255',
            'billing_address.address' => 'required|string|max:255',
            'billing_address.city' => 'required|string|max:255',
            'billing_address.postal_code' => 'required|string|max:10',
            'billing_address.country' => 'required|string|max:255',
            'shipping_address' => 'required|array',
            'shipping_address.name' => 'required|string|max:255',
            'shipping_address.address' => 'required|string|max:255',
            'shipping_address.city' => 'required|string|max:255',
            'shipping_address.postal_code' => 'required|string|max:10',
            'shipping_address.country' => 'required|string|max:255',
            'payment_method' => 'required|string|in:card,paypal,bank_transfer',
            'use_profile_addresses' => 'boolean'
        ]);

        // Récupérer le panier de l'utilisateur
        $cart = Auth::user()->getOrCreateActiveCart();
        $cart->load(['items.product.category']);

        if (!$cart || $cart->items->isEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Votre panier est vide'
                ], 422);
            }
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }

        // Vérifier la disponibilité des produits
        foreach ($cart->items as $item) {
            if (!$item->product->is_active) {
                $errorMsg = "Le produit '{$item->product->name}' n'est plus disponible";
                if ($request->expectsJson()) {
                    return response()->json(['status' => 'error', 'message' => $errorMsg], 422);
                }
                return redirect()->route('checkout.index')->with('error', $errorMsg);
            }

            if ($item->product->quantity < $item->quantity) {
                $errorMsg = "Stock insuffisant pour le produit '{$item->product->name}'";
                if ($request->expectsJson()) {
                    return response()->json(['status' => 'error', 'message' => $errorMsg], 422);
                }
                return redirect()->route('checkout.index')->with('error', $errorMsg);
            }
        }

        // Utiliser les adresses du profil si demandé
        if ($validated['use_profile_addresses'] ?? false) {
            $user = Auth::user();
            $validated['billing_address'] = [
                'name' => $user->name,
                'address' => $user->address,
                'city' => $user->city,
                'postal_code' => $user->postal_code,
                'country' => $user->country ?? 'France'
            ];
            $validated['shipping_address'] = $validated['billing_address'];
        }

        DB::beginTransaction();
        try {
            // Créer la commande
            $order = Order::createFromCart(
                $cart,
                $validated['billing_address'],
                $validated['shipping_address'],
                $validated['payment_method']
            );

            // Note: Le stock sera décrémenter seulement après validation du paiement
            // Voir StripeService::handleSuccessfulPayment()

            // Vider le panier
            $cart->items()->delete();
            $cart->calculateTotal();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Commande créée avec succès',
                    'data' => $order->load(['items.product'])
                ], 201);
            }

            // Redirection vers la page de paiement Stripe
            return redirect()->route('payment.stripe', $order)->with('success', 'Commande créée avec succès ! Procédez au paiement.');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erreur lors de la création de la commande: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('checkout.index')->with('error', 'Erreur lors de la création de la commande: ' . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/orders/{order}/cancel",
     *     tags={"Orders"},
     *     summary="Annuler une commande",
     *     description="Annule une commande si elle est éligible à l'annulation",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         description="ID de la commande",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="reason", type="string", maxLength=500, example="Changement d'avis", description="Raison de l'annulation")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commande annulée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Commande annulée avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/Order")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Commande ne peut plus être annulée",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commande non trouvée",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
     * Annuler une commande
     */
    public function cancel(Request $request, Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette commande');
        }

        if (!$order->can_be_cancelled_now) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cette commande ne peut plus être annulée'
                ], 422);
            }
            return redirect()->back()->with('error', 'Cette commande ne peut plus être annulée');
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        try {
            $order->cancel($validated['reason'] ?? null);

            // Restaurer le stock des produits SEULEMENT si la commande était payée
            // (c'est-à-dire si le stock avait été décrémenté)
            if ($order->payment_status === 'paid') {
                foreach ($order->items as $item) {
                    $item->product->increment('quantity', $item->quantity);
                    
                    Log::info('Stock restauré après annulation', [
                        'product_id' => $item->product->id,
                        'product_name' => $item->product->name,
                        'quantity_restored' => $item->quantity,
                        'order_id' => $order->id,
                        'order_number' => $order->order_number
                    ]);
                }
                Log::info('Stock restauré pour commande annulée payée', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ]);
            } else {
                Log::info('Stock non restauré car commande non payée', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'payment_status' => $order->payment_status
                ]);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Commande annulée avec succès',
                    'data' => $order->fresh()
                ]);
            }

            // Rediriger vers une page de confirmation d'annulation
            return redirect()->route('orders.cancelled', $order)->with('success', 'Votre commande a été annulée avec succès');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 422);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Télécharger la facture PDF
     */
    public function downloadInvoice(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé à cette facture');
        }

        // Vérifier que la facture peut être générée
        if (!$order->invoice_number || $order->status === 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'La facture n\'est pas encore disponible'
            ], 422);
        }

        $order->load(['items.product.category', 'user']);

        // La locale est déjà définie par le middleware SetLocale
        // Générer le PDF
        $pdf = Pdf::loadView('invoices.order', compact('order'));
        
        $filename = "facture-{$order->invoice_number}.pdf";
        
        return $pdf->download($filename);
    }

    /**
     * Suivre une commande
     */
    public function track(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé à cette commande');
        }

        $tracking = [
            'order_number' => $order->order_number,
            'status' => $order->status,
            'status_label' => $order->status_label,
            'tracking_number' => $order->tracking_number,
            'shipped_at' => $order->shipped_at,
            'delivered_at' => $order->delivered_at,
            'estimated_delivery' => $order->estimated_delivery,
            'status_history' => $order->status_history,
            'can_be_cancelled' => $order->can_be_cancelled_now,
            'can_be_returned' => $order->can_be_returned_now,
            'return_deadline' => $order->return_deadline,
            'days_until_return_deadline' => $order->days_until_return_deadline
        ];

        return response()->json([
            'status' => 'success',
            'data' => $tracking
        ]);
    }

    /**
     * Afficher toutes les commandes (Admin seulement)
     */
    public function adminIndex(Request $request)
    {
        $query = Order::with(['user', 'items']);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrage par statut de paiement
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filtrage par date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'recent');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'total_asc':
                $query->orderBy('total_amount', 'asc');
                break;
            case 'total_desc':
                $query->orderBy('total_amount', 'desc');
                break;
            default:
                $query->latest();
        }

        $orders = $query->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }

    /**
     * Afficher une commande (Admin)
     */
    public function adminShow(Order $order)
    {
        $order->load([
            'user',
            'items.product.category',
            'items.returns',
            'returns'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $order
        ]);
    }

    /**
     * Mettre à jour le statut d'une commande (Admin seulement)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Valider les transitions de statut
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['preparing', 'cancelled'],
            'preparing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
            'delivered' => [],
            'cancelled' => []
        ];

        if (!in_array($validated['status'], $validTransitions[$order->status])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transition de statut invalide'
            ], 422);
        }

        // Mettre à jour le numéro de suivi si fourni
        if ($validated['tracking_number'] ?? null) {
            $order->update(['tracking_number' => $validated['tracking_number']]);
        }

        // Ajouter des notes si fournies
        if ($validated['notes'] ?? null) {
            $order->update(['notes' => $validated['notes']]);
        }

        // Mettre à jour le statut
        $order->updateStatus($validated['status']);

        return response()->json([
            'status' => 'success',
            'message' => 'Statut de commande mis à jour avec succès',
            'data' => $order->fresh()
        ]);
    }

    /**
     * Statistiques des commandes (Admin seulement)
     */
    public function adminStats(Request $request)
    {
        $period = $request->get('period', '30'); // 30 jours par défaut

        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::pending()->count(),
            'confirmed_orders' => Order::confirmed()->count(),
            'shipped_orders' => Order::shipped()->count(),
            'delivered_orders' => Order::delivered()->count(),
            'cancelled_orders' => Order::cancelled()->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'average_order_value' => Order::where('payment_status', 'paid')->avg('total_amount'),
            'orders_this_month' => Order::whereMonth('created_at', now()->month)
                                       ->whereYear('created_at', now()->year)
                                       ->count(),
            'revenue_this_month' => Order::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->where('payment_status', 'paid')
                                        ->sum('total_amount'),
            'recent_orders' => Order::with(['user', 'items'])
                                   ->latest()
                                   ->take(10)
                                   ->get(),
            'top_customers' => User::withCount('orders')
                                  ->withSum('orders', 'total_amount')
                                  ->orderBy('orders_sum_total_amount', 'desc')
                                  ->take(10)
                                  ->get(),
            'orders_by_status' => Order::selectRaw('status, COUNT(*) as count')
                                      ->groupBy('status')
                                      ->get(),
            'orders_by_day' => Order::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total_amount) as revenue')
                                   ->where('created_at', '>=', now()->subDays($period))
                                   ->groupBy('date')
                                   ->orderBy('date')
                                   ->get(),
            'payment_methods' => Order::selectRaw('payment_method, COUNT(*) as count')
                                     ->groupBy('payment_method')
                                     ->get()
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    /**
     * Exporter les commandes (Admin seulement)
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        // Appliquer les mêmes filtres que l'index admin
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        // Générer CSV ou Excel selon le format demandé
        $format = $request->get('format', 'csv');
        
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.orders', compact('orders'));
            return $pdf->download('commandes-' . now()->format('Y-m-d') . '.pdf');
        }

        // Format CSV par défaut
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="commandes-' . now()->format('Y-m-d') . '.csv"'
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'Numéro commande',
                'Client',
                'Email',
                'Statut',
                'Statut paiement',
                'Total',
                'Date commande',
                'Date livraison'
            ]);

            // Données
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name,
                    $order->user->email,
                    $order->status_label,
                    $order->payment_status_label,
                    $order->formatted_total,
                    $order->created_at->format('d/m/Y H:i'),
                    $order->delivered_at ? $order->delivered_at->format('d/m/Y H:i') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Afficher la page de confirmation de retour
     */
    public function showReturnConfirmation(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette commande');
        }

        // Vérifier que la commande est livrée
        if ($order->status !== 'delivered') {
            return redirect()->route('orders.show', $order)->with('error', 'Seules les commandes livrées peuvent être retournées.');
        }

        // Vérifier que la commande peut être retournée (moins de 14 jours)
        if (!$order->can_be_returned) {
            return redirect()->route('orders.show', $order)->with('error', 'La période de retour de 14 jours est dépassée.');
        }

        // Séparer les articles retournables et non-retournables
        $returnableItems = $order->items->filter(function ($item) {
            return $item->is_returnable;
        });

        $nonReturnableItems = $order->items->filter(function ($item) {
            return !$item->is_returnable;
        });

        // Calculer les montants
        $returnableAmount = $returnableItems->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });

        $nonReturnableAmount = $nonReturnableItems->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });

        return view('orders.return-confirmation', compact(
            'order', 
            'returnableItems', 
            'nonReturnableItems',
            'returnableAmount',
            'nonReturnableAmount'
        ));
    }

    /**
     * Demander un retour de commande
     */
    public function requestReturn(Order $order, Request $request)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette commande');
        }

        // Vérifier que la commande est livrée
        if ($order->status !== 'delivered') {
            return redirect()->route('orders.show', $order)->with('error', 'Seules les commandes livrées peuvent être retournées.');
        }

        // Vérifier que la commande peut être retournée (moins de 14 jours)
        if (!$order->can_be_returned) {
            return redirect()->route('orders.show', $order)->with('error', 'La période de retour de 14 jours est dépassée.');
        }

        // Valider la demande
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Traitement automatique du retour si toutes les conditions sont remplies
            // (moins de 14 jours + produits non-alimentaires uniquement)
            if ($order->has_returnable_items && $order->can_be_returned_now) {
                
                // Marquer la commande comme retournée automatiquement
                $order->update([
                    'status' => 'returned',
                    'return_reason' => $request->reason,
                    'return_requested_at' => now(),
                    'return_processed_at' => now(),
                    'return_processed_by' => 'system_auto',
                ]);

                // Restaurer le stock des produits retournables
                foreach ($order->items as $item) {
                    if ($item->is_returnable) {
                        $item->product->increment('quantity', $item->quantity);
                        
                        Log::info('Stock restauré après retour automatique', [
                            'product_id' => $item->product->id,
                            'product_name' => $item->product->name,
                            'quantity_restored' => $item->quantity,
                            'order_id' => $order->id,
                            'order_number' => $order->order_number
                        ]);
                    }
                }

                // TODO: Déclencher un remboursement automatique via Stripe
                $refundSuccess = $this->stripeService->processAutomaticRefund($order);
                
                if (!$refundSuccess) {
                    Log::warning('Remboursement automatique échoué, traitement manuel requis', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number
                    ]);
                }

                Log::info('Retour automatique traité', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => $order->user_id,
                    'reason' => $request->reason
                ]);

                // Envoyer l'email de confirmation du retour
                try {
                    Mail::to($order->user->email)->send(new OrderReturnConfirmed($order->fresh()));
                    Log::info('Email de retour confirmé envoyé', [
                        'order_number' => $order->order_number,
                        'user_email' => $order->user->email
                    ]);
                } catch (\Exception $e) {
                    Log::error('Erreur lors de l\'envoi de l\'email de confirmation de retour: ' . $e->getMessage());
                }

                // Mettre à jour le statut vers "refunded" (mode pédagogique - remboursement immédiat)
                $order->update([
                    'status' => 'refunded',
                ]);

                // Envoyer immédiatement l'email de confirmation de remboursement (mode pédagogique)
                try {
                    Mail::to($order->user->email)->send(new OrderRefundProcessed($order->fresh()));
                    Log::info('Email de remboursement effectué envoyé', [
                        'order_number' => $order->order_number,
                        'user_email' => $order->user->email
                    ]);
                } catch (\Exception $e) {
                    Log::error('Erreur lors de l\'envoi de l\'email de remboursement: ' . $e->getMessage());
                }

                DB::commit();

                return redirect()->route('orders.show', $order)->with('success', 
                    'Votre retour a été traité automatiquement ! Le stock a été restauré et un remboursement sera effectué sous 3-5 jours ouvrés.');
                
            } else {
                // Cas exceptionnel : demande manuelle (ne devrait pas arriver avec nos conditions)
                $order->update([
                    'status' => 'return_requested',
                    'return_reason' => $request->reason,
                    'return_requested_at' => now(),
                ]);

                Log::info('Demande de retour manuelle créée', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'reason' => $request->reason
                ]);

                DB::commit();

                return redirect()->route('orders.show', $order)->with('success', 
                    'Votre demande de retour a été envoyée. Nous vous recontacterons sous 48h.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du traitement du retour', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('orders.show', $order)->with('error', 
                'Une erreur est survenue lors du traitement de votre retour. Veuillez réessayer.');
        }
    }

    /**
     * Afficher la page de confirmation d'annulation
     */
    public function showCancelled(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette commande');
        }

        // Vérifier que la commande est bien annulée
        if ($order->status !== 'cancelled') {
            return redirect()->route('orders.show', $order);
        }

        $order->load(['items.product', 'user']);

        return view('orders.cancelled', compact('order'));
    }

    /**
     * Récupérer le statut d'une commande pour l'API
     */
    public function getOrderStatus(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Accès non autorisé à cette commande'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'delivered_at' => $order->delivered_at?->toISOString(),
                'has_returnable_items' => $order->has_returnable_items,
                'can_be_cancelled' => $order->can_be_cancelled,
                'invoice_number' => $order->invoice_number,
                'can_be_returned_now' => $order->can_be_returned_now
            ]
        ]);
    }

    /**
     * Traiter un remboursement partiel
     */
    private function processPartialRefund(Order $order, float $amount): bool
    {
        try {
            if (!$order->stripe_payment_intent_id) {
                Log::error('Impossible de rembourser: aucun PaymentIntent trouvé', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ]);
                return false;
            }

            // Créer le remboursement partiel via Stripe
            $refund = \Stripe\Refund::create([
                'payment_intent' => $order->stripe_payment_intent_id,
                'amount' => $this->stripeService->convertToStripeAmount($amount),
                'reason' => 'requested_by_customer',
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'refund_type' => 'partial_return',
                    'user_id' => $order->user_id,
                    'partial_amount' => $amount
                ]
            ]);

            // Mettre à jour la commande
            $order->update([
                'refund_processed' => true,
                'refund_processed_at' => now(),
                'refund_stripe_id' => $refund->id
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors du remboursement partiel', [
                'order_id' => $order->id,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Envoyer l'email de confirmation de retour personnalisé
     */
    private function sendReturnConfirmationEmail(Order $order, $returnableItems, $nonReturnableItems, float $refundAmount)
    {
        try {
            // TODO: Créer et envoyer l'email de confirmation personnalisé
            // Cette fonctionnalité sera implémentée avec un template email dédié
            
            Log::info('Email de confirmation de retour envoyé', [
                'order_id' => $order->id,
                'user_email' => $order->user->email,
                'refund_amount' => $refundAmount,
                'returnable_items_count' => $returnableItems->count(),
                'non_returnable_items_count' => $nonReturnableItems->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de confirmation de retour', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Renouveler une commande en remettant tous ses produits dans le panier
     */
    public function reorder(Request $request, Order $order)
    {
        try {
            $user = Auth::user();
            
            // Vérifier que la commande appartient à l'utilisateur connecté
            if ($order->user_id !== $user->id) {
                return redirect()->route('orders.index')
                    ->with('error', 'Vous n\'êtes pas autorisé à renouveler cette commande.');
            }

            // Vérifier que la commande est dans un état valide pour être renouvelée
            Log::info('Tentative de renouvellement de commande', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'current_status' => $order->status,
                'payment_status' => $order->payment_status
            ]);
            
            if (!in_array($order->status, ['delivered', 'cancelled', 'shipped', 'confirmed', 'preparing'])) {
                return redirect()->route('orders.show', $order)
                    ->with('error', "Cette commande ne peut pas être renouvelée dans son état actuel (statut: {$order->status}).");
            }

            // Récupérer ou créer le panier actif de l'utilisateur
            $cart = $user->getOrCreateActiveCart();
            
            $addedItems = 0;
            $unavailableItems = [];

            // Parcourir tous les items de la commande
            foreach ($order->items as $orderItem) {
                $product = $orderItem->product;
                
                // Vérifier que le produit existe encore et est disponible
                if (!$product || !$product->is_active) {
                    $unavailableItems[] = $orderItem->product_name;
                    continue;
                }

                // Vérifier le stock disponible
                $requestedQuantity = $orderItem->quantity;
                $availableStock = $product->stock_quantity;
                
                if ($availableStock < $requestedQuantity) {
                    // Ajouter la quantité disponible si elle est > 0
                    if ($availableStock > 0) {
                        $cart->items()->updateOrCreate(
                            ['product_id' => $product->id],
                            ['quantity' => \DB::raw("LEAST(quantity + {$availableStock}, {$availableStock})")]
                        );
                        $addedItems++;
                        $unavailableItems[] = "{$orderItem->product_name} (seulement {$availableStock} disponible au lieu de {$requestedQuantity})";
                    } else {
                        $unavailableItems[] = "{$orderItem->product_name} (rupture de stock)";
                    }
                } else {
                    // Ajouter la quantité complète au panier
                    $existingItem = $cart->items()->where('product_id', $product->id)->first();
                    
                    if ($existingItem) {
                        // Augmenter la quantité si le produit est déjà dans le panier
                        $newQuantity = min($existingItem->quantity + $requestedQuantity, $availableStock);
                        $existingItem->update(['quantity' => $newQuantity]);
                    } else {
                        // Créer un nouvel item dans le panier
                        $cart->items()->create([
                            'product_id' => $product->id,
                            'quantity' => $requestedQuantity,
                            'unit_price' => $product->price,
                        ]);
                    }
                    $addedItems++;
                }
            }

            // Préparer le message de retour
            $message = "Commande renouvelée avec succès ! {$addedItems} produit(s) ajouté(s) au panier.";
            
            if (!empty($unavailableItems)) {
                $message .= " Attention : " . implode(', ', $unavailableItems);
            }

            Log::info('Commande renouvelée', [
                'original_order_id' => $order->id,
                'original_order_number' => $order->order_number,
                'user_id' => $user->id,
                'added_items' => $addedItems,
                'unavailable_items' => count($unavailableItems)
            ]);

            return redirect()->route('cart.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Erreur lors du renouvellement de commande', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('orders.show', $order)
                ->with('error', 'Une erreur est survenue lors du renouvellement de la commande.');
        }
    }
}
