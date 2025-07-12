<?php

namespace App\Http\Controllers;

use App\Models\OrderItemLocation;
use App\Models\OrderLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderItemLocationController extends Controller
{
    /**
     * Afficher la liste des éléments d'une commande de location
     */
    public function index(OrderLocation $orderLocation)
    {
        // Vérifier les permissions
        if (!Auth::user()->isAdmin() && $orderLocation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $orderItems = $orderLocation->orderItemLocations()
            ->with(['product', 'product.media'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orderItems
        ]);
    }

    /**
     * Afficher les détails d'un élément de commande de location
     */
    public function show(OrderLocation $orderLocation, OrderItemLocation $orderItemLocation)
    {
        // Vérifier que l'item appartient à la commande
        if ($orderItemLocation->order_location_id !== $orderLocation->id) {
            return response()->json(['error' => 'Élément non trouvé'], 404);
        }

        // Vérifier les permissions
        if (!Auth::user()->isAdmin() && $orderLocation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $orderItemLocation->load(['product', 'product.media']);

        return response()->json([
            'success' => true,
            'data' => $orderItemLocation
        ]);
    }

    /**
     * Mettre à jour la condition de récupération d'un élément (Admin seulement)
     */
    public function updatePickupCondition(OrderLocation $orderLocation, OrderItemLocation $orderItemLocation, Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Vérifier que l'item appartient à la commande
        if ($orderItemLocation->order_location_id !== $orderLocation->id) {
            return response()->json(['error' => 'Élément non trouvé'], 404);
        }

        // Vérifier que la commande est confirmée ou active
        if (!in_array($orderLocation->status, ['confirmed', 'active'])) {
            return response()->json(['error' => 'La condition ne peut être mise à jour que pour des commandes confirmées ou actives'], 400);
        }

        $request->validate([
            'pickup_condition' => 'required|in:good,damaged,missing',
            'pickup_notes' => 'nullable|string|max:500'
        ]);

        $orderItemLocation->update([
            'pickup_condition' => $request->pickup_condition,
            'pickup_notes' => $request->pickup_notes,
            'pickup_checked_at' => now(),
            'pickup_checked_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Condition de récupération mise à jour',
            'data' => $orderItemLocation->fresh()
        ]);
    }

    /**
     * Mettre à jour la condition de retour d'un élément (Inspection - Admin seulement)
     */
    public function updateReturnCondition(OrderLocation $orderLocation, OrderItemLocation $orderItemLocation, Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Vérifier que l'item appartient à la commande
        if ($orderItemLocation->order_location_id !== $orderLocation->id) {
            return response()->json(['error' => 'Élément non trouvé'], 404);
        }

        // Vérifier que la commande est en inspection
        if ($orderLocation->status !== 'inspecting') {
            return response()->json(['error' => 'La condition de retour ne peut être mise à jour qu\'en cours d\'inspection'], 400);
        }

        $request->validate([
            'return_condition' => 'required|in:good,damaged,lost',
            'return_notes' => 'nullable|string|max:500',
            'damage_cost' => 'nullable|numeric|min:0'
        ]);

        $orderItemLocation->update([
            'return_condition' => $request->return_condition,
            'return_notes' => $request->return_notes,
            'damage_cost' => $request->damage_cost ?? 0,
            'return_checked_at' => now(),
            'return_checked_by' => Auth::id()
        ]);

        // Recalculer les pénalités pour cet item
        $penalties = $orderItemLocation->calculatePenalties();
        $orderItemLocation->update(['penalty_amount' => $penalties]);

        return response()->json([
            'success' => true,
            'message' => 'Condition de retour mise à jour',
            'data' => $orderItemLocation->fresh()
        ]);
    }

    /**
     * Obtenir un résumé des conditions d'un élément
     */
    public function getConditionSummary(OrderLocation $orderLocation, OrderItemLocation $orderItemLocation)
    {
        // Vérifier que l'item appartient à la commande
        if ($orderItemLocation->order_location_id !== $orderLocation->id) {
            return response()->json(['error' => 'Élément non trouvé'], 404);
        }

        // Vérifier les permissions
        if (!Auth::user()->isAdmin() && $orderLocation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $summary = [
            'product_name' => $orderItemLocation->product_name,
            'quantity' => $orderItemLocation->quantity,
            'pickup' => [
                'condition' => $orderItemLocation->pickup_condition,
                'notes' => $orderItemLocation->pickup_notes,
                'checked_at' => $orderItemLocation->pickup_checked_at,
                'checked_by' => $orderItemLocation->pickup_checked_by
            ],
            'return' => [
                'condition' => $orderItemLocation->return_condition,
                'notes' => $orderItemLocation->return_notes,
                'damage_cost' => $orderItemLocation->damage_cost,
                'penalty_amount' => $orderItemLocation->penalty_amount,
                'checked_at' => $orderItemLocation->return_checked_at,
                'checked_by' => $orderItemLocation->return_checked_by
            ],
            'rental_period' => [
                'planned_days' => $orderItemLocation->total_days,
                'actual_days' => $orderLocation->getActualRentalDays(),
                'late_days' => max(0, $orderLocation->getActualRentalDays() - $orderItemLocation->total_days),
                'daily_price' => $orderItemLocation->daily_price
            ],
            'financial' => [
                'subtotal' => $orderItemLocation->subtotal,
                'deposit_amount' => $orderItemLocation->deposit_amount,
                'penalty_amount' => $orderItemLocation->penalty_amount,
                'total_penalties' => $orderItemLocation->calculatePenalties()
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    /**
     * Obtenir l'historique des modifications d'un élément
     */
    public function getHistory(OrderLocation $orderLocation, OrderItemLocation $orderItemLocation)
    {
        // Vérifier que l'item appartient à la commande
        if ($orderItemLocation->order_location_id !== $orderLocation->id) {
            return response()->json(['error' => 'Élément non trouvé'], 404);
        }

        // Vérifier les permissions (Admin seulement pour l'historique détaillé)
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $history = [];

        // Événement de création
        $history[] = [
            'event' => 'created',
            'description' => 'Élément ajouté à la commande',
            'timestamp' => $orderItemLocation->created_at,
            'user' => null,
            'data' => [
                'quantity' => $orderItemLocation->quantity,
                'daily_price' => $orderItemLocation->daily_price,
                'total_days' => $orderItemLocation->total_days
            ]
        ];

        // Vérification à la récupération
        if ($orderItemLocation->pickup_checked_at) {
            $history[] = [
                'event' => 'pickup_checked',
                'description' => 'Condition vérifiée à la récupération',
                'timestamp' => $orderItemLocation->pickup_checked_at,
                'user' => $orderItemLocation->pickup_checked_by,
                'data' => [
                    'condition' => $orderItemLocation->pickup_condition,
                    'notes' => $orderItemLocation->pickup_notes
                ]
            ];
        }

        // Vérification au retour
        if ($orderItemLocation->return_checked_at) {
            $history[] = [
                'event' => 'return_checked',
                'description' => 'Condition vérifiée au retour',
                'timestamp' => $orderItemLocation->return_checked_at,
                'user' => $orderItemLocation->return_checked_by,
                'data' => [
                    'condition' => $orderItemLocation->return_condition,
                    'notes' => $orderItemLocation->return_notes,
                    'damage_cost' => $orderItemLocation->damage_cost,
                    'penalty_amount' => $orderItemLocation->penalty_amount
                ]
            ];
        }

        // Trier par timestamp
        usort($history, function ($a, $b) {
            return $a['timestamp'] <=> $b['timestamp'];
        });

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * Calculer les pénalités actuelles pour un élément
     */
    public function calculatePenalties(OrderLocation $orderLocation, OrderItemLocation $orderItemLocation)
    {
        // Vérifier que l'item appartient à la commande
        if ($orderItemLocation->order_location_id !== $orderLocation->id) {
            return response()->json(['error' => 'Élément non trouvé'], 404);
        }

        // Vérifier les permissions
        if (!Auth::user()->isAdmin() && $orderLocation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $penalties = $orderItemLocation->calculatePenalties();
        $breakdown = $orderItemLocation->getPenaltyBreakdown();

        return response()->json([
            'success' => true,
            'data' => [
                'total_penalties' => $penalties,
                'breakdown' => $breakdown,
                'current_penalty_amount' => $orderItemLocation->penalty_amount
            ]
        ]);
    }

    /**
     * Mettre à jour manuellement les pénalités (Admin seulement)
     */
    public function updatePenalties(OrderLocation $orderLocation, OrderItemLocation $orderItemLocation, Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Vérifier que l'item appartient à la commande
        if ($orderItemLocation->order_location_id !== $orderLocation->id) {
            return response()->json(['error' => 'Élément non trouvé'], 404);
        }

        $request->validate([
            'penalty_amount' => 'required|numeric|min:0',
            'penalty_reason' => 'nullable|string|max:500'
        ]);

        $orderItemLocation->update([
            'penalty_amount' => $request->penalty_amount,
            'penalty_reason' => $request->penalty_reason
        ]);

        // Recalculer le total des pénalités de la commande
        $totalPenalties = $orderLocation->orderItemLocations()->sum('penalty_amount');
        $orderLocation->update(['penalty_amount' => $totalPenalties]);

        return response()->json([
            'success' => true,
            'message' => 'Pénalités mises à jour',
            'data' => $orderItemLocation->fresh()
        ]);
    }
}
