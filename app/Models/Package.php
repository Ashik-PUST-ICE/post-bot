<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'others' => 'array',
    ];

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'page_limit',
        'message_limit',
        'others',
        'monthly_price',
        'yearly_price',
        'stripe_monthly_plan_id',
        'stripe_yearly_plan_id',
        'stripe_product_id',
        'status',
        'is_default',
        'is_trail',
    ];
}
