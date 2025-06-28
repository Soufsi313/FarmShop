<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Newsletter extends Model
{
    use HasFactory;

    // Statuts de la newsletter
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_SENT = 'sent';

    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'image',
        'status',
        'published_at',
        'sent_at',
        'recipients_count',
        'opened_count',
        'clicked_count',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'sent_at' => 'datetime',
        'recipients_count' => 'integer',
        'opened_count' => 'integer',
        'clicked_count' => 'integer',
    ];

    /**
     * Relation avec l'utilisateur créateur
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope pour les newsletters publiées
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Scope pour les newsletters envoyées
     */
    public function scopeSent(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SENT);
    }

    /**
     * Scope pour les newsletters en brouillon
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Vérifier si la newsletter est publiée
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * Vérifier si la newsletter est envoyée
     */
    public function isSent(): bool
    {
        return $this->status === self::STATUS_SENT;
    }

    /**
     * Vérifier si la newsletter est en brouillon
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Publier la newsletter
     */
    public function publish(): bool
    {
        if (!$this->isDraft()) {
            return false;
        }

        $this->status = self::STATUS_PUBLISHED;
        $this->published_at = Carbon::now();

        return $this->save();
    }

    /**
     * Marquer comme envoyée
     */
    public function markAsSent(int $recipientsCount = 0): bool
    {
        if (!$this->isPublished()) {
            return false;
        }

        $this->status = self::STATUS_SENT;
        $this->sent_at = Carbon::now();
        $this->recipients_count = $recipientsCount;

        return $this->save();
    }

    /**
     * Calculer le taux d'ouverture
     */
    public function getOpenRateAttribute(): float
    {
        if ($this->recipients_count == 0) {
            return 0;
        }

        return round(($this->opened_count / $this->recipients_count) * 100, 2);
    }

    /**
     * Calculer le taux de clic
     */
    public function getClickRateAttribute(): float
    {
        if ($this->recipients_count == 0) {
            return 0;
        }

        return round(($this->clicked_count / $this->recipients_count) * 100, 2);
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            self::STATUS_DRAFT => 'Brouillon',
            self::STATUS_PUBLISHED => 'Publié',
            self::STATUS_SENT => 'Envoyé',
        ];

        return $statuses[$this->status] ?? 'Inconnu';
    }

    /**
     * Générer un extrait automatique si vide
     */
    public function getExcerptAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        return \Str::limit(strip_tags($this->content), 150);
    }

    /**
     * Obtenir tous les statuts disponibles
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Brouillon',
            self::STATUS_PUBLISHED => 'Publié',
            self::STATUS_SENT => 'Envoyé',
        ];
    }
}
