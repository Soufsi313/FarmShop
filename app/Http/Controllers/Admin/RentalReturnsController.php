<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RentalReturnsController extends Controller
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
     * Afficher la liste des retours de location
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

        // Filtrage par statut d'inspection
        if ($request->filled('inspection_status')) {
            $query->where('inspection_status', $request->inspection_status);
        }

        // Filtrage par statut de retour
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Par défaut, afficher seulement les retours (completed, closed, inspecting, finished)
            $query->whereIn('status', ['completed', 'closed', 'inspecting', 'finished']);
        }

        // Filtrage par dates de retour
        if ($request->filled('return_date_from')) {
            $query->where('end_date', '>=', $request->return_date_from);
        }

        if ($request->filled('return_date_to')) {
            $query->where('end_date', '<=', $request->return_date_to);
        }

        // Tri par défaut par date de fin décroissante
        $orderLocations = $query->orderBy('end_date', 'desc')->paginate(20);

        // Calculer les statistiques
        $stats = [
            'total_returns' => OrderLocation::whereIn('status', ['completed', 'closed', 'inspecting', 'finished'])->count(),
            'pending_inspection' => OrderLocation::where('status', 'closed')->count(),
            'in_inspection' => OrderLocation::where('status', 'inspecting')->count(),
            'completed_inspection' => OrderLocation::where('status', 'finished')->count(),
            'awaiting_return' => OrderLocation::where('status', 'completed')->count(),
            'overdue_returns' => OrderLocation::where('status', 'active')
                ->where('end_date', '<', now())
                ->count(),
            'total_penalties' => OrderLocation::where('status', 'finished')->sum('penalty_amount'),
            'total_damage_costs' => OrderItemLocation::sum('item_damage_cost')
        ];

        return view('admin.rental-returns.index', compact('orderLocations', 'stats'));
    }

    /**
     * Afficher les détails d'un retour de location
     */
    public function show(OrderLocation $orderLocation)
    {
        $this->checkAdminAccess();

        // Charger toutes les relations nécessaires
        $orderLocation->load([
            'user',
            'orderItemLocations.product'
        ]);

        return view('admin.rental-returns.show', compact('orderLocation'));
    }

    /**
     * Démarrer l'inspection d'un retour
     */
    public function startInspection(OrderLocation $orderLocation)
    {
        $this->checkAdminAccess();

        if ($orderLocation->status !== 'closed') {
            return redirect()->back()->with('error', 'Cette location ne peut pas être inspectée dans son état actuel.');
        }

        $orderLocation->update([
            'status' => 'inspecting',
            'inspection_status' => 'in_progress',
            'inspection_started_at' => now()
        ]);

        return redirect()->back()->with('success', 'Inspection démarrée avec succès.');
    }

    /**
     * Finaliser l'inspection
     */
    public function finishInspection(OrderLocation $orderLocation, Request $request)
    {
        $this->checkAdminAccess();

        $request->validate([
            'items' => 'required|array',
            'items.*.condition_at_return' => 'required|in:excellent,good,poor',
            'items.*.item_inspection_notes' => 'nullable|string|max:1000',
            'items.*.item_damage_cost' => 'nullable|numeric|min:0|max:999999.99',
            'general_notes' => 'nullable|string|max:2000'
        ]);

        DB::beginTransaction();
        try {
            $totalDamageCosts = 0;

            // Mettre à jour chaque item avec les résultats de l'inspection
            foreach ($request->items as $itemId => $itemData) {
                $orderItem = $orderLocation->orderItemLocations()->findOrFail($itemId);
                
                $damageCost = floatval($itemData['item_damage_cost'] ?? 0);
                $totalDamageCosts += $damageCost;

                $orderItem->update([
                    'condition_at_return' => $itemData['condition_at_return'],
                    'item_inspection_notes' => $itemData['item_inspection_notes'] ?? null,
                    'item_damage_cost' => $damageCost
                ]);
            }

            // Calculer le remboursement de caution
            $depositRefund = max(0, $orderLocation->deposit_amount - $totalDamageCosts);

            // Finaliser la location
            $orderLocation->update([
                'status' => 'finished',
                'inspection_status' => 'completed',
                'inspection_finished_at' => now(),
                'penalty_amount' => $totalDamageCosts,
                'deposit_refund' => $depositRefund,
                'inspection_notes' => $request->general_notes
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Inspection terminée avec succès. Remboursement de caution: ' . number_format($depositRefund, 2) . '€');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de la finalisation de l\'inspection: ' . $e->getMessage());
        }
    }

    /**
     * Marquer un retour comme reçu (passage de completed à closed)
     */
    public function markAsReturned(OrderLocation $orderLocation)
    {
        $this->checkAdminAccess();

        if ($orderLocation->status !== 'completed') {
            return redirect()->back()->with('error', 'Cette location ne peut pas être marquée comme retournée.');
        }

        $orderLocation->update([
            'status' => 'closed',
            'actual_return_date' => now()
        ]);

        return redirect()->back()->with('success', 'Retour confirmé. Prêt pour inspection.');
    }

    /**
     * Exporter les données des retours
     */
    public function export(Request $request)
    {
        $this->checkAdminAccess();

        $query = OrderLocation::with(['user', 'orderItemLocations.product'])
            ->whereIn('status', ['completed', 'closed', 'inspecting', 'finished']);

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('inspection_status')) {
            $query->where('inspection_status', $request->inspection_status);
        }

        $returns = $query->orderBy('end_date', 'desc')->get();

        $data = $returns->map(function ($return) {
            return [
                'Numéro Commande' => $return->order_number,
                'Client' => $return->user->name,
                'Email' => $return->user->email,
                'Statut' => $return->status,
                'Statut Inspection' => $return->inspection_status ?? 'N/A',
                'Date Fin Location' => $return->end_date,
                'Date Retour Effectif' => $return->actual_return_date ?? 'N/A',
                'Dépôt Initial' => number_format($return->deposit_amount, 2),
                'Coûts Dégâts' => number_format($return->penalty_amount ?? 0, 2),
                'Remboursement' => number_format($return->deposit_refund ?? 0, 2),
                'Notes Inspection' => $return->inspection_notes ?? ''
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'filename' => 'retours_location_' . now()->format('Y-m-d') . '.csv'
        ]);
    }
}
