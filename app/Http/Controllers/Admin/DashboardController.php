<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\RentalCategory;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
     * Afficher un utilisateur spécifique
     */
    public function showUser(User $user)
    {
        $this->checkAdminAccess();
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Afficher le formulaire d'édition d'un utilisateur
     */
    public function editUser(User $user)
    {
        $this->checkAdminAccess();
        
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function updateUser(Request $request, User $user)
    {
        $this->checkAdminAccess();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:Admin,User',
            'newsletter_subscribed' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Mise à jour des données
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->newsletter_subscribed = $request->has('newsletter_subscribed');

        // Mise à jour du mot de passe si fourni
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroyUser(User $user)
    {
        $this->checkAdminAccess();
        
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
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

    /**
     * Gestion des catégories de location
     */
    public function rentalCategories(Request $request)
    {
        $this->checkAdminAccess();
        
        $query = RentalCategory::query();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $rentalCategories = $query->orderBy('display_order', 'asc')
                                 ->orderBy('name', 'asc')
                                 ->paginate(15);

        return view('admin.rental-categories.index', compact('rentalCategories'));
    }

    /**
     * Afficher le formulaire de création d'une catégorie de location
     */
    public function createRentalCategory()
    {
        $this->checkAdminAccess();
        
        return view('admin.rental-categories.create');
    }

    /**
     * Enregistrer une nouvelle catégorie de location
     */
    public function storeRentalCategory(Request $request)
    {
        $this->checkAdminAccess();
        
        $request->validate([
            'name' => 'required|string|max:255|unique:rental_categories,name',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        RentalCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'icon' => $request->icon,
            'display_order' => $request->display_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.rental-categories.index')
                        ->with('success', 'Catégorie de location créée avec succès.');
    }

    /**
     * Afficher une catégorie de location
     */
    public function showRentalCategory(RentalCategory $rentalCategory)
    {
        $this->checkAdminAccess();
        
        return view('admin.rental-categories.show', compact('rentalCategory'));
    }

    /**
     * Afficher le formulaire d'édition d'une catégorie de location
     */
    public function editRentalCategory(RentalCategory $rentalCategory)
    {
        $this->checkAdminAccess();
        
        return view('admin.rental-categories.edit', compact('rentalCategory'));
    }

    /**
     * Mettre à jour une catégorie de location
     */
    public function updateRentalCategory(Request $request, RentalCategory $rentalCategory)
    {
        $this->checkAdminAccess();
        
        $request->validate([
            'name' => 'required|string|max:255|unique:rental_categories,name,' . $rentalCategory->id,
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $rentalCategory->update([
            'name' => $request->name,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'icon' => $request->icon,
            'display_order' => $request->display_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.rental-categories.show', $rentalCategory)
                        ->with('success', 'Catégorie de location mise à jour avec succès.');
    }

    /**
     * Supprimer une catégorie de location
     */
    public function destroyRentalCategory(RentalCategory $rentalCategory)
    {
        $this->checkAdminAccess();
        
        // Vérifier si la catégorie a des produits associés
        $hasProducts = Product::where('rental_category_id', $rentalCategory->id)->exists();
        
        if ($hasProducts) {
            return redirect()->route('admin.rental-categories.index')
                           ->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits.');
        }

        $rentalCategory->delete();

        return redirect()->route('admin.rental-categories.index')
                        ->with('success', 'Catégorie de location supprimée avec succès.');
    }
}