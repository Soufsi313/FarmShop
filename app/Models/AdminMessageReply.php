<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMessageReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_message_id',
        'user_id',
        'message',
        'is_admin_reply'
    ];

    protected $casts = [
        'is_admin_reply' => 'boolean',
    ];

    // Relations
    public function adminMessage()
    {
        return $this->belongsTo(AdminMessage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
