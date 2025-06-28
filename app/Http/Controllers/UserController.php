<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage users')->except(['show', 'exportData', 'subscribeNewsletter', 'unsubscribeNewsletter']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('manage users');

        $query = User::with('roles');

        // Filtrage par rôle
        if ($request->filled('role')) {
            $query->byRole($request->role);
        }

        // Filtrage par statut (actif/supprimé)
        if ($request->filled('status')) {
            if ($request->status === 'deleted') {
                $query->onlyTrashed();
            } else {
                $query->active();
            }
        }

        // Filtrage par newsletter
        if ($request->filled('newsletter')) {
            if ($request->newsletter === 'subscribed') {
                $query->newsletterSubscribed();
            }
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('manage users');
        
        $roles = [
            User::ROLE_USER => 'Utilisateur',
            User::ROLE_ADMIN => 'Administrateur'
        ];

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user->load('roles', 'permissions');
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('manage users');

        $roles = [
            User::ROLE_USER => 'Utilisateur',
            User::ROLE_ADMIN => 'Administrateur'
        ];

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $updateData = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(User $user)
    {
        $this->authorize('delete users');

        // Vérifier si l'utilisateur connecté peut supprimer cet utilisateur
        if (!auth()->user()->canDeleteUser($user)) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas supprimer cet utilisateur.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Restore a soft deleted user.
     */
    public function restore($id)
    {
        $this->authorize('manage users');

        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur restauré avec succès.');
    }

    /**
     * Force delete a user (permanent deletion).
     */
    public function forceDelete($id)
    {
        $this->authorize('manage users');

        $user = User::withTrashed()->findOrFail($id);
        
        // Seuls les superusers peuvent forcer la suppression
        if (!auth()->user()->isSuperuser()) {
            return redirect()->back()
                ->with('error', 'Seuls les super administrateurs peuvent supprimer définitivement un utilisateur.');
        }

        $user->forceDelete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé définitivement.');
    }

    /**
     * Subscribe user to newsletter.
     */
    public function subscribeNewsletter(Request $request)
    {
        $user = auth()->user();
        $user->subscribeToNewsletter();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Abonnement à la newsletter réussi.']);
        }

        return redirect()->back()
            ->with('success', 'Vous êtes maintenant abonné à notre newsletter.');
    }

    /**
     * Unsubscribe user from newsletter.
     */
    public function unsubscribeNewsletter(Request $request)
    {
        $user = auth()->user();
        $user->unsubscribeFromNewsletter();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Désabonnement de la newsletter réussi.']);
        }

        return redirect()->back()
            ->with('success', 'Vous êtes maintenant désabonné de notre newsletter.');
    }

    /**
     * Export user personal data (GDPR compliance).
     */
    public function exportData(Request $request)
    {
        $user = auth()->user();
        $data = $user->exportData();

        $filename = "user_data_{$user->username}_" . now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->header('Content-Type', 'application/json');
    }

    /**
     * Bulk actions on users.
     */
    public function bulkAction(Request $request)
    {
        $this->authorize('manage users');

        $validator = Validator::make($request->all(), [
            'action' => 'required|in:delete,restore,assign_role',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'role' => 'required_if:action,assign_role|in:' . implode(',', [User::ROLE_USER, User::ROLE_ADMIN])
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $userIds = $request->user_ids;
        $action = $request->action;

        switch ($action) {
            case 'delete':
                $users = User::whereIn('id', $userIds)->get();
                foreach ($users as $user) {
                    if (auth()->user()->canDeleteUser($user)) {
                        $user->delete();
                    }
                }
                $message = 'Utilisateurs supprimés avec succès.';
                break;

            case 'restore':
                User::withTrashed()->whereIn('id', $userIds)->restore();
                $message = 'Utilisateurs restaurés avec succès.';
                break;

            case 'assign_role':
                $users = User::whereIn('id', $userIds)->get();
                foreach ($users as $user) {
                    $user->syncRoles([$request->role]);
                }
                $message = 'Rôles assignés avec succès.';
                break;
        }

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }

    /**
     * Get user statistics for dashboard.
     */
    public function statistics()
    {
        $this->authorize('manage users');

        $stats = [
            'total_users' => User::count(),
            'active_users' => User::active()->count(),
            'deleted_users' => User::onlyTrashed()->count(),
            'newsletter_subscribers' => User::newsletterSubscribed()->count(),
            'admins' => User::role(User::ROLE_ADMIN)->count(),
            'recent_registrations' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return response()->json($stats);
    }
}
