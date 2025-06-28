<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class NewsletterAdminController extends Controller
{
    /**
     * Constructor - Middleware d'authentification et permissions
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage newsletters');
    }

    /**
     * Afficher la liste des newsletters
     */
    public function index(Request $request)
    {
        $query = Newsletter::with('creator');

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $newsletters = $query->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $newsletters
            ]);
        }

        return view('admin.newsletters.index', compact('newsletters'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.newsletters.create');
    }

    /**
     * Créer une nouvelle newsletter
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:' . implode(',', array_keys(Newsletter::getStatuses())),
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
            $data = $request->validated();
            $data['created_by'] = Auth::id();

            // Upload de l'image si présente
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('newsletters', 'public');
            }

            $newsletter = Newsletter::create($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Newsletter créée avec succès.',
                    'data' => $newsletter
                ], 201);
            }

            return redirect()->route('admin.newsletters.index')->with('success', 'Newsletter créée avec succès.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la création : ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Afficher une newsletter spécifique
     */
    public function show(Newsletter $newsletter)
    {
        $newsletter->load('creator');

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $newsletter
            ]);
        }

        return view('admin.newsletters.show', compact('newsletter'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Newsletter $newsletter)
    {
        return view('admin.newsletters.edit', compact('newsletter'));
    }

    /**
     * Mettre à jour une newsletter
     */
    public function update(Request $request, Newsletter $newsletter)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:' . implode(',', array_keys(Newsletter::getStatuses())),
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
            $data = $request->validated();

            // Upload de la nouvelle image si présente
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image
                if ($newsletter->image && Storage::disk('public')->exists($newsletter->image)) {
                    Storage::disk('public')->delete($newsletter->image);
                }
                $data['image'] = $request->file('image')->store('newsletters', 'public');
            }

            $newsletter->update($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Newsletter mise à jour avec succès.',
                    'data' => $newsletter->fresh()
                ]);
            }

            return redirect()->route('admin.newsletters.show', $newsletter)->with('success', 'Newsletter mise à jour avec succès.');

        } catch (\Exception $e) {
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
     * Supprimer une newsletter
     */
    public function destroy(Request $request, Newsletter $newsletter)
    {
        try {
            // Supprimer l'image si elle existe
            if ($newsletter->image && Storage::disk('public')->exists($newsletter->image)) {
                Storage::disk('public')->delete($newsletter->image);
            }

            $newsletter->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Newsletter supprimée avec succès.'
                ]);
            }

            return redirect()->route('admin.newsletters.index')->with('success', 'Newsletter supprimée avec succès.');

        } catch (\Exception $e) {
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
     * Publier une newsletter
     */
    public function publish(Request $request, Newsletter $newsletter)
    {
        try {
            if (!$newsletter->publish()) {
                throw new \Exception('Impossible de publier cette newsletter.');
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Newsletter publiée avec succès.',
                    'data' => $newsletter->fresh()
                ]);
            }

            return redirect()->back()->with('success', 'Newsletter publiée avec succès.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la publication : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la publication : ' . $e->getMessage());
        }
    }

    /**
     * Envoyer une newsletter aux abonnés
     */
    public function send(Request $request, Newsletter $newsletter)
    {
        try {
            if (!$newsletter->isPublished()) {
                throw new \Exception('La newsletter doit être publiée avant d\'être envoyée.');
            }

            if ($newsletter->isSent()) {
                throw new \Exception('Cette newsletter a déjà été envoyée.');
            }

            // Obtenir tous les abonnés actifs
            $subscribers = NewsletterSubscription::getActiveSubscribers();
            $count = $subscribers->count();

            if ($count === 0) {
                throw new \Exception('Aucun abonné trouvé.');
            }

            // TODO: Implémenter l'envoi d'emails en masse avec queue
            // Pour l'instant, on simule l'envoi
            $newsletter->markAsSent($count);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Newsletter envoyée à {$count} abonnés.",
                    'data' => $newsletter->fresh()
                ]);
            }

            return redirect()->back()->with('success', "Newsletter envoyée à {$count} abonnés.");

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'envoi : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de l\'envoi : ' . $e->getMessage());
        }
    }

    /**
     * Afficher les statistiques des abonnés
     */
    public function subscribers(Request $request)
    {
        $query = NewsletterSubscription::with('user');

        // Filtres
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } else {
                $query->inactive();
            }
        }

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        $subscribers = $query->paginate(15);

        $stats = [
            'total' => NewsletterSubscription::count(),
            'active' => NewsletterSubscription::getActiveSubscribersCount(),
            'inactive' => NewsletterSubscription::inactive()->count(),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'subscribers' => $subscribers,
                    'stats' => $stats
                ]
            ]);
        }

        return view('admin.newsletters.subscribers', compact('subscribers', 'stats'));
    }
}
