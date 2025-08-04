<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Models\NewsletterSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewsletterController extends Controller
{
    /**
     * Afficher la liste des newsletters
     */
    public function index(Request $request)
    {
        $query = Newsletter::with(['creator', 'updater'])
                          ->notTemplates()
                          ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $newsletters = $query->paginate(15);

        // Statistiques
        $stats = [
            'total' => Newsletter::notTemplates()->count(),
            'draft' => Newsletter::notTemplates()->where('status', 'draft')->count(),
            'scheduled' => Newsletter::notTemplates()->where('status', 'scheduled')->count(),
            'sent' => Newsletter::notTemplates()->where('status', 'sent')->count(),
            'subscribers' => User::where('newsletter_subscribed', true)->count()
        ];

        return view('admin.newsletters.index', compact('newsletters', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $templates = Newsletter::where('is_template', true)->get();
        return view('admin.newsletters.create', compact('templates'));
    }

    /**
     * Stocker une nouvelle newsletter
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,scheduled,sent',
            'scheduled_at' => 'nullable|date|after:now',
            'tags' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();
        
        // Traitement de l'image
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('newsletters', 'public');
        }

        // Traitement des tags
        if ($request->filled('tags')) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        // Compter les destinataires
        $data['recipients_count'] = User::where('newsletter_subscribed', true)->count();

        $newsletter = Newsletter::create($data);

        // Si programmée, programmer l'envoi
        if ($newsletter->status === 'scheduled' && $newsletter->scheduled_at) {
            // Programmer le job d'envoi
            \App\Jobs\SendNewsletterJob::dispatch($newsletter)->delay($newsletter->scheduled_at);
        }

        return redirect()->route('admin.newsletters.index')
                        ->with('success', 'Newsletter créée avec succès !');
    }

    /**
     * Afficher une newsletter
     */
    public function show(Newsletter $newsletter)
    {
        $newsletter->load(['creator', 'updater', 'sends.user']);
        
        // Statistiques détaillées
        $stats = [
            'sent_count' => $newsletter->sent_count,
            'opened_count' => $newsletter->opened_count,
            'clicked_count' => $newsletter->clicked_count,
            'unsubscribed_count' => $newsletter->unsubscribed_count,
            'open_rate' => $newsletter->sent_count > 0 ? round(($newsletter->opened_count / $newsletter->sent_count) * 100, 2) : 0,
            'click_rate' => $newsletter->opened_count > 0 ? round(($newsletter->clicked_count / $newsletter->opened_count) * 100, 2) : 0
        ];

        return view('admin.newsletters.show', compact('newsletter', 'stats'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Newsletter $newsletter)
    {
        if ($newsletter->status === 'sent') {
            return redirect()->route('admin.newsletters.show', $newsletter)
                           ->with('error', 'Impossible de modifier une newsletter déjà envoyée.');
        }

        $templates = Newsletter::where('is_template', true)->get();
        return view('admin.newsletters.edit', compact('newsletter', 'templates'));
    }

    /**
     * Mettre à jour une newsletter
     */
    public function update(Request $request, Newsletter $newsletter)
    {
        if ($newsletter->status === 'sent') {
            return redirect()->route('admin.newsletters.show', $newsletter)
                           ->with('error', 'Impossible de modifier une newsletter déjà envoyée.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,scheduled,sent',
            'scheduled_at' => 'nullable|date|after:now',
            'tags' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['updated_by'] = Auth::id();
        
        // Traitement de l'image
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('newsletters', 'public');
        }

        // Traitement des tags
        if ($request->filled('tags')) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        // Mettre à jour le nombre de destinataires
        $data['recipients_count'] = User::where('newsletter_subscribed', true)->count();

        $newsletter->update($data);

        return redirect()->route('admin.newsletters.show', $newsletter)
                        ->with('success', 'Newsletter mise à jour avec succès !');
    }

    /**
     * Supprimer une newsletter
     */
    public function destroy(Newsletter $newsletter)
    {
        if ($newsletter->status === 'sent') {
            return redirect()->route('admin.newsletters.index')
                           ->with('error', 'Impossible de supprimer une newsletter déjà envoyée.');
        }

        $newsletter->delete();

        return redirect()->route('admin.newsletters.index')
                        ->with('success', 'Newsletter supprimée avec succès !');
    }

    /**
     * Envoyer immédiatement une newsletter
     */
    public function sendNow(Newsletter $newsletter)
    {
        if ($newsletter->status === 'sent') {
            return redirect()->route('admin.newsletters.show', $newsletter)
                           ->with('error', 'Cette newsletter a déjà été envoyée.');
        }

        // Mettre à jour le statut et programmer l'envoi immédiat
        $newsletter->update([
            'status' => 'scheduled',
            'scheduled_at' => now(),
            'recipients_count' => User::where('newsletter_subscribed', true)->count()
        ]);

        // Programmer l'envoi immédiat
        \App\Jobs\SendNewsletterJob::dispatch($newsletter);

        return redirect()->route('admin.newsletters.show', $newsletter)
                        ->with('success', 'Newsletter programmée pour envoi immédiat !');
    }

    /**
     * Prévisualiser une newsletter
     */
    public function preview(Newsletter $newsletter)
    {
        return view('admin.newsletters.preview', compact('newsletter'));
    }

    /**
     * Tester l'envoi à l'admin
     */
    public function sendTest(Newsletter $newsletter)
    {
        $admin = Auth::user();
        
        try {
            \Mail::to($admin->email)->send(new \App\Mail\NewsletterMail($newsletter, $admin));
            
            return response()->json([
                'success' => true,
                'message' => "Email de test envoyé à {$admin->email}"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gestion des abonnés
     */
    public function subscribers(Request $request)
    {
        $query = User::where('newsletter_subscribed', true)
                    ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $subscribers = $query->paginate(20);
        
        $stats = [
            'total_users' => User::count(),
            'subscribers' => User::where('newsletter_subscribed', true)->count(),
            'unsubscribed' => User::where('newsletter_subscribed', false)->count(),
            'subscription_rate' => User::count() > 0 ? round((User::where('newsletter_subscribed', true)->count() / User::count()) * 100, 2) : 0
        ];

        return view('admin.newsletters.subscribers', compact('subscribers', 'stats'));
    }
    
    /**
     * Afficher tous les abonnés à la newsletter
     */
    public function allSubscribers(Request $request)
    {
        $query = User::query();
        
        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('newsletter_subscribed', true);
            } elseif ($request->status === 'inactive') {
                $query->where('newsletter_subscribed', false);
            }
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('newsletter_subscribed_at', '>=', $request->date_from);
        }
        
        // Tri
        $sortField = $request->get('sort', 'newsletter_subscribed_at');
        $sortDirection = $request->get('direction', 'desc');
        
        if (in_array($sortField, ['name', 'email', 'newsletter_subscribed_at'])) {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $subscribers = $query->paginate(20);
        
        // Statistiques
        $totalSubscribers = User::count();
        $activeSubscribers = User::where('newsletter_subscribed', true)->count();
        $recentSubscribers = User::where('newsletter_subscribed', true)
                                ->where('newsletter_subscribed_at', '>=', now()->subWeek())
                                ->count();
        $unsubscribedUsers = User::where('newsletter_subscribed', false)
                                ->whereNotNull('newsletter_subscribed_at')
                                ->count();
        
        return view('admin.newsletters.subscribers', compact(
            'subscribers', 
            'totalSubscribers', 
            'activeSubscribers', 
            'recentSubscribers', 
            'unsubscribedUsers'
        ));
    }
    
    /**
     * Basculer l'abonnement d'un utilisateur
     */
    public function toggleSubscription(Request $request, User $user)
    {
        $subscribe = $request->boolean('subscribe');
        
        $user->update([
            'newsletter_subscribed' => $subscribe,
            'newsletter_subscribed_at' => $subscribe ? now() : null
        ]);
        
        $message = $subscribe ? 'Utilisateur abonné avec succès' : 'Utilisateur désabonné avec succès';
        
        return response()->json(['success' => true, 'message' => $message]);
    }
    
    /**
     * Supprimer un abonné
     */
    public function deleteSubscriber(User $user)
    {
        $user->update([
            'newsletter_subscribed' => false,
            'newsletter_subscribed_at' => null
        ]);
        
        return response()->json(['success' => true, 'message' => 'Abonné supprimé avec succès']);
    }
    
    /**
     * Actions groupées sur les abonnés
     */
    public function bulkSubscriberAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);
        
        $users = User::whereIn('id', $request->user_ids);
        
        switch ($request->action) {
            case 'activate':
                $users->update([
                    'newsletter_subscribed' => true,
                    'newsletter_subscribed_at' => now()
                ]);
                $message = count($request->user_ids) . ' abonné(s) activé(s)';
                break;
                
            case 'deactivate':
                $users->update(['newsletter_subscribed' => false]);
                $message = count($request->user_ids) . ' abonné(s) désactivé(s)';
                break;
                
            case 'delete':
                $users->update([
                    'newsletter_subscribed' => false,
                    'newsletter_subscribed_at' => null
                ]);
                $message = count($request->user_ids) . ' abonné(s) supprimé(s)';
                break;
        }
        
        return response()->json(['success' => true, 'message' => $message]);
    }
    
    /**
     * Historique d'un abonné
     */
    public function subscriberHistory(User $user)
    {
        $newsletters = NewsletterSend::where('user_id', $user->id)
                                   ->with('newsletter')
                                   ->orderBy('created_at', 'desc')
                                   ->get();
        
        $html = view('admin.newsletters.partials.subscriber-history', compact('user', 'newsletters'))->render();
        
        return response($html);
    }
    
    /**
     * Exporter les abonnés
     */
    public function exportSubscribers(Request $request)
    {
        $format = $request->get('format', 'csv');
        $fields = $request->get('fields', ['name', 'email']);
        $activeOnly = $request->boolean('active_only', true);
        
        $query = User::query();
        
        if ($activeOnly) {
            $query->where('newsletter_subscribed', true);
        }
        
        // Appliquer les mêmes filtres que la vue
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('newsletter_subscribed', true);
            } elseif ($request->status === 'inactive') {
                $query->where('newsletter_subscribed', false);
            }
        }
        
        $subscribers = $query->get();
        
        if ($format === 'csv') {
            return $this->exportSubscribersAsCsv($subscribers, $fields);
        } else {
            return $this->exportSubscribersAsJson($subscribers, $fields);
        }
    }
    
    /**
     * Exporter en CSV
     */
    private function exportSubscribersAsCsv($subscribers, $fields)
    {
        $filename = 'newsletter_subscribers_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        return response()->stream(function() use ($subscribers, $fields) {
            $handle = fopen('php://output', 'w');
            
            // En-têtes
            $csvHeaders = [];
            if (in_array('name', $fields)) $csvHeaders[] = 'Nom';
            if (in_array('email', $fields)) $csvHeaders[] = 'Email';
            if (in_array('phone', $fields)) $csvHeaders[] = 'Téléphone';
            if (in_array('subscription_date', $fields)) $csvHeaders[] = 'Date d\'abonnement';
            if (in_array('status', $fields)) $csvHeaders[] = 'Statut';
            
            fputcsv($handle, $csvHeaders);
            
            // Données
            foreach ($subscribers as $subscriber) {
                $row = [];
                if (in_array('name', $fields)) $row[] = $subscriber->name;
                if (in_array('email', $fields)) $row[] = $subscriber->email;
                if (in_array('phone', $fields)) $row[] = $subscriber->phone ?? '';
                if (in_array('subscription_date', $fields)) {
                    $row[] = $subscriber->newsletter_subscribed_at ? 
                            $subscriber->newsletter_subscribed_at->format('d/m/Y H:i') : '';
                }
                if (in_array('status', $fields)) {
                    $row[] = $subscriber->newsletter_subscribed ? 'Actif' : 'Inactif';
                }
                
                fputcsv($handle, $row);
            }
            
            fclose($handle);
        }, 200, $headers);
    }
    
    /**
     * Exporter en JSON
     */
    private function exportSubscribersAsJson($subscribers, $fields)
    {
        $data = $subscribers->map(function($subscriber) use ($fields) {
            $item = [];
            if (in_array('name', $fields)) $item['name'] = $subscriber->name;
            if (in_array('email', $fields)) $item['email'] = $subscriber->email;
            if (in_array('phone', $fields)) $item['phone'] = $subscriber->phone;
            if (in_array('subscription_date', $fields)) {
                $item['subscription_date'] = $subscriber->newsletter_subscribed_at ? 
                                           $subscriber->newsletter_subscribed_at->toISOString() : null;
            }
            if (in_array('status', $fields)) {
                $item['status'] = $subscriber->newsletter_subscribed ? 'active' : 'inactive';
            }
            return $item;
        });
        
        $filename = 'newsletter_subscribers_' . now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data, 200, [
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
