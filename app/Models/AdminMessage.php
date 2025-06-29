<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'status',
        'read_at',
        'resolved_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(AdminMessageReply::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    // Accessors
    public function getIsResolvedAttribute()
    {
        return $this->status === 'resolved';
    }

    public function getIsReadAttribute()
    {
        return !is_null($this->read_at);
    }
}
