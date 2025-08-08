<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Cookie extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'necessary',
        'analytics',
        'marketing',
        'preferences',
        'social_media',
        'accepted_at',
        'rejected_at',
        'last_updated_at',
        'migrated_at',
        'preferences_details',
        'consent_version',
        'status',
        'page_url',
        'referer',
        'browser_info'
    ];

    protected $casts = [
        'necessary' => 'boolean',
        'analytics' => 'boolean',
        'marketing' => 'boolean',
        'preferences' => 'boolean',
        'social_media' => 'boolean',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'last_updated_at' => 'datetime',
        'migrated_at' => 'datetime',
        'preferences_details' => 'array',
        'browser_info' => 'array'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir les préférences de cookies par défaut
     */
    public static function getDefaultPreferences(): array
    {
        return [
            'necessary' => true,
            'analytics' => false,
            'marketing' => false,
            'preferences' => false,
            'social_media' => false
        ];
    }

    /**
     * Obtenir les descriptions des types de cookies
     */
    public static function getCookieDescriptions(): array
    {
        return [
            'necessary' => [
                'name' => 'Cookies nécessaires',
                'description' => 'Ces cookies sont essentiels au fonctionnement du site et ne peuvent pas être désactivés.',
                'examples' => ['Session utilisateur', 'Panier d\'achat', 'Authentification']
            ],
            'analytics' => [
                'name' => 'Cookies analytiques',
                'description' => 'Ces cookies nous aident à comprendre comment les visiteurs utilisent notre site.',
                'examples' => ['Google Analytics', 'Statistiques de visite', 'Comportement utilisateur']
            ],
            'marketing' => [
                'name' => 'Cookies marketing',
                'description' => 'Ces cookies sont utilisés pour personnaliser les publicités et mesurer leur efficacité.',
                'examples' => ['Publicités ciblées', 'Remarketing', 'Suivi conversions']
            ],
            'preferences' => [
                'name' => 'Cookies de préférences',
                'description' => 'Ces cookies permettent de mémoriser vos choix et personnaliser votre expérience.',
                'examples' => ['Langue', 'Région', 'Paramètres d\'affichage']
            ],
            'social_media' => [
                'name' => 'Cookies réseaux sociaux',
                'description' => 'Ces cookies permettent le partage sur les réseaux sociaux et l\'intégration de contenu.',
                'examples' => ['Boutons de partage', 'Widgets sociaux', 'Connexion sociale']
            ]
        ];
    }

    /**
     * Mettre à jour les préférences de cookies
     */
    public function updatePreferences(array $preferences): void
    {
        $this->update([
            'necessary' => true, // Toujours vrai
            'analytics' => $preferences['analytics'] ?? false,
            'marketing' => $preferences['marketing'] ?? false,
            'preferences' => $preferences['preferences'] ?? false,
            'social_media' => $preferences['social_media'] ?? false,
            'preferences_details' => $preferences,
            'last_updated_at' => now(),
            'status' => $this->determineStatus($preferences)
        ]);
    }

    /**
     * Accepter tous les cookies
     */
    public function acceptAll(): void
    {
        $this->update([
            'necessary' => true,
            'analytics' => true,
            'marketing' => true,
            'preferences' => true,
            'social_media' => true,
            'accepted_at' => now(),
            'rejected_at' => null,
            'last_updated_at' => now(),
            'status' => 'accepted'
        ]);
    }

    /**
     * Rejeter tous les cookies optionnels
     */
    public function rejectAll(): void
    {
        $this->update([
            'necessary' => true,
            'analytics' => false,
            'marketing' => false,
            'preferences' => false,
            'social_media' => false,
            'accepted_at' => null,
            'rejected_at' => now(),
            'last_updated_at' => now(),
            'status' => 'rejected'
        ]);
    }

    /**
     * Déterminer le statut basé sur les préférences
     */
    private function determineStatus(array $preferences): string
    {
        $optionalCookies = ['analytics', 'marketing', 'preferences', 'social_media'];
        $acceptedOptional = 0;

        foreach ($optionalCookies as $cookie) {
            if ($preferences[$cookie] ?? false) {
                $acceptedOptional++;
            }
        }

        if ($acceptedOptional === 0) {
            return 'rejected';
        } elseif ($acceptedOptional === count($optionalCookies)) {
            return 'accepted';
        } else {
            return 'partial';
        }
    }

    /**
     * Obtenir le résumé des préférences
     */
    public function getPreferencesSummary(): array
    {
        return [
            'status' => $this->status,
            'total_cookies' => 5,
            'accepted_cookies' => collect([
                $this->necessary,
                $this->analytics,
                $this->marketing,
                $this->preferences,
                $this->social_media
            ])->filter()->count(),
            'last_updated' => $this->last_updated_at?->format('d/m/Y H:i'),
            'preferences' => [
                'necessary' => $this->necessary,
                'analytics' => $this->analytics,
                'marketing' => $this->marketing,
                'preferences' => $this->preferences,
                'social_media' => $this->social_media
            ]
        ];
    }

    /**
     * Vérifier si un type de cookie est accepté
     */
    public function isAccepted(string $cookieType): bool
    {
        return $this->{$cookieType} ?? false;
    }

    /**
     * Obtenir les cookies par statut
     */
    public static function getByStatus(string $status)
    {
        return static::where('status', $status);
    }

    /**
     * Obtenir les statistiques globales des cookies
     */
    public static function getGlobalStats(): array
    {
        $total = static::count();
        
        if ($total === 0) {
            return [
                'total' => 0,
                'accepted' => 0,
                'rejected' => 0,
                'partial' => 0,
                'pending' => 0,
                'acceptance_rate' => 0,
                'rejection_rate' => 0
            ];
        }

        $stats = static::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "accepted" THEN 1 ELSE 0 END) as accepted,
            SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN status = "partial" THEN 1 ELSE 0 END) as partial,
            SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending
        ')->first();

        return [
            'total' => $stats->total,
            'accepted' => $stats->accepted,
            'rejected' => $stats->rejected,
            'partial' => $stats->partial,
            'pending' => $stats->pending,
            'acceptance_rate' => round(($stats->accepted / $stats->total) * 100, 2),
            'rejection_rate' => round(($stats->rejected / $stats->total) * 100, 2)
        ];
    }

    /**
     * Scope pour les cookies récents
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Scope pour les utilisateurs connectés uniquement
     */
    public function scopeAuthenticatedUsers($query)
    {
        return $query->whereNotNull('user_id');
    }

    /**
     * Scope pour les visiteurs uniquement
     */
    public function scopeGuestUsers($query)
    {
        return $query->whereNull('user_id');
    }
}
