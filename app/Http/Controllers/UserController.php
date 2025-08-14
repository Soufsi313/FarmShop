<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountDeletionNotification;
use App\Notifications\ConfirmAccountDeletionNotification;

class UserController extends Controller
{
    /**
     * Afficher la liste des utilisateurs (Admin seulement)
     */
    public function index()
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()?->isAdmin()) {
            abort(403, 'Accès refusé. Privilèges administrateur requis.');
        }

        $users = User::withTrashed()->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Liste des utilisateurs récupérée avec succès'
        ]);
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['sometimes', Rule::in(['Admin', 'User'])],
            'newsletter_subscribed' => 'boolean'
        ]);

        // Seuls les admins peuvent créer d'autres admins
        if (isset($validated['role']) && $validated['role'] === 'Admin') {
            if (!Auth::user()?->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Seuls les administrateurs peuvent créer des comptes admin'
                ], 403);
            }
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = $validated['role'] ?? 'User';

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Utilisateur créé avec succès'
        ], 201);
    }

    /**
     * Afficher un utilisateur spécifique
     */
    public function show(Request $request, User $user = null)
    {
        // Si aucun utilisateur spécifié, utiliser l'utilisateur connecté (pour /profile)
        if (!$user) {
            $user = Auth::user();
        }

        // Un utilisateur peut voir son propre profil, ou admin peut voir tous
        if (!Auth::user()?->isAdmin() && Auth::id() !== $user->id) {
            abort(403, 'Accès refusé.');
        }

        // Si c'est une requête API (Accept: application/json), retourner JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Utilisateur récupéré avec succès'
            ]);
        }

        // Sinon, retourner la vue pour l'interface web
        // Récupérer les messages de la boîte de réception si c'est son propre profil
        $messages = null;
        $unreadCount = 0;
        
        if (Auth::id() === $user->id) {
            // Messages de la table messages avec pagination (10 par page)
            $messages = Message::where('user_id', $user->id)
                ->with('sender')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
            // Compter les messages non lus
            $unreadCount = Message::where('user_id', $user->id)
                ->whereNull('read_at')
                ->count();
        }
        
        return view('users.profile', compact('user', 'messages', 'unreadCount'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $user = null)
    {
        // Si aucun utilisateur spécifié, utiliser l'utilisateur connecté (pour /profile)
        if (!$user) {
            $user = Auth::user();
        }

        // Un utilisateur peut modifier son propre profil, ou admin peut modifier tous
        if (!Auth::user()?->isAdmin() && Auth::id() !== $user->id) {
            abort(403, 'Accès refusé.');
        }

        $validated = $request->validate([
            'username' => ['sometimes', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'name' => 'sometimes|nullable|string|max:255',
            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|string|min:8|confirmed',
            'role' => ['sometimes', Rule::in(['Admin', 'User'])],
            'newsletter_subscribed' => 'sometimes|boolean',
            'phone' => 'sometimes|nullable|string|max:255',
            'address' => 'sometimes|nullable|string|max:255',
            'address_line_2' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|nullable|string|max:255',
            'postal_code' => 'sometimes|nullable|string|max:20',
            'country' => 'sometimes|nullable|string|max:2',
        ]);

        // Seuls les admins peuvent modifier les rôles
        if (isset($validated['role']) && !Auth::user()?->isAdmin()) {
            unset($validated['role']);
        }

        // Hacher le mot de passe si fourni
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        // Si c'est une requête API (Accept: application/json), retourner JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $user->fresh(),
                'message' => 'Utilisateur mis à jour avec succès'
            ]);
        }

        // Sinon, rediriger vers le profil avec un message de succès
        return redirect()->route('users.profile')
                        ->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Supprimer un utilisateur (soft delete)
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        // Vérifier les permissions de suppression
        if (!$user->canBeDeletedBy($currentUser)) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas le droit de supprimer cet utilisateur'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur supprimé avec succès'
        ]);
    }

    /**
     * Restaurer un utilisateur supprimé (Admin seulement)
     */
    public function restore($id)
    {
        if (!Auth::user()?->isAdmin()) {
            abort(403, 'Accès refusé. Privilèges administrateur requis.');
        }

        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.index')
            ->with('success', "Utilisateur '{$user->name}' restauré avec succès !");
    }

    /**
     * S'abonner à la newsletter
     */
    public function subscribeNewsletter()
    {
        $user = Auth::user();
        $user->subscribeToNewsletter();

        return response()->json([
            'success' => true,
            'message' => 'Abonnement à la newsletter effectué avec succès'
        ]);
    }

    /**
     * Se désabonner de la newsletter
     */
    public function unsubscribeNewsletter()
    {
        $user = Auth::user();
        $user->unsubscribeFromNewsletter();

        return response()->json([
            'success' => true,
            'message' => 'Désabonnement de la newsletter effectué avec succès'
        ]);
    }

    /**
     * Télécharger les données utilisateur complètes (RGPD) en ZIP avec PDF
     */
    public function downloadData()
    {
        $user = Auth::user();
        
        // Charger toutes les données de l'utilisateur avec les relations
        $user->load([
            'newsletterSubscription',
            'carts' => function($query) {
                $query->where('status', 'active');
            },
            'cartLocations'
        ]);
        
        // Récupérer toutes les données liées
        $orders = $user->hasMany(\App\Models\Order::class, 'user_id')->get();
        $rentalOrders = $user->orderLocations;
        $likedProducts = \App\Models\ProductLike::where('user_id', $user->id)
            ->with('product.category')->get();
        $wishlistItems = \App\Models\Wishlist::where('user_id', $user->id)
            ->with('product.category')->get();
        $activeCarts = $user->carts;
        $activeCartLocations = $user->cartLocations;
        $newsletterSubscription = $user->newsletterSubscription;
        
        // Générer le PDF avec toutes les données
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.user-data', compact(
            'user', 'orders', 'rentalOrders', 'likedProducts', 'wishlistItems',
            'activeCarts', 'activeCartLocations', 'newsletterSubscription'
        ));
        
        // Créer un ZIP avec le PDF et les données JSON
        $zipFileName = "donnees-{$user->username}-" . now()->format('Y-m-d-H-i-s') . ".zip";
        $zipPath = storage_path('app/temp/' . $zipFileName);
        
        // S'assurer que le dossier temp existe
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            
            // Ajouter le PDF principal
            $pdfContent = $pdf->output();
            $zip->addFromString("donnees-personnelles-{$user->username}.pdf", $pdfContent);
            
            // Ajouter un fichier JSON avec toutes les données brutes
            $jsonData = [
                'utilisateur' => [
                    'informations_personnelles' => [
                        'username' => $user->username,
                        'nom' => $user->name,
                        'email' => $user->email,
                        'telephone' => $user->phone,
                        'role' => $user->role,
                        'abonne_newsletter' => $user->newsletter_subscribed,
                        'date_creation' => $user->created_at,
                        'derniere_modification' => $user->updated_at,
                    ],
                    'adresse' => [
                        'adresse' => $user->address,
                        'complement' => $user->address_line_2,
                        'ville' => $user->city,
                        'code_postal' => $user->postal_code,
                        'pays' => $user->country,
                    ]
                ],
                'commandes_achat' => $orders->map(function($order) {
                    return [
                        'numero' => $order->order_number,
                        'date' => $order->created_at,
                        'statut' => $order->status,
                        'total' => $order->total_amount,
                        'adresse_facturation' => $order->billing_address,
                        'adresse_livraison' => $order->shipping_address,
                        'methode_paiement' => $order->payment_method,
                        'statut_paiement' => $order->payment_status,
                    ];
                }),
                'commandes_location' => $rentalOrders->map(function($rental) {
                    return [
                        'numero' => $rental->order_number,
                        'date_debut' => $rental->start_date,
                        'date_fin' => $rental->end_date,
                        'statut' => $rental->status,
                        'total' => $rental->total_amount,
                        'caution' => $rental->deposit_amount,
                        'adresse_livraison' => $rental->delivery_address,
                    ];
                }),
                'produits_favoris' => $likedProducts->map(function($like) {
                    return [
                        'produit' => $like->product->name,
                        'categorie' => $like->product->category->name ?? null,
                        'prix' => $like->product->price,
                        'date_ajout' => $like->created_at,
                    ];
                }),
                'liste_souhaits' => $wishlistItems->map(function($wishlist) {
                    return [
                        'produit' => $wishlist->product->name,
                        'categorie' => $wishlist->product->category->name ?? null,
                        'prix' => $wishlist->product->price,
                        'date_ajout' => $wishlist->created_at,
                    ];
                }),
                'newsletter' => $newsletterSubscription ? [
                    'abonne' => $newsletterSubscription->is_subscribed,
                    'date_abonnement' => $newsletterSubscription->subscribed_at,
                    'date_desabonnement' => $newsletterSubscription->unsubscribed_at,
                    'source' => $newsletterSubscription->source,
                ] : null,
                'statistiques' => [
                    'total_commandes_achat' => $orders->count(),
                    'total_commandes_location' => $rentalOrders->count(),
                    'montant_total_depense' => $orders->sum('total_amount'),
                    'nombre_produits_favoris' => $likedProducts->count(),
                    'nombre_liste_souhaits' => $wishlistItems->count(),
                ]
            ];
            
            $zip->addFromString("donnees-brutes-{$user->username}.json", json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            // Ajouter un fichier README explicatif
            $readmeContent = "ARCHIVE DES DONNÉES PERSONNELLES - FARMSHOP\n";
            $readmeContent .= "==========================================\n\n";
            $readmeContent .= "Utilisateur: {$user->name} ({$user->email})\n";
            $readmeContent .= "Généré le: " . now()->format('d/m/Y à H:i:s') . "\n\n";
            $readmeContent .= "CONTENU DE L'ARCHIVE:\n";
            $readmeContent .= "- donnees-personnelles-{$user->username}.pdf : Document PDF complet et lisible\n";
            $readmeContent .= "- donnees-brutes-{$user->username}.json : Données au format JSON pour traitement informatique\n";
            $readmeContent .= "- README.txt : Ce fichier explicatif\n\n";
            $readmeContent .= "CONFORMITÉ RGPD:\n";
            $readmeContent .= "Cette archive contient TOUTES vos données personnelles stockées sur FarmShop.\n";
            $readmeContent .= "Vous avez le droit de modifier ou supprimer ces données à tout moment.\n";
            $readmeContent .= "Pour toute question, contactez-nous à l'adresse: support@farmshop.com\n";
            
            $zip->addFromString("README.txt", $readmeContent);
            
            $zip->close();
        }
        
        // Retourner le fichier ZIP
        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Demander la suppression du compte utilisateur (étape 1)
     */
    public function requestSelfDelete()
    {
        $user = Auth::user();

        // Un admin ne peut pas se supprimer lui-même
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Un administrateur ne peut pas supprimer son propre compte');
        }

        try {
            // Envoyer l'email de confirmation
            $user->notify(new ConfirmAccountDeletionNotification());
            
            return view('auth.account-deletion-requested');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'email de confirmation : ' . $e->getMessage());
        }
    }

    /**
     * Confirmer la suppression du compte utilisateur (étape 2)
     */
    public function confirmSelfDelete(Request $request, $userId)
    {
        // Vérifier la signature de l'URL
        if (!$request->hasValidSignature()) {
            abort(403, 'Lien de confirmation invalide ou expiré');
        }

        $user = User::findOrFail($userId);

        // Vérifier que c'est bien l'utilisateur connecté
        if (Auth::id() !== $user->id) {
            abort(403, 'Accès non autorisé');
        }

        // Un admin ne peut pas se supprimer lui-même
        if ($user->isAdmin()) {
            abort(403, 'Un administrateur ne peut pas supprimer son propre compte');
        }

        try {
            // Générer le téléchargement GDPR avant suppression
            $zipPath = $this->generateGdprZip($user);
            $zipFileName = basename($zipPath);
            
            // Supprimer le compte (soft delete)
            $user->delete();

            // Déconnecter l'utilisateur
            Auth::logout();

            // Retourner la page de confirmation avec téléchargement automatique
            return view('auth.account-deleted-success')->with('zipFileName', $zipFileName);
            
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Générer le ZIP GDPR avec toutes les données utilisateur
     */
    private function generateGdprZip($user)
    {
        $zipFileName = 'donnees_utilisateur_' . $user->id . '_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/public/gdpr/' . $zipFileName);
        
        // Créer le dossier s'il n'existe pas
        $gdprDir = dirname($zipPath);
        if (!is_dir($gdprDir)) {
            mkdir($gdprDir, 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Impossible de créer le fichier ZIP');
        }

        // 1. Données de profil
        $profilePdf = $this->generateProfilePdf($user);
        $zip->addFile($profilePdf, 'profil_utilisateur.pdf');

        // 2. Historique des commandes
        if ($user->orders()->exists()) {
            $ordersPdf = $this->generateOrdersPdf($user);
            $zip->addFile($ordersPdf, 'historique_commandes.pdf');
        }

        // 3. Historique des locations
        if ($user->rentals()->exists()) {
            $rentalsPdf = $this->generateRentalsPdf($user);
            $zip->addFile($rentalsPdf, 'historique_locations.pdf');
        }

        // 4. Messages et communications
        if ($user->messages()->exists()) {
            $messagesPdf = $this->generateMessagesPdf($user);
            $zip->addFile($messagesPdf, 'messages_communications.pdf');
        }

        // 5. Données de navigation (préférences, newsletter, etc.)
        $navigationPdf = $this->generateNavigationPdf($user);
        $zip->addFile($navigationPdf, 'donnees_navigation.pdf');

        $zip->close();

        return $zipPath;
    }

    /**
     * Générer le PDF du profil utilisateur
     */
    private function generateProfilePdf($user)
    {
        $html = view('pdfs.user-profile', compact('user'))->render();
        $pdf = Pdf::loadHTML($html);
        
        $pdfPath = storage_path('app/temp/profile_' . $user->id . '.pdf');
        $pdf->save($pdfPath);
        
        return $pdfPath;
    }

    /**
     * Générer le PDF des commandes
     */
    private function generateOrdersPdf($user)
    {
        $orders = $user->orders()->with('items.product')->get();
        $html = view('pdfs.user-orders', compact('user', 'orders'))->render();
        $pdf = Pdf::loadHTML($html);
        
        $pdfPath = storage_path('app/temp/orders_' . $user->id . '.pdf');
        $pdf->save($pdfPath);
        
        return $pdfPath;
    }

    /**
     * Générer le PDF des locations
     */
    private function generateRentalsPdf($user)
    {
        $rentals = $user->rentals()->with('product', 'inspections')->get();
        $html = view('pdfs.user-rentals', compact('user', 'rentals'))->render();
        $pdf = Pdf::loadHTML($html);
        
        $pdfPath = storage_path('app/temp/rentals_' . $user->id . '.pdf');
        $pdf->save($pdfPath);
        
        return $pdfPath;
    }

    /**
     * Générer le PDF des messages
     */
    private function generateMessagesPdf($user)
    {
        $messages = $user->messages()->orderBy('created_at', 'desc')->get();
        $html = view('pdfs.user-messages', compact('user', 'messages'))->render();
        $pdf = Pdf::loadHTML($html);
        
        $pdfPath = storage_path('app/temp/messages_' . $user->id . '.pdf');
        $pdf->save($pdfPath);
        
        return $pdfPath;
    }

    /**
     * Générer le PDF des données de navigation
     */
    private function generateNavigationPdf($user)
    {
        $data = [
            'user' => $user,
            'newsletter_subscribed' => $user->newsletter_subscribed,
            'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'last_login' => $user->last_login_at ?? 'Jamais connecté'
        ];
        
        $html = view('pdfs.user-navigation', $data)->render();
        $pdf = Pdf::loadHTML($html);
        
        $pdfPath = storage_path('app/temp/navigation_' . $user->id . '.pdf');
        $pdf->save($pdfPath);
        
        return $pdfPath;
    }

    /**
     * Marquer un message comme lu
     */
    public function markMessageAsRead(Message $message)
    {
        // Vérifier que le message appartient à l'utilisateur connecté
        if ($message->user_id !== Auth::id()) {
            abort(403, 'Accès refusé.');
        }

        $message->update([
            'read_at' => now()
        ]);

        return redirect()->back()->with('success', 'Message marqué comme lu.');
    }

    /**
     * Archiver un message
     */
    public function archiveMessage(Message $message)
    {
        // Vérifier que le message appartient à l'utilisateur connecté
        if ($message->user_id !== Auth::id()) {
            abort(403, 'Accès refusé.');
        }

        $message->update([
            'archived_at' => now()
        ]);

        return redirect()->back()->with('success', 'Message archivé.');
    }

    /**
     * Supprimer un message
     */
    public function deleteMessage(Message $message)
    {
        // Vérifier que le message appartient à l'utilisateur connecté
        if ($message->user_id !== Auth::id()) {
            abort(403, 'Accès refusé.');
        }

        $message->delete();

        return redirect()->back()->with('success', 'Message supprimé.');
    }
}
