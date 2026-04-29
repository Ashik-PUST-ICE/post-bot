<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'nick_name',
        'email',
        'mobile',
        'country',
        'city',
        'zip_code',
        'address',
        'currency',
        'company_name',
        'company_country',
        'company_city',
        'company_designation',
        'company_zip_code',
        'company_address',
        'email_verified_at',
        'password',
        'image',
        'role',
        'email_verification_status',
        'phone_verification_status',
        'google_auth_status',
        'google2fa_secret',
        'google_id',
        'facebook_id',
        'verify_token',
        'otp',
        'otp_expiry',
        'last_seen',
        'show_email_in_public',
        'show_phone_in_public',
        'created_by',
        'company_logo',
        'status',
        'tenant_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    public function userDetail()
    {
        return $this->hasOne(UserDetails::class);
    }

    public function employee(){
        return $this->hasOne(EmployeeDetails::class);
    }

    public function package()
    {
        return $this->hasOne(UserPackage::class, 'user_id')->where('status', STATUS_ACTIVE);
    }

}
