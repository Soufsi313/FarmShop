<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
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
     * Afficher les commandes de l'utilisateur connecté (API)
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

            if ($item->product->stock < $item->quantity) {
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

            // Décrémenter le stock des produits
            foreach ($cart->items as $item) {
                $item->product->decrement('stock', $item->quantity);
            }

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

            // Redirection vers la page de confirmation de commande
            return redirect()->route('orders.show', $order)->with('success', 'Commande créée avec succès !');

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
     * Annuler une commande
     */
    public function cancel(Request $request, Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette commande');
        }

        if (!$order->can_be_cancelled_now) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cette commande ne peut plus être annulée'
            ], 422);
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        try {
            $order->cancel($validated['reason'] ?? null);

            // Restaurer le stock des produits
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Commande annulée avec succès',
                'data' => $order->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
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

        $order->load(['items.product', 'user']);

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
}
