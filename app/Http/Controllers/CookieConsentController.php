<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CookieConsent;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CookieConsentController extends Controller
{
    /**
     * Enregistrer le consentement de l'utilisateur
     */
    public function store(Request $request)
    {
        $request->validate([
            'consents' => 'required|array',
            'consents.essential' => 'required|boolean',
            'consents.analytics' => 'required|boolean',
            'consents.marketing' => 'required|boolean',
            'consents.personalization' => 'required|boolean',
            'consent_type' => 'required|in:accept_all,reject_all,custom'
        ]);

        // Désactiver les anciens consentements pour cet utilisateur/session
        $this->deactivateExistingConsents($request);

        // Créer le nouveau consentement
        $consent = CookieConsent::create([
            'user_id' => Auth::id(),
            'session_id' => $request->session()->getId(),
            'fingerprint' => $this->generateFingerprint($request),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'consents' => $request->consents,
            'consent_type' => $request->consent_type,
            'consent_date' => Carbon::now(),
            'expires_at' => Carbon::now()->addYear(), // Expire dans 1 an
            'is_active' => true,
            'metadata' => [
                'version' => '1.0',
                'language' => app()->getLocale(),
                'url' => $request->fullUrl(),
                'referrer' => $request->header('referer')
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Consentement enregistré avec succès',
            'consent_id' => $consent->id,
            'expires_at' => $consent->expires_at->toISOString()
        ]);
    }

    /**
     * Récupérer le consentement actuel de l'utilisateur
     */
    public function show(Request $request)
    {
        $consent = $this->getCurrentConsent($request);

        if (!$consent) {
            return response()->json([
                'has_consent' => false,
                'preferences' => [
                    'essential' => true,
                    'analytics' => false,
                    'marketing' => false,
                    'personalization' => false
                ]
            ]);
        }

        return response()->json([
            'has_consent' => true,
            'preferences' => $consent->consents,
            'consent_type' => $consent->consent_type,
            'consent_date' => $consent->consent_date->toISOString(),
            'expires_at' => $consent->expires_at ? $consent->expires_at->toISOString() : null
        ]);
    }

    /**
     * Supprimer le consentement (réinitialiser)
     */
    public function destroy(Request $request)
    {
        $this->deactivateExistingConsents($request);

        return response()->json([
            'success' => true,
            'message' => 'Consentement supprimé avec succès'
        ]);
    }

    /**
     * Obtenir le consentement actuel pour cet utilisateur/session
     */
    private function getCurrentConsent(Request $request)
    {
        $query = CookieConsent::where('is_active', true)
            ->where('expires_at', '>', Carbon::now());

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', $request->session()->getId())
                  ->orWhere('fingerprint', $this->generateFingerprint($request));
        }

        return $query->latest('consent_date')->first();
    }

    /**
     * Désactiver les consentements existants
     */
    private function deactivateExistingConsents(Request $request)
    {
        $query = CookieConsent::where('is_active', true);

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', $request->session()->getId());
        }

        $query->update(['is_active' => false]);
    }

    /**
     * Générer une empreinte du navigateur
     */
    private function generateFingerprint(Request $request)
    {
        $data = [
            'user_agent' => $request->userAgent(),
            'accept_language' => $request->header('accept-language'),
            'accept_encoding' => $request->header('accept-encoding'),
            'ip' => $request->ip()
        ];

        return hash('sha256', serialize($data));
    }

    /**
     * API pour obtenir les statistiques des consentements (admin)
     */
    public function statistics()
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }

        $totalConsents = CookieConsent::where('is_active', true)->count();
        $consentsByType = CookieConsent::where('is_active', true)
            ->groupBy('consent_type')
            ->selectRaw('consent_type, count(*) as count')
            ->pluck('count', 'consent_type');

        $consentsByCategory = CookieConsent::where('is_active', true)
            ->get()
            ->map(function ($consent) {
                return $consent->consents;
            })
            ->reduce(function ($carry, $consents) {
                foreach ($consents as $category => $accepted) {
                    if (!isset($carry[$category])) {
                        $carry[$category] = ['accepted' => 0, 'rejected' => 0];
                    }
                    if ($accepted) {
                        $carry[$category]['accepted']++;
                    } else {
                        $carry[$category]['rejected']++;
                    }
                }
                return $carry;
            }, []);

        return response()->json([
            'total_consents' => $totalConsents,
            'consents_by_type' => $consentsByType,
            'consents_by_category' => $consentsByCategory,
            'period' => [
                'start' => Carbon::now()->subDays(30)->toDateString(),
                'end' => Carbon::now()->toDateString()
            ]
        ]);
    }
}
