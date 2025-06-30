<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturn;
use App\Models\Cart;
use App\Models\Product;
use App\Notifications\OrderConfirmation;
use App\Notifications\OrderStatusChanged;
use App\Notifications\OrderCancellation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Constructor - Middleware d'authentification
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la liste des commandes de l'utilisateur connecté
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour voir vos commandes.');
        }
        
        $query = $user->orders()->with(['items.product']);

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrage par date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $orders = $query->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        }

        return view('orders.index', compact('orders'));
    }

    /**
     * Afficher le formulaire de création d'une nouvelle commande
     */
    public function create()
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with(['product'])->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        return view('orders.create', compact('cartItems'));
    }

    /**
     * Créer une nouvelle commande à partir du panier
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_address' => 'required|string|max:500',
            'shipping_additional' => 'nullable|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_country' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $cartItems = $user->cartItems()->with(['product'])->get();

        if ($cartItems->isEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Votre panier est vide.'
                ], 400);
            }
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        // Calculer les montants
        $subtotal = $cartItems->sum('total_price');
        $shippingCost = $subtotal < 25 ? 2.50 : 0; // Frais de livraison si < 25€
        $taxAmount = 0; // Pas de TVA pour l'instant
        $totalAmount = $subtotal + $shippingCost + $taxAmount;

        // Préparer l'adresse de livraison
        $shippingAddress = [
            'street' => $request->shipping_address,
            'additional_info' => $request->shipping_additional,
            'city' => $request->shipping_city,
            'postal_code' => $request->shipping_postal_code,
            'country' => $request->shipping_country ?: 'France',
        ];

        DB::beginTransaction();

        try {
            // Créer la commande
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'status' => Order::STATUS_CONFIRMED, // Changer en confirmé directement
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'shipping_address' => json_encode($shippingAddress),
                'billing_address' => json_encode($shippingAddress), // Utiliser la même adresse pour la facturation
                'payment_method' => 'stripe', // Méthode par défaut
                'payment_status' => Order::PAYMENT_PENDING,
                'notes' => $request->notes,
            ]);

            // Créer les articles de commande
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;

                // Vérifier le stock
                if ($product->quantity < $cartItem->quantity) {
                    throw new \Exception("Stock insuffisant pour le produit {$product->name}");
                }

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_description' => $product->description,
                    'unit_price' => $cartItem->unit_price,
                    'quantity' => $cartItem->quantity,
                    'total_price' => $cartItem->total_price,
                    'status' => OrderItem::STATUS_PENDING,
                ]);

                // Décrémenter le stock
                $product->decrement('quantity', $cartItem->quantity);
            }

            // Vider le panier
            $user->cartItems()->delete();

            // Envoyer notification email de confirmation
            $this->sendOrderConfirmationEmail($order);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Commande créée avec succès.',
                    'data' => $order->load('items.product')
                ], 201);
            }

            return redirect()->route('orders.user.show', $order)
                ->with('success', 'Votre commande a été créée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la commande: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la création de la commande: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher une commande spécifique
     */
    public function show(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette commande.');
        }

        $order->load(['items.product', 'returns']);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $order
            ]);
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Annuler une commande
     */
    public function cancel(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette commande.');
        }

        if (!$order->canBeCancelled()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette commande ne peut pas être annulée.'
                ], 400);
            }

            return redirect()->back()->with('error', 'Cette commande ne peut pas être annulée.');
        }

        DB::beginTransaction();

        try {
            $order->cancel();

            // Envoyer notification d'annulation
            $this->sendOrderCancellationEmail($order);

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Commande annulée avec succès.'
                ]);
            }

            return redirect()->back()->with('success', 'Votre commande a été annulée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'annulation : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de l\'annulation : ' . $e->getMessage());
        }
    }

    /**
     * Télécharger la facture PDF
     */
    public function downloadInvoice(Order $order)
    {
        // Vérifier l'autorisation : soit le propriétaire de la commande, soit un admin
        if ($order->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé à cette commande.');
        }

        // Vérifier que la commande est confirmée (sauf pour les admins qui peuvent accéder aux factures même annulées)
        if (!Auth::user()->hasRole('admin') && !$order->isConfirmed() && !$order->isProcessing() && !$order->isShipped() && !$order->isDelivered()) {
            abort(400, 'La facture n\'est disponible que pour les commandes confirmées.');
        }

        $order->load(['items.product', 'user']);

        $pdf = Pdf::loadView('orders.invoice', compact('order'));

        return $pdf->download("facture-{$order->order_number}.pdf");
    }

    /**
     * Mettre à jour le statut d'une commande (admin seulement)
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Cette méthode devrait être protégée par un middleware admin
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:' . implode(',', Order::getAllStatuses()),
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->admin_notes = $request->admin_notes;

        // Mettre à jour les timestamps selon le statut
        switch ($request->status) {
            case Order::STATUS_CONFIRMED:
                $order->confirmed_at = Carbon::now();
                break;
            case Order::STATUS_SHIPPED:
                $order->shipped_at = Carbon::now();
                break;
            case Order::STATUS_DELIVERED:
                $order->delivered_at = Carbon::now();
                break;
        }

        $order->save();

        // Envoyer notification de changement de statut
        $this->sendStatusChangeEmail($order, $oldStatus);

        return response()->json([
            'success' => true,
            'message' => 'Statut de la commande mis à jour avec succès.',
            'data' => $order
        ]);
    }

    /**
     * Créer une demande de retour
     */
    public function createReturn(Request $request, Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette commande.');
        }

        $validator = Validator::make($request->all(), [
            'order_item_id' => 'required|exists:order_items,id',
            'quantity_returned' => 'required|integer|min:1',
            'reason' => 'required|in:' . implode(',', OrderReturn::getAllReasons()),
            'description' => 'required|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $orderItem = OrderItem::findOrFail($request->order_item_id);

        // Vérifier que l'article appartient à cette commande
        if ($orderItem->order_id !== $order->id) {
            abort(400, 'Article de commande invalide.');
        }

        // Vérifier si le retour est possible
        if (!$orderItem->canBeReturned()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet article ne peut pas être retourné.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Cet article ne peut pas être retourné.');
        }

        // Vérifier la quantité
        if ($request->quantity_returned > $orderItem->getReturnableQuantity()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantité de retour invalide.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Quantité de retour invalide.');
        }

        DB::beginTransaction();

        try {
            // Traiter les images
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('returns', 'public');
                    $imagePaths[] = $path;
                }
            }

            // Créer le retour
            $return = OrderReturn::create([
                'order_id' => $order->id,
                'order_item_id' => $orderItem->id,
                'user_id' => Auth::id(),
                'quantity_returned' => $request->quantity_returned,
                'reason' => $request->reason,
                'description' => $request->description,
                'status' => OrderReturn::STATUS_PENDING,
                'refund_status' => OrderReturn::REFUND_STATUS_PENDING,
                'images' => $imagePaths,
            ]);

            // Mettre à jour l'article de commande
            $orderItem->quantity_returned += $request->quantity_returned;
            $orderItem->save();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Demande de retour créée avec succès.',
                    'data' => $return
                ], 201);
            }

            return redirect()->back()->with('success', 'Votre demande de retour a été créée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création du retour : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la création du retour : ' . $e->getMessage());
        }
    }

    /**
     * Afficher les retours d'une commande
     */
    public function returns(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette commande.');
        }

        $returns = $order->returns()->get();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $returns
            ]);
        }

        return view('orders.returns', compact('order', 'returns'));
    }

    /**
     * Envoyer email de confirmation de commande
     */
    private function sendOrderConfirmationEmail(Order $order): void
    {
        $order->user->notify(new OrderConfirmation($order));
    }

    /**
     * Envoyer email d'annulation de commande
     */
    private function sendOrderCancellationEmail(Order $order): void
    {
        $order->user->notify(new OrderCancellation($order));
    }

    /**
     * Envoyer email de changement de statut
     */
    private function sendStatusChangeEmail(Order $order, string $oldStatus): void
    {
        $order->user->notify(new OrderStatusChanged($order, $oldStatus));
    }

    /**
     * Afficher la page d'automatisation des statuts (Admin uniquement)
     * Et exécuter l'automatisation si demandé via API
     */
    public function automateStatusUpdates(Request $request)
    {
        // Vérifier les permissions
        if (!Auth::user()->can('manage orders')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        // Si c'est une requête API, exécuter l'automatisation
        if ($request->expectsJson()) {
            $updated = 0;

            // Auto-confirmer les commandes payées depuis plus de 1 heure
            $pendingOrders = Order::where('status', Order::STATUS_PENDING)
                ->where('payment_status', Order::PAYMENT_STATUS_COMPLETED)
                ->where('created_at', '<=', Carbon::now()->subHour())
                ->get();

            foreach ($pendingOrders as $order) {
                $order->update(['status' => Order::STATUS_CONFIRMED, 'confirmed_at' => Carbon::now()]);
                $this->sendStatusChangeEmail($order, Order::STATUS_PENDING);
                $updated++;
            }

            // Auto-expédier les commandes confirmées depuis plus de 2 jours
            $confirmedOrders = Order::where('status', Order::STATUS_CONFIRMED)
                ->where('confirmed_at', '<=', Carbon::now()->subDays(2))
                ->get();

            foreach ($confirmedOrders as $order) {
                $order->update(['status' => Order::STATUS_SHIPPED, 'shipped_at' => Carbon::now()]);
                $this->sendStatusChangeEmail($order, Order::STATUS_CONFIRMED);
                $updated++;
            }

            // Auto-livrer les commandes expédiées depuis plus de 3 jours
            $shippedOrders = Order::where('status', Order::STATUS_SHIPPED)
                ->where('shipped_at', '<=', Carbon::now()->subDays(3))
                ->get();

            foreach ($shippedOrders as $order) {
                $order->update(['status' => Order::STATUS_DELIVERED, 'delivered_at' => Carbon::now()]);
                $this->sendStatusChangeEmail($order, Order::STATUS_SHIPPED);
                $updated++;
            }

            return response()->json([
                'success' => true,
                'message' => "Automatisation terminée. {$updated} commandes mises à jour."
            ]);
        }

        // Pour les requêtes web, afficher la vue d'administration
        return view('admin.orders.automation');
    }

    /**
     * Déclencher manuellement l'automatisation des statuts de commandes (Admin uniquement)
     */
    public function runStatusAutomation(Request $request)
    {
        // Vérifier les permissions
        if (!Auth::user()->can('manage orders')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        try {
            // Exécuter la commande d'automatisation
            $exitCode = \Artisan::call('orders:automate-statuses');
            $output = \Artisan::output();

            if ($exitCode === 0) {
                $message = 'Automatisation des statuts de commandes exécutée avec succès.';
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        'output' => $output
                    ]);
                }
                
                return redirect()->back()->with('success', $message);
            } else {
                $errorMessage = 'Erreur lors de l\'exécution de l\'automatisation.';
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'output' => $output
                    ], 500);
                }
                
                return redirect()->back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            $errorMessage = 'Une erreur est survenue: ' . $e->getMessage();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    /**
     * Annuler une commande par l'utilisateur (avec remboursement automatique si non expédiée)
     */
    public function userCancel(Request $request, Order $order)
    {
        // Vérifier que l'utilisateur est propriétaire de la commande
        if ($order->user_id !== auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas l\'autorisation d\'accéder à cette commande.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation d\'accéder à cette commande.');
        }

        // Vérifier si la commande peut être annulée avec remboursement
        if (!$order->canBeCancelledWithRefund()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette commande ne peut plus être annulée. Elle a déjà été expédiée ou livrée.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Cette commande ne peut plus être annulée. Elle a déjà été expédiée ou livrée.');
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $reason = $request->reason ?? 'Annulation par le client';
            
            if ($order->cancelWithRefund($reason)) {
                // Envoyer notification d'annulation
                if (class_exists(\App\Notifications\OrderCancellation::class)) {
                    $order->user->notify(new \App\Notifications\OrderCancellation($order));
                }

                DB::commit();

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Commande annulée avec succès. Le remboursement sera traité automatiquement.',
                        'data' => $order->fresh()
                    ]);
                }

                return redirect()->route('orders.show', $order)->with('success', 'Commande annulée avec succès. Le remboursement sera traité automatiquement.');
            } else {
                throw new \Exception('Impossible d\'annuler la commande.');
            }

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'annulation : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de l\'annulation : ' . $e->getMessage());
        }
    }

    /**
     * Vérifier si une commande peut être annulée par l'utilisateur
     */
    public function checkCancellationEligibility(Request $request, Order $order)
    {
        // Vérifier que l'utilisateur est propriétaire de la commande
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas l\'autorisation d\'accéder à cette commande.'
            ], 403);
        }

        $canCancel = $order->canBeCancelledWithRefund();
        $reasons = [];

        if (!$canCancel) {
            if ($order->isShipped()) {
                $reasons[] = 'La commande a déjà été expédiée.';
            }
            if ($order->isDelivered()) {
                $reasons[] = 'La commande a déjà été livrée.';
            }
            if ($order->isCancelled()) {
                $reasons[] = 'La commande a déjà été annulée.';
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'can_cancel' => $canCancel,
                'reasons' => $reasons,
                'will_refund' => $canCancel && $order->isPaid(),
                'refund_amount' => $canCancel && $order->isPaid() ? $order->total_amount : 0,
            ]
        ]);
    }
}
