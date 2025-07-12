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
use App\Mail\RentalOrderCancelled;
use App\Mail\RentalOrderCompleted;
use App\Mail\RentalOrderInspection;

class OrderLocationController extends Controller
{
    /**
     * Afficher la liste des commandes de location
     */
    public function index(Request $request)
    {
        $query = OrderLocation::with(['user', 'orderItemLocations.product']);

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        return response()->json([
            'success' => true,
            'data' => $orderLocations
        ]);
    }

    /**
     * Afficher les détails d'une commande de location
     */
    public function show(OrderLocation $orderLocation)
    {
        // Vérifier les permissions
        if (!Auth::user()->isAdmin() && $orderLocation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $orderLocation->load([
            'user',
            'orderItemLocations.product',
            'orderItemLocations.product.media'
        ]);

        return response()->json([
            'success' => true,
            'data' => $orderLocation
        ]);
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

        $cartLocation = CartLocation::with('cartItemLocations.product')
            ->where('id', $request->cart_location_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($cartLocation->cartItemLocations->isEmpty()) {
            return response()->json(['error' => 'Le panier est vide'], 400);
        }

        DB::beginTransaction();
        try {
            // Créer la commande de location
            $orderLocation = OrderLocation::create([
                'user_id' => Auth::id(),
                'order_number' => OrderLocation::generateOrderNumber(),
                'status' => 'pending',
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'pickup_address' => $request->pickup_address,
                'return_address' => $request->return_address,
                'notes' => $request->notes,
                'subtotal' => 0,
                'deposit_amount' => 0,
                'tax_amount' => 0,
                'total_amount' => 0,
                'currency' => 'EUR',
                'tax_rate' => 21.00
            ]);

            $subtotal = 0;
            $totalDeposit = 0;

            // Créer les éléments de la commande
            foreach ($cartLocation->cartItemLocations as $cartItem) {
                $product = $cartItem->product;
                $days = $orderLocation->getRentalDaysCount();
                $itemTotal = $product->rental_price_per_day * $cartItem->quantity * $days;
                $itemDeposit = $product->rental_deposit * $cartItem->quantity;

                $orderLocation->orderItemLocations()->create([
                    'product_id' => $product->id,
                    'quantity' => $cartItem->quantity,
                    'daily_price' => $product->rental_price_per_day,
                    'total_days' => $days,
                    'subtotal' => $itemTotal,
                    'deposit_amount' => $itemDeposit,
                    'product_name' => $product->name,
                    'product_description' => $product->description
                ]);

                $subtotal += $itemTotal;
                $totalDeposit += $itemDeposit;
            }

            // Calculer les montants finaux
            $taxAmount = $subtotal * ($orderLocation->tax_rate / 100);
            $totalAmount = $subtotal + $taxAmount + $totalDeposit;

            $orderLocation->update([
                'subtotal' => $subtotal,
                'deposit_amount' => $totalDeposit,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount
            ]);

            // Vider le panier de location
            $cartLocation->cartItemLocations()->delete();

            DB::commit();

            // Charger les relations pour la réponse
            $orderLocation->load(['user', 'orderItemLocations.product']);

            return response()->json([
                'success' => true,
                'message' => 'Commande de location créée avec succès',
                'data' => $orderLocation
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur lors de la création de la commande'], 500);
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

        if (!$orderLocation->canBeCancelled()) {
            return response()->json(['error' => 'Cette commande ne peut plus être annulée'], 400);
        }

        // Le changement de statut déclenchera automatiquement les événements
        $orderLocation->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Commande annulée avec succès',
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
                $orderItem = $orderLocation->orderItemLocations()->findOrFail($itemData['id']);
                
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

            // Envoyer l'email de fin d'inspection
            try {
                Mail::to($orderLocation->user->email)->send(new RentalOrderInspection($orderLocation));
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email inspection location: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Inspection terminée avec succès',
                'data' => $orderLocation->fresh()->load('orderItemLocations')
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

        $query = OrderLocation::with(['user', 'orderItemLocations.product']);

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
}
