<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaAppConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'fb_app_id',
        'fb_app_secret',
        'fb_page_access_token',
        'fb_page_id',
        'wa_phone_number_id',
        'wa_business_account_id',
        'wa_access_token',
        'ig_access_token',
        'ig_user_id',
        'webhook_verify_token',
        'status',
    ];

    protected $hidden = [
        'fb_app_secret',
        'fb_page_access_token',
        'wa_access_token',
        'ig_access_token',
    ];

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Get config for a user, or create with empty defaults.
     */
    public static function forUser(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'tenant_id'           => auth()->user()->tenant_id ?? null,
                'webhook_verify_token' => \Illuminate\Support\Str::random(40),
                'status'              => STATUS_ACTIVE,
            ]
        );
    }

    /**
     * Check if Facebook App credentials are configured (App ID + Secret).
     * Uses getRawOriginal() because fb_app_secret is in $hidden.
     */
    public function hasFacebook(): bool
    {
        return !empty($this->fb_app_id)
            && !empty($this->getRawOriginal('fb_app_secret'));
    }

    /**
     * Check if WhatsApp credentials are configured.
     */
    public function hasWhatsApp(): bool
    {
        return !empty($this->wa_phone_number_id)
            && !empty($this->getRawOriginal('wa_access_token'));
    }

    /**
     * Check if Instagram credentials are configured.
     */
    public function hasInstagram(): bool
    {
        return !empty($this->getRawOriginal('ig_access_token'))
            && !empty($this->ig_user_id);
    }

    // ─── Attribute Accessors (expose hidden fields to server-side services only) ─

    /**
     * Allow MetaOAuthService / MetaService to read the secret via property access.
     * Blade and JSON serialization still hide this field.
     */
    public function getFbAppSecretAttribute(): ?string
    {
        return $this->getRawOriginal('fb_app_secret');
    }

    public function getFbPageAccessTokenAttribute(): ?string
    {
        return $this->getRawOriginal('fb_page_access_token');
    }

    public function getWaAccessTokenAttribute(): ?string
    {
        return $this->getRawOriginal('wa_access_token');
    }

    public function getIgAccessTokenAttribute(): ?string
    {
        return $this->getRawOriginal('ig_access_token');
    }
}
