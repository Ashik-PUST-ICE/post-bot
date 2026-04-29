<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReplyTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'title',
        'content',
        'platform',
        'usage_count',
        'status',
    ];

    // ─── Note: platform constants  → TEMPLATE_PLATFORM_* in app/Helpers/Constant.php ─
    // ─── Note: platform label list → replyTemplatePlatforms() in app/Helpers/CoreArray.php ─

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }
}
