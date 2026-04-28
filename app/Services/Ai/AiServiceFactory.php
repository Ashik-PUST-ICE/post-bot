<?php

namespace App\Services\Ai;

use App\Models\AiAgentSetting;

/**
 * Factory that resolves the correct AiServiceInterface implementation
 * based on the admin's active AI provider setting.
 */
class AiServiceFactory
{
    /**
     * Build the correct AI service for a given AiAgentSetting.
     *
     * @throws \RuntimeException if the provider is unknown or the API key is missing.
     */
    public static function make(AiAgentSetting $settings): AiServiceInterface
    {
        $apiKey = $settings->getActiveApiKey();

        if (empty($apiKey)) {
            throw new \RuntimeException(
                __('No API key configured for provider: ') . aiProviders($settings->ai_provider)
            );
        }

        return match ($settings->ai_provider) {
            AI_PROVIDER_CLAUDE   => new ClaudeAiService($apiKey, $settings->ai_model),
            AI_PROVIDER_OPENAI   => new OpenAiService($apiKey, $settings->ai_model),
            AI_PROVIDER_GEMINI   => new GeminiAiService($apiKey, $settings->ai_model),
            AI_PROVIDER_GROK     => new GrokAiService($apiKey, $settings->ai_model),
            AI_PROVIDER_DEEPSEEK => new DeepSeekAiService($apiKey, $settings->ai_model),
            default => throw new \RuntimeException(
                __('Unsupported AI provider: ') . $settings->ai_provider
            ),
        };
    }

    /**
     * Build an AI service by provider slug + API key directly (for testing).
     */
    public static function makeForProvider(string $provider, string $apiKey, string $model = ''): AiServiceInterface
    {
        $defaultModels = [
            AI_PROVIDER_CLAUDE   => AI_MODEL_CLAUDE_SONNET,
            AI_PROVIDER_OPENAI   => AI_MODEL_GPT4O,
            AI_PROVIDER_GEMINI   => AI_MODEL_GEMINI_25_FLASH,
            AI_PROVIDER_GROK     => AI_MODEL_GROK3,
            AI_PROVIDER_DEEPSEEK => AI_MODEL_DEEPSEEK_CHAT,
        ];

        $resolvedModel = $model ?: ($defaultModels[$provider] ?? '');

        return match ($provider) {
            AI_PROVIDER_CLAUDE   => new ClaudeAiService($apiKey, $resolvedModel),
            AI_PROVIDER_OPENAI   => new OpenAiService($apiKey, $resolvedModel),
            AI_PROVIDER_GEMINI   => new GeminiAiService($apiKey, $resolvedModel),
            AI_PROVIDER_GROK     => new GrokAiService($apiKey, $resolvedModel),
            AI_PROVIDER_DEEPSEEK => new DeepSeekAiService($apiKey, $resolvedModel),
            default => throw new \RuntimeException('Unsupported provider: ' . $provider),
        };
    }
}
