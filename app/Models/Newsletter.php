<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Newsletter extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'subject',
        'content',
        'excerpt',
        'featured_image',
        'status',
        'scheduled_at',
        'sent_at',
        'recipients_count',
        'sent_count',
        'failed_count',
        'opened_count',
        'clicked_count',
        'unsubscribed_count',
        'tags',
        'metadata',
        'is_template',
        'template_name',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'tags' => 'array',
        'metadata' => 'array',
        'is_template' => 'boolean',
        'recipients_count' => 'integer',
        'sent_count' => 'integer',
        'failed_count' => 'integer',
        'opened_count' => 'integer',
        'clicked_count' => 'integer',
        'unsubscribed_count' => 'integer'
    ];

    // Constantes pour les statuts
    const STATUSES = [
        'draft' => 'Brouillon',
        'scheduled' => 'Programmée',
        'sent' => 'Envoyée',
        'cancelled' => 'Annulée'
    ];

    /**
     * Relation avec le créateur (admin)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation avec le dernier modificateur
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relation avec les envois individuels
     */
    public function sends(): HasMany
    {
        return $this->hasMany(NewsletterSend::class);
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Obtenir la couleur du statut (pour l'interface)
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'scheduled' => 'warning',
            'sent' => 'success',
            'cancelled' => 'danger',
            default => 'primary'
        };
    }

    /**
     * Obtenir le taux d'ouverture
     */
    public function getOpenRateAttribute(): float
    {
        if ($this->sent_count === 0) return 0;
        return round(($this->opened_count / $this->sent_count) * 100, 2);
    }

    /**
     * Obtenir le taux de clic
     */
    public function getClickRateAttribute(): float
    {
        if ($this->sent_count === 0) return 0;
        return round(($this->clicked_count / $this->sent_count) * 100, 2);
    }

    /**
     * Obtenir le taux de désabonnement
     */
    public function getUnsubscribeRateAttribute(): float
    {
        if ($this->sent_count === 0) return 0;
        return round(($this->unsubscribed_count / $this->sent_count) * 100, 2);
    }

    /**
     * Obtenir le taux de réussite d'envoi
     */
    public function getDeliveryRateAttribute(): float
    {
        if ($this->recipients_count === 0) return 0;
        return round(($this->sent_count / $this->recipients_count) * 100, 2);
    }

    /**
     * Vérifier si la newsletter peut être modifiée
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, ['draft', 'scheduled']);
    }

    /**
     * Vérifier si la newsletter peut être envoyée
     */
    public function canBeSent(): bool
    {
        return $this->status === 'draft' && !empty($this->content) && !empty($this->subject);
    }

    /**
     * Vérifier si la newsletter peut être programmée
     */
    public function canBeScheduled(): bool
    {
        return $this->status === 'draft' && !empty($this->content) && !empty($this->subject);
    }

    /**
     * Vérifier si la newsletter peut être annulée
     */
    public function canBeCancelled(): bool
    {
        return $this->status === 'scheduled';
    }

    /**
     * Marquer comme programmée
     */
    public function schedule(Carbon $scheduledAt): void
    {
        $this->update([
            'status' => 'scheduled',
            'scheduled_at' => $scheduledAt
        ]);
    }

    /**
     * Marquer comme envoyée
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    /**
     * Annuler l'envoi
     */
    public function cancel(): void
    {
        $this->update([
            'status' => 'cancelled',
            'scheduled_at' => null
        ]);
    }

    /**
     * Dupliquer comme template
     */
    public function duplicateAsTemplate(string $templateName): self
    {
        $duplicate = $this->replicate(['sent_at', 'scheduled_at', 'recipients_count', 'sent_count', 'failed_count', 'opened_count', 'clicked_count', 'unsubscribed_count']);
        
        $duplicate->fill([
            'title' => $this->title . ' (Template)',
            'status' => 'draft',
            'is_template' => true,
            'template_name' => $templateName,
            'created_by' => auth()->id(),
            'updated_by' => null
        ]);
        
        $duplicate->save();
        
        return $duplicate;
    }

    /**
     * Créer à partir d'un template
     */
    public static function createFromTemplate(self $template, array $data): self
    {
        $newsletter = $template->replicate(['sent_at', 'scheduled_at', 'recipients_count', 'sent_count', 'failed_count', 'opened_count', 'clicked_count', 'unsubscribed_count']);
        
        $newsletter->fill(array_merge([
            'status' => 'draft',
            'is_template' => false,
            'template_name' => null,
            'created_by' => auth()->id(),
            'updated_by' => null
        ], $data));
        
        $newsletter->save();
        
        return $newsletter;
    }

    /**
     * Mettre à jour les statistiques d'envoi
     */
    public function updateSendStatistics(): void
    {
        $this->update([
            'recipients_count' => $this->sends()->count(),
            'sent_count' => $this->sends()->where('status', 'sent')->count(),
            'failed_count' => $this->sends()->whereIn('status', ['failed', 'bounced'])->count(),
            'opened_count' => $this->sends()->where('is_opened', true)->count(),
            'clicked_count' => $this->sends()->where('is_clicked', true)->count(),
            'unsubscribed_count' => $this->sends()->where('is_unsubscribed', true)->count()
        ]);
    }

    /**
     * Envoyer la newsletter à tous les abonnés
     */
    public function sendToSubscribers(): bool
    {
        try {
            // Récupérer tous les utilisateurs abonnés
            $subscribedUsers = User::whereHas('newsletterSubscription', function($query) {
                $query->where('is_subscribed', true);
            })->get();

            if ($subscribedUsers->isEmpty()) {
                return false;
            }

            // Créer les enregistrements d'envoi
            $userIds = $subscribedUsers->pluck('id')->toArray();
            NewsletterSend::createForNewsletter($this, $userIds);

            // Envoyer les emails
            $this->sends()->pending()->chunk(50, function($sends) {
                foreach ($sends as $send) {
                    try {
                        \Mail::send(new \App\Mail\NewsletterMail($this, $send->user, $send));
                        $send->markAsSent();
                    } catch (\Exception $e) {
                        $send->markAsFailed($e->getMessage());
                        \Log::error('Erreur envoi newsletter', [
                            'newsletter_id' => $this->id,
                            'user_id' => $send->user_id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            });

            // Marquer la newsletter comme envoyée
            $this->markAsSent();
            $this->updateSendStatistics();

            return true;

        } catch (\Exception $e) {
            \Log::error('Erreur envoi newsletter globale', [
                'newsletter_id' => $this->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Programmer l'envoi d'une newsletter
     */
    public function scheduleAndSend(Carbon $scheduledAt): bool
    {
        // Programmer d'abord
        $this->schedule($scheduledAt);

        // Si c'est pour maintenant ou le passé, envoyer immédiatement
        if ($scheduledAt->isPast() || $scheduledAt->isToday()) {
            return $this->sendToSubscribers();
        }

        return true;
    }

    /**
     * Envoyer immédiatement la newsletter
     */
    public function sendNow(): bool
    {
        if (!$this->canBeSent()) {
            return false;
        }

        return $this->sendToSubscribers();
    }

    /**
     * Scopes pour filtrer les newsletters
     */
    public function scopeDrafts($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeTemplates($query)
    {
        return $query->where('is_template', true);
    }

    public function scopeNotTemplates($query)
    {
        return $query->where('is_template', false);
    }

    public function scopeByTag($query, string $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeReadyToSend($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_at', '<=', now());
    }

    /**
     * Statistiques globales pour le dashboard admin
     */
    public static function getStatistics(): array
    {
        $total = self::count();
        $sent = self::sent()->count();
        $scheduled = self::scheduled()->count();
        $drafts = self::drafts()->count();
        $templates = self::templates()->count();

        // Statistiques d'engagement globales
        $totalSent = self::sum('sent_count');
        $totalOpened = self::sum('opened_count');
        $totalClicked = self::sum('clicked_count');
        $totalUnsubscribed = self::sum('unsubscribed_count');

        return [
            'total_newsletters' => $total,
            'sent_newsletters' => $sent,
            'scheduled_newsletters' => $scheduled,
            'draft_newsletters' => $drafts,
            'templates_count' => $templates,
            'recent_newsletters' => self::recent()->count(),
            
            // Engagement global
            'total_emails_sent' => $totalSent,
            'total_opens' => $totalOpened,
            'total_clicks' => $totalClicked,
            'total_unsubscribes' => $totalUnsubscribed,
            
            // Taux globaux
            'global_open_rate' => $totalSent > 0 ? round(($totalOpened / $totalSent) * 100, 2) : 0,
            'global_click_rate' => $totalSent > 0 ? round(($totalClicked / $totalSent) * 100, 2) : 0,
            'global_unsubscribe_rate' => $totalSent > 0 ? round(($totalUnsubscribed / $totalSent) * 100, 2) : 0,
            
            // Performance par mois
            'monthly_performance' => self::selectRaw('
                YEAR(sent_at) as year,
                MONTH(sent_at) as month,
                COUNT(*) as newsletters_sent,
                SUM(sent_count) as emails_sent,
                SUM(opened_count) as total_opens,
                SUM(clicked_count) as total_clicks
            ')
            ->whereNotNull('sent_at')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get()
            ->toArray()
        ];
    }

    /**
     * Boot method pour générer des tokens automatiquement
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($newsletter) {
            $newsletter->created_by = $newsletter->created_by ?? auth()->id();
        });

        static::updating(function ($newsletter) {
            $newsletter->updated_by = auth()->id();
        });
    }
}
