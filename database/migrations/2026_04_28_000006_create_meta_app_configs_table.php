<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Stores the admin's Meta Developer App credentials (App ID, App Secret,
     * WhatsApp-specific WABA ID, webhook verify token) in one row per admin.
     * These credentials are used to:
     *  - Authenticate with the Meta Graph API (FB pages, IG, WA)
     *  - Verify incoming webhooks (X-Hub-Signature-256)
     *  - Exchange short-lived user tokens for long-lived page tokens
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_app_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();    // one row per admin
            $table->string('tenant_id')->nullable();

            // ── Meta Developer App credentials ────────────────────────────────
            $table->string('fb_app_id')->nullable()
                ->comment('Meta App ID from developers.facebook.com > App Settings > Basic');
            $table->text('fb_app_secret')->nullable()
                ->comment('Meta App Secret — server-side only, never expose to clients');
            $table->text('fb_page_access_token')->nullable()
                ->comment('Long-lived Page Access Token (60-day or permanent via System User)');
            $table->string('fb_page_id')->nullable()
                ->comment('The specific Facebook Page ID to manage');

            // ── WhatsApp Business API ──────────────────────────────────────────
            $table->string('wa_phone_number_id')->nullable()
                ->comment('WhatsApp Phone Number ID from Meta App Dashboard > WhatsApp > API Setup');
            $table->string('wa_business_account_id')->nullable()
                ->comment('WhatsApp Business Account (WABA) ID');
            $table->text('wa_access_token')->nullable()
                ->comment('Permanent System User access token with whatsapp_business_messaging permission');

            // ── Instagram Basic Display API ────────────────────────────────────
            $table->text('ig_access_token')->nullable()
                ->comment('Instagram long-lived access token');
            $table->string('ig_user_id')->nullable()
                ->comment('Instagram Business Account ID');

            // ── Webhook settings (shared for all Meta platforms) ──────────────
            $table->string('webhook_verify_token')->nullable()
                ->comment('Custom string you define in Meta App Dashboard > Webhooks > Verify Token');

            // ── Status ─────────────────────────────────────────────────────────
            $table->tinyInteger('status')->default(STATUS_ACTIVE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta_app_configs');
    }
};
