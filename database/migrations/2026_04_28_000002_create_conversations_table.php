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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');                     // admin owner
            $table->string('tenant_id')->nullable();                    // tenant isolation
            $table->unsignedBigInteger('platform_connection_id');       // which page/number
            $table->tinyInteger('platform_type');                       // 1=fb_page, 2=messenger, 3=whatsapp, 4=instagram
            $table->string('contact_id')->nullable();                   // sender's platform ID (PSID / WA number)
            $table->string('contact_name')->nullable();                 // sender's display name
            $table->string('contact_avatar')->nullable();               // sender's profile picture URL
            $table->string('external_thread_id')->nullable();           // platform thread/conversation ID
            $table->tinyInteger('status')->default(1)->comment('1=open, 2=resolved, 3=pending, 4=escalated');
            $table->unsignedBigInteger('assigned_to')->nullable();      // moderator user_id
            $table->text('last_message')->nullable();                   // preview of last message
            $table->timestamp('last_message_at')->nullable();
            $table->unsignedInteger('ai_replied_count')->default(0);    // count of AI replies sent
            $table->tinyInteger('human_taken_over')->default(0)->comment('1=yes, 0=no');
            $table->string('label')->nullable();                        // tag/label for this conversation
            $table->timestamps();
            $table->softDeletes();

            // Unique thread per admin+platform connection+contact
            $table->unique(['user_id', 'platform_connection_id', 'contact_id', 'platform_type'], 'unique_conversation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
};
