<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
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
     * Afficher le dashboard principal
     */
    public function index()
    {
        $this->checkAdminAccess();
        
        $stats = [
            'users' => User::count(),
            'products' => Product::count() ?? 0,
            'categories' => Category::count() ?? 0,
            'orders' => Order::count() ?? 0,
            'recent_users' => User::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Gestion des utilisateurs
     */
    public function users(Request $request)
    {
        $this->checkAdminAccess();
        
        $query = User::query();
        
        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        // Validation des champs de tri
        $allowedSorts = ['name', 'username', 'email', 'role', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        
        $allowedOrders = ['asc', 'desc'];
        if (!in_array($sortOrder, $allowedOrders)) {
            $sortOrder = 'desc';
        }
        
        // Recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filtre par rôle
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }
        
        $users = $query->orderBy($sortBy, $sortOrder)->paginate(20);
        
        return view('admin.users.index', compact('users', 'sortBy', 'sortOrder'));
    }

    /**
     * Gestion des produits - Redirection vers le contrôleur dédié
     */
    public function products(Request $request)
    {
        return redirect()->route('admin.products.index');
    }

    /**
     * Gestion des catégories - Redirection vers le contrôleur dédié
     */
    public function categories(Request $request)
    {
        return redirect()->route('admin.categories.index');
    }

    /**
     * Gestion des commandes
     */
    public function orders(Request $request)
    {
        $this->checkAdminAccess();
        
        // Pour l'instant simulation avec pagination vide
        $orders = collect([]); // Collection vide pour simulation
        
        $currentPage = $request->get('page', 1);
        $perPage = 20;
        
        return view('admin.orders.index', compact('orders', 'currentPage', 'perPage'));
    }

    /**
     * Gestion des offres spéciales
     */
    public function specialOffers(Request $request)
    {
        $this->checkAdminAccess();
        
        return view('admin.special-offers.index');
    }

    /**
     * Paramètres du site
     */
    public function settings()
    {
        $this->checkAdminAccess();
        
        return view('admin.settings.index');
    }
}
