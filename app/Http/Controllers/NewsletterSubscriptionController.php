<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use App\Models\NewsletterSend;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class NewsletterSubscriptionController extends Controller
{
    /**
     * S'abonner à la newsletter (Utilisateur connecté)
     */
    public function subscribe(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentification requise'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'source' => 'nullable|string|max:50',
                'preferences' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            $source = $request->get('source', 'manual');
            $subscription = $user->subscribeToNewsletterNew($source);

            if ($request->has('preferences')) {
                $subscription->updatePreferences($request->preferences);
            }

            return response()->json([
                'success' => true,
                'message' => 'Abonnement à la newsletter réussi',
                'data' => $subscription
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'abonnement newsletter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'abonnement'
            ], 500);
        }
    }

    /**
     * Se désabonner de la newsletter (Utilisateur connecté)
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentification requise'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'reason' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            $reason = $request->get('reason', 'user_choice');
            $user->unsubscribeFromNewsletterNew($reason);

            return response()->json([
                'success' => true,
                'message' => 'Désabonnement de la newsletter réussi'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du désabonnement newsletter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du désabonnement'
            ], 500);
        }
    }

    /**
     * Basculer l'état d'abonnement (Utilisateur connecté)
     */
    public function toggle(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentification requise'
                ], 401);
            }

            $subscription = NewsletterSubscription::findOrCreateForUser($user);
            $isSubscribed = $subscription->toggle($request->get('source', 'manual'));

            $message = $isSubscribed ? 
                'Abonnement à la newsletter activé' : 
                'Abonnement à la newsletter désactivé';

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'is_subscribed' => $isSubscribed,
                    'subscription' => $subscription->fresh()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du basculement abonnement newsletter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification de l\'abonnement'
            ], 500);
        }
    }

    /**
     * Obtenir le statut d'abonnement (Utilisateur connecté)
     */
    public function status(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentification requise'
                ], 401);
            }

            $subscription = $user->newsletterSubscription;
            $isSubscribed = $user->isSubscribedToNewsletter();

            return response()->json([
                'success' => true,
                'data' => [
                    'is_subscribed' => $isSubscribed,
                    'subscription' => $subscription,
                    'legacy_field' => $user->newsletter_subscribed
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du statut abonnement: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du statut'
            ], 500);
        }
    }

    /**
     * Mettre à jour les préférences d'abonnement (Utilisateur connecté)
     */
    public function updatePreferences(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentification requise'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'preferences' => 'required|array',
                'preferences.frequency' => 'nullable|in:daily,weekly,monthly',
                'preferences.categories' => 'nullable|array',
                'preferences.categories.*' => 'string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            $subscription = NewsletterSubscription::findOrCreateForUser($user);
            $subscription->updatePreferences($request->preferences);

            return response()->json([
                'success' => true,
                'message' => 'Préférences mises à jour avec succès',
                'data' => $subscription->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des préférences: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour des préférences'
            ], 500);
        }
    }

    /**
     * Désabonnement via lien email (Public)
     */
    public function unsubscribeByToken($token): JsonResponse
    {
        try {
            $send = NewsletterSend::findByUnsubscribeToken($token);
            
            if (!$send) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token de désabonnement invalide'
                ], 404);
            }

            // Enregistrer le désabonnement
            $send->recordUnsubscribe();

            return response()->json([
                'success' => true,
                'message' => 'Désabonnement effectué avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du désabonnement par token: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du désabonnement'
            ], 500);
        }
    }

    /**
     * Tracking d'ouverture d'email (Public)
     */
    public function trackOpen($token)
    {
        try {
            $send = NewsletterSend::findByTrackingToken($token);
            
            if ($send) {
                $metadata = [
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'opened_at' => now()->toISOString()
                ];
                
                $send->recordOpen($metadata);
            }

            // Retourner une image transparente 1x1
            $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
            
            return response($pixel)
                ->header('Content-Type', 'image/gif')
                ->header('Content-Length', strlen($pixel))
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');

        } catch (\Exception $e) {
            Log::error('Erreur lors du tracking d\'ouverture: ' . $e->getMessage());
            
            // Retourner quand même l'image pour ne pas casser l'affichage
            $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
            return response($pixel)->header('Content-Type', 'image/gif');
        }
    }

    /**
     * Tracking de clic dans l'email (Public)
     */
    public function trackClick(Request $request, $token): JsonResponse
    {
        try {
            $send = NewsletterSend::findByTrackingToken($token);
            
            if ($send) {
                $metadata = [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'clicked_at' => now()->toISOString(),
                    'url' => $request->get('url', '')
                ];
                
                $send->recordClick($metadata);
            }

            return response()->json([
                'success' => true,
                'message' => 'Clic enregistré'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du tracking de clic: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement du clic'
            ], 500);
        }
    }

    /**
     * Obtenir l'historique des newsletters reçues (Utilisateur connecté)
     */
    public function history(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentification requise'
                ], 401);
            }

            $sends = NewsletterSend::where('user_id', $user->id)
                ->with(['newsletter:id,title,subject,sent_at'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $sends
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de l\'historique: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'historique'
            ], 500);
        }
    }
}
