<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderItemController extends Controller
{
    /**
     * Constructor - Middleware d'authentification
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage orders')->except(['index', 'show']);
    }

    /**
     * Afficher la liste des articles de commande pour une commande spécifique
     */
    public function index(Request $request, Order $order = null)
    {
        // Si pas d'ordre spécifié, afficher tous les articles de l'utilisateur connecté
        if (!$order) {
            $query = OrderItem::whereHas('order', function ($q) {
                $q->where('user_id', Auth::id());
            })->with(['order', 'product', 'productImage']);
        } else {
            // Vérifier que la commande appartient à l'utilisateur connecté
            if ($order->user_id !== Auth::id() && !Auth::user()->can('manage orders')) {
                abort(403, 'Accès non autorisé à cette commande.');
            }

            $query = $order->orderItems()->with(['product', 'productImage']);
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrage par produit
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $orderItems = $query->paginate(15);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $orderItems
            ]);
        }

        return view('order-items.index', compact('orderItems', 'order'));
    }

    /**
     * Afficher le formulaire de création d'un nouvel article de commande (admin)
     */
    public function create(Order $order): View
    {
        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->get();

        return view('order-items.create', compact('order', 'products'));
    }

    /**
     * Créer un nouvel article de commande (admin)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:' . implode(',', OrderItem::getAllStatuses()),
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $order = Order::findOrFail($request->order_id);
        $product = Product::findOrFail($request->product_id);

        // Vérifier le stock
        if ($product->stock < $request->quantity) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuffisant pour ce produit.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Stock insuffisant pour ce produit.');
        }

        DB::beginTransaction();

        try {
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_description' => $product->description,
                'price' => $request->price ?? $product->price,
                'quantity' => $request->quantity,
                'status' => $request->status ?? OrderItem::STATUS_PENDING,
            ]);

            // Décrémenter le stock
            $product->decrement('stock', $request->quantity);

            // Recalculer les totaux de la commande
            $order->recalculateTotals();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Article ajouté à la commande avec succès.',
                    'data' => $orderItem->load(['product', 'productImage'])
                ], 201);
            }

            return redirect()->route('orders.show', $order)
                ->with('success', 'Article ajouté à la commande avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'ajout : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'ajout : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher un article de commande spécifique
     */
    public function show(OrderItem $orderItem)
    {
        // Vérifier l'accès
        if ($orderItem->order->user_id !== Auth::id() && !Auth::user()->can('manage orders')) {
            abort(403, 'Accès non autorisé à cet article.');
        }

        $orderItem->load(['order', 'product', 'productImage', 'orderReturns']);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $orderItem
            ]);
        }

        return view('order-items.show', compact('orderItem'));
    }

    /**
     * Afficher le formulaire d'édition d'un article de commande (admin)
     */
    public function edit(OrderItem $orderItem): View
    {
        $products = Product::where('is_active', true)->get();

        return view('order-items.edit', compact('orderItem', 'products'));
    }

    /**
     * Mettre à jour un article de commande (admin)
     */
    public function update(Request $request, OrderItem $orderItem)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:' . implode(',', OrderItem::getAllStatuses()),
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $oldQuantity = $orderItem->quantity;

            // Mettre à jour les champs
            if ($request->has('quantity')) {
                $quantityDiff = $request->quantity - $oldQuantity;
                
                // Vérifier le stock si on augmente la quantité
                if ($quantityDiff > 0 && $orderItem->product) {
                    if ($orderItem->product->stock < $quantityDiff) {
                        throw new \Exception('Stock insuffisant pour cette modification.');
                    }
                    $orderItem->product->decrement('stock', $quantityDiff);
                } elseif ($quantityDiff < 0 && $orderItem->product) {
                    // Remettre en stock si on diminue la quantité
                    $orderItem->product->increment('stock', abs($quantityDiff));
                }

                $orderItem->quantity = $request->quantity;
            }

            if ($request->has('price')) {
                $orderItem->price = $request->price;
            }

            if ($request->has('status')) {
                $orderItem->status = $request->status;
            }

            if ($request->has('admin_notes')) {
                $orderItem->admin_notes = $request->admin_notes;
            }

            $orderItem->save();

            // Recalculer les totaux de la commande
            $orderItem->order->recalculateTotals();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Article mis à jour avec succès.',
                    'data' => $orderItem->fresh(['product', 'productImage'])
                ]);
            }

            return redirect()->route('order-items.show', $orderItem)
                ->with('success', 'Article mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprimer un article de commande (admin)
     */
    public function destroy(OrderItem $orderItem)
    {
        // Vérifier si l'article peut être supprimé
        if (!$orderItem->canBeDeleted()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet article ne peut pas être supprimé.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Cet article ne peut pas être supprimé.');
        }

        DB::beginTransaction();

        try {
            $order = $orderItem->order;

            // Remettre en stock
            if ($orderItem->product) {
                $orderItem->product->increment('stock', $orderItem->quantity);
            }

            $orderItem->delete();

            // Recalculer les totaux de la commande
            $order->recalculateTotals();

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Article supprimé avec succès.'
                ]);
            }

            return redirect()->route('orders.show', $order)
                ->with('success', 'Article supprimé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour le statut d'un article de commande (admin)
     */
    public function updateStatus(Request $request, OrderItem $orderItem): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:' . implode(',', OrderItem::getAllStatuses()),
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $orderItem->status = $request->status;
            if ($request->admin_notes) {
                $orderItem->admin_notes = $request->admin_notes;
            }
            $orderItem->save();

            return response()->json([
                'success' => true,
                'message' => 'Statut de l\'article mis à jour avec succès.',
                'data' => $orderItem->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer une demande de retour pour un article (utilisateur)
     */
    public function createReturn(Request $request, OrderItem $orderItem)
    {
        // Vérifier l'accès
        if ($orderItem->order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cet article.');
        }

        $validator = Validator::make($request->all(), [
            'quantity_returned' => 'required|integer|min:1|max:' . $orderItem->getReturnableQuantity(),
            'reason' => 'required|in:' . implode(',', \App\Models\OrderReturn::getAllReasons()),
            'description' => 'required|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Vérifier si le retour est possible
        if (!$orderItem->canBeReturned()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet article ne peut pas être retourné.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Cet article ne peut pas être retourné.');
        }

        DB::beginTransaction();

        try {
            // Traiter les images
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('returns', 'public');
                    $imagePaths[] = $path;
                }
            }

            // Créer le retour
            $return = \App\Models\OrderReturn::create([
                'order_id' => $orderItem->order_id,
                'order_item_id' => $orderItem->id,
                'user_id' => Auth::id(),
                'quantity_returned' => $request->quantity_returned,
                'reason' => $request->reason,
                'description' => $request->description,
                'status' => \App\Models\OrderReturn::STATUS_PENDING,
                'refund_status' => \App\Models\OrderReturn::REFUND_STATUS_PENDING,
                'images' => $imagePaths,
            ]);

            // Mettre à jour l'article de commande
            $orderItem->quantity_returned += $request->quantity_returned;
            $orderItem->save();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Demande de retour créée avec succès.',
                    'data' => $return
                ], 201);
            }

            return redirect()->route('order-items.show', $orderItem)
                ->with('success', 'Votre demande de retour a été créée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création du retour : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la création du retour : ' . $e->getMessage());
        }
    }

    /**
     * Obtenir les statistiques des articles de commande (admin)
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_items' => OrderItem::count(),
            'items_by_status' => OrderItem::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'top_products' => OrderItem::selectRaw('product_id, product_name, SUM(quantity) as total_quantity, COUNT(*) as order_count')
                ->groupBy('product_id', 'product_name')
                ->orderBy('total_quantity', 'desc')
                ->limit(10)
                ->get(),
            'monthly_sales' => OrderItem::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(quantity) as total_quantity, SUM(price * quantity) as total_amount')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->limit(12)
                ->get(),
            'returns_rate' => [
                'total_items' => OrderItem::count(),
                'items_with_returns' => OrderItem::where('quantity_returned', '>', 0)->count(),
                'percentage' => OrderItem::count() > 0 
                    ? round((OrderItem::where('quantity_returned', '>', 0)->count() / OrderItem::count()) * 100, 2)
                    : 0
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Actions en lot sur les articles de commande (admin)
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:update_status,delete',
            'items' => 'required|array|min:1',
            'items.*' => 'exists:order_items,id',
            'status' => 'required_if:action,update_status|in:' . implode(',', OrderItem::getAllStatuses()),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $updated = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($request->items as $itemId) {
                $orderItem = OrderItem::find($itemId);
                
                if (!$orderItem) {
                    $errors[] = "Article {$itemId} introuvable.";
                    continue;
                }

                switch ($request->action) {
                    case 'update_status':
                        $orderItem->status = $request->status;
                        $orderItem->save();
                        $updated++;
                        break;

                    case 'delete':
                        if ($orderItem->canBeDeleted()) {
                            // Remettre en stock
                            if ($orderItem->product) {
                                $orderItem->product->increment('stock', $orderItem->quantity);
                            }
                            $orderItem->delete();
                            $updated++;
                        } else {
                            $errors[] = "L'article {$itemId} ne peut pas être supprimé.";
                        }
                        break;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$updated} articles traités avec succès.",
                'updated' => $updated,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement : ' . $e->getMessage()
            ], 500);
        }
    }
}
