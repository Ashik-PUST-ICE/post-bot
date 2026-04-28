<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * ai_agent_settings stores the per-admin AI agent configuration.
     * Supports multiple AI providers (Claude, OpenAI/ChatGPT, Gemini, Grok, DeepSeek).
     * Each provider has its own encrypted API key column; only the active provider is used.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_agent_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('tenant_id')->nullable();

            // ── Active Provider & Model ───────────────────────────────────────
            $table->string('ai_provider')->default('claude')
                ->comment('Active provider: claude | openai | gemini | grok | deepseek');
            $table->string('ai_model')->default('claude-sonnet-4-5')
                ->comment('Model slug for the active provider');

            // ── Provider API Keys (encrypted at application level) ────────────
            $table->text('claude_api_key')->nullable()
                ->comment('Anthropic API key — console.anthropic.com');
            $table->text('openai_api_key')->nullable()
                ->comment('OpenAI API key — platform.openai.com/api-keys');
            $table->text('gemini_api_key')->nullable()
                ->comment('Google Gemini API key — aistudio.google.com/apikey');
            $table->text('grok_api_key')->nullable()
                ->comment('xAI Grok API key — console.x.ai');
            $table->text('deepseek_api_key')->nullable()
                ->comment('DeepSeek API key — platform.deepseek.com');

            // ── Prompt & Context ──────────────────────────────────────────────
            $table->text('system_prompt')->nullable();
            $table->text('business_context')->nullable()
                ->comment('FAQs, product info, tone guidelines etc.');
            $table->string('language_mode')->default('auto')
                ->comment('auto=detect, or ISO code: en, bn, ar ...');

            // ── Behaviour Toggles ─────────────────────────────────────────────
            $table->tinyInteger('auto_reply_enabled')->default(1);
            $table->tinyInteger('sentiment_analysis')->default(1);
            $table->tinyInteger('smart_suggestions')->default(1)
                ->comment('Suggest replies for human approval before sending');
            $table->tinyInteger('spam_detection')->default(1);
            $table->tinyInteger('conversation_memory')->default(1)
                ->comment('Include past messages in context window');

            // ── Performance ───────────────────────────────────────────────────
            $table->unsignedSmallInteger('reply_delay_seconds')->default(2)
                ->comment('Simulated typing delay before sending');
            $table->unsignedSmallInteger('confidence_threshold')->default(70)
                ->comment('0-100: below this score, escalate to human');
            $table->unsignedSmallInteger('max_tokens')->default(512)
                ->comment('Max tokens per AI response');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_agent_settings');
    }
};
