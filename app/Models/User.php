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
}
