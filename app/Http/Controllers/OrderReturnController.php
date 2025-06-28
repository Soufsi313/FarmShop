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
}
