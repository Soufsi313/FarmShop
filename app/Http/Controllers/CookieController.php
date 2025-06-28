<?php

namespace App\Http\Controllers;

use App\Models\Cookie;
use App\Models\CookieConsent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CookieController extends Controller
{
    /**
     * Afficher les préférences de cookies
     */
    public function preferences(Request $request)
    {
        $cookiesByCategory = Cookie::getByCategory();
        $currentConsent = CookieConsent::getCurrentConsent();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'categories' => $cookiesByCategory,
                    'current_consent' => $currentConsent,
                    'categories_list' => Cookie::getCategories()
                ],
                'message' => 'Préférences cookies récupérées'
            ]);
        }
        
        return view('cookies.preferences', compact('cookiesByCategory', 'currentConsent'));
    }
    
    /**
     * Accepter tous les cookies
     */
    public function acceptAll(Request $request)
    {
        $metadata = [
            'source' => 'accept_all_button',
            'user_id' => Auth::id(),
            'timestamp' => now()->toISOString()
        ];
        
        $consent = CookieConsent::acceptAll($metadata);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $consent,
                'message' => 'Tous les cookies ont été acceptés'
            ]);
        }
        
        return back()->with('success', 'Tous les cookies ont été acceptés');
    }
    
    /**
     * Refuser tous les cookies (sauf essentiels)
     */
    public function rejectAll(Request $request)
    {
        $metadata = [
            'source' => 'reject_all_button',
            'user_id' => Auth::id(),
            'timestamp' => now()->toISOString()
        ];
        
        $consent = CookieConsent::rejectAll($metadata);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $consent,
                'message' => 'Seuls les cookies essentiels ont été acceptés'
            ]);
        }
        
        return back()->with('success', 'Seuls les cookies essentiels ont été acceptés');
    }
    
    /**
     * Sauvegarder les préférences personnalisées
     */
    public function savePreferences(Request $request)
    {
        $validated = $request->validate([
            'consents' => 'required|array',
            'consents.*' => 'boolean'
        ]);
        
        // S'assurer que les cookies essentiels sont toujours acceptés
        $consents = $validated['consents'];
        $consents['essential'] = true;
        
        $metadata = [
            'source' => 'custom_preferences',
            'user_id' => Auth::id(),
            'timestamp' => now()->toISOString(),
            'details' => $consents
        ];
        
        $consent = CookieConsent::createOrUpdate($consents, 'custom', $metadata);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $consent,
                'message' => 'Vos préférences de cookies ont été sauvegardées'
            ]);
        }
        
        return back()->with('success', 'Vos préférences de cookies ont été sauvegardées');
    }
    
    /**
     * Obtenir le statut actuel des consentements
     */
    public function getConsentStatus(Request $request)
    {
        $currentConsent = CookieConsent::getCurrentConsent();
        $acceptedCategories = CookieConsent::getAcceptedCategories();
        
        return response()->json([
            'success' => true,
            'data' => [
                'has_consent' => $currentConsent !== null,
                'consent' => $currentConsent,
                'accepted_categories' => $acceptedCategories,
                'consent_summary' => $currentConsent ? $currentConsent->summary : null
            ],
            'message' => 'Statut des consentements récupéré'
        ]);
    }
    
    /**
     * Vérifier si une catégorie spécifique est acceptée
     */
    public function checkCategory(Request $request, $category)
    {
        $isAccepted = CookieConsent::isConsentGiven($category);
        
        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category,
                'is_accepted' => $isAccepted,
                'is_essential' => $category === 'essential'
            ],
            'message' => "Statut pour la catégorie {$category} récupéré"
        ]);
    }
    
    /**
     * Obtenir la liste des cookies par catégorie
     */
    public function getCookiesByCategory(Request $request)
    {
        $cookiesByCategory = Cookie::getByCategory();
        
        return response()->json([
            'success' => true,
            'data' => $cookiesByCategory,
            'message' => 'Liste des cookies par catégorie récupérée'
        ]);
    }
    
    /**
     * Obtenir l'historique des consentements de l'utilisateur
     */
    public function getConsentHistory(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentification requise'
            ], 401);
        }
        
        $history = Auth::user()
            ->cookieConsents()
            ->latest('consent_date')
            ->paginate(10);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $history,
                'message' => 'Historique des consentements récupéré'
            ]);
        }
        
        return view('user.cookie-history', compact('history'));
    }
    
    /**
     * Révoquer le consentement actuel
     */
    public function revokeConsent(Request $request)
    {
        $currentConsent = CookieConsent::getCurrentConsent();
        
        if ($currentConsent) {
            $currentConsent->update(['is_active' => false]);
        }
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Consentement révoqué. Seuls les cookies essentiels sont maintenant actifs.'
            ]);
        }
        
        return back()->with('success', 'Consentement révoqué. Seuls les cookies essentiels sont maintenant actifs.');
    }
    
    /**
     * Afficher la politique de cookies
     */
    public function policy(Request $request)
    {
        $cookiesByCategory = Cookie::getByCategory();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'categories' => $cookiesByCategory,
                    'categories_list' => Cookie::getCategories(),
                    'types_list' => Cookie::getTypes()
                ],
                'message' => 'Politique de cookies récupérée'
            ]);
        }
        
        return view('cookies.policy', compact('cookiesByCategory'));
    }
}
