<?php

namespace App\Http\Controllers;

use App\Models\OrderReturn;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class OrderReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('admin')->only(['adminIndex', 'adminShow', 'approve', 'reject', 'adminStats']);
    }

    /**
     * Afficher les demandes de retour de l'utilisateur connecté
     */
    public function index(Request $request)
    {
        $query = Auth::user()->orderReturns()->with(['order', 'orderItem.product']);

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrage par type de retour
        if ($request->filled('return_type')) {
            $query->where('return_type', $request->return_type);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'recent');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'amount_asc':
                $query->orderBy('refund_amount', 'asc');
                break;
            case 'amount_desc':
                $query->orderBy('refund_amount', 'desc');
                break;
            default:
                $query->latest();
        }

        $returns = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $returns,
            'meta' => [
                'total_returns' => Auth::user()->orderReturns()->count(),
                'pending_returns' => Auth::user()->orderReturns()->where('status', 'pending')->count(),
                'approved_returns' => Auth::user()->orderReturns()->where('status', 'approved')->count(),
                'total_refunded' => Auth::user()->orderReturns()->where('status', 'approved')->sum('refund_amount'),
            ]
        ]);
    }

    /**
     * Afficher une demande de retour spécifique
     */
    public function show(OrderReturn $return)
    {
        // Vérifier que le retour appartient à l'utilisateur connecté
        if ($return->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé à cette demande de retour');
        }

        $return->load([
            'order',
            'orderItem.product.category',
            'user'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $return
        ]);
    }

    /**
     * Créer une nouvelle demande de retour
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'order_item_id' => 'required|exists:order_items,id',
            'quantity' => 'required|integer|min:1',
            'return_type' => 'required|in:defective,unwanted,damaged,wrong_item',
            'reason' => 'required|string|max:1000',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Vérifier que la commande appartient à l'utilisateur
        $order = Order::findOrFail($validated['order_id']);
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette commande');
        }

        // Vérifier que l'article appartient à la commande
        $orderItem = OrderItem::where('id', $validated['order_item_id'])
                             ->where('order_id', $validated['order_id'])
                             ->firstOrFail();

        // Vérifier l'éligibilité de retour
        if (!$orderItem->can_be_returned) {
            return response()->json([
                'status' => 'error',
                'message' => $orderItem->return_eligibility_reason
            ], 422);
        }

        // Vérifier la quantité disponible pour retour
        if ($validated['quantity'] > $orderItem->available_for_return_quantity) {
            return response()->json([
                'status' => 'error',
                'message' => "Quantité non disponible pour retour. Maximum : {$orderItem->available_for_return_quantity}"
            ], 422);
        }

        // Traitement des photos
        $photosPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('returns', 'public');
                $photosPaths[] = $path;
            }
        }

        // Calculer le montant de remboursement
        $refundAmount = $orderItem->unit_price * $validated['quantity'];

        // Créer la demande de retour
        $return = OrderReturn::create([
            'user_id' => Auth::id(),
            'order_id' => $validated['order_id'],
            'order_item_id' => $validated['order_item_id'],
            'quantity' => $validated['quantity'],
            'return_type' => $validated['return_type'],
            'reason' => $validated['reason'],
            'photos' => $photosPaths,
            'refund_amount' => $refundAmount,
            'status' => 'pending'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Demande de retour créée avec succès',
            'data' => $return->load(['order', 'orderItem.product'])
        ], 201);
    }

    /**
     * Annuler une demande de retour
     */
    public function cancel(OrderReturn $return)
    {
        // Vérifier que le retour appartient à l'utilisateur connecté
        if ($return->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette demande de retour');
        }

        // Vérifier que le retour peut être annulé
        if ($return->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Cette demande de retour ne peut plus être annulée'
            ], 422);
        }

        $return->update([
            'status' => 'cancelled',
            'admin_notes' => 'Annulé par le client'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Demande de retour annulée avec succès',
            'data' => $return->fresh()
        ]);
    }

    /**
     * Afficher toutes les demandes de retour (Admin seulement)
     */
    public function adminIndex(Request $request)
    {
        $query = OrderReturn::with(['user', 'order', 'orderItem.product']);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                  ->orWhereHas('order', function($orderQuery) use ($search) {
                      $orderQuery->where('order_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('orderItem.product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrage par type de retour
        if ($request->filled('return_type')) {
            $query->where('return_type', $request->return_type);
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
            case 'amount_asc':
                $query->orderBy('refund_amount', 'asc');
                break;
            case 'amount_desc':
                $query->orderBy('refund_amount', 'desc');
                break;
            default:
                $query->latest();
        }

        $returns = $query->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $returns
        ]);
    }

    /**
     * Afficher une demande de retour (Admin)
     */
    public function adminShow(OrderReturn $return)
    {
        $return->load([
            'user',
            'order',
            'orderItem.product.category'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $return
        ]);
    }

    /**
     * Approuver une demande de retour (Admin seulement)
     */
    public function approve(Request $request, OrderReturn $return)
    {
        if ($return->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Cette demande de retour ne peut plus être modifiée'
            ], 422);
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
            'inspection_notes' => 'nullable|string|max:1000',
            'refund_amount' => 'nullable|numeric|min:0|max:' . $return->refund_amount
        ]);

        // Mettre à jour le montant de remboursement si spécifié
        $refundAmount = $validated['refund_amount'] ?? $return->refund_amount;

        $return->update([
            'status' => 'approved',
            'admin_notes' => $validated['admin_notes'] ?? null,
            'inspection_notes' => $validated['inspection_notes'] ?? null,
            'refund_amount' => $refundAmount,
            'approved_at' => now(),
            'approved_by' => Auth::id()
        ]);

        // Restaurer le stock du produit
        $return->orderItem->product->increment('quantity', $return->quantity);

        return response()->json([
            'status' => 'success',
            'message' => 'Demande de retour approuvée avec succès',
            'data' => $return->fresh()
        ]);
    }

    /**
     * Rejeter une demande de retour (Admin seulement)
     */
    public function reject(Request $request, OrderReturn $return)
    {
        if ($return->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Cette demande de retour ne peut plus être modifiée'
            ], 422);
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
            'rejection_reason' => 'required|in:condition_not_met,out_of_deadline,insufficient_evidence,policy_violation'
        ]);

        $return->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
            'rejection_reason' => $validated['rejection_reason'],
            'rejected_at' => now(),
            'rejected_by' => Auth::id()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Demande de retour rejetée',
            'data' => $return->fresh()
        ]);
    }

    /**
     * Marquer un retour comme reçu (Admin seulement)
     */
    public function markAsReceived(Request $request, OrderReturn $return)
    {
        if ($return->status !== 'approved') {
            return response()->json([
                'status' => 'error',
                'message' => 'Ce retour n\'est pas dans le bon statut'
            ], 422);
        }

        $validated = $request->validate([
            'received_notes' => 'nullable|string|max:1000',
            'condition_assessment' => 'required|in:excellent,good,fair,poor',
            'final_refund_amount' => 'nullable|numeric|min:0|max:' . $return->refund_amount
        ]);

        $finalRefundAmount = $validated['final_refund_amount'] ?? $return->refund_amount;

        $return->update([
            'status' => 'received',
            'received_notes' => $validated['received_notes'] ?? null,
            'condition_assessment' => $validated['condition_assessment'],
            'refund_amount' => $finalRefundAmount,
            'received_at' => now(),
            'received_by' => Auth::id()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Retour marqué comme reçu',
            'data' => $return->fresh()
        ]);
    }

    /**
     * Finaliser le remboursement (Admin seulement)
     */
    public function processRefund(Request $request, OrderReturn $return)
    {
        if ($return->status !== 'received') {
            return response()->json([
                'status' => 'error',
                'message' => 'Ce retour n\'est pas prêt pour le remboursement'
            ], 422);
        }

        $validated = $request->validate([
            'refund_method' => 'required|in:original_payment,bank_transfer,store_credit',
            'refund_notes' => 'nullable|string|max:1000'
        ]);

        $return->update([
            'status' => 'refunded',
            'refund_method' => $validated['refund_method'],
            'refund_notes' => $validated['refund_notes'] ?? null,
            'refunded_at' => now(),
            'refunded_by' => Auth::id()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Remboursement traité avec succès',
            'data' => $return->fresh()
        ]);
    }

    /**
     * Statistiques des retours (Admin seulement)
     */
    public function adminStats(Request $request)
    {
        $period = $request->get('period', '30'); // 30 jours par défaut

        $stats = [
            'total_returns' => OrderReturn::count(),
            'pending_returns' => OrderReturn::where('status', 'pending')->count(),
            'approved_returns' => OrderReturn::where('status', 'approved')->count(),
            'rejected_returns' => OrderReturn::where('status', 'rejected')->count(),
            'received_returns' => OrderReturn::where('status', 'received')->count(),
            'refunded_returns' => OrderReturn::where('status', 'refunded')->count(),
            'cancelled_returns' => OrderReturn::where('status', 'cancelled')->count(),
            
            'total_refund_amount' => OrderReturn::where('status', 'refunded')->sum('refund_amount'),
            'average_refund_amount' => OrderReturn::where('status', 'refunded')->avg('refund_amount'),
            'pending_refund_amount' => OrderReturn::whereIn('status', ['approved', 'received'])->sum('refund_amount'),
            
            'returns_this_month' => OrderReturn::whereMonth('created_at', now()->month)
                                             ->whereYear('created_at', now()->year)
                                             ->count(),
            
            'refunds_this_month' => OrderReturn::whereMonth('refunded_at', now()->month)
                                              ->whereYear('refunded_at', now()->year)
                                              ->where('status', 'refunded')
                                              ->sum('refund_amount'),
            
            'return_types' => OrderReturn::selectRaw('return_type, COUNT(*) as count')
                                        ->groupBy('return_type')
                                        ->get(),
            
            'return_reasons' => OrderReturn::selectRaw('rejection_reason, COUNT(*) as count')
                                          ->whereNotNull('rejection_reason')
                                          ->groupBy('rejection_reason')
                                          ->get(),
            
            'returns_by_day' => OrderReturn::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(refund_amount) as amount')
                                          ->where('created_at', '>=', now()->subDays($period))
                                          ->groupBy('date')
                                          ->orderBy('date')
                                          ->get(),
            
            'top_returned_products' => OrderReturn::select('products.name as product_name',
                                                          'products.id as product_id',
                                                          \DB::raw('SUM(order_returns.quantity) as total_returned'),
                                                          \DB::raw('SUM(order_returns.refund_amount) as total_refund'))
                                                 ->join('order_items', 'order_returns.order_item_id', '=', 'order_items.id')
                                                 ->join('products', 'order_items.product_id', '=', 'products.id')
                                                 ->groupBy('products.id', 'products.name')
                                                 ->orderBy('total_returned', 'desc')
                                                 ->take(10)
                                                 ->get(),
            
            'average_processing_time' => OrderReturn::whereNotNull('approved_at')
                                                   ->selectRaw('AVG(DATEDIFF(approved_at, created_at)) as avg_days')
                                                   ->value('avg_days'),
            
            'recent_returns' => OrderReturn::with(['user', 'order', 'orderItem.product'])
                                          ->latest()
                                          ->take(10)
                                          ->get()
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    /**
     * Exporter les demandes de retour (Admin seulement)
     */
    public function export(Request $request)
    {
        $query = OrderReturn::with(['user', 'order', 'orderItem.product']);

        // Appliquer les mêmes filtres que l'index admin
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('return_type')) {
            $query->where('return_type', $request->return_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $returns = $query->get();

        // Format CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="retours-' . now()->format('Y-m-d') . '.csv"'
        ];

        $callback = function() use ($returns) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'Numéro retour',
                'Numéro commande',
                'Client',
                'Produit',
                'Quantité',
                'Type de retour',
                'Montant remboursement',
                'Statut',
                'Date demande',
                'Date approbation',
                'Date remboursement'
            ]);

            // Données
            foreach ($returns as $return) {
                fputcsv($file, [
                    $return->return_number,
                    $return->order->order_number,
                    $return->user->name,
                    $return->orderItem->product->name,
                    $return->quantity,
                    $return->return_type_label,
                    number_format($return->refund_amount, 2, ',', ' ') . ' €',
                    $return->status_label,
                    $return->created_at->format('d/m/Y H:i'),
                    $return->approved_at ? $return->approved_at->format('d/m/Y H:i') : '',
                    $return->refunded_at ? $return->refunded_at->format('d/m/Y H:i') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
