<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NewsletterSubscriptionController extends Controller
{
    /**
     * Constructor - Middleware d'authentification pour certaines actions
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['store', 'unsubscribeByToken', 'checkStatus']);
        $this->middleware('permission:manage newsletters')->only(['index', 'destroy', 'bulkAction', 'export']);
    }

    /**
     * Afficher la liste des abonnements (admin)
     */
    public function index(Request $request)
    {
        $query = NewsletterSubscription::with('user');

        // Filtres
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            }
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('subscribed_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('subscribed_at', '<=', $request->date_to);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $subscriptions = $query->paginate(15);

        // Statistiques
        $stats = [
            'total' => NewsletterSubscription::count(),
            'active' => NewsletterSubscription::getActiveSubscribersCount(),
            'inactive' => NewsletterSubscription::inactive()->count(),
            'today' => NewsletterSubscription::whereDate('subscribed_at', today())->count(),
            'this_week' => NewsletterSubscription::whereBetween('subscribed_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => NewsletterSubscription::whereMonth('subscribed_at', now()->month)->count(),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'subscriptions' => $subscriptions,
                    'stats' => $stats
                ]
            ]);
        }

        return view('admin.newsletter-subscriptions.index', compact('subscriptions', 'stats'));
    }

    /**
     * Créer un nouvel abonnement
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'preferences' => 'nullable|array',
            'preferences.*' => 'string',
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
            DB::beginTransaction();

            // Trouver l'utilisateur s'il existe
            $user = User::where('email', $email)->first();
            $userId = $user ? $user->id : null;

            // Créer l'abonnement
            $subscription = NewsletterSubscription::subscribe(
                $email, 
                $userId, 
                $request->preferences ?? []
            );

            // Mettre à jour l'utilisateur si trouvé
            if ($user) {
                $user->subscribeToNewsletter($request->preferences ?? []);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Abonnement créé avec succès !',
                    'data' => $subscription
                ], 201);
            }

            return redirect()->back()->with('success', 'Abonnement créé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Afficher un abonnement spécifique
     */
    public function show(NewsletterSubscription $subscription)
    {
        $subscription->load('user');

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $subscription
            ]);
        }

        return view('admin.newsletter-subscriptions.show', compact('subscription'));
    }

    /**
     * Mettre à jour un abonnement
     */
    public function update(Request $request, NewsletterSubscription $subscription)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:newsletter_subscriptions,email,' . $subscription->id,
            'is_active' => 'required|boolean',
            'preferences' => 'nullable|array',
            'preferences.*' => 'string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Mettre à jour l'abonnement
            $subscription->update([
                'email' => $request->email,
                'preferences' => $request->preferences ?? [],
            ]);

            // Gérer le statut actif/inactif
            if ($request->is_active && !$subscription->isActive()) {
                $subscription->activate();
            } elseif (!$request->is_active && $subscription->isActive()) {
                $subscription->deactivate();
            }

            // Mettre à jour l'utilisateur associé si changement d'email
            if ($subscription->user && $subscription->wasChanged('email')) {
                $newUser = User::where('email', $request->email)->first();
                $subscription->user_id = $newUser ? $newUser->id : null;
                $subscription->save();
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Abonnement mis à jour avec succès.',
                    'data' => $subscription->fresh()
                ]);
            }

            return redirect()->route('admin.newsletter-subscriptions.show', $subscription)
                           ->with('success', 'Abonnement mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Supprimer un abonnement
     */
    public function destroy(Request $request, NewsletterSubscription $subscription)
    {
        try {
            DB::beginTransaction();

            // Mettre à jour l'utilisateur associé
            if ($subscription->user) {
                $subscription->user->unsubscribeFromNewsletter();
            }

            $subscription->delete();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Abonnement supprimé avec succès.'
                ]);
            }

            return redirect()->route('admin.newsletter-subscriptions.index')
                           ->with('success', 'Abonnement supprimé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Désabonner via token (public)
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
                return view('newsletter.unsubscribe', [
                    'success' => false, 
                    'message' => 'Token de désabonnement invalide.'
                ]);
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

            return view('newsletter.unsubscribe', [
                'success' => true, 
                'message' => 'Vous avez été désabonné avec succès de la newsletter.'
            ]);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du désabonnement : ' . $e->getMessage()
                ], 500);
            }

            return view('newsletter.unsubscribe', [
                'success' => false, 
                'message' => 'Erreur lors du désabonnement : ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Vérifier le statut d'abonnement d'un email (public)
     */
    public function checkStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->email;
        $subscription = NewsletterSubscription::byEmail($email)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'email' => $email,
                'subscribed' => NewsletterSubscription::isSubscribed($email),
                'subscription' => $subscription,
            ]
        ]);
    }

    /**
     * Actions en lot sur les abonnements (admin)
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'subscription_ids' => 'required|array|min:1',
            'subscription_ids.*' => 'exists:newsletter_subscriptions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $subscriptions = NewsletterSubscription::whereIn('id', $request->subscription_ids)->get();
            $count = $subscriptions->count();

            foreach ($subscriptions as $subscription) {
                switch ($request->action) {
                    case 'activate':
                        $subscription->activate();
                        if ($subscription->user) {
                            $subscription->user->subscribeToNewsletter();
                        }
                        break;
                    case 'deactivate':
                        $subscription->deactivate();
                        if ($subscription->user) {
                            $subscription->user->unsubscribeFromNewsletter();
                        }
                        break;
                    case 'delete':
                        if ($subscription->user) {
                            $subscription->user->unsubscribeFromNewsletter();
                        }
                        $subscription->delete();
                        break;
                }
            }

            DB::commit();

            $actionLabels = [
                'activate' => 'activés',
                'deactivate' => 'désactivés',
                'delete' => 'supprimés',
            ];

            return response()->json([
                'success' => true,
                'message' => "{$count} abonnements {$actionLabels[$request->action]} avec succès."
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'action en lot : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exporter les abonnements (admin)
     */
    public function export(Request $request)
    {
        $query = NewsletterSubscription::with('user');

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            }
        }

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        $subscriptions = $query->get();

        $csvData = [];
        $csvData[] = ['Email', 'Nom', 'Statut', 'Date abonnement', 'Date désabonnement', 'Préférences'];

        foreach ($subscriptions as $subscription) {
            $csvData[] = [
                $subscription->email,
                $subscription->user ? $subscription->user->name : 'N/A',
                $subscription->is_active ? 'Actif' : 'Inactif',
                $subscription->subscribed_at ? $subscription->subscribed_at->format('Y-m-d H:i:s') : '',
                $subscription->unsubscribed_at ? $subscription->unsubscribed_at->format('Y-m-d H:i:s') : '',
                $subscription->preferences ? implode(', ', $subscription->preferences) : '',
            ];
        }

        $filename = 'newsletter_subscriptions_' . date('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
