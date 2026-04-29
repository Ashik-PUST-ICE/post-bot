<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'tenant_id',
        'external_id',
        'direction',
        'sender_type',
        'body',
        'message_type',
        'meta_type',
        'attachment_url',
        'ai_metadata',
        'status',
        'is_approved',
        'sent_at',
    ];

    protected $casts = [
        'ai_metadata' => 'array',
        'sent_at'     => 'datetime',
    ];

    // ─── Note: all direction/sender/status constants live in app/Helpers/Constant.php ─
    // MESSAGE_DIRECTION_INBOUND / MESSAGE_DIRECTION_OUTBOUND
    // MESSAGE_SENDER_CUSTOMER  / MESSAGE_SENDER_AI / MESSAGE_SENDER_HUMAN_ADMIN
    // MESSAGE_STATUS_SENT      / MESSAGE_STATUS_DELIVERED / MESSAGE_STATUS_FAILED

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isFromCustomer(): bool
    {
        return $this->sender_type === MESSAGE_SENDER_CUSTOMER;
    }

    public function isFromAI(): bool
    {
        return $this->sender_type === MESSAGE_SENDER_AI;
    }

    public function isFromHuman(): bool
    {
        return $this->sender_type === MESSAGE_SENDER_HUMAN_ADMIN;
    }
}
