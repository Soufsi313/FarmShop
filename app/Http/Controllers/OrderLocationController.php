<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderLocation;
use App\Models\CartLocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderLocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la liste des commandes de location de l'utilisateur
     */
    public function index()
    {
        $orders = OrderLocation::with(['items.product'])
            ->forUser(Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order-locations.index', compact('orders'));
    }

    /**
     * Afficher les détails d'une commande de location
     */
    public function show(OrderLocation $orderLocation)
    {
        // Vérifier que l'utilisateur peut voir cette commande
        if ($orderLocation->user_id !== Auth::id()) {
            abort(403);
        }

        $orderLocation->load(['items.product', 'cartLocation']);

        return view('order-locations.show', compact('orderLocation'));
    }

    /**
     * Valider le panier de location et créer une commande
     */
    public function createFromCart(Request $request)
    {
        $user = Auth::user();
        
        // Récupérer le panier de location actuel
        $cart = CartLocation::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('items.product')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Votre panier de location est vide'
            ], 400);
        }

        // Valider le panier
        $validation = $cart->validate();
        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation du panier',
                'errors' => $validation['errors']
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Créer la commande de location
            $order = OrderLocation::createFromCart($cart);

            // Marquer le panier comme validé
            $cart->submit();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Commande de location créée avec succès',
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'redirect_url' => route('order-locations.show', $order)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la commande: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Annuler une commande de location (si possible)
     */
    public function cancel(Request $request, OrderLocation $orderLocation)
    {
        // Vérifier que l'utilisateur peut annuler cette commande
        if ($orderLocation->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$orderLocation->can_be_cancelled) {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande ne peut plus être annulée'
            ], 400);
        }

        $reason = $request->input('reason', 'Annulée par l\'utilisateur');
        
        if ($orderLocation->cancel($reason)) {
            return response()->json([
                'success' => true,
                'message' => 'Commande annulée avec succès'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'annulation de la commande'
        ], 500);
    }

    /**
     * Page de validation du panier (avant création de commande)
     */
    public function checkout()
    {
        $user = Auth::user();
        
        // Récupérer le panier de location actuel
        $cart = CartLocation::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('items.product')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart-location.index')
                ->with('error', 'Votre panier de location est vide');
        }

        // Valider le panier
        $validation = $cart->validate();

        return view('order-locations.checkout', compact('cart', 'validation'));
    }

    /**
     * API - Liste des commandes pour l'utilisateur connecté
     */
    public function apiIndex()
    {
        $orders = OrderLocation::with(['items.product'])
            ->forUser(Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'status_label' => $order->status_label,
                    'total_amount' => $order->total_amount,
                    'deposit_amount' => $order->deposit_amount,
                    'rental_start_date' => $order->rental_start_date->format('Y-m-d'),
                    'rental_end_date' => $order->rental_end_date->format('Y-m-d'),
                    'duration_days' => $order->duration_days,
                    'can_be_cancelled' => $order->can_be_cancelled,
                    'is_overdue' => $order->is_overdue,
                    'items_count' => $order->items->count(),
                    'created_at' => $order->created_at->format('Y-m-d H:i:s')
                ];
            })
        ]);
    }

    /**
     * API - Détails d'une commande
     */
    public function apiShow(OrderLocation $orderLocation)
    {
        // Vérifier que l'utilisateur peut voir cette commande
        if ($orderLocation->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }

        $orderLocation->load(['items.product']);

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $orderLocation->id,
                'order_number' => $orderLocation->order_number,
                'status' => $orderLocation->status,
                'status_label' => $orderLocation->status_label,
                'total_amount' => $orderLocation->total_amount,
                'deposit_amount' => $orderLocation->deposit_amount,
                'paid_amount' => $orderLocation->paid_amount,
                'remaining_amount' => $orderLocation->remaining_amount,
                'rental_start_date' => $orderLocation->rental_start_date->format('Y-m-d'),
                'rental_end_date' => $orderLocation->rental_end_date->format('Y-m-d'),
                'duration_days' => $orderLocation->duration_days,
                'can_be_cancelled' => $orderLocation->can_be_cancelled,
                'is_overdue' => $orderLocation->is_overdue,
                'pickup_notes' => $orderLocation->pickup_notes,
                'return_notes' => $orderLocation->return_notes,
                'late_fee' => $orderLocation->late_fee,
                'damage_fee' => $orderLocation->damage_fee,
                'created_at' => $orderLocation->created_at->format('Y-m-d H:i:s'),
                'items' => $orderLocation->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_name' => $item->product_name,
                        'rental_price_per_day' => $item->rental_price_per_day,
                        'deposit_amount' => $item->deposit_amount,
                        'duration_days' => $item->duration_days,
                        'subtotal' => $item->subtotal,
                        'total_with_deposit' => $item->total_with_deposit,
                        'damage_fee' => $item->damage_fee,
                        'late_fee' => $item->late_fee,
                        'final_total' => $item->final_total,
                        'condition_at_pickup' => $item->condition_at_pickup_label,
                        'condition_at_return' => $item->condition_at_return_label,
                        'product' => $item->product ? [
                            'id' => $item->product->id,
                            'name' => $item->product->name,
                            'main_image' => $item->product->main_image
                        ] : null
                    ];
                })
            ]
        ]);
    }

    /**
     * Clôturer une location par le client (le jour de fin de location)
     */
    public function closeLocation(Request $request, OrderLocation $orderLocation)
    {
        // Vérifier que l'utilisateur peut clôturer cette location
        if ($orderLocation->user_id !== Auth::id()) {
            return redirect()->route('order-locations.index')
                ->with('error', 'Accès non autorisé.');
        }

        if (!$orderLocation->can_be_closed_by_client) {
            return redirect()->route('order-locations.show', $orderLocation)
                ->with('error', 'Cette location ne peut pas être clôturée maintenant.');
        }

        $request->validate([
            'client_notes' => 'nullable|string|max:1000'
        ]);

        if ($orderLocation->closeByClient($request->client_notes)) {
            return redirect()->route('order-locations.show', $orderLocation)
                ->with('success', 'Location clôturée avec succès. L\'administrateur procédera à l\'inspection du matériel.');
        }

        return redirect()->route('order-locations.show', $orderLocation)
            ->with('error', 'Erreur lors de la clôture de la location.');
    }

    /**
     * Annuler une location par le client (avant le début)
     */
    public function cancelByClient(Request $request, OrderLocation $orderLocation)
    {
        // Vérifier que l'utilisateur peut annuler cette location
        if ($orderLocation->user_id !== Auth::id()) {
            return redirect()->route('order-locations.index')
                ->with('error', 'Accès non autorisé.');
        }

        if (!$orderLocation->can_be_cancelled_by_client) {
            return redirect()->route('order-locations.show', $orderLocation)
                ->with('error', 'Cette location ne peut plus être annulée.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        if ($orderLocation->cancelByClient($request->reason)) {
            return redirect()->route('order-locations.show', $orderLocation)
                ->with('success', 'Location annulée avec succès.');
        }

        return redirect()->route('order-locations.show', $orderLocation)
            ->with('error', 'Erreur lors de l\'annulation de la location.');
    }
}
