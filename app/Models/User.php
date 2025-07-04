<?php

namespace App\Models;

use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;
    use HasRoles;

    // Constantes pour les rôles
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 
        'username', 
        'email', 
        'password',
        'biography',
        'profile_photo_path',
        'is_newsletter_subscribed',
        'newsletter_subscribed_at',
        'newsletter_unsubscribed_at',
        'default_shipping_address',
        'default_billing_address',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_newsletter_subscribed' => 'boolean',
        'newsletter_subscribed_at' => 'datetime',
        'newsletter_unsubscribed_at' => 'datetime',
        'default_shipping_address' => 'array',
        'default_billing_address' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Vérifie si l'utilisateur est un administrateur
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    /**
     * Vérifie si l'utilisateur est un utilisateur standard
     */
    public function isUser(): bool
    {
        return $this->hasRole(self::ROLE_USER);
    }

    /**
     * Vérifie si l'utilisateur peut gérer le CRUD (admin uniquement)
     */
    public function canManageCrud(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Vérifie si l'utilisateur peut supprimer d'autres utilisateurs
     */
    public function canDeleteUser(User $user): bool
    {
        // Un admin ne peut pas se supprimer lui-même
        if ($this->id === $user->id) {
            return false;
        }

        // Un admin ne peut pas supprimer un autre admin
        if ($user->isAdmin() && $this->isAdmin()) {
            return false;
        }

        // Seuls les admins et superusers peuvent supprimer
        return $this->canManageCrud();
    }

    /**
     * Exporter les données de l'utilisateur
     */
    public function exportData(): array
    {
        return [
            'personal_info' => [
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'created_at' => $this->created_at,
            ],
            'newsletter' => [
                'is_subscribed' => $this->is_newsletter_subscribed,
                'subscribed_at' => $this->newsletter_subscribed_at,
                'unsubscribed_at' => $this->newsletter_unsubscribed_at,
            ],
            'roles' => $this->roles->pluck('name')->toArray(),
            'permissions' => $this->permissions->pluck('name')->toArray(),
        ];
    }

    /**
     * Relation avec les messages de contact envoyés par l'utilisateur
     */
    public function contactMessages()
    {
        return $this->hasMany(\App\Models\Contact::class, 'user_id');
    }

    // ==================== RELATIONS BLOG ====================

    /**
     * Articles de blog créés par l'utilisateur (admin)
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    /**
     * Commentaires de blog créés par l'utilisateur
     */
    public function blogComments()
    {
        return $this->hasMany(BlogComment::class, 'user_id');
    }

    /**
     * Signalements de commentaires effectués par l'utilisateur
     */
    public function blogCommentReports()
    {
        return $this->hasMany(BlogCommentReport::class, 'reporter_id');
    }

    /**
     * Signalements traités par l'utilisateur (admin)
     */
    public function reviewedReports()
    {
        return $this->hasMany(BlogCommentReport::class, 'reviewed_by');
    }

    /**
     * Commentaires approuvés par l'utilisateur (admin)
     */
    public function approvedComments()
    {
        return $this->hasMany(BlogComment::class, 'approved_by');
    }

    /**
     * Relation avec les contacts de l'utilisateur
     */
    public function contacts()
    {
        return $this->hasMany(\App\Models\Contact::class, 'user_id');
    }

    /**
     * Relation avec les contacts assignés à cet administrateur
     */
    public function assignedContacts()
    {
        return $this->hasMany(\App\Models\Contact::class, 'assigned_to');
    }

    /**
     * Relation avec les commandes de l'utilisateur
     */
    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    /**
     * Relation avec les locations de l'utilisateur
     */
    public function rentals()
    {
        return $this->hasMany(\App\Models\Rental::class);
    }

    /**
     * Relation avec le panier de l'utilisateur
     */
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Relation avec les articles du panier de l'utilisateur
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Relation avec le panier de location de l'utilisateur
     */
    public function cartLocations()
    {
        return $this->hasMany(CartLocation::class);
    }

    /**
     * Relation avec les produits aimés
     */
    public function likedProducts()
    {
        return $this->belongsToMany(Product::class, 'product_likes')->withTimestamps();
    }

    /**
     * Relation avec la wishlist
     */
    public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')->withTimestamps();
    }

    /**
     * Relation avec l'abonnement newsletter
     */
    public function newsletterSubscription()
    {
        return $this->hasOne(NewsletterSubscription::class);
    }

    /**
     * Messages envoyés à l'admin
     */
    public function adminMessages()
    {
        return $this->hasMany(AdminMessage::class);
    }

    /**
     * Réponses aux messages admin
     */
    public function adminMessageReplies()
    {
        return $this->hasMany(AdminMessageReply::class);
    }

    /**
     * Vérifier si l'utilisateur est abonné à la newsletter
     */
    public function isNewsletterSubscribed(): bool
    {
        return $this->is_newsletter_subscribed || 
               NewsletterSubscription::isSubscribed($this->email);
    }

    /**
     * S'abonner à la newsletter
     */
    public function subscribeToNewsletter(array $preferences = []): bool
    {
        // Mettre à jour le champ utilisateur
        $this->is_newsletter_subscribed = true;
        $this->newsletter_subscribed_at = Carbon::now();
        $this->newsletter_unsubscribed_at = null;
        $this->save();

        // Créer ou mettre à jour l'abonnement
        NewsletterSubscription::subscribe($this->email, $this->id, $preferences);

        return true;
    }

    /**
     * Se désabonner de la newsletter
     */
    public function unsubscribeFromNewsletter(): bool
    {
        // Mettre à jour le champ utilisateur
        $this->is_newsletter_subscribed = false;
        $this->newsletter_unsubscribed_at = Carbon::now();
        $this->save();

        // Désactiver l'abonnement
        $subscription = NewsletterSubscription::byEmail($this->email)->first();
        if ($subscription) {
            $subscription->deactivate();
        }

        return true;
    }

    /**
     * Basculer l'abonnement newsletter
     */
    public function toggleNewsletterSubscription(array $preferences = []): bool
    {
        if ($this->isNewsletterSubscribed()) {
            return $this->unsubscribeFromNewsletter();
        } else {
            return $this->subscribeToNewsletter($preferences);
        }
    }

    /**
     * Vérifie si l'utilisateur peut accéder à une fonctionnalité
     */
    public function canAccessFeature(string $permission): bool
    {
        return $this->can($permission);
    }

    /**
     * Vérifie si l'utilisateur peut voir les produits
     */
    public function canViewProducts(): bool
    {
        return true; // Tous les utilisateurs peuvent voir les produits
    }

    /**
     * Vérifie si l'utilisateur peut acheter des produits
     */
    public function canPurchaseProducts(): bool
    {
        return $this->can('purchase_products');
    }

    /**
     * Vérifie si l'utilisateur peut louer des produits
     */
    public function canRentProducts(): bool
    {
        return $this->can('rent_products');
    }

    /**
     * Vérifie si l'utilisateur peut commenter le blog
     */
    public function canCommentBlog(): bool
    {
        return $this->can('comment_blog');
    }

    /**
     * Vérifie si l'utilisateur peut contacter l'admin
     */
    public function canContactAdmin(): bool
    {
        return $this->can('contact_admin');
    }

    /**
     * Assigne un rôle par défaut à l'utilisateur lors de la création
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Assigner le rôle USER par défaut si aucun rôle n'est assigné
            if (!$user->roles()->exists()) {
                $user->assignRole(self::ROLE_USER);
            }
        });
    }

    /**
     * Scope pour les utilisateurs actifs (non supprimés)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope pour les utilisateurs supprimés
     */
    public function scopeDeleted($query)
    {
        return $query->onlyTrashed();
    }

    /**
     * Scope pour les abonnés à la newsletter
     */
    public function scopeNewsletterSubscribed($query)
    {
        return $query->where('is_newsletter_subscribed', true);
    }

    /**
     * Scope par rôle
     */
    public function scopeByRole($query, string $role)
    {
        return $query->role($role);
    }

    /**
     * Vérifie si l'utilisateur peut être supprimé par un autre utilisateur
     */
    public function canBeDeletedBy(User $user): bool
    {
        return $user->canDeleteUser($this);
    }

    /**
     * Génère un nom d'utilisateur unique basé sur le nom
     */
    public static function generateUniqueUsername(string $name): string
    {
        $baseUsername = \Str::slug($name, '');
        $username = $baseUsername;
        $counter = 1;

        while (self::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Obtenir le rôle principal de l'utilisateur
     */
    public function getPrimaryRole(): string
    {
        if ($this->isAdmin()) {
            return self::ROLE_ADMIN;
        } else {
            return self::ROLE_USER;
        }
    }

    /**
     * Vérifie si l'utilisateur peut accéder au panel d'administration
     */
    public function canAccessAdmin(): bool
    {
        return $this->canManageCrud();
    }

    /**
     * Forcer la suppression définitive (uniquement pour les admins)
     */
    public function forceDeleteUser(): bool
    {
        if (!$this->isAdmin()) {
            return false;
        }
        return $this->forceDelete();
    }

    /**
     * Restaurer un utilisateur supprimé (soft delete)
     */
    public function restoreUser(): bool
    {
        return $this->restore();
    }

    /**
     * Obtenir les statistiques de l'utilisateur
     */
    public function getStats(): array
    {
        return [
            'registration_date' => $this->created_at,
            'last_login' => $this->current_team_id ? 'Active' : 'Never',
            'role' => $this->getPrimaryRole(),
            'newsletter_status' => $this->is_newsletter_subscribed ? 'Subscribed' : 'Not subscribed',
            'account_status' => $this->deleted_at ? 'Deleted' : 'Active',
        ];
    }

    // Relations pour les cookies
    /**
     * Consentements cookies de l'utilisateur
     */
    public function cookieConsents()
    {
        return $this->hasMany(CookieConsent::class);
    }

    /**
     * Obtenir le consentement cookie actuel
     */
    public function getCurrentCookieConsent()
    {
        return $this->cookieConsents()
            ->active()
            ->latest('consent_date')
            ->first();
    }

    /**
     * Vérifier si l'utilisateur a donné son consentement pour une catégorie de cookie
     */
    public function hasConsentForCookie($category)
    {
        return CookieConsent::isConsentGiven($category, $this->id);
    }

    /**
     * Obtenir toutes les catégories de cookies acceptées par l'utilisateur
     */
    public function getAcceptedCookieCategories()
    {
        return CookieConsent::getAcceptedCategories($this->id);
    }

    /**
     * Vérifier si l'utilisateur a une adresse de livraison par défaut
     */
    public function hasDefaultShippingAddress()
    {
        return !empty($this->default_shipping_address) && 
               !empty($this->default_shipping_address['street']) &&
               !empty($this->default_shipping_address['city']) &&
               !empty($this->default_shipping_address['postal_code']);
    }

    /**
     * Obtenir l'adresse de livraison formatée
     */
    public function getFormattedShippingAddress()
    {
        if (!$this->hasDefaultShippingAddress()) {
            return null;
        }

        $address = $this->default_shipping_address;
        $formatted = $address['street'] . "\n";
        
        if (!empty($address['additional_info'])) {
            $formatted .= $address['additional_info'] . "\n";
        }
        
        $formatted .= $address['postal_code'] . ' ' . $address['city'];
        
        if (!empty($address['country'])) {
            $formatted .= "\n" . $address['country'];
        }

        return $formatted;
    }

    /**
     * Obtenir l'adresse de livraison sur une ligne
     */
    public function getShippingAddressOneLine()
    {
        if (!$this->hasDefaultShippingAddress()) {
            return null;
        }

        $address = $this->default_shipping_address;
        $parts = [
            $address['street'],
            $address['postal_code'] . ' ' . $address['city']
        ];

        if (!empty($address['country'])) {
            $parts[] = $address['country'];
        }

        return implode(', ', $parts);
    }

    /**
     * Mettre à jour l'adresse de livraison par défaut
     */
    public function updateDefaultShippingAddress($addressData)
    {
        $this->update([
            'default_shipping_address' => [
                'street' => $addressData['street'] ?? '',
                'additional_info' => $addressData['additional_info'] ?? '',
                'city' => $addressData['city'] ?? '',
                'postal_code' => $addressData['postal_code'] ?? '',
                'country' => $addressData['country'] ?? 'France',
            ]
        ]);
    }
}
