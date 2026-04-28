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
        'external_id',         // fixed: was external_message_id
        'direction',
        'sender_type',
        'body',
        'message_type',
        'meta_type',           // platform sub-type: messenger, fb_comment, whatsapp, instagram, ig_comment, ig_mention
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

    // ─── Note: direction/sender/status constants are in app/Helpers/Constant.php ─
    // Use MESSAGE_DIRECTION_INBOUND, MESSAGE_SENDER_AI, MESSAGE_STATUS_SENT, etc.

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
        return $this->sender_type === self::SENDER_CUSTOMER;
    }

    public function isFromAI(): bool
    {
        return $this->sender_type === self::SENDER_AI;
    }

    public function isFromHuman(): bool
    {
        return $this->sender_type === self::SENDER_HUMAN_ADMIN;
    }
}
