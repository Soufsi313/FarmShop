<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class NewsletterSend extends Model
{
    protected $fillable = [
        'newsletter_id',
        'user_id',
        'status',
        'sent_at',
        'failure_reason',
        'is_opened',
        'opened_at',
        'open_count',
        'last_opened_at',
        'is_clicked',
        'clicked_at',
        'click_count',
        'last_clicked_at',
        'is_unsubscribed',
        'unsubscribed_at',
        'tracking_token',
        'unsubscribe_token',
        'metadata'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
        'last_opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'last_clicked_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'is_opened' => 'boolean',
        'is_clicked' => 'boolean',
        'is_unsubscribed' => 'boolean',
        'open_count' => 'integer',
        'click_count' => 'integer',
        'metadata' => 'array'
    ];

    // Constantes pour les statuts
    const STATUSES = [
        'pending' => 'En attente',
        'sent' => 'Envoyé',
        'failed' => 'Échec',
        'bounced' => 'Rejeté'
    ];

    /**
     * Relation avec la newsletter
     */
    public function newsletter(): BelongsTo
    {
        return $this->belongsTo(Newsletter::class);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Marquer comme envoyé
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    /**
     * Marquer comme échoué
     */
    public function markAsFailed(string $reason): void
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason
        ]);
    }

    /**
     * Marquer comme rejeté (bounce)
     */
    public function markAsBounced(string $reason): void
    {
        $this->update([
            'status' => 'bounced',
            'failure_reason' => $reason
        ]);
    }

    /**
     * Enregistrer une ouverture
     */
    public function recordOpen(array $metadata = []): void
    {
        $updates = [
            'open_count' => $this->open_count + 1,
            'last_opened_at' => now()
        ];

        if (!$this->is_opened) {
            $updates['is_opened'] = true;
            $updates['opened_at'] = now();
        }

        if (!empty($metadata)) {
            $existingMetadata = $this->metadata ?? [];
            $updates['metadata'] = array_merge($existingMetadata, [
                'opens' => array_merge($existingMetadata['opens'] ?? [], [$metadata])
            ]);
        }

        $this->update($updates);

        // Mettre à jour les statistiques de la newsletter
        $this->newsletter->updateSendStatistics();
    }

    /**
     * Enregistrer un clic
     */
    public function recordClick(array $metadata = []): void
    {
        $updates = [
            'click_count' => $this->click_count + 1,
            'last_clicked_at' => now()
        ];

        if (!$this->is_clicked) {
            $updates['is_clicked'] = true;
            $updates['clicked_at'] = now();
        }

        if (!empty($metadata)) {
            $existingMetadata = $this->metadata ?? [];
            $updates['metadata'] = array_merge($existingMetadata, [
                'clicks' => array_merge($existingMetadata['clicks'] ?? [], [$metadata])
            ]);
        }

        $this->update($updates);

        // Mettre à jour les statistiques de la newsletter
        $this->newsletter->updateSendStatistics();
    }

    /**
     * Enregistrer un désabonnement
     */
    public function recordUnsubscribe(): void
    {
        $this->update([
            'is_unsubscribed' => true,
            'unsubscribed_at' => now()
        ]);

        // Désabonner l'utilisateur globalement
        $subscription = NewsletterSubscription::where('user_id', $this->user_id)->first();
        if ($subscription) {
            $subscription->unsubscribe('newsletter_link');
        }

        // Mettre à jour les statistiques de la newsletter
        $this->newsletter->updateSendStatistics();
    }

    /**
     * Générer des URLs de tracking
     */
    public function getTrackingUrlAttribute(): string
    {
        return url("/newsletter/track/open/{$this->tracking_token}");
    }

    public function getUnsubscribeUrlAttribute(): string
    {
        return url("/newsletter/unsubscribe/{$this->unsubscribe_token}");
    }

    /**
     * Scopes pour filtrer les envois
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->whereIn('status', ['failed', 'bounced']);
    }

    public function scopeOpened($query)
    {
        return $query->where('is_opened', true);
    }

    public function scopeClicked($query)
    {
        return $query->where('is_clicked', true);
    }

    public function scopeUnsubscribed($query)
    {
        return $query->where('is_unsubscribed', true);
    }

    public function scopeByNewsletter($query, int $newsletterId)
    {
        return $query->where('newsletter_id', $newsletterId);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Trouver par token de tracking
     */
    public static function findByTrackingToken(string $token): ?self
    {
        return self::where('tracking_token', $token)->first();
    }

    /**
     * Trouver par token de désabonnement
     */
    public static function findByUnsubscribeToken(string $token): ?self
    {
        return self::where('unsubscribe_token', $token)->first();
    }

    /**
     * Créer des envois pour une newsletter
     */
    public static function createForNewsletter(Newsletter $newsletter, array $userIds): void
    {
        $sends = [];
        
        foreach ($userIds as $userId) {
            $sends[] = [
                'newsletter_id' => $newsletter->id,
                'user_id' => $userId,
                'status' => 'pending',
                'tracking_token' => Str::random(40),
                'unsubscribe_token' => Str::random(40),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        self::insert($sends);

        // Mettre à jour le nombre de destinataires
        $newsletter->update(['recipients_count' => count($userIds)]);
    }

    /**
     * Boot method pour générer des tokens automatiquement
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($send) {
            if (empty($send->tracking_token)) {
                $send->tracking_token = Str::random(40);
            }
            if (empty($send->unsubscribe_token)) {
                $send->unsubscribe_token = Str::random(40);
            }
        });
    }
}
