<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;

/**
 * Anthropic Claude provider.
 * Docs: https://docs.anthropic.com/en/api/messages
 */
class ClaudeAiService implements AiServiceInterface
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://api.anthropic.com/v1';
    protected string $apiVersion = '2023-06-01';

    public function __construct(string $apiKey, string $model = AI_MODEL_CLAUDE_SONNET)
    {
        $this->apiKey = $apiKey;
        $this->model  = $model;
    }

    public function getProvider(): string { return AI_PROVIDER_CLAUDE; }
    public function getModel(): string    { return $this->model; }

    public function chat(array $messages, string $systemPrompt = '', array $options = []): string
    {
        $payload = [
            'model'      => $this->model,
            'max_tokens' => $options['max_tokens'] ?? 512,
            'messages'   => $messages,
        ];

        if (!empty($systemPrompt)) {
            $payload['system'] = $systemPrompt;
        }

        $response = Http::withHeaders([
            'x-api-key'         => $this->apiKey,
            'anthropic-version' => $this->apiVersion,
            'content-type'      => 'application/json',
        ])->post("{$this->baseUrl}/messages", $payload);

        if ($response->failed()) {
            throw new \RuntimeException('Claude API error: ' . $response->body());
        }

        return $response->json('content.0.text', '');
    }

    public function testConnection(): array
    {
        try {
            $this->chat([['role' => 'user', 'content' => 'Hello']], '', ['max_tokens' => 10]);
            return ['ok' => true, 'message' => __('Connected to Claude successfully.')];
        } catch (\Exception $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }
}
