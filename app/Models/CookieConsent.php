<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CookieConsent extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'session_id',
        'fingerprint',
        'ip_address',
        'user_agent',
        'consents',
        'consent_type',
        'consent_date',
        'expires_at',
        'is_active',
        'metadata'
    ];
    
    protected $casts = [
        'consents' => 'array',
        'metadata' => 'array',
        'consent_date' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];
    
    protected $dates = [
        'consent_date',
        'expires_at'
    ];
    
    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope pour les consentements actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }
    
    /**
     * Scope pour les consentements expirés
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }
    
    /**
     * Scope par utilisateur ou session
     */
    public function scopeForUserOrSession($query, $userId = null, $sessionId = null)
    {
        return $query->where(function($q) use ($userId, $sessionId) {
            if ($userId) {
                $q->where('user_id', $userId);
            } else {
                $q->where('session_id', $sessionId);
            }
        });
    }
    
    /**
     * Créer ou mettre à jour un consentement
     */
    public static function createOrUpdate($consents, $consentType = 'custom', $metadata = [])
    {
        $userId = Auth::id();
        $sessionId = session()->getId();
        $fingerprint = static::generateFingerprint();
        
        // Désactiver les anciens consentements
        static::forUserOrSession($userId, $sessionId)
            ->active()
            ->update(['is_active' => false]);
        
        // Créer le nouveau consentement
        return static::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'fingerprint' => $fingerprint,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'consents' => $consents,
            'consent_type' => $consentType,
            'consent_date' => now(),
            'expires_at' => now()->addYear(), // Expire dans 1 an
            'is_active' => true,
            'metadata' => array_merge([
                'version' => '1.0',
                'language' => app()->getLocale(),
                'url' => request()->url()
            ], $metadata)
        ]);
    }
    
    /**
     * Obtenir le consentement actuel pour un utilisateur ou session
     */
    public static function getCurrentConsent($userId = null, $sessionId = null)
    {
        $userId = $userId ?: Auth::id();
        $sessionId = $sessionId ?: session()->getId();
        
        return static::forUserOrSession($userId, $sessionId)
            ->active()
            ->latest('consent_date')
            ->first();
    }
    
    /**
     * Vérifier si une catégorie de cookie est acceptée
     */
    public static function isConsentGiven($category, $userId = null, $sessionId = null)
    {
        $consent = static::getCurrentConsent($userId, $sessionId);
        
        if (!$consent) {
            // Pas de consentement = seuls les cookies essentiels
            return $category === 'essential';
        }
        
        return $consent->consents[$category] ?? false;
    }
    
    /**
     * Obtenir tous les consentements donnés
     */
    public static function getAcceptedCategories($userId = null, $sessionId = null)
    {
        $consent = static::getCurrentConsent($userId, $sessionId);
        
        if (!$consent) {
            return ['essential']; // Seuls les cookies essentiels par défaut
        }
        
        return array_keys(array_filter($consent->consents, function($value) {
            return $value === true;
        }));
    }
    
    /**
     * Accepter tous les cookies
     */
    public static function acceptAll($metadata = [])
    {
        $categories = Cookie::getCategories();
        $consents = [];
        
        foreach (array_keys($categories) as $category) {
            $consents[$category] = true;
        }
        
        return static::createOrUpdate($consents, 'accept_all', $metadata);
    }
    
    /**
     * Rejeter tous les cookies (sauf essentiels)
     */
    public static function rejectAll($metadata = [])
    {
        $categories = Cookie::getCategories();
        $consents = [];
        
        foreach (array_keys($categories) as $category) {
            $consents[$category] = $category === 'essential';
        }
        
        return static::createOrUpdate($consents, 'reject_all', $metadata);
    }
    
    /**
     * Générer une empreinte du navigateur
     */
    protected static function generateFingerprint()
    {
        $components = [
            request()->userAgent(),
            request()->header('Accept-Language'),
            request()->header('Accept-Encoding'),
            request()->ip()
        ];
        
        return hash('sha256', implode('|', $components));
    }
    
    /**
     * Vérifier si le consentement a expiré
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
    
    /**
     * Obtenir le temps restant avant expiration
     */
    public function getTimeUntilExpirationAttribute()
    {
        if (!$this->expires_at) {
            return null;
        }
        
        return $this->expires_at->diffForHumans();
    }
    
    /**
     * Obtenir un résumé du consentement
     */
    public function getSummaryAttribute()
    {
        $accepted = array_keys(array_filter($this->consents));
        $total = count($this->consents);
        
        return [
            'accepted_count' => count($accepted),
            'total_count' => $total,
            'accepted_categories' => $accepted,
            'consent_type' => $this->consent_type,
            'is_expired' => $this->isExpired()
        ];
    }
}
