<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;

/**
 * DeepSeek provider.
 * Docs: https://platform.deepseek.com/api-docs
 * DeepSeek uses OpenAI-compatible chat completions endpoint.
 */
class DeepSeekAiService implements AiServiceInterface
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://api.deepseek.com/v1';

    public function __construct(string $apiKey, string $model = AI_MODEL_DEEPSEEK_CHAT)
    {
        $this->apiKey = $apiKey;
        $this->model  = $model;
    }

    public function getProvider(): string { return AI_PROVIDER_DEEPSEEK; }
    public function getModel(): string    { return $this->model; }

    public function chat(array $messages, string $systemPrompt = '', array $options = []): string
    {
        $chatMessages = [];
        if (!empty($systemPrompt)) {
            $chatMessages[] = ['role' => 'system', 'content' => $systemPrompt];
        }
        foreach ($messages as $m) {
            $chatMessages[] = $m;
        }

        $response = Http::withToken($this->apiKey)
            ->post("{$this->baseUrl}/chat/completions", [
                'model'      => $this->model,
                'max_tokens' => $options['max_tokens'] ?? 512,
                'messages'   => $chatMessages,
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('DeepSeek API error: ' . $response->body());
        }

        return $response->json('choices.0.message.content', '');
    }

    public function testConnection(): array
    {
        try {
            $this->chat([['role' => 'user', 'content' => 'Hello']], '', ['max_tokens' => 10]);
            return ['ok' => true, 'message' => __('Connected to DeepSeek successfully.')];
        } catch (\Exception $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }
}
