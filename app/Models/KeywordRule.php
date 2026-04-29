<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KeywordRule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'platform_connection_id',
        'keyword',
        'match_type',
        'reply_template',
        'action',
        'use_ai',
        'status',
        'priority',
    ];

    // ─── Note: match_type constants → KEYWORD_MATCH_* in app/Helpers/Constant.php ─
    // ─── Note: action constants    → KEYWORD_ACTION_* in app/Helpers/Constant.php ─
    // ─── Note: label helpers       → keywordMatchTypes(), keywordActionLabel()     ─

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function platformConnection()
    {
        return $this->belongsTo(PlatformConnection::class);
    }
}
