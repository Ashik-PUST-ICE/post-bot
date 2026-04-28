<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');           // admin who owns this connection
            $table->string('tenant_id')->nullable();          // tenant isolation
            $table->tinyInteger('platform_type');             // 1=facebook_page, 2=messenger, 3=whatsapp, 4=instagram
            $table->string('platform_name');                  // human-readable name e.g. "My Store Page"
            $table->string('platform_id')->nullable();        // Facebook Page ID / WhatsApp Phone Number ID
            $table->string('waba_id')->nullable();            // WhatsApp Business Account ID
            $table->text('access_token')->nullable();         // long-lived token
            $table->string('phone_number')->nullable();       // for WhatsApp display
            $table->string('verify_token')->nullable();       // webhook verify token
            $table->string('webhook_url')->nullable();        // computed webhook URL
            $table->text('meta')->nullable();                 // JSON for extra data (page picture, category, etc.)
            $table->tinyInteger('auto_reply_status')->default(DEACTIVATE)->comment('1=active, 0=deactivate');
            $table->tinyInteger('status')->default(STATUS_ACTIVE)->comment('1=active, 0=deactivate');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('platform_connections');
    }
};
