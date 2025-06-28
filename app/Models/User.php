<?php

namespace App\Models;

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

class User extends Authenticatable
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
        'is_newsletter_subscribed',
        'newsletter_subscribed_at',
        'newsletter_unsubscribed_at',
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
     * S'abonner à la newsletter
     */
    public function subscribeToNewsletter(): void
    {
        $this->update([
            'is_newsletter_subscribed' => true,
            'newsletter_subscribed_at' => now(),
            'newsletter_unsubscribed_at' => null,
        ]);
    }

    /**
     * Se désabonner de la newsletter
     */
    public function unsubscribeFromNewsletter(): void
    {
        $this->update([
            'is_newsletter_subscribed' => false,
            'newsletter_unsubscribed_at' => now(),
        ]);
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
     * Relation avec l'adhésion de l'utilisateur
     */
    public function membership()
    {
        return $this->hasOne(\App\Models\Membership::class);
    }

    /**
     * Relation avec les adhésions approuvées par cet administrateur
     */
    public function approvedMemberships()
    {
        return $this->hasMany(\App\Models\Membership::class, 'approved_by');
    }

    /**
     * Relation avec les utilisateurs parrainés par cet utilisateur
     */
    public function referredMemberships()
    {
        return $this->hasMany(\App\Models\Membership::class, 'referred_by');
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
     * Relation avec les commentaires de blog de l'utilisateur
     */
    public function blogComments()
    {
        return $this->hasMany(\App\Models\BlogComment::class);
    }

    /**
     * Relation avec le panier de l'utilisateur
     */
    public function cart()
    {
        return $this->hasMany(Cart::class);
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
}
