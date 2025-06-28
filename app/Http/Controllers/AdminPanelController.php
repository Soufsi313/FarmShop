<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Blog;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'can:access admin panel']);
    }

    /**
     * Afficher le tableau de bord admin
     */
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            'products_count' => Product::count(),
            'orders_count' => Order::count(),
            'blogs_count' => Blog::count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_orders' => Order::with('user')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Gestion des utilisateurs
     */
    public function users()
    {
        $users = User::with('roles')->paginate(20);
        $roles = Role::all();
        
        return view('admin.users', compact('users', 'roles'));
    }

    /**
     * Assigner un rôle à un utilisateur
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'Rôle assigné avec succès !');
    }

    /**
     * Retirer un rôle d'un utilisateur
     */
    public function removeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user->removeRole($request->role);

        return redirect()->back()->with('success', 'Rôle retiré avec succès !');
    }
}
