<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\NewsletterSubscription;
use App\Models\Order;
use App\Models\Rental;
use App\Models\BlogComment;
use App\Models\AdminMessage;
use App\Models\ProductLike;
use App\Models\Wishlist;
use App\Models\CookieConsent;
use Carbon\Carbon;
use ZipArchive;

class UserProfileController extends Controller
{
    /**
     * Afficher le profil de l'utilisateur
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Mettre à jour les informations du profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'biography' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'shipping_street' => ['nullable', 'string', 'max:255'],
            'shipping_additional' => ['nullable', 'string', 'max:255'],
            'shipping_city' => ['nullable', 'string', 'max:100'],
            'shipping_postal_code' => ['nullable', 'string', 'max:10'],
            'shipping_country' => ['nullable', 'string', 'max:100'],
        ]);

        // Préparer l'adresse de livraison
        $shippingAddress = null;
        if ($request->filled('shipping_street') && $request->filled('shipping_city') && $request->filled('shipping_postal_code')) {
            $shippingAddress = [
                'street' => $request->shipping_street,
                'additional_info' => $request->shipping_additional,
                'city' => $request->shipping_city,
                'postal_code' => $request->shipping_postal_code,
                'country' => $request->shipping_country ?: 'France',
            ];
        }

        // Mise à jour des informations de base
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'biography' => $request->biography,
            'phone' => $request->phone,
            'default_shipping_address' => $shippingAddress,
        ]);

        return redirect()->route('profile.show')->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Télécharger une photo de profil
     */
    public function uploadPhoto(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);

        // Supprimer l'ancienne photo si elle existe
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Sauvegarder la nouvelle photo
        $path = $request->file('photo')->store('profile-photos', 'public');
        $user->update(['profile_photo_path' => $path]);

        return redirect()->route('profile.show')->with('success', 'Photo de profil mise à jour avec succès !');
    }

    /**
     * Supprimer la photo de profil
     */
    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->update(['profile_photo_path' => null]);
        }

        return redirect()->route('profile.show')->with('success', 'Photo de profil supprimée !');
    }

    /**
     * S'abonner/se désabonner à la newsletter
     */
    public function toggleNewsletter(Request $request)
    {
        $user = Auth::user();
        
        if ($user->is_newsletter_subscribed) {
            // Désabonnement
            $user->update([
                'is_newsletter_subscribed' => false,
                'newsletter_unsubscribed_at' => Carbon::now(),
            ]);
            
            // Désactiver aussi l'abonnement dans la table newsletter_subscriptions si elle existe
            $subscription = NewsletterSubscription::where('email', $user->email)->first();
            if ($subscription) {
                $subscription->update([
                    'is_active' => false,
                    'unsubscribed_at' => Carbon::now(),
                ]);
            }
            
            // Envoyer un email de confirmation de désabonnement
            $this->sendNewsletterConfirmationEmail($user, 'unsubscribed');
            
            return redirect()->route('profile.show')->with('success', 'Vous avez été désabonné(e) de la newsletter. Un email de confirmation vous a été envoyé.');
        } else {
            // Abonnement
            $user->update([
                'is_newsletter_subscribed' => true,
                'newsletter_subscribed_at' => Carbon::now(),
                'newsletter_unsubscribed_at' => null,
            ]);
            
            // Créer ou réactiver l'abonnement dans la table newsletter_subscriptions
            $subscription = NewsletterSubscription::where('email', $user->email)->first();
            if ($subscription) {
                $subscription->update([
                    'is_active' => true,
                    'subscribed_at' => Carbon::now(),
                    'unsubscribed_at' => null,
                ]);
            } else {
                NewsletterSubscription::create([
                    'email' => $user->email,
                    'user_id' => $user->id,
                    'is_active' => true,
                    'subscribed_at' => Carbon::now(),
                    'unsubscribe_token' => \Illuminate\Support\Str::random(32),
                ]);
            }
            
            // Envoyer un email de confirmation d'abonnement
            $this->sendNewsletterConfirmationEmail($user, 'subscribed');
            
            return redirect()->route('profile.show')->with('success', 'Vous avez été abonné(e) à la newsletter. Un email de confirmation vous a été envoyé.');
        }
    }

    /**
     * Demander la suppression du compte
     */
    public function requestAccountDeletion(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => ['required', 'string'],
            'confirmation' => ['required', 'accepted'],
        ]);

        // Vérifier le mot de passe
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->route('profile.show')->withErrors(['password' => 'Le mot de passe est incorrect.']);
        }

        // Supprimer le compte (soft delete)
        $user->delete();

        // Envoyer un email de confirmation de suppression
        $this->sendAccountDeletionConfirmationEmail($user);

        // Déconnecter l'utilisateur
        Auth::logout();

        return redirect()->route('welcome')->with('success', 'Votre compte a été supprimé avec succès. Un email de confirmation vous a été envoyé.');
    }

    /**
     * Envoyer un email de confirmation pour la newsletter
     */
    private function sendNewsletterConfirmationEmail($user, $action)
    {
        $subject = $action === 'subscribed' ? 'Confirmation d\'abonnement à la newsletter' : 'Confirmation de désabonnement de la newsletter';
        $message = $action === 'subscribed' 
            ? "Bonjour {$user->name},\n\nVous avez été abonné(e) avec succès à notre newsletter. Vous recevrez désormais nos dernières actualités et offres spéciales.\n\nCordialement,\nL'équipe FarmShop"
            : "Bonjour {$user->name},\n\nVous avez été désabonné(e) avec succès de notre newsletter. Vous ne recevrez plus nos emails.\n\nNous espérons vous revoir bientôt !\n\nCordialement,\nL'équipe FarmShop";

        try {
            Mail::raw($message, function ($mail) use ($user, $subject) {
                $mail->to($user->email, $user->name)
                     ->subject($subject);
            });
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email newsletter: ' . $e->getMessage());
        }
    }

    /**
     * Envoyer un email de confirmation de suppression de compte
     */
    private function sendAccountDeletionConfirmationEmail($user)
    {
        $subject = 'Confirmation de suppression de compte';
        $message = "Bonjour {$user->name},\n\nVotre compte FarmShop a été supprimé avec succès. Toutes vos données personnelles ont été effacées de nos serveurs.\n\nSi vous souhaitez créer un nouveau compte à l'avenir, vous serez le/la bienvenu(e).\n\nCordialement,\nL'équipe FarmShop";

        try {
            Mail::raw($message, function ($mail) use ($user, $subject) {
                $mail->to($user->email, $user->name)
                     ->subject($subject);
            });
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email suppression compte: ' . $e->getMessage());
        }
    }

    /**
     * Télécharger toutes les données utilisateur au format ZIP
     */
    public function downloadUserData()
    {
        $user = Auth::user();
        
        // Créer un nom de fichier unique
        $filename = 'donnees_utilisateur_' . $user->id . '_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/temp/' . $filename);
        
        // Créer le dossier temp s'il n'existe pas
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            return redirect()->route('profile.show')->withErrors(['error' => 'Impossible de créer l\'archive ZIP.']);
        }
        
        try {
            // 1. Données de profil
            $profileData = [
                'informations_personnelles' => [
                    'nom' => $user->name,
                    'email' => $user->email,
                    'nom_utilisateur' => $user->username ?? 'Non défini',
                    'biographie' => $user->biography ?? 'Aucune biographie',
                    'date_inscription' => $user->created_at->format('d/m/Y H:i:s'),
                    'derniere_mise_a_jour' => $user->updated_at->format('d/m/Y H:i:s'),
                    'email_verifie' => $user->email_verified_at ? 'Oui (' . $user->email_verified_at->format('d/m/Y H:i:s') . ')' : 'Non',
                    'newsletter_abonne' => $user->is_newsletter_subscribed ? 'Oui' : 'Non',
                    'date_abonnement_newsletter' => $user->newsletter_subscribed_at ? $user->newsletter_subscribed_at->format('d/m/Y H:i:s') : 'Non applicable',
                ]
            ];
            $zip->addFromString('01_profil/informations_personnelles.json', json_encode($profileData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            // 2. Historique des commandes
            $orders = Order::where('user_id', $user->id)->with(['items.product'])->get();
            $ordersData = [];
            foreach ($orders as $order) {
                $ordersData[] = [
                    'numero_commande' => $order->order_number,
                    'date_commande' => $order->created_at->format('d/m/Y H:i:s'),
                    'statut' => $order->status,
                    'statut_paiement' => $order->payment_status,
                    'montant_total' => $order->total_amount . '€',
                    'adresse_livraison' => $order->shipping_address,
                    'articles' => $order->items->map(function($item) {
                        return [
                            'produit' => $item->product->name ?? 'Produit supprimé',
                            'quantite' => $item->quantity,
                            'prix_unitaire' => $item->price . '€',
                            'prix_total' => ($item->quantity * $item->price) . '€'
                        ];
                    })->toArray()
                ];
            }
            $zip->addFromString('02_commandes/historique_commandes.json', json_encode($ordersData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            // 3. Historique des locations
            $rentals = Rental::where('user_id', $user->id)->with(['items.product'])->get();
            $rentalsData = [];
            foreach ($rentals as $rental) {
                $rentalsData[] = [
                    'numero_location' => $rental->rental_number,
                    'date_debut' => $rental->start_date,
                    'date_fin' => $rental->end_date,
                    'date_retour_prevue' => $rental->expected_return_date,
                    'date_retour_effective' => $rental->actual_return_date ?? 'Non retourné',
                    'statut' => $rental->status,
                    'montant_total' => $rental->total_amount . '€',
                    'articles_loues' => $rental->items->map(function($item) {
                        return [
                            'produit' => $item->product->name ?? 'Produit supprimé',
                            'quantite' => $item->quantity,
                            'prix_journalier' => $item->daily_price . '€',
                            'duree_jours' => $item->days_count,
                            'prix_total' => $item->total_price . '€'
                        ];
                    })->toArray()
                ];
            }
            $zip->addFromString('03_locations/historique_locations.json', json_encode($rentalsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            // 4. Commentaires sur le blog
            $comments = BlogComment::where('user_id', $user->id)->with(['blog'])->get();
            $commentsData = [];
            foreach ($comments as $comment) {
                $commentsData[] = [
                    'article_blog' => $comment->blog->title ?? 'Article supprimé',
                    'contenu_commentaire' => $comment->content,
                    'date_publication' => $comment->created_at->format('d/m/Y H:i:s'),
                    'statut' => $comment->status,
                    'approuve_le' => $comment->approved_at ? $comment->approved_at->format('d/m/Y H:i:s') : 'Non approuvé',
                    'nombre_signalements' => $comment->reports_count ?? 0
                ];
            }
            $zip->addFromString('04_commentaires/commentaires_blog.json', json_encode($commentsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            // 5. Messages à l'administration
            $adminMessages = AdminMessage::where('user_id', $user->id)->with(['replies'])->get();
            $messagesData = [];
            foreach ($adminMessages as $message) {
                $messagesData[] = [
                    'sujet' => $message->subject,
                    'message' => $message->message,
                    'date_envoi' => $message->created_at->format('d/m/Y H:i:s'),
                    'statut' => $message->status,
                    'resolu_le' => $message->resolved_at ? $message->resolved_at->format('d/m/Y H:i:s') : 'Non résolu',
                    'reponses_admin' => $message->replies->map(function($reply) {
                        return [
                            'message' => $reply->message,
                            'date_reponse' => $reply->created_at->format('d/m/Y H:i:s')
                        ];
                    })->toArray()
                ];
            }
            $zip->addFromString('05_messages/messages_administration.json', json_encode($messagesData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            // 6. Produits aimés
            $likedProducts = ProductLike::where('user_id', $user->id)->with(['product'])->get();
            $likesData = [];
            foreach ($likedProducts as $like) {
                $likesData[] = [
                    'produit' => $like->product->name ?? 'Produit supprimé',
                    'date_ajout_favoris' => $like->created_at->format('d/m/Y H:i:s')
                ];
            }
            $zip->addFromString('06_favoris/produits_aimes.json', json_encode($likesData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            // 7. Liste de souhaits
            $wishlistItems = Wishlist::where('user_id', $user->id)->with(['product'])->get();
            $wishlistData = [];
            foreach ($wishlistItems as $item) {
                $wishlistData[] = [
                    'produit' => $item->product->name ?? 'Produit supprimé',
                    'date_ajout' => $item->created_at->format('d/m/Y H:i:s')
                ];
            }
            $zip->addFromString('07_liste_souhaits/liste_souhaits.json', json_encode($wishlistData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            // 8. Consentements cookies
            $cookieConsents = CookieConsent::where('user_id', $user->id)->get();
            $cookiesData = [];
            foreach ($cookieConsents as $consent) {
                $cookiesData[] = [
                    'type_cookie' => $consent->cookie_type,
                    'consent_donne' => $consent->consent_given ? 'Oui' : 'Non',
                    'date_consentement' => $consent->created_at->format('d/m/Y H:i:s'),
                    'adresse_ip' => $consent->ip_address ?? 'Non enregistrée'
                ];
            }
            $zip->addFromString('08_cookies/consentements_cookies.json', json_encode($cookiesData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            // 9. Abonnement newsletter (détaillé)
            $newsletterSub = NewsletterSubscription::where('user_id', $user->id)->first();
            $newsletterData = [
                'abonne' => $user->is_newsletter_subscribed ? 'Oui' : 'Non',
                'email_abonnement' => $newsletterSub->email ?? $user->email,
                'date_abonnement' => $user->newsletter_subscribed_at ? $user->newsletter_subscribed_at->format('d/m/Y H:i:s') : 'Non abonné',
                'date_desabonnement' => $user->newsletter_unsubscribed_at ? $user->newsletter_unsubscribed_at->format('d/m/Y H:i:s') : 'Jamais désabonné',
                'preferences' => $newsletterSub->preferences ?? 'Aucune préférence définie',
                'statut_actuel' => $newsletterSub && $newsletterSub->is_active ? 'Actif' : 'Inactif'
            ];
            $zip->addFromString('09_newsletter/abonnement_newsletter.json', json_encode($newsletterData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            // 10. Ajouter un fichier README explicatif
            $readmeContent = "EXPORT DE VOS DONNÉES PERSONNELLES - FARMSHOP\n\n";
            $readmeContent .= "Date d'export : " . now()->format('d/m/Y H:i:s') . "\n";
            $readmeContent .= "Utilisateur : " . $user->name . " (" . $user->email . ")\n\n";
            $readmeContent .= "CONTENU DE L'ARCHIVE :\n\n";
            $readmeContent .= "01_profil/ - Vos informations de profil personnelles\n";
            $readmeContent .= "02_commandes/ - Historique complet de vos commandes\n";
            $readmeContent .= "03_locations/ - Historique complet de vos locations\n";
            $readmeContent .= "04_commentaires/ - Tous vos commentaires sur le blog\n";
            $readmeContent .= "05_messages/ - Vos échanges avec l'administration\n";
            $readmeContent .= "06_favoris/ - Liste des produits que vous avez aimés\n";
            $readmeContent .= "07_liste_souhaits/ - Votre liste de souhaits\n";
            $readmeContent .= "08_cookies/ - Historique de vos consentements cookies\n";
            $readmeContent .= "09_newsletter/ - Informations sur votre abonnement newsletter\n\n";
            $readmeContent .= "Les fichiers sont au format JSON pour une lecture facile.\n";
            $readmeContent .= "Cet export respecte le RGPD et contient toutes vos données personnelles.\n\n";
            $readmeContent .= "Pour toute question, contactez notre équipe à support@farmshop.com\n";
            
            $zip->addFromString('README.txt', $readmeContent);
            
            $zip->close();
            
            // Télécharger le fichier
            return response()->download($zipPath, $filename)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            $zip->close();
            if (file_exists($zipPath)) {
                unlink($zipPath);
            }
            return redirect()->route('profile.show')->withErrors(['error' => 'Erreur lors de la création de l\'archive : ' . $e->getMessage()]);
        }
    }
}