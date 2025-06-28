<?php

namespace App\Http\Controllers;

use App\Models\OrderReturn;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderReturnController extends Controller
{
    /**
     * Constructor - Middleware d'authentification et permissions
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage orders')->except(['index', 'show']);
    }

    /**
     * Afficher la liste des retours pour une commande (admin)
     */
    public function index(Order $order)
    {
        $returns = $order->orderReturns()
            ->with(['orderItem.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $returns
            ]);
        }

        return view('admin.orders.returns.index', compact('order', 'returns'));
    }

    /**
     * Afficher un retour spécifique (admin)
     */
    public function show(OrderReturn $return)
    {
        $return->load(['order', 'orderItem.product', 'user']);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $return
            ]);
        }

        return view('admin.orders.returns.show', compact('return'));
    }

    /**
     * Approuver un retour (admin)
     */
    public function approve(Request $request, OrderReturn $return)
    {
        $validator = Validator::make($request->all(), [
            'admin_notes' => 'nullable|string|max:1000',
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

        if (!$return->isPending()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce retour ne peut pas être approuvé.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Ce retour ne peut pas être approuvé.');
        }

        DB::beginTransaction();

        try {
            $return->approve($request->admin_notes);

            // Calculer et initier le remboursement
            $refundAmount = $return->calculateRefundAmount();
            $return->initiateRefund($refundAmount);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Retour approuvé avec succès.',
                    'data' => $return->fresh()
                ]);
            }

            return redirect()->back()->with('success', 'Retour approuvé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'approbation : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de l\'approbation : ' . $e->getMessage());
        }
    }

    /**
     * Rejeter un retour (admin)
     */
    public function reject(Request $request, OrderReturn $return)
    {
        $validator = Validator::make($request->all(), [
            'admin_notes' => 'required|string|max:1000',
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

        if (!$return->isPending()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce retour ne peut pas être rejeté.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Ce retour ne peut pas être rejeté.');
        }

        try {
            $return->reject($request->admin_notes);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Retour rejeté avec succès.',
                    'data' => $return->fresh()
                ]);
            }

            return redirect()->back()->with('success', 'Retour rejeté avec succès.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du rejet : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors du rejet : ' . $e->getMessage());
        }
    }

    /**
     * Traiter un retour (marquer comme traité et finaliser le remboursement)
     */
    public function process(Request $request, OrderReturn $return)
    {
        $validator = Validator::make($request->all(), [
            'admin_notes' => 'nullable|string|max:1000',
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

        if (!$return->isApproved()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce retour doit d\'abord être approuvé.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Ce retour doit d\'abord être approuvé.');
        }

        DB::beginTransaction();

        try {
            // Marquer le retour comme traité
            $return->process($request->admin_notes);

            // Finaliser le remboursement
            $return->completeRefund();

            // Remettre en stock si applicable
            $orderItem = $return->orderItem;
            if ($orderItem && $orderItem->product) {
                $orderItem->product->increment('stock', $return->quantity_returned);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Retour traité avec succès.',
                    'data' => $return->fresh()
                ]);
            }

            return redirect()->back()->with('success', 'Retour traité avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du traitement : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors du traitement : ' . $e->getMessage());
        }
    }

    /**
     * Obtenir les statistiques des retours (admin)
     */
    public function statistics()
    {
        $stats = [
            'total_returns' => OrderReturn::count(),
            'pending_returns' => OrderReturn::pending()->count(),
            'approved_returns' => OrderReturn::approved()->count(),
            'rejected_returns' => OrderReturn::rejected()->count(),
            'processed_returns' => OrderReturn::processed()->count(),
            'total_refund_amount' => OrderReturn::where('refund_status', OrderReturn::REFUND_STATUS_COMPLETED)
                ->sum('refund_amount'),
            'average_processing_time' => OrderReturn::whereNotNull('processed_at')
                ->whereNotNull('approved_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, approved_at, processed_at)) as avg_hours')
                ->value('avg_hours'),
            'returns_by_reason' => OrderReturn::selectRaw('reason, COUNT(*) as count')
                ->groupBy('reason')
                ->get(),
            'monthly_returns' => OrderReturn::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->limit(12)
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Créer une demande de retour (utilisateur)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_item_id' => 'required|exists:order_items,id',
            'quantity_returned' => 'required|integer|min:1',
            'reason' => 'required|in:' . implode(',', OrderReturn::getAllReasons()),
            'description' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|max:2048',
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

        $orderItem = OrderItem::with(['order', 'product.category'])->findOrFail($request->order_item_id);

        // Vérifier que l'utilisateur est propriétaire de la commande
        if ($orderItem->order->user_id !== auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas l\'autorisation d\'accéder à cette commande.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation d\'accéder à cette commande.');
        }

        // Vérifier si le produit est retournable
        if (!$orderItem->product->isReturnableProduct()) {
            $message = $orderItem->product->isPerishable() 
                ? OrderReturn::getPerishableReturnMessage()
                : 'Ce produit n\'est pas retournable.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }
            return redirect()->back()->with('error', $message);
        }

        // Vérifier si la commande est éligible au retour
        if (!$orderItem->order->isEligibleForReturn()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette commande n\'est pas éligible au retour.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Cette commande n\'est pas éligible au retour.');
        }

        // Vérifier la période de retour
        if (!$orderItem->product->isWithinReturnPeriod($orderItem->order->created_at)) {
            $daysLeft = $orderItem->product->getDaysLeftForReturn($orderItem->order->created_at);
            $message = $daysLeft > 0 
                ? "Il vous reste {$daysLeft} jour(s) pour retourner ce produit."
                : 'La période de retour de 14 jours est dépassée.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }
            return redirect()->back()->with('error', $message);
        }

        // Vérifier la quantité
        if ($request->quantity_returned > $orderItem->quantity) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La quantité à retourner ne peut pas dépasser la quantité commandée.'
                ], 400);
            }
            return redirect()->back()->with('error', 'La quantité à retourner ne peut pas dépasser la quantité commandée.');
        }

        // Vérifier qu'il n'y a pas déjà un retour en cours
        if (!OrderReturn::canCreateReturn($orderItem)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Un retour est déjà en cours pour cet article.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Un retour est déjà en cours pour cet article.');
        }

        DB::beginTransaction();
        try {
            // Créer le retour
            $return = OrderReturn::createReturn($orderItem, $request->validated());

            if (!$return) {
                throw new \Exception('Impossible de créer la demande de retour.');
            }

            // Gérer les images si présentes
            if ($request->hasFile('images')) {
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('returns', 'public');
                    $imagePaths[] = $path;
                }
                $return->addImages($imagePaths);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Demande de retour créée avec succès.',
                    'data' => $return->fresh()
                ], 201);
            }

            return redirect()->back()->with('success', 'Demande de retour créée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Vérifier l'éligibilité d'un produit au retour
     */
    public function checkEligibility(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_item_id' => 'required|exists:order_items,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $orderItem = OrderItem::with(['order', 'product.category'])->findOrFail($request->order_item_id);

        // Vérifier que l'utilisateur est propriétaire de la commande
        if ($orderItem->order->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas l\'autorisation d\'accéder à cette commande.'
            ], 403);
        }

        $eligible = OrderReturn::canCreateReturn($orderItem);
        $reasons = [];

        if (!$eligible) {
            if (!$orderItem->product->isReturnableProduct()) {
                $reasons[] = $orderItem->product->isPerishable() 
                    ? OrderReturn::getPerishableReturnMessage()
                    : 'Ce produit n\'est pas retournable.';
            }

            if (!$orderItem->order->isEligibleForReturn()) {
                $reasons[] = 'Cette commande n\'est pas éligible au retour.';
            }

            if (!$orderItem->product->isWithinReturnPeriod($orderItem->order->created_at)) {
                $reasons[] = 'La période de retour de 14 jours est dépassée.';
            }

            $existingReturn = OrderReturn::where('order_item_id', $orderItem->id)
                ->whereIn('status', [OrderReturn::STATUS_PENDING, OrderReturn::STATUS_APPROVED])
                ->exists();

            if ($existingReturn) {
                $reasons[] = 'Un retour est déjà en cours pour cet article.';
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'eligible' => $eligible,
                'reasons' => $reasons,
                'days_left' => $orderItem->product->getDaysLeftForReturn($orderItem->order->created_at),
                'return_conditions' => $orderItem->product->return_conditions,
            ]
        ]);
    }
}
