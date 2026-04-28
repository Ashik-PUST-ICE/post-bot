<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlatformConnection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'platform_type',
        'platform_name',
        'platform_id',
        'waba_id',
        'access_token',
        'phone_number',
        'verify_token',
        'webhook_url',
        'meta',
        'auto_reply_status',
        'status',
    ];

    protected $hidden = [
        'access_token',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    // ─── Constants removed — use global constants from Constant.php ───────────
    // e.g. PLATFORM_FACEBOOK_PAGE, PLATFORM_WHATSAPP, etc.
    // ─── Label helpers delegated to CoreArray.php helpers ─────────────────────
    // e.g. platformTypes($type), platformIcons($type), platformColors($type)

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function keywordRules()
    {
        return $this->hasMany(KeywordRule::class);
    }
}
