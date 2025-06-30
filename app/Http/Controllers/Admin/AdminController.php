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
}
