<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    /**
     * Constructor - Middleware d'authentification
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['subscribe', 'unsubscribe', 'unsubscribeByToken']);
    }

    /**
     * S'abonner à la newsletter (public)
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'preferences' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator);
        }

        $email = $request->email;

        // Vérifier si déjà abonné
        if (NewsletterSubscription::isSubscribed($email)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette adresse email est déjà abonnée à la newsletter.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Cette adresse email est déjà abonnée à la newsletter.');
        }

        try {
            // Créer l'abonnement
            $user = Auth::user();
            $userId = $user ? $user->id : null;
            
            NewsletterSubscription::subscribe($email, $userId, $request->preferences ?? []);

            // Mettre à jour l'utilisateur si connecté
            if ($user && $user->email === $email) {
                $user->subscribeToNewsletter($request->preferences ?? []);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vous êtes maintenant abonné à notre newsletter !'
                ]);
            }

            return redirect()->back()->with('success', 'Vous êtes maintenant abonné à notre newsletter !');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'abonnement : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de l\'abonnement : ' . $e->getMessage());
        }
    }

    /**
     * Se désabonner de la newsletter (utilisateur connecté)
     */
    public function unsubscribe(Request $request)
    {
        $user = Auth::user();

        try {
            $user->unsubscribeFromNewsletter();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vous êtes maintenant désabonné de la newsletter.'
                ]);
            }

            return redirect()->back()->with('success', 'Vous êtes maintenant désabonné de la newsletter.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du désabonnement : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors du désabonnement : ' . $e->getMessage());
        }
    }

    /**
     * Se désabonner via token (lien dans l'email)
     */
    public function unsubscribeByToken(Request $request, string $token)
    {
        try {
            $subscription = NewsletterSubscription::unsubscribeByToken($token);

            if (!$subscription) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Token de désabonnement invalide.'
                    ], 404);
                }
                return view('newsletter.unsubscribe', ['success' => false, 'message' => 'Token de désabonnement invalide.']);
            }

            // Mettre à jour l'utilisateur si trouvé
            if ($subscription->user) {
                $subscription->user->unsubscribeFromNewsletter();
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vous avez été désabonné avec succès de la newsletter.'
                ]);
            }

            return view('newsletter.unsubscribe', ['success' => true, 'message' => 'Vous avez été désabonné avec succès de la newsletter.']);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du désabonnement : ' . $e->getMessage()
                ], 500);
            }

            return view('newsletter.unsubscribe', ['success' => false, 'message' => 'Erreur lors du désabonnement : ' . $e->getMessage()]);
        }
    }

    /**
     * Basculer l'abonnement newsletter (utilisateur connecté)
     */
    public function toggle(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'preferences' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator);
        }

        try {
            $wasSubscribed = $user->isNewsletterSubscribed();
            $user->toggleNewsletterSubscription($request->preferences ?? []);

            $message = $wasSubscribed 
                ? 'Vous êtes maintenant désabonné de la newsletter.'
                : 'Vous êtes maintenant abonné à notre newsletter !';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'subscribed' => !$wasSubscribed
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du changement d\'abonnement : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors du changement d\'abonnement : ' . $e->getMessage());
        }
    }

    /**
     * Vérifier le statut d'abonnement (utilisateur connecté)
     */
    public function status(Request $request)
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'subscribed' => $user->isNewsletterSubscribed(),
                'email' => $user->email,
                'subscribed_at' => $user->newsletter_subscribed_at,
                'unsubscribed_at' => $user->newsletter_unsubscribed_at,
            ]
        ]);
    }
}
