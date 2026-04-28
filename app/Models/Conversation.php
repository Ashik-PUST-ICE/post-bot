<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'platform_connection_id',
        'platform_type',
        'contact_id',
        'contact_name',
        'contact_avatar',
        'external_thread_id',
        'status',
        'assigned_to',
        'last_message',
        'last_message_at',
        'ai_replied_count',    // fixed: was ai_replied (tinyInt), now unsignedInt counter
        'human_taken_over',
        'label',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    // ─── Constants removed — use global constants from Constant.php ───────────
    // e.g. CONVERSATION_STATUS_OPEN, CONVERSATION_STATUS_RESOLVED, etc.
    // ─── Label/badge helpers in CoreArray.php ─────────────────────────────
    // conversationStatuses($status), conversationStatusBadge($status)

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function platformConnection()
    {
        return $this->belongsTo(PlatformConnection::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeOpen($query)
    {
        return $query->where('status', CONVERSATION_STATUS_OPEN);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isOpen(): bool
    {
        return $this->status === CONVERSATION_STATUS_OPEN;
    }

    public function isEscalated(): bool
    {
        return $this->status === CONVERSATION_STATUS_ESCALATED;
    }
}
