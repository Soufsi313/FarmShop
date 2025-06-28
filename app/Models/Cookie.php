<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cookie extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'category',
        'description',
        'purpose',
        'provider',
        'duration_days',
        'type',
        'is_essential',
        'is_active',
        'domains',
        'technical_details'
    ];
    
    protected $casts = [
        'is_essential' => 'boolean',
        'is_active' => 'boolean',
        'domains' => 'array',
        'technical_details' => 'array'
    ];
    
    /**
     * Catégories de cookies disponibles
     */
    public static function getCategories()
    {
        return [
            'essential' => 'Cookies essentiels',
            'analytics' => 'Cookies d\'analyse',
            'marketing' => 'Cookies marketing',
            'preferences' => 'Cookies de préférences',
            'social' => 'Cookies réseaux sociaux'
        ];
    }
    
    /**
     * Types de cookies disponibles
     */
    public static function getTypes()
    {
        return [
            'session' => 'Cookie de session',
            'persistent' => 'Cookie persistant',
            'third_party' => 'Cookie tiers'
        ];
    }
    
    /**
     * Scope pour les cookies actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope pour les cookies essentiels
     */
    public function scopeEssential($query)
    {
        return $query->where('is_essential', true);
    }
    
    /**
     * Scope par catégorie
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
    
    /**
     * Obtenir tous les cookies par catégorie
     */
    public static function getByCategory()
    {
        return static::active()
            ->get()
            ->groupBy('category')
            ->map(function ($cookies, $category) {
                return [
                    'name' => static::getCategories()[$category] ?? $category,
                    'cookies' => $cookies
                ];
            });
    }
    
    /**
     * Obtenir les cookies essentiels
     */
    public static function getEssentialCookies()
    {
        return static::essential()->active()->get();
    }
    
    /**
     * Vérifier si un cookie est essentiel
     */
    public function isEssential()
    {
        return $this->is_essential;
    }
    
    /**
     * Obtenir la durée formatée
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_days) {
            return 'Session';
        }
        
        if ($this->duration_days == 1) {
            return '1 jour';
        }
        
        if ($this->duration_days < 30) {
            return $this->duration_days . ' jours';
        }
        
        if ($this->duration_days < 365) {
            $months = round($this->duration_days / 30);
            return $months . ' mois';
        }
        
        $years = round($this->duration_days / 365);
        return $years . ' an' . ($years > 1 ? 's' : '');
    }
    
    /**
     * Obtenir la description de la catégorie
     */
    public function getCategoryNameAttribute()
    {
        return static::getCategories()[$this->category] ?? $this->category;
    }
    
    /**
     * Obtenir le nom du type
     */
    public function getTypeNameAttribute()
    {
        return static::getTypes()[$this->type] ?? $this->type;
    }
}
