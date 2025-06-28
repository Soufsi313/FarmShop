<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cookie;
use App\Models\CookieConsent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CookieAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:manage cookies']);
    }
    
    /**
     * Liste des cookies (admin)
     */
    public function index(Request $request)
    {
        $query = Cookie::query();
        
        // Filtres
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        
        if ($request->filled('is_essential')) {
            $query->where('is_essential', $request->boolean('is_essential'));
        }
        
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('provider', 'LIKE', "%{$search}%");
            });
        }
        
        // Tri
        $sortBy = $request->input('sort', 'category');
        $sortOrder = $request->input('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);
        
        $cookies = $query->paginate(20);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $cookies,
                'message' => 'Cookies récupérés avec succès'
            ]);
        }
        
        return view('admin.cookies.index', compact('cookies'));
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = Cookie::getCategories();
        $types = Cookie::getTypes();
        
        return view('admin.cookies.create', compact('categories', 'types'));
    }
    
    /**
     * Stocker un nouveau cookie
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cookies,name',
            'category' => ['required', Rule::in(array_keys(Cookie::getCategories()))],
            'description' => 'required|string',
            'purpose' => 'required|string',
            'provider' => 'nullable|string|max:255',
            'duration_days' => 'nullable|integer|min:0',
            'type' => ['required', Rule::in(array_keys(Cookie::getTypes()))],
            'is_essential' => 'boolean',
            'is_active' => 'boolean',
            'domains' => 'nullable|array',
            'domains.*' => 'string|max:255',
            'technical_details' => 'nullable|array'
        ]);
        
        // Les cookies essentiels ne peuvent pas être désactivés
        if ($validated['is_essential']) {
            $validated['is_active'] = true;
        }
        
        $cookie = Cookie::create($validated);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $cookie,
                'message' => 'Cookie créé avec succès'
            ], 201);
        }
        
        return redirect()->route('admin.cookies.index')->with('success', 'Cookie créé avec succès');
    }
    
    /**
     * Afficher un cookie spécifique
     */
    public function show(Cookie $cookie)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $cookie,
                'message' => 'Cookie récupéré avec succès'
            ]);
        }
        
        return view('admin.cookies.show', compact('cookie'));
    }
    
    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Cookie $cookie)
    {
        $categories = Cookie::getCategories();
        $types = Cookie::getTypes();
        
        return view('admin.cookies.edit', compact('cookie', 'categories', 'types'));
    }
    
    /**
     * Mettre à jour un cookie
     */
    public function update(Request $request, Cookie $cookie)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('cookies', 'name')->ignore($cookie->id)],
            'category' => ['required', Rule::in(array_keys(Cookie::getCategories()))],
            'description' => 'required|string',
            'purpose' => 'required|string',
            'provider' => 'nullable|string|max:255',
            'duration_days' => 'nullable|integer|min:0',
            'type' => ['required', Rule::in(array_keys(Cookie::getTypes()))],
            'is_essential' => 'boolean',
            'is_active' => 'boolean',
            'domains' => 'nullable|array',
            'domains.*' => 'string|max:255',
            'technical_details' => 'nullable|array'
        ]);
        
        // Les cookies essentiels ne peuvent pas être désactivés
        if ($validated['is_essential']) {
            $validated['is_active'] = true;
        }
        
        $cookie->update($validated);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $cookie->fresh(),
                'message' => 'Cookie mis à jour avec succès'
            ]);
        }
        
        return redirect()->route('admin.cookies.index')->with('success', 'Cookie mis à jour avec succès');
    }
    
    /**
     * Supprimer un cookie
     */
    public function destroy(Request $request, Cookie $cookie)
    {
        // Empêcher la suppression des cookies essentiels
        if ($cookie->is_essential) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Les cookies essentiels ne peuvent pas être supprimés'
                ], 400);
            }
            
            return back()->with('error', 'Les cookies essentiels ne peuvent pas être supprimés');
        }
        
        $cookie->delete();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cookie supprimé avec succès'
            ]);
        }
        
        return redirect()->route('admin.cookies.index')->with('success', 'Cookie supprimé avec succès');
    }
    
    /**
     * Actions en lot sur les cookies
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:cookies,id'
        ]);
        
        $cookies = Cookie::whereIn('id', $request->ids);
        $count = $cookies->count();
        
        // Empêcher les actions sur les cookies essentiels pour certaines opérations
        if (in_array($request->action, ['deactivate', 'delete'])) {
            $essentialCount = $cookies->where('is_essential', true)->count();
            if ($essentialCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette action ne peut pas être effectuée sur des cookies essentiels'
                ], 400);
            }
        }
        
        switch ($request->action) {
            case 'activate':
                $cookies->update(['is_active' => true]);
                $message = "{$count} cookie(s) activé(s)";
                break;
                
            case 'deactivate':
                $cookies->update(['is_active' => false]);
                $message = "{$count} cookie(s) désactivé(s)";
                break;
                
            case 'delete':
                $cookies->delete();
                $message = "{$count} cookie(s) supprimé(s)";
                break;
        }
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
        
        return back()->with('success', $message);
    }
    
    /**
     * Statistiques des cookies
     */
    public function statistics()
    {
        $stats = [
            'total_cookies' => Cookie::count(),
            'active_cookies' => Cookie::where('is_active', true)->count(),
            'essential_cookies' => Cookie::where('is_essential', true)->count(),
            'cookies_by_category' => Cookie::selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category'),
            'cookies_by_type' => Cookie::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type'),
            'cookies_by_provider' => Cookie::whereNotNull('provider')
                ->selectRaw('provider, COUNT(*) as count')
                ->groupBy('provider')
                ->orderBy('count', 'desc')
                ->take(10)
                ->pluck('count', 'provider')
        ];
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques des cookies récupérées'
            ]);
        }
        
        return view('admin.cookies.statistics', compact('stats'));
    }
    
    /**
     * Gestion des consentements
     */
    public function consents(Request $request)
    {
        $query = CookieConsent::with(['user']);
        
        // Filtres
        if ($request->filled('consent_type')) {
            $query->where('consent_type', $request->input('consent_type'));
        }
        
        if ($request->filled('is_active')) {
            if ($request->boolean('is_active')) {
                $query->active();
            } else {
                $query->where('is_active', false);
            }
        }
        
        if ($request->filled('date_from')) {
            $query->where('consent_date', '>=', $request->input('date_from'));
        }
        
        if ($request->filled('date_to')) {
            $query->where('consent_date', '<=', $request->input('date_to'));
        }
        
        // Tri
        $sortBy = $request->input('sort', 'consent_date');
        $sortOrder = $request->input('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $consents = $query->paginate(20);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $consents,
                'message' => 'Consentements récupérés avec succès'
            ]);
        }
        
        return view('admin.cookies.consents', compact('consents'));
    }
    
    /**
     * Statistiques des consentements
     */
    public function consentStatistics()
    {
        $stats = [
            'total_consents' => CookieConsent::count(),
            'active_consents' => CookieConsent::active()->count(),
            'expired_consents' => CookieConsent::expired()->count(),
            'consents_by_type' => CookieConsent::selectRaw('consent_type, COUNT(*) as count')
                ->groupBy('consent_type')
                ->pluck('count', 'consent_type'),
            'consents_today' => CookieConsent::whereDate('consent_date', today())->count(),
            'consents_this_week' => CookieConsent::whereBetween('consent_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'consents_this_month' => CookieConsent::whereMonth('consent_date', now()->month)->count(),
            'acceptance_rates' => $this->calculateAcceptanceRates()
        ];
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques des consentements récupérées'
            ]);
        }
        
        return view('admin.cookies.consent-statistics', compact('stats'));
    }
    
    /**
     * Calculer les taux d'acceptation par catégorie
     */
    private function calculateAcceptanceRates()
    {
        $categories = array_keys(Cookie::getCategories());
        $rates = [];
        
        $totalConsents = CookieConsent::active()->count();
        
        if ($totalConsents === 0) {
            return $rates;
        }
        
        foreach ($categories as $category) {
            $acceptedCount = CookieConsent::active()
                ->whereRaw("JSON_EXTRACT(consents, '$.{$category}') = true")
                ->count();
            
            $rates[$category] = [
                'accepted' => $acceptedCount,
                'total' => $totalConsents,
                'percentage' => round(($acceptedCount / $totalConsents) * 100, 2)
            ];
        }
        
        return $rates;
    }
}
