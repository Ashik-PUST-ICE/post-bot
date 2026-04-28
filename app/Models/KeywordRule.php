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
        'use_ai',
        'status',
        'priority',
    ];

    // ─── Constants ────────────────────────────────────────────────────────────

    const MATCH_CONTAINS     = 1;
    const MATCH_EXACT        = 2;
    const MATCH_STARTS_WITH  = 3;

    public static function matchLabel(int $type): string
    {
        return match ($type) {
            self::MATCH_CONTAINS    => 'Contains',
            self::MATCH_EXACT       => 'Exact Match',
            self::MATCH_STARTS_WITH => 'Starts With',
            default                 => 'Unknown',
        };
    }

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
