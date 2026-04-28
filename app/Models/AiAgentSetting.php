<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiAgentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'ai_provider',
        'ai_model',
        // API keys
        'claude_api_key',
        'openai_api_key',
        'gemini_api_key',
        'grok_api_key',
        'deepseek_api_key',
        // Prompt & context
        'system_prompt',
        'business_context',
        'language_mode',
        // Toggles
        'auto_reply_enabled',
        'sentiment_analysis',
        'smart_suggestions',
        'spam_detection',
        'conversation_memory',
        // Performance
        'reply_delay_seconds',
        'confidence_threshold',
        'max_tokens',
    ];

    protected $hidden = [
        'claude_api_key',
        'openai_api_key',
        'gemini_api_key',
        'grok_api_key',
        'deepseek_api_key',
    ];

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Get or create default settings for a user.
     */
    public static function forUser(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'ai_provider'          => AI_PROVIDER_CLAUDE,
                'ai_model'             => AI_MODEL_CLAUDE_SONNET,
                'system_prompt'        => 'You are a helpful business assistant. Reply politely and professionally.',
                'language_mode'        => AI_LANGUAGE_AUTO,
                'auto_reply_enabled'   => STATUS_ACTIVE,
                'sentiment_analysis'   => STATUS_ACTIVE,
                'smart_suggestions'    => STATUS_ACTIVE,
                'spam_detection'       => STATUS_ACTIVE,
                'conversation_memory'  => STATUS_ACTIVE,
                'reply_delay_seconds'  => 2,
                'confidence_threshold' => 70,
                'max_tokens'           => 512,
            ]
        );
    }

    /**
     * Get the active API key for the currently selected provider.
     * Uses getRawOriginal() because API keys are in $hidden.
     */
    public function getActiveApiKey(): ?string
    {
        return match ($this->ai_provider) {
            AI_PROVIDER_CLAUDE   => $this->getRawOriginal('claude_api_key'),
            AI_PROVIDER_OPENAI   => $this->getRawOriginal('openai_api_key'),
            AI_PROVIDER_GEMINI   => $this->getRawOriginal('gemini_api_key'),
            AI_PROVIDER_GROK     => $this->getRawOriginal('grok_api_key'),
            AI_PROVIDER_DEEPSEEK => $this->getRawOriginal('deepseek_api_key'),
            default              => null,
        };
    }

    /**
     * Get the API key for a specific provider (used for test-connection checks).
     */
    public function getApiKeyFor(string $provider): ?string
    {
        return match ($provider) {
            AI_PROVIDER_CLAUDE   => $this->getRawOriginal('claude_api_key'),
            AI_PROVIDER_OPENAI   => $this->getRawOriginal('openai_api_key'),
            AI_PROVIDER_GEMINI   => $this->getRawOriginal('gemini_api_key'),
            AI_PROVIDER_GROK     => $this->getRawOriginal('grok_api_key'),
            AI_PROVIDER_DEEPSEEK => $this->getRawOriginal('deepseek_api_key'),
            default              => null,
        };
    }

    /**
     * Check whether the active provider has an API key saved.
     */
    public function isProviderConfigured(): bool
    {
        return !empty($this->getActiveApiKey());
    }
}
