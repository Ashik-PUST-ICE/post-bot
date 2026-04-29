<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontendContent extends Model
{
    protected $fillable = [
        'type',
        'name',
        'title',
        'sub_title',
        'description',
        'image',
        'others',
        'rating',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'others' => 'array',
        'rating'  => 'float',
        'status'  => 'integer',
    ];

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type)->where('status', STATUS_ACTIVE)->orderBy('sort_order');
    }

    // ── Type constants ──────────────────────────────────────────────────────

    const TYPE_FEATURE      = 'feature';
    const TYPE_SERVICE      = 'service';
    const TYPE_CORE_FEATURE = 'core_feature';
    const TYPE_CHOOSE_US    = 'choose_us';
    const TYPE_FAQ          = 'faq';
    const TYPE_TESTIMONIAL  = 'testimonial';

    public static function allTypes(): array
    {
        return [
            self::TYPE_FEATURE,
            self::TYPE_SERVICE,
            self::TYPE_CORE_FEATURE,
            self::TYPE_CHOOSE_US,
            self::TYPE_FAQ,
            self::TYPE_TESTIMONIAL,
        ];
    }
}
