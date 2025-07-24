<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'newsletter_subscribed',
        'phone',
        'address',
        'address_line_2',
        'city',
        'postal_code',
        'country',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'newsletter_subscribed' => 'boolean',
        ];
    }

    /**
     * Vérifier si l'utilisateur est un Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    /**
     * Vérifier si l'utilisateur est un User
     */
    public function isUser(): bool
    {
        return $this->role === 'User';
    }

    /**
     * Scope pour récupérer seulement les admins
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'Admin');
    }

    /**
     * Scope pour récupérer seulement les users
     */
    public function scopeUsers($query)
    {
        return $query->where('role', 'User');
    }

    /**
     * Vérifier si l'utilisateur peut être supprimé
     * (un admin ne peut pas supprimer un autre admin)
     */
    public function canBeDeletedBy(User $user): bool
    {
        // Un admin ne peut pas supprimer un autre admin
        if ($this->isAdmin() && $user->isAdmin()) {
            return false;
        }
        
        // Un admin ne peut pas se supprimer lui-même
        if ($this->id === $user->id && $this->isAdmin()) {
            return false;
        }
        
        // Seuls les admins peuvent supprimer des utilisateurs
        return $user->isAdmin();
    }

    /**
     * S'abonner à la newsletter
     */
    public function subscribeToNewsletter(): void
    {
        $this->update(['newsletter_subscribed' => true]);
    }

    /**
     * Se désabonner de la newsletter
     */
    public function unsubscribeFromNewsletter(): void
    {
        $this->update(['newsletter_subscribed' => false]);
    }

    /**
     * Relations avec les paniers
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function activeCart()
    {
        return $this->hasOne(Cart::class)->where('status', 'active');
    }

    /**
     * Récupérer ou créer le panier actif de l'utilisateur
     */
    public function getOrCreateActiveCart(): Cart
    {
        $cart = $this->activeCart()->notExpired()->first();
        
        if (!$cart) {
            $cart = $this->carts()->create([
                'status' => 'active',
                'tax_rate' => 20.00, // TVA par défaut
                'expires_at' => now()->addDays(7) // Expiration dans 7 jours
            ]);
        }
        
        return $cart;
    }

    /**
     * Relations avec les paniers de location
     */
    public function cartLocations()
    {
        return $this->hasMany(CartLocation::class);
    }

    public function activeCartLocation()
    {
        return $this->hasOne(CartLocation::class)->latest();
    }

    /**
     * Récupérer ou créer le panier de location actif de l'utilisateur
     */
    public function getOrCreateActiveCartLocation(): CartLocation
    {
        $cartLocation = $this->activeCartLocation()->first();
        
        if (!$cartLocation) {
            $cartLocation = $this->cartLocations()->create();
        }
        
        return $cartLocation;
    }

    /**
     * Relation avec l'abonnement newsletter
     */
    public function newsletterSubscription()
    {
        return $this->hasOne(NewsletterSubscription::class);
    }

    /**
     * Relation avec les envois de newsletter reçus
     */
    public function newsletterSends()
    {
        return $this->hasMany(NewsletterSend::class);
    }

    /**
     * Vérifier si l'utilisateur est abonné à la newsletter
     */
    public function isSubscribedToNewsletter(): bool
    {
        $subscription = $this->newsletterSubscription;
        return $subscription && $subscription->is_subscribed;
    }

    /**
     * Gérer l'abonnement newsletter (nouvelle approche avec table dédiée)
     */
    public function subscribeToNewsletterNew(string $source = 'manual'): NewsletterSubscription
    {
        $subscription = NewsletterSubscription::findOrCreateForUser($this, true, $source);
        $subscription->subscribe($source);
        
        // Mettre à jour aussi l'ancien champ pour compatibilité
        $this->update(['newsletter_subscribed' => true]);
        
        return $subscription;
    }

    /**
     * Se désabonner de la newsletter (nouvelle approche)
     */
    public function unsubscribeFromNewsletterNew(string $reason = null): void
    {
        $subscription = $this->newsletterSubscription;
        if ($subscription) {
            $subscription->unsubscribe($reason);
        }
        
        // Mettre à jour aussi l'ancien champ pour compatibilité
        $this->update(['newsletter_subscribed' => false]);
    }

    /**
     * Relations avec les wishlists
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Relations avec les commandes de location
     */
    public function orderLocations()
    {
        return $this->hasMany(OrderLocation::class);
    }

    /**
     * Relations avec les commandes d'achat
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Commandes de location par statut
     */
    public function activeRentalOrders()
    {
        return $this->orderLocations()->whereIn('status', ['confirmed', 'active', 'completed']);
    }

    public function pendingRentalOrders()
    {
        return $this->orderLocations()->where('status', 'pending');
    }

    public function finishedRentalOrders()
    {
        return $this->orderLocations()->where('status', 'finished');
    }

    /**
     * Méthodes utilitaires pour les adresses
     */

    /**
     * Vérifier si l'utilisateur a une adresse complète
     */
    public function hasCompleteAddress(): bool
    {
        return !empty($this->address) && 
               !empty($this->city) && 
               !empty($this->postal_code) && 
               !empty($this->country);
    }

    /**
     * Obtenir l'adresse formatée
     */
    public function getFormattedAddress(): array
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'address_line_2' => $this->address_line_2,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'country' => $this->country ?? 'France'
        ];
    }

    /**
     * Obtenir l'adresse sous forme de texte
     */
    public function getAddressText(): string
    {
        $parts = array_filter([
            $this->address,
            $this->address_line_2,
            trim($this->postal_code . ' ' . $this->city),
            $this->country
        ]);
        
        return implode(', ', $parts);
    }
}
