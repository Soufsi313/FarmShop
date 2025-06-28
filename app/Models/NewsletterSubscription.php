<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Str;

class NewsletterSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'user_id',
        'is_active',
        'subscribed_at',
        'unsubscribed_at',
        'unsubscribe_token',
        'preferences',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'preferences' => 'array',
    ];

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscription) {
            if (!$subscription->unsubscribe_token) {
                $subscription->unsubscribe_token = Str::random(40);
            }
            
            if (!$subscription->subscribed_at) {
                $subscription->subscribed_at = Carbon::now();
            }
        });
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour les abonnements actifs
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les abonnements inactifs
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope pour un email spécifique
     */
    public function scopeByEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', $email);
    }

    /**
     * Vérifier si l'abonnement est actif
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Activer l'abonnement
     */
    public function activate(): bool
    {
        if ($this->is_active) {
            return true;
        }

        $this->is_active = true;
        $this->subscribed_at = Carbon::now();
        $this->unsubscribed_at = null;

        return $this->save();
    }

    /**
     * Désactiver l'abonnement
     */
    public function deactivate(): bool
    {
        if (!$this->is_active) {
            return true;
        }

        $this->is_active = false;
        $this->unsubscribed_at = Carbon::now();

        return $this->save();
    }

    /**
     * Créer ou mettre à jour un abonnement
     */
    public static function subscribe(string $email, ?int $userId = null, array $preferences = []): self
    {
        $subscription = self::firstOrNew(['email' => $email]);

        $subscription->user_id = $userId;
        $subscription->preferences = $preferences;
        
        if (!$subscription->exists) {
            $subscription->unsubscribe_token = Str::random(40);
            $subscription->subscribed_at = Carbon::now();
        }
        
        $subscription->is_active = true;
        $subscription->unsubscribed_at = null;
        
        $subscription->save();

        return $subscription;
    }

    /**
     * Désabonner par token
     */
    public static function unsubscribeByToken(string $token): ?self
    {
        $subscription = self::where('unsubscribe_token', $token)->first();

        if ($subscription) {
            $subscription->deactivate();
        }

        return $subscription;
    }

    /**
     * Vérifier si un email est abonné
     */
    public static function isSubscribed(string $email): bool
    {
        return self::active()->byEmail($email)->exists();
    }

    /**
     * Obtenir tous les abonnés actifs
     */
    public static function getActiveSubscribers()
    {
        return self::active()->get();
    }

    /**
     * Obtenir le nombre d'abonnés actifs
     */
    public static function getActiveSubscribersCount(): int
    {
        return self::active()->count();
    }

    /**
     * Générer un nouveau token de désabonnement
     */
    public function generateNewUnsubscribeToken(): string
    {
        $this->unsubscribe_token = Str::random(40);
        $this->save();

        return $this->unsubscribe_token;
    }

    /**
     * Obtenir l'URL de désabonnement
     */
    public function getUnsubscribeUrlAttribute(): string
    {
        return route('newsletter.unsubscribe', ['token' => $this->unsubscribe_token]);
    }
}
