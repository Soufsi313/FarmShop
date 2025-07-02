<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderLocationAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Afficher toutes les commandes de location
     */
    public function index(Request $request)
    {
        $query = OrderLocation::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc');

        // Filtres - s'assurer que les valeurs ne sont pas vides ou null
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('rental_start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('rental_end_date', '<=', $request->date_to);
        }

        $orders = $query->paginate(20);

        // Statistiques
        $stats = [
            'total' => OrderLocation::count(),
            'pending' => OrderLocation::where('status', 'pending')->count(),
            'confirmed' => OrderLocation::where('status', 'confirmed')->count(),
            'active' => OrderLocation::where('status', 'active')->count(),
            'completed' => OrderLocation::where('status', 'completed')->count(),
            'overdue' => OrderLocation::where('status', 'overdue')->count(),
            'cancelled' => OrderLocation::where('status', 'cancelled')->count(),
        ];

        return view('admin.order-locations.index', compact('orders', 'stats'));
    }

    /**
     * Afficher une commande spécifique
     */
    public function show(OrderLocation $orderLocation)
    {
        $orderLocation->load(['user', 'items.product']);
        
        return view('admin.order-locations.show', compact('orderLocation'));
    }

    /**
     * Afficher la page d'inspection avant récupération
     */
    public function showPickup(OrderLocation $orderLocation)
    {
        // Vérifier que la commande peut être récupérée
        if ($orderLocation->status !== 'confirmed') {
            return redirect()->route('admin.locations.show', $orderLocation)
                ->with('error', 'Cette commande n\'est pas confirmée et ne peut pas être récupérée.');
        }

        if (!$orderLocation->can_be_picked_up) {
            return redirect()->route('admin.locations.show', $orderLocation)
                ->with('error', 'Cette commande ne peut pas être récupérée aujourd\'hui.');
        }

        $orderLocation->load(['user', 'items.product']);
        
        return view('admin.order-locations.pickup', compact('orderLocation'));
    }

    /**
     * Confirmer une commande de location
     */
    public function confirm(Request $request, OrderLocation $orderLocation)
    {
        if ($orderLocation->status !== 'pending') {
            return redirect()->route('admin.locations.show', $orderLocation)
                ->with('error', 'Cette commande ne peut pas être confirmée.');
        }

        if ($orderLocation->confirm()) {
            return redirect()->route('admin.locations.show', $orderLocation)
                ->with('success', 'Commande confirmée avec succès.');
        }

        return redirect()->route('admin.locations.show', $orderLocation)
            ->with('error', 'Erreur lors de la confirmation.');
    }

    /**
     * Marquer une commande comme récupérée
     */
    public function markAsPickedUp(Request $request, OrderLocation $orderLocation)
    {
        $request->validate([
            'pickup_notes' => 'nullable|string|max:1000',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:order_item_locations,id',
            'items.*.condition' => 'required|in:excellent,good,fair,poor',
            'items.*.notes' => 'nullable|string|max:500'
        ]);

        if ($orderLocation->status !== 'confirmed') {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette commande n\'est pas prête pour la récupération'
                ], 400);
            }
            
            return redirect()->route('admin.locations.show', $orderLocation)
                ->with('error', 'Cette commande n\'est pas prête pour la récupération.');
        }

        try {
            DB::beginTransaction();

            // Marquer la commande comme récupérée
            $orderLocation->update([
                'pickup_notes' => $request->pickup_notes
            ]);
            
            if ($orderLocation->markAsPickedUp()) {
                // Enregistrer l'état de chaque article à la récupération
                foreach ($request->items as $itemData) {
                    $item = OrderItemLocation::find($itemData['id']);
                    if ($item && $item->order_location_id === $orderLocation->id) {
                        $item->recordPickupCondition(
                            $itemData['condition'],
                            $itemData['notes'] ?? null
                        );
                    }
                }

                DB::commit();
                
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Commande marquée comme récupérée'
                    ]);
                }
                
                return redirect()->route('admin.locations.show', $orderLocation)
                    ->with('success', 'La location a été activée avec succès. Le matériel a été remis au client.');
            }

            DB::rollBack();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du marquage'
                ], 500);
            }
            
            return redirect()->route('admin.locations.pickup.show', $orderLocation)
                ->with('error', 'Erreur lors du marquage de la récupération.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.locations.pickup.show', $orderLocation)
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Afficher la page d'inspection avant retour
     */
    public function showReturn(OrderLocation $orderLocation)
    {
        // Vérifier que la commande peut être retournée
        if (!in_array($orderLocation->status, ['active', 'pending_inspection'])) {
            return redirect()->route('admin.locations.show', $orderLocation)
                ->with('error', 'Cette commande ne peut pas être inspectée pour retour.');
        }

        $orderLocation->load(['user', 'items.product']);
        
        // Utiliser les méthodes du modèle pour calculer le retard
        $isOverdue = $orderLocation->is_overdue;
        $daysLate = $orderLocation->days_late;
        
        return view('admin.order-locations.return', compact('orderLocation', 'isOverdue', 'daysLate'));
    }

    /**
     * Marquer une commande comme retournée
     */
    public function markAsReturned(Request $request, OrderLocation $orderLocation)
    {
        // Validation conditionnelle : items requis seulement si la commande a des articles
        $hasItems = $orderLocation->items()->count() > 0;
        
        $validationRules = [
            'return_notes' => 'nullable|string|max:1000',
            'late_fee' => 'nullable|numeric|min:0|max:9999.99'
        ];
        
        // Ajouter les règles pour les articles uniquement s'il y en a
        if ($hasItems) {
            $validationRules['items'] = 'required|array';
            $validationRules['items.*.id'] = 'required|exists:order_item_locations,id';
            $validationRules['items.*.condition'] = 'required|in:excellent,good,fair,poor';
            $validationRules['items.*.notes'] = 'nullable|string|max:500';
            $validationRules['items.*.damage_fee'] = 'nullable|numeric|min:0|max:9999.99';
        } else {
            // Si pas d'articles, permettre quand même des frais de dégâts généraux
            $validationRules['general_damage_fee'] = 'nullable|numeric|min:0|max:9999.99';
        }
        
        $request->validate($validationRules);

        if (!in_array($orderLocation->status, ['active', 'pending_inspection'])) {
            return redirect()->route('admin.locations.show', $orderLocation)
                ->with('error', 'Cette commande ne peut pas être inspectée pour retour.');
        }

        try {
            DB::beginTransaction();

            // Calculer les frais de retard si applicable
            $lateFee = $request->late_fee ?? 0;
            if ($orderLocation->is_overdue && $lateFee === 0) {
                // Calculer automatiquement les frais de retard basés sur le nouveau calcul
                $daysLate = $orderLocation->days_late;
                $lateFee = $daysLate * 10; // 10€ par jour de retard par défaut
            }

            // Calculer le total des frais de dégâts
            $totalDamageFee = 0;
            
            if ($hasItems && $request->has('items')) {
                foreach ($request->items as $itemData) {
                    $totalDamageFee += $itemData['damage_fee'] ?? 0;
                }
            } else {
                // Si pas d'articles, utiliser les frais de dégâts généraux s'ils sont spécifiés
                $totalDamageFee = $request->general_damage_fee ?? 0;
            }

            // Marquer la commande comme retournée
            $orderLocation->update([
                'return_notes' => $request->return_notes,
                'late_fee' => $lateFee,
                'damage_fee' => $totalDamageFee
            ]);
            
            if ($orderLocation->markAsReturned($request->return_notes)) {
                // Enregistrer l'état de chaque article au retour (seulement s'il y a des articles)
                if ($hasItems && $request->has('items')) {
                    foreach ($request->items as $itemData) {
                        $item = OrderItemLocation::find($itemData['id']);
                        if ($item && $item->order_location_id === $orderLocation->id) {
                            $item->recordReturnCondition(
                                $itemData['condition'],
                                $itemData['notes'] ?? null,
                                $itemData['damage_fee'] ?? 0
                            );
                        }
                    }
                }

                // Calculer et traiter automatiquement le remboursement de caution
                $totalPenalties = $lateFee + $totalDamageFee;
                $refundAmount = max(0, $orderLocation->deposit_amount - $totalPenalties);
                
                $orderLocation->update([
                    'total_penalties' => $totalPenalties,
                    'deposit_refund_amount' => $refundAmount,
                    'deposit_refunded_at' => now(),
                    'refund_notes' => "Remboursement automatique après inspection. Caution: {$orderLocation->deposit_amount}€, Pénalités: {$totalPenalties}€, Remboursé: {$refundAmount}€",
                    'status' => 'completed'
                ]);

                DB::commit();
                
                $message = "Commande marquée comme retournée avec succès. ";
                $message .= "Caution de {$orderLocation->deposit_amount}€ : ";
                if ($totalPenalties > 0) {
                    $message .= "Pénalités de {$totalPenalties}€ déduites, ";
                }
                $message .= "montant remboursé : {$refundAmount}€.";
                
                return redirect()->route('admin.locations.show', $orderLocation)
                    ->with('success', $message);
            }

            DB::rollBack();
            return redirect()->route('admin.locations.return.show', $orderLocation)
                ->with('error', 'Erreur lors du marquage du retour.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.locations.return.show', $orderLocation)
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Annuler une commande de location
     */
    public function cancel(Request $request, OrderLocation $orderLocation)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        if (!$orderLocation->can_be_cancelled) {
            return redirect()->route('admin.locations.show', $orderLocation)
                ->with('error', 'Cette commande ne peut pas être annulée.');
        }

        if ($orderLocation->cancel($request->reason)) {
            return redirect()->route('admin.locations.show', $orderLocation)
                ->with('success', 'Commande annulée avec succès.');
        }

        return redirect()->route('admin.locations.show', $orderLocation)
            ->with('error', 'Erreur lors de l\'annulation.');
    }

    /**
     * Marquer une commande comme en retard
     */
    public function markAsOverdue(OrderLocation $orderLocation)
    {
        if ($orderLocation->markAsOverdue()) {
            return redirect()->route('admin.locations.show', $orderLocation)
                ->with('success', 'Commande marquée comme en retard.');
        }

        return redirect()->route('admin.locations.show', $orderLocation)
            ->with('error', 'Erreur lors du marquage en retard.');
    }

    /**
     * Tableau de bord des locations
     */
    public function dashboard()
    {
        $stats = [
            'total_orders' => OrderLocation::count(),
            'pending_orders' => OrderLocation::where('status', 'pending')->count(),
            'confirmed_orders' => OrderLocation::where('status', 'confirmed')->count(),
            'active_orders' => OrderLocation::where('status', 'active')->count(),
            'pending_inspection_orders' => OrderLocation::where('status', 'pending_inspection')->count(),
            'returned_orders' => OrderLocation::where('status', 'returned')->count(),
            'overdue_orders' => OrderLocation::overdue()->count(),
            'today_pickups' => OrderLocation::where('status', 'confirmed')
                ->whereDate('rental_start_date', today())->count(),
            'today_returns' => OrderLocation::where('status', 'active')
                ->whereDate('rental_end_date', today())->count(),
        ];

        // Commandes récentes
        $recentOrders = OrderLocation::with(['user', 'items'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Commandes en retard
        $overdueOrders = OrderLocation::overdue()
            ->with(['user', 'items'])
            ->orderBy('rental_end_date', 'asc')
            ->take(10)
            ->get();

        // Récupérations du jour
        $todayPickups = OrderLocation::where('status', 'confirmed')
            ->whereDate('rental_start_date', today())
            ->with(['user', 'items'])
            ->get();

        // Retours du jour
        $todayReturns = OrderLocation::where('status', 'active')
            ->whereDate('rental_end_date', today())
            ->with(['user', 'items'])
            ->get();

        // Locations en attente d'inspection (nouvelles)
        $pendingInspections = OrderLocation::where('status', 'pending_inspection')
            ->with(['user', 'items'])
            ->orderBy('client_return_date', 'asc')
            ->get();

        return view('admin.order-locations.dashboard', compact(
            'stats', 'recentOrders', 'overdueOrders', 'todayPickups', 'todayReturns', 'pendingInspections'
        ));
    }

    /**
     * Export des données de location
     */
    public function export(Request $request)
    {
        // Implémentation de l'export CSV/Excel si nécessaire
        // Pour l'instant, on peut retourner une réponse simple
        
        return response()->json([
            'success' => false,
            'message' => 'Export non implémenté pour le moment'
        ]);
    }
}
