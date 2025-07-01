<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboard()
    {
        $stats = [
            'users_count' => User::count(),
            'products_count' => Product::count(),
            'orders_count' => Order::count(),
            'categories_count' => Category::count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_orders' => Order::with('user')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // GESTION UTILISATEURS
    public function usersIndex(Request $request)
    {
        $query = User::with('roles');

        // Recherche
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        // Filtre par rôle
        if ($request->role) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filtre par statut de vérification
        if ($request->status === 'verified') {
            $query->whereNotNull('email_verified_at');
        } elseif ($request->status === 'unverified') {
            $query->whereNull('email_verified_at');
        }

        $users = $query->latest()->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,admin',
        ]);

        DB::transaction(function() use ($request) {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            // Assigner le rôle directement
            $user->assignRole($request->role);
        });

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
        ]);

        DB::transaction(function() use ($request, $user) {
            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
            ]);

            // Mettre à jour le rôle avec Spatie Permission
            $user->syncRoles([$request->role]);
        });

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'Vous ne pouvez pas supprimer votre propre compte.'], 403);
        }

        $user->delete();
        return response()->json(['success' => 'Utilisateur supprimé avec succès.']);
    }

    // GESTION PRODUITS
    public function productsIndex(Request $request)
    {
        $query = Product::with('category');

        // Recherche
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filtre par catégorie
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filtre par statut
        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        // Filtre par type de produit
        if ($request->product_type) {
            switch ($request->product_type) {
                case 'purchase':
                    $query->where('price', '>', 0)->where('is_rentable', false);
                    break;
                case 'rental':
                    $query->where('is_rentable', true);
                    break;
                case 'both':
                    $query->where('price', '>', 0)->where('is_rentable', true);
                    break;
            }
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::all();
        
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'critical_stock_threshold' => 'required|integer|min:1',
            'unit_symbol' => 'required|in:kg,piece,liter',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['is_perishable'] = true; // Produits alimentaires = périssables
        $data['is_returnable'] = false; // Pas de retour pour l'alimentaire
        $data['is_rentable'] = false; // Pas de location pour l'alimentaire

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produit créé avec succès.');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'critical_stock_threshold' => 'required|integer|min:1',
            'unit_symbol' => 'required|in:kg,piece,liter',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            $data['main_image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produit modifié avec succès.');
    }

    public function destroyProduct(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        return response()->json(['success' => 'Produit supprimé avec succès.']);
    }

    // GESTION COMMANDES
    public function ordersIndex(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        // Recherche
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($subQ) use ($request) {
                      $subQ->where('name', 'like', '%' . $request->search . '%')
                           ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filtre par statut
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filtre par date
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Tri
        switch ($request->sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'amount_desc':
                $query->orderBy('total_amount', 'desc');
                break;
            case 'amount_asc':
                $query->orderBy('total_amount', 'asc');
                break;
            default:
                $query->latest();
        }

        $orders = $query->paginate(15);
        
        // Statistiques pour les cartes
        $stats = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'monthly_revenue' => Order::whereMonth('created_at', now()->month)
                                     ->whereYear('created_at', now()->year)
                                     ->sum('total_amount'),
        ];
        
        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return response()->json(['success' => 'Statut de la commande mis à jour.']);
    }

    // AUTOMATISATION DES COMMANDES
    public function automationDashboard()
    {
        $stats = [
            'confirmed' => Order::where('status', Order::STATUS_CONFIRMED)->count(),
            'preparation' => Order::where('status', Order::STATUS_PREPARATION)->count(),
            'shipped' => Order::where('status', Order::STATUS_SHIPPED)->count(),
            'automated_today' => Order::whereDate('updated_at', today())
                                     ->whereIn('status', [Order::STATUS_PREPARATION, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])
                                     ->count(),
        ];

        // Calculer les prochaines transitions
        $nextTransitions = $this->getNextTransitions();

        // Passer les traductions de statuts à la vue
        $statusTranslations = $this->getStatusTranslations();

        return view('admin.orders.automation', compact('stats', 'nextTransitions', 'statusTranslations'));
    }

    public function runAutomation(Request $request)
    {
        try {
            $output = [];
            $exitCode = \Artisan::call('orders:update-status', [], $output);
            
            $commandOutput = \Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => 'Automatisation exécutée avec succès',
                'details' => $commandOutput,
                'exit_code' => $exitCode
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'exécution de l\'automatisation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function runDryRun(Request $request)
    {
        try {
            $output = [];
            $exitCode = \Artisan::call('orders:update-status', ['--dry-run' => true], $output);
            
            $commandOutput = \Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => 'Test (Dry Run) exécuté avec succès',
                'details' => $commandOutput,
                'exit_code' => $exitCode
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du test',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function automationStats()
    {
        return response()->json([
            'confirmed' => Order::where('status', Order::STATUS_CONFIRMED)->count(),
            'preparation' => Order::where('status', Order::STATUS_PREPARATION)->count(),
            'shipped' => Order::where('status', Order::STATUS_SHIPPED)->count(),
            'automated_today' => Order::whereDate('updated_at', today())
                                     ->whereIn('status', [Order::STATUS_PREPARATION, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])
                                     ->count(),
        ]);
    }

    private function getNextTransitions()
    {
        $transitions = [];
        $now = Carbon::now();

        // Commandes confirmées → préparation (après 90 secondes)
        $confirmedOrders = Order::where('status', Order::STATUS_CONFIRMED)
                                ->where('confirmed_at', '>', $now->copy()->subSeconds(90))
                                ->orderBy('confirmed_at')
                                ->take(5)
                                ->get();

        foreach ($confirmedOrders as $order) {
            $nextTransitionTime = $order->confirmed_at->addSeconds(90);
            $timeRemaining = $now->diffInSeconds($nextTransitionTime, false);
            
            if ($timeRemaining > 0) {
                $transitions[] = [
                    'order_number' => $order->order_number,
                    'current_status' => 'Confirmée',
                    'next_status' => 'En préparation',
                    'time_remaining' => $timeRemaining > 60 ? 
                        ceil($timeRemaining / 60) . ' min' : 
                        $timeRemaining . ' sec'
                ];
            }
        }

        // Commandes en préparation → expédition (après 90 secondes)
        $preparationOrders = Order::where('status', Order::STATUS_PREPARATION)
                                  ->where('preparation_at', '>', $now->copy()->subSeconds(90))
                                  ->orderBy('preparation_at')
                                  ->take(5)
                                  ->get();

        foreach ($preparationOrders as $order) {
            $nextTransitionTime = $order->preparation_at->addSeconds(90);
            $timeRemaining = $now->diffInSeconds($nextTransitionTime, false);
            
            if ($timeRemaining > 0) {
                $transitions[] = [
                    'order_number' => $order->order_number,
                    'current_status' => 'En préparation',
                    'next_status' => 'Expédiée',
                    'time_remaining' => $timeRemaining > 60 ? 
                        ceil($timeRemaining / 60) . ' min' : 
                        $timeRemaining . ' sec'
                ];
            }
        }

        return collect($transitions)->sortBy('time_remaining')->take(10)->values()->all();
    }

    // GESTION DES ANNULATIONS ET RETOURS
    
    /**
     * Traduire les statuts en français
     */
    private function translateStatus($status)
    {
        $translations = $this->getStatusTranslations();
        return $translations[$status] ?? ucfirst($status);
    }
    
    /**
     * Obtenir les traductions de statuts
     */
    private function getStatusTranslations()
    {
        return [
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'preparation' => 'En préparation',
            'shipped' => 'Expédiée',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
            'returned' => 'Retournée'
        ];
    }
    
    /**
     * Interface de recherche et gestion des commandes pour annulation/retour
     */
    public function orderCancellationIndex(Request $request)
    {
        $query = Order::with(['user', 'items.product'])
            ->whereIn('status', [
                Order::STATUS_CONFIRMED, 
                Order::STATUS_PREPARATION, 
                Order::STATUS_SHIPPED, 
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED,
                Order::STATUS_RETURNED
            ]);

        // Recherche par numéro de commande ou email client
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('email', 'like', '%' . $request->search . '%')
                               ->orWhere('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filtre par statut
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filtre par date
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);
        
        // Passer les traductions de statuts à la vue
        $statusTranslations = $this->getStatusTranslations();
        
        return view('admin.orders.cancellation', compact('orders', 'statusTranslations'));
    }

    /**
     * Vérifier si une commande peut être annulée
     */
    public function checkCancellationEligibility(Order $order)
    {
        $canCancel = in_array($order->status, [
            Order::STATUS_CONFIRMED, 
            Order::STATUS_PREPARATION
        ]);

        $reason = $canCancel ? null : 'La commande a déjà été expédiée et ne peut plus être annulée.';

        return response()->json([
            'can_cancel' => $canCancel,
            'reason' => $reason,
            'current_status' => $order->status
        ]);
    }

    /**
     * Annuler une commande
     */
    public function cancelOrder(Request $request, Order $order)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
            'refund_method' => 'required|in:original,store_credit',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        // Vérifier que la commande peut être annulée
        if (!in_array($order->status, [Order::STATUS_CONFIRMED, Order::STATUS_PREPARATION])) {
            return back()->with('error', 'Cette commande ne peut plus être annulée car elle a déjà été expédiée.');
        }

        DB::transaction(function() use ($request, $order) {
            // Mettre à jour la commande
            $order->update([
                'status' => Order::STATUS_CANCELLED,
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason,
                'payment_status' => Order::PAYMENT_REFUNDED,
            ]);

            // Remettre en stock les produits
            foreach ($order->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }

            // Log de l'action admin
            \Illuminate\Support\Facades\Log::info('Commande annulée par admin', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'admin_user_id' => auth()->id(),
                'admin_user_name' => auth()->user()->name,
                'reason' => $request->cancellation_reason,
                'refund_method' => $request->refund_method,
                'admin_notes' => $request->admin_notes,
                'cancelled_at' => now()
            ]);

            // Envoyer notification au client
            $order->user->notify(new \App\Notifications\OrderCancelled($order, $request->cancellation_reason));
        });

        return redirect()->route('admin.orders.cancellation')
            ->with('success', "Commande #{$order->order_number} annulée avec succès.");
    }

    /**
     * Vérifier l'éligibilité au retour d'une commande
     */
    public function checkReturnEligibility(Order $order)
    {
        if ($order->status !== Order::STATUS_DELIVERED) {
            return response()->json([
                'can_return' => false,
                'reason' => 'La commande doit être livrée pour pouvoir être retournée.',
                'returnable_items' => []
            ]);
        }

        // Vérifier le délai de 14 jours
        $returnDeadline = $order->return_deadline ?? Carbon::parse($order->delivered_at)->addDays(14);
        $isWithinDeadline = now()->lte($returnDeadline);

        if (!$isWithinDeadline) {
            return response()->json([
                'can_return' => false,
                'reason' => 'Le délai de retour de 14 jours est dépassé.',
                'deadline' => $returnDeadline->format('d/m/Y'),
                'returnable_items' => []
            ]);
        }

        // Identifier les produits retournables (non périssables)
        $returnableItems = [];
        $nonReturnableItems = [];

        foreach ($order->items as $item) {
            // Vérifier si le produit est périssable (en utilisant le produit ou les données de l'item)
            $isPerishable = $item->product ? $item->product->isPerishable() : $item->is_perishable;
            
            if (!$isPerishable) {
                $returnableItems[] = [
                    'id' => $item->id,
                    'product_name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'price' => $item->unit_price,
                    'total' => $item->total_price
                ];
            } else {
                $nonReturnableItems[] = [
                    'product_name' => $item->product_name,
                    'reason' => 'Produit périssable'
                ];
            }
        }

        return response()->json([
            'can_return' => count($returnableItems) > 0,
            'reason' => count($returnableItems) === 0 ? 'Aucun produit de cette commande n\'est retournable (produits périssables).' : null,
            'deadline' => $returnDeadline->format('d/m/Y'),
            'returnable_items' => $returnableItems,
            'non_returnable_items' => $nonReturnableItems,
            'total_returnable_amount' => collect($returnableItems)->sum('total')
        ]);
    }

    /**
     * Créer un retour pour une commande
     */
    public function createReturn(Request $request, Order $order)
    {
        $request->validate([
            'return_items' => 'required|array|min:1',
            'return_items.*.item_id' => 'required|exists:order_items,id',
            'return_items.*.quantity' => 'required|integer|min:1',
            'return_reason' => 'required|string|max:500',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        // Vérifier l'éligibilité
        $eligibility = $this->checkReturnEligibility($order);
        $eligibilityData = $eligibility->getData(true);
        
        if (!$eligibilityData['can_return']) {
            return back()->with('error', $eligibilityData['reason']);
        }

        try {
            $returnNumber = '';
            $totalRefundAmount = 0;
            
            DB::transaction(function() use ($request, $order, &$returnNumber, &$totalRefundAmount) {
                // Générer un numéro de retour unique avec timestamp précis et random
                $maxAttempts = 10;
                $attempt = 0;
                do {
                    $attempt++;
                    $microtime = str_replace('.', '', microtime(true)); // Timestamp avec microsecondes
                    $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); // 4 chiffres random
                    $returnNumber = 'RET' . substr($microtime, 0, 14) . $random;
                } while (\App\Models\OrderReturn::where('return_number', $returnNumber)->exists() && $attempt < $maxAttempts);
                
                if ($attempt >= $maxAttempts) {
                    throw new \Exception('Impossible de générer un numéro de retour unique après ' . $maxAttempts . ' tentatives.');
                }

                foreach ($request->return_items as $returnItemData) {
                    $orderItem = $order->items()->find($returnItemData['item_id']);
                    
                    // Vérifier que le produit n'est pas périssable
                    $isPerishable = $orderItem->product ? $orderItem->product->isPerishable() : $orderItem->is_perishable;
                    if ($isPerishable) {
                        continue;
                    }

                    // Vérifier la quantité
                    $returnQuantity = min($returnItemData['quantity'], $orderItem->quantity);
                    $refundAmount = $returnQuantity * $orderItem->unit_price;
                    $totalRefundAmount += $refundAmount;

                    // Créer l'enregistrement de retour
                    \App\Models\OrderReturn::create([
                        'order_id' => $order->id,
                        'order_item_id' => $orderItem->id,
                        'user_id' => $order->user_id,
                        'return_number' => $returnNumber,
                        'quantity_returned' => $returnQuantity,
                        'refund_amount' => $refundAmount,
                        'return_reason' => $request->return_reason,
                        'admin_notes' => $request->admin_notes,
                        'status' => 'approved', // Auto-approuvé par admin
                        'refund_status' => 'pending',
                        'requested_at' => now(),
                        'approved_at' => now(),
                        'is_within_return_period' => true,
                        'return_deadline' => $order->return_deadline ?? Carbon::parse($order->delivered_at)->addDays(14),
                    ]);

                    // Remettre en stock
                    $orderItem->product->increment('quantity', $returnQuantity);
                }

                // Changer le statut de la commande à "retournée"
                $order->update(['status' => \App\Models\Order::STATUS_RETURNED]);

                // Log de l'action
                \Illuminate\Support\Facades\Log::info('Retour créé par admin', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'return_number' => $returnNumber,
                    'admin_user_id' => auth()->id(),
                    'admin_user_name' => auth()->user()->name,
                    'reason' => $request->return_reason,
                    'admin_notes' => $request->admin_notes,
                    'refund_amount' => $totalRefundAmount,
                    'created_at' => now()
                ]);

                // Envoyer notification au client
                $order->user->notify(new \App\Notifications\OrderReturnApproved($order, $returnNumber, $totalRefundAmount));
            });

            return redirect()->route('admin.orders.cancellation')
                ->with('success', "Retour créé avec succès pour la commande #{$order->order_number}.");
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Si c'est une erreur de contrainte d'unicité, on essaie de nouveau
            if (strpos($e->getMessage(), 'Duplicate entry') !== false && strpos($e->getMessage(), 'return_number_unique') !== false) {
                return back()->with('error', 'Une erreur temporaire s\'est produite. Veuillez réessayer dans quelques secondes.');
            }
            // Pour les autres erreurs, on les relance
            throw $e;
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du retour : ' . $e->getMessage());
        }
    }
}
