<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class CookieConsent extends Model
{
    use HasFactory;

    protected $table = 'cookies'; 

    protected $fillable = [
        'user_id',
        'session_id',
        'status',
        'necessary',
        'analytics',
        'marketing',
        'preferences',
        'social_media',
        'ip_address',
        'user_agent',
        'consent_data',
        'expires_at'
    ];

    protected $casts = [
        'necessary' => 'boolean',
        'analytics' => 'boolean',
        'marketing' => 'boolean',
        'preferences' => 'boolean',
        'social_media' => 'boolean',
        'consent_data' => 'array',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $attributes = [
        'necessary' => true, // Les cookies nécessaires sont toujours acceptés
        'analytics' => false,
        'marketing' => false,
        'preferences' => false,
        'social_media' => false,
        'status' => 'pending'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour les consentements acceptés
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope pour les consentements rejetés
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope pour les consentements en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour les consentements non expirés
     */
    public function scopeValid($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope pour les consentements expirés
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
                    ->where('expires_at', '<=', now());
    }

    /**
     * Vérifie si le consentement est valide (non expiré)
     */
    public function isValid(): bool
    {
        return $this->expires_at === null || $this->expires_at->isFuture();
    }

    /**
     * Vérifie si le consentement est expiré
     */
    public function isExpired(): bool
    {
        return !$this->isValid();
    }

    /**
     * Retourne le nombre de jours avant expiration
     */
    public function daysUntilExpiration(): ?int
    {
        if ($this->expires_at === null) {
            return null;
        }

        return now()->diffInDays($this->expires_at, false);
    }

    /**
     * Met à jour le consentement avec de nouvelles préférences
     */
    public function updateConsent(array $preferences): bool
    {
        $this->analytics = $preferences['analytics'] ?? false;
        $this->marketing = $preferences['marketing'] ?? false;
        $this->preferences = $preferences['preferences'] ?? false;
        $this->social_media = $preferences['social_media'] ?? false;
        $this->status = 'accepted';
        $this->consent_data = $preferences;
        
        // Définir une nouvelle date d'expiration (1 an par défaut)
        $this->expires_at = now()->addYear();

        return $this->save();
    }

    /**
     * Rejette le consentement
     */
    public function reject(): bool
    {
        $this->status = 'rejected';
        $this->analytics = false;
        $this->marketing = false;
        $this->preferences = false;
        $this->social_media = false;
        $this->expires_at = now()->addYear();

        return $this->save();
    }

    /**
     * Retourne les types de cookies acceptés
     */
    public function getAcceptedCookieTypes(): array
    {
        $types = [];
        
        if ($this->necessary) {
            $types[] = 'necessary';
        }
        if ($this->analytics) {
            $types[] = 'analytics';
        }
        if ($this->marketing) {
            $types[] = 'marketing';
        }
        if ($this->preferences) {
            $types[] = 'preferences';
        }
        if ($this->social_media) {
            $types[] = 'social_media';
        }

        return $types;
    }

    /**
     * Vérifie si un type de cookie spécifique est accepté
     */
    public function acceptsCookieType(string $type): bool
    {
        return in_array($type, $this->getAcceptedCookieTypes());
    }

    /**
     * Retourne un résumé du consentement
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_email' => $this->user?->email,
            'session_id' => $this->session_id,
            'status' => $this->status,
            'accepted_types' => $this->getAcceptedCookieTypes(),
            'is_valid' => $this->isValid(),
            'expires_at' => $this->expires_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Trouve un consentement valide pour une session ou un utilisateur
     */
    public static function findValidConsent(?int $userId, string $sessionId): ?self
    {
        $query = static::valid();

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        return $query->latest()->first();
    }

    /**
     * Crée ou met à jour un consentement
     */
    public static function createOrUpdateConsent(
        ?int $userId,
        string $sessionId,
        array $preferences,
        string $ipAddress,
        string $userAgent
    ): self {
        // Chercher un consentement existant
        $consent = static::findValidConsent($userId, $sessionId);

        if ($consent) {
            // Mettre à jour le consentement existant
            $consent->updateConsent($preferences);
        } else {
            // Créer un nouveau consentement
            $consent = static::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'status' => 'accepted',
                'necessary' => true,
                'analytics' => $preferences['analytics'] ?? false,
                'marketing' => $preferences['marketing'] ?? false,
                'preferences' => $preferences['preferences'] ?? false,
                'social_media' => $preferences['social_media'] ?? false,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'consent_data' => $preferences,
                'expires_at' => now()->addYear()
            ]);
        }

        return $consent;
    }
}
