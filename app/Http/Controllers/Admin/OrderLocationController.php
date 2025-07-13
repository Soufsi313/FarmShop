<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderLocationController extends Controller
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
     * Afficher la liste des commandes de location (Admin)
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();

        $query = OrderLocation::with(['user', 'orderItemLocations.product']);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
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

        // Filtrage par dates de location
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        // Filtrage par date de création
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
            case 'start_date':
                $query->orderBy('start_date', 'asc');
                break;
            case 'end_date':
                $query->orderBy('end_date', 'asc');
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
            'total_orders' => OrderLocation::count(),
            'active_rentals' => OrderLocation::where('status', 'active')->count(),
            'pending_returns' => OrderLocation::where('status', 'pending_return')->count(),
            'revenue_month' => OrderLocation::whereMonth('created_at', now()->month)
                                            ->sum('total_amount'),
        ];

        return view('admin.order-locations.index', compact('orders', 'stats'));
    }

    /**
     * Afficher le détail d'une commande de location
     */
    public function show(OrderLocation $orderLocation)
    {
        $this->checkAdminAccess();

        $orderLocation->load([
            'user',
            'orderItemLocations.product.category',
        ]);

        return view('admin.order-locations.show', compact('orderLocation'));
    }

    /**
     * Mettre à jour le statut d'une commande de location
     */
    public function updateStatus(Request $request, OrderLocation $orderLocation)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,active,pending_return,returned,cancelled',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Valider les transitions de statut
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['active', 'cancelled'],
            'active' => ['pending_return', 'cancelled'],
            'pending_return' => ['returned'],
            'returned' => [],
            'cancelled' => []
        ];

        if (!in_array($validated['status'], $validTransitions[$orderLocation->status])) {
            return redirect()->back()->with('error', 'Transition de statut invalide.');
        }

        $orderLocation->update($validated);

        return redirect()->back()->with('success', 'Statut de la location mis à jour avec succès.');
    }

    /**
     * Supprimer une commande de location (soft delete)
     */
    public function destroy(OrderLocation $orderLocation)
    {
        $this->checkAdminAccess();

        if ($orderLocation->status === 'active' || $orderLocation->status === 'pending_return') {
            return redirect()->back()->with('error', 'Impossible de supprimer une location active ou en attente de retour.');
        }

        $orderLocation->delete();

        return redirect()->route('admin.order-locations.index')->with('success', 'Commande de location supprimée avec succès.');
    }
}
