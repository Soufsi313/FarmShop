<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('admin')->only(['adminIndex', 'adminShow']);
    }

    /**
     * Afficher les articles d'une commande
     */
    public function index(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté ou admin
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé à cette commande');
        }

        $items = $order->items()->with([
            'product.category',
            'returns'
        ])->get();

        return response()->json([
            'status' => 'success',
            'data' => $items,
            'meta' => [
                'order_number' => $order->order_number,
                'total_items' => $items->count(),
                'total_quantity' => $items->sum('quantity'),
                'subtotal' => $items->sum('subtotal')
            ]
        ]);
    }

    /**
     * Afficher un article spécifique d'une commande
     */
    public function show(Order $order, OrderItem $item)
    {
        // Vérifier que l'article appartient à la commande
        if ($item->order_id !== $order->id) {
            abort(404, 'Article non trouvé dans cette commande');
        }

        // Vérifier que la commande appartient à l'utilisateur connecté ou admin
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé à cette commande');
        }

        $item->load([
            'product.category',
            'returns',
            'order'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item
        ]);
    }

    /**
     * Vérifier l'éligibilité de retour d'un article
     */
    public function checkReturnEligibility(Order $order, OrderItem $item)
    {
        // Vérifier que l'article appartient à la commande
        if ($item->order_id !== $order->id) {
            abort(404, 'Article non trouvé dans cette commande');
        }

        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette commande');
        }

        $eligibility = [
            'eligible' => $item->can_be_returned,
            'reason' => $item->return_eligibility_reason,
            'return_deadline' => $item->return_deadline,
            'days_remaining' => $item->days_until_return_deadline,
            'returned_quantity' => $item->returned_quantity,
            'available_quantity' => $item->available_for_return_quantity,
            'product_category' => $item->product->category->name,
            'is_food_product' => $item->product->category->food_type === 'alimentaire',
            'category_returnable' => $item->product->category->is_returnable
        ];

        return response()->json([
            'status' => 'success',
            'data' => $eligibility
        ]);
    }

    /**
     * Obtenir l'historique des retours d'un article
     */
    public function returnHistory(Order $order, OrderItem $item)
    {
        // Vérifier que l'article appartient à la commande
        if ($item->order_id !== $order->id) {
            abort(404, 'Article non trouvé dans cette commande');
        }

        // Vérifier que la commande appartient à l'utilisateur connecté ou admin
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé à cette commande');
        }

        $returns = $item->returns()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $returns,
            'meta' => [
                'total_returns' => $returns->count(),
                'total_returned_quantity' => $returns->sum('quantity'),
                'total_refund_amount' => $returns->where('status', 'approved')->sum('refund_amount'),
                'pending_returns' => $returns->where('status', 'pending')->count(),
                'approved_returns' => $returns->where('status', 'approved')->count(),
                'rejected_returns' => $returns->where('status', 'rejected')->count()
            ]
        ]);
    }

    /**
     * Afficher tous les articles de commandes (Admin seulement)
     */
    public function adminIndex(Request $request)
    {
        $query = OrderItem::with(['order.user', 'product.category', 'returns']);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('order', function($orderQuery) use ($search) {
                    $orderQuery->where('order_number', 'like', "%{$search}%");
                })->orWhereHas('product', function($productQuery) use ($search) {
                    $productQuery->where('name', 'like', "%{$search}%");
                })->orWhereHas('order.user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Filtrage par produit
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filtrage par catégorie
        if ($request->filled('category_id')) {
            $query->whereHas('product', function($productQuery) use ($request) {
                $productQuery->where('category_id', $request->category_id);
            });
        }

        // Filtrage par statut de commande
        if ($request->filled('order_status')) {
            $query->whereHas('order', function($orderQuery) use ($request) {
                $orderQuery->where('status', $request->order_status);
            });
        }

        // Filtrage par date
        if ($request->filled('date_from')) {
            $query->whereHas('order', function($orderQuery) use ($request) {
                $orderQuery->whereDate('created_at', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('order', function($orderQuery) use ($request) {
                $orderQuery->whereDate('created_at', '<=', $request->date_to);
            });
        }

        // Filtrage par éligibilité de retour
        if ($request->filled('returnable')) {
            if ($request->returnable === 'true') {
                $query->where(function($q) {
                    $q->whereHas('product.category', function($categoryQuery) {
                        $categoryQuery->where('is_returnable', true)
                                    ->where('food_type', 'non_alimentaire');
                    })->whereHas('order', function($orderQuery) {
                        $orderQuery->where('status', 'delivered')
                                  ->where('delivered_at', '>=', now()->subDays(14));
                    });
                });
            } else {
                $query->where(function($q) {
                    $q->whereHas('product.category', function($categoryQuery) {
                        $categoryQuery->where('is_returnable', false)
                                    ->orWhere('food_type', 'alimentaire');
                    })->orWhereHas('order', function($orderQuery) {
                        $orderQuery->where('status', '!=', 'delivered')
                                  ->orWhere('delivered_at', '<', now()->subDays(14));
                    });
                });
            }
        }

        // Tri
        $sortBy = $request->get('sort_by', 'recent');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_asc':
                $query->orderBy('unit_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('unit_price', 'desc');
                break;
            case 'quantity_asc':
                $query->orderBy('quantity', 'asc');
                break;
            case 'quantity_desc':
                $query->orderBy('quantity', 'desc');
                break;
            default:
                $query->latest();
        }

        $items = $query->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $items
        ]);
    }

    /**
     * Afficher un article de commande (Admin)
     */
    public function adminShow(OrderItem $item)
    {
        $item->load([
            'order.user',
            'product.category',
            'returns'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item
        ]);
    }

    /**
     * Statistiques des articles de commandes (Admin seulement)
     */
    public function adminStats(Request $request)
    {
        $period = $request->get('period', '30'); // 30 jours par défaut

        $stats = [
            'total_items_sold' => OrderItem::whereHas('order', function($query) {
                $query->where('payment_status', 'paid');
            })->sum('quantity'),
            
            'total_revenue_items' => OrderItem::whereHas('order', function($query) {
                $query->where('payment_status', 'paid');
            })->sum('subtotal'),
            
            'average_item_price' => OrderItem::whereHas('order', function($query) {
                $query->where('payment_status', 'paid');
            })->avg('unit_price'),
            
            'items_by_category' => OrderItem::select('categories.name as category', 
                                                   \DB::raw('SUM(order_items.quantity) as total_quantity'),
                                                   \DB::raw('SUM(order_items.subtotal) as total_revenue'))
                                           ->join('products', 'order_items.product_id', '=', 'products.id')
                                           ->join('categories', 'products.category_id', '=', 'categories.id')
                                           ->whereHas('order', function($query) {
                                               $query->where('payment_status', 'paid');
                                           })
                                           ->groupBy('categories.id', 'categories.name')
                                           ->orderBy('total_quantity', 'desc')
                                           ->get(),
            
            'top_selling_products' => OrderItem::select('products.name as product_name',
                                                       'products.id as product_id',
                                                       \DB::raw('SUM(order_items.quantity) as total_sold'),
                                                       \DB::raw('SUM(order_items.subtotal) as total_revenue'))
                                               ->join('products', 'order_items.product_id', '=', 'products.id')
                                               ->whereHas('order', function($query) {
                                                   $query->where('payment_status', 'paid');
                                               })
                                               ->groupBy('products.id', 'products.name')
                                               ->orderBy('total_sold', 'desc')
                                               ->take(10)
                                               ->get(),
            
            'returnable_items' => OrderItem::whereHas('product.category', function($query) {
                $query->where('is_returnable', true)
                      ->where('food_type', 'non_alimentaire');
            })->whereHas('order', function($query) {
                $query->where('status', 'delivered')
                      ->where('delivered_at', '>=', now()->subDays(14));
            })->count(),
            
            'returned_items' => OrderItem::whereHas('returns', function($query) {
                $query->where('status', 'approved');
            })->sum('quantity'),
            
            'return_rate' => 0, // Calculé après
            
            'items_this_month' => OrderItem::whereHas('order', function($query) {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year)
                      ->where('payment_status', 'paid');
            })->sum('quantity'),
            
            'revenue_this_month' => OrderItem::whereHas('order', function($query) {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year)
                      ->where('payment_status', 'paid');
            })->sum('subtotal'),
            
            'items_by_day' => OrderItem::select(\DB::raw('DATE(orders.created_at) as date'),
                                               \DB::raw('SUM(order_items.quantity) as quantity'),
                                               \DB::raw('SUM(order_items.subtotal) as revenue'))
                                       ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                       ->where('orders.created_at', '>=', now()->subDays($period))
                                       ->where('orders.payment_status', 'paid')
                                       ->groupBy('date')
                                       ->orderBy('date')
                                       ->get()
        ];

        // Calculer le taux de retour
        $totalItemsSold = $stats['total_items_sold'];
        $returnedItems = $stats['returned_items'];
        $stats['return_rate'] = $totalItemsSold > 0 ? round(($returnedItems / $totalItemsSold) * 100, 2) : 0;

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    /**
     * Exporter les articles de commandes (Admin seulement)
     */
    public function export(Request $request)
    {
        $query = OrderItem::with(['order.user', 'product.category']);

        // Appliquer les mêmes filtres que l'index admin
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('product', function($productQuery) use ($request) {
                $productQuery->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereHas('order', function($orderQuery) use ($request) {
                $orderQuery->whereDate('created_at', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('order', function($orderQuery) use ($request) {
                $orderQuery->whereDate('created_at', '<=', $request->date_to);
            });
        }

        $items = $query->get();

        // Format CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="articles-commandes-' . now()->format('Y-m-d') . '.csv"'
        ];

        $callback = function() use ($items) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'Numéro commande',
                'Client',
                'Produit',
                'Catégorie',
                'Quantité',
                'Prix unitaire',
                'Sous-total',
                'Date commande',
                'Statut commande',
                'Retournable',
                'Quantité retournée'
            ]);

            // Données
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->order->order_number,
                    $item->order->user->name,
                    $item->product->name,
                    $item->product->category->name,
                    $item->quantity,
                    number_format($item->unit_price, 2, ',', ' ') . ' €',
                    number_format($item->subtotal, 2, ',', ' ') . ' €',
                    $item->order->created_at->format('d/m/Y H:i'),
                    $item->order->status_label,
                    $item->can_be_returned ? 'Oui' : 'Non',
                    $item->returned_quantity
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
