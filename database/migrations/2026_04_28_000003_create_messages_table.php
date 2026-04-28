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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('user_id');                  // admin owner
            $table->string('tenant_id')->nullable();                 // tenant isolation
            $table->string('external_id')->nullable()->index();      // platform message ID (mid/wamid) for deduplication
            $table->tinyInteger('direction')->default(1)->comment('1=inbound (customer), 2=outbound (reply)');
            $table->tinyInteger('sender_type')->default(1)->comment('1=customer, 2=ai_agent, 3=human_admin');
            $table->longText('body')->nullable();                    // text content
            $table->string('message_type')->default('text')->comment('text, image, audio, video, file, comment, mention');
            $table->string('attachment_url')->nullable();            // media attachment
            $table->string('meta_type')->nullable()->comment('platform sub-type: messenger,fb_comment,whatsapp,instagram,ig_comment,ig_mention');
            $table->text('ai_metadata')->nullable();                 // JSON: sentiment, confidence score, provider used
            $table->tinyInteger('status')->default(1)->comment('1=sent, 2=delivered, 3=read, 4=failed');
            $table->tinyInteger('is_approved')->default(1)->comment('1=auto-sent, 0=pending human approval');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
