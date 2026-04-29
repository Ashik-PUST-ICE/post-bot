<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontendSection extends Model
{
    protected $fillable = [
        'section_key',
        'page_title',
        'title',
        'description',
        'banner_image',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public static function getByKey(string $key): ?self
    {
        return static::where('section_key', $key)->first();
    }

    public static function allKeyed(): \Illuminate\Support\Collection
    {
        return static::all()->keyBy('section_key');
    }
}
