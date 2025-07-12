<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class NewsletterSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'is_subscribed',
        'subscribed_at',
        'unsubscribed_at',
        'unsubscribe_reason',
        'unsubscribe_token',
        'preferences',
        'source',
        'metadata'
    ];

    protected $casts = [
        'is_subscribed' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'preferences' => 'array',
        'metadata' => 'array'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * S'abonner à la newsletter
     */
    public function subscribe(string $source = 'manual'): void
    {
        $this->update([
            'is_subscribed' => true,
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
            'unsubscribe_reason' => null,
            'source' => $source
        ]);
    }

    /**
     * Se désabonner de la newsletter
     */
    public function unsubscribe(string $reason = null): void
    {
        $this->update([
            'is_subscribed' => false,
            'unsubscribed_at' => now(),
            'unsubscribe_reason' => $reason
        ]);
    }

    /**
     * Basculer l'état d'abonnement
     */
    public function toggle(string $source = 'manual'): bool
    {
        if ($this->is_subscribed) {
            $this->unsubscribe('user_choice');
            return false;
        } else {
            $this->subscribe($source);
            return true;
        }
    }

    /**
     * Mettre à jour les préférences
     */
    public function updatePreferences(array $preferences): void
    {
        $this->update(['preferences' => $preferences]);
    }

    /**
     * Générer un nouveau token de désabonnement
     */
    public function generateUnsubscribeToken(): string
    {
        $token = Str::random(40);
        $this->update(['unsubscribe_token' => $token]);
        return $token;
    }

    /**
     * Scopes pour filtrer les abonnements
     */
    public function scopeSubscribed($query)
    {
        return $query->where('is_subscribed', true);
    }

    public function scopeUnsubscribed($query)
    {
        return $query->where('is_subscribed', false);
    }

    public function scopeBySource($query, string $source)
    {
        return $query->where('source', $source);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Statistiques globales des abonnements
     */
    public static function getStatistics(): array
    {
        $total = self::count();
        $subscribed = self::subscribed()->count();
        $unsubscribed = self::unsubscribed()->count();
        $recentSubscriptions = self::subscribed()->recent()->count();
        $recentUnsubscriptions = self::unsubscribed()->recent()->count();

        return [
            'total_subscriptions' => $total,
            'active_subscriptions' => $subscribed,
            'inactive_subscriptions' => $unsubscribed,
            'subscription_rate' => $total > 0 ? round(($subscribed / $total) * 100, 2) : 0,
            'recent_subscriptions' => $recentSubscriptions,
            'recent_unsubscriptions' => $recentUnsubscriptions,
            
            // Sources d'abonnement
            'subscription_sources' => self::subscribed()
                ->selectRaw('source, COUNT(*) as count')
                ->groupBy('source')
                ->pluck('count', 'source')
                ->toArray(),
                
            // Raisons de désabonnement
            'unsubscribe_reasons' => self::unsubscribed()
                ->whereNotNull('unsubscribe_reason')
                ->selectRaw('unsubscribe_reason, COUNT(*) as count')
                ->groupBy('unsubscribe_reason')
                ->pluck('count', 'unsubscribe_reason')
                ->toArray(),
                
            // Évolution mensuelle
            'monthly_evolution' => self::selectRaw('
                YEAR(created_at) as year,
                MONTH(created_at) as month,
                SUM(CASE WHEN is_subscribed = 1 THEN 1 ELSE 0 END) as subscriptions,
                SUM(CASE WHEN is_subscribed = 0 THEN 1 ELSE 0 END) as unsubscriptions
            ')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get()
            ->toArray()
        ];
    }

    /**
     * Créer ou récupérer un abonnement pour un utilisateur
     */
    public static function findOrCreateForUser(User $user, bool $subscribed = true, string $source = 'manual'): self
    {
        $subscription = self::where('user_id', $user->id)->first();
        
        if (!$subscription) {
            $subscription = self::create([
                'user_id' => $user->id,
                'is_subscribed' => $subscribed,
                'subscribed_at' => $subscribed ? now() : null,
                'unsubscribe_token' => Str::random(40),
                'source' => $source
            ]);
        }
        
        return $subscription;
    }

    /**
     * Boot method pour générer des tokens automatiquement
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscription) {
            if (empty($subscription->unsubscribe_token)) {
                $subscription->unsubscribe_token = Str::random(40);
            }
        });
    }
}
