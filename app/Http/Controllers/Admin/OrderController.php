<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Vérifier que l'utilisateur est admin avant chaque action
     */
    private function checkAdminAccess()
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent accéder au dashboard.');
        }
    }

    /**
     * Afficher la liste des commandes d'achat (Admin)
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();

        $query = Order::with(['user', 'items.product.category']);

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

        // Statistiques pour le dashboard
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'revenue_today' => Order::whereDate('created_at', today())
                                   ->where('payment_status', 'paid')
                                   ->sum('total_amount'),
            'revenue_month' => Order::whereMonth('created_at', now()->month)
                                   ->where('payment_status', 'paid')
                                   ->sum('total_amount'),
            'total_revenue' => Order::where('payment_status', 'paid')
                                   ->sum('total_amount'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Afficher le détail d'une commande d'achat
     */
    public function show(Order $order)
    {
        $this->checkAdminAccess();

        $order->load([
            'user',
            'items.product.category',
            'items.returns',
            'returns'
        ]);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatus(Request $request, Order $order)
    {
        $this->checkAdminAccess();

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
            return redirect()->back()->with('error', 'Transition de statut invalide.');
        }

        $order->update($validated);

        return redirect()->back()->with('success', 'Statut de la commande mis à jour avec succès.');
    }

    /**
     * Annuler une commande
     */
    public function cancel(Order $order)
    {
        $this->checkAdminAccess();

        // Vérifier que la commande peut être annulée
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'Cette commande ne peut pas être annulée dans son état actuel.');
        }

        // Mettre à jour le statut de la commande
        $order->update([
            'status' => 'cancelled'
        ]);

        return redirect()->back()->with('success', 'Commande annulée avec succès.');
    }

    /**
     * Supprimer une commande (soft delete)
     */
    public function destroy(Order $order)
    {
        $this->checkAdminAccess();

        if ($order->status === 'delivered' || $order->payment_status === 'paid') {
            return redirect()->back()->with('error', 'Impossible de supprimer une commande livrée ou payée.');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Commande supprimée avec succès.');
    }
}
