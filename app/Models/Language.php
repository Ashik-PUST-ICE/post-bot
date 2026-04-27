<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'language',
        'iso_code',
        'flag_id',
        'rtl',
        'status',
        'default',
        'font',
    ];

    public function getFlagAttribute()
    {
       return getFileUrl($this->flag_id);
    }
}
