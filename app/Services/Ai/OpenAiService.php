<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;

/**
 * OpenAI ChatGPT provider.
 * Docs: https://platform.openai.com/docs/api-reference/chat
 */
class OpenAiService implements AiServiceInterface
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://api.openai.com/v1';

    public function __construct(string $apiKey, string $model = AI_MODEL_GPT4O)
    {
        $this->apiKey = $apiKey;
        $this->model  = $model;
    }

    public function getProvider(): string { return AI_PROVIDER_OPENAI; }
    public function getModel(): string    { return $this->model; }

    public function chat(array $messages, string $systemPrompt = '', array $options = []): string
    {
        // Prepend system message if provided
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
            throw new \RuntimeException('OpenAI API error: ' . $response->body());
        }

        return $response->json('choices.0.message.content', '');
    }

    public function testConnection(): array
    {
        try {
            $this->chat([['role' => 'user', 'content' => 'Hello']], '', ['max_tokens' => 10]);
            return ['ok' => true, 'message' => __('Connected to OpenAI successfully.')];
        } catch (\Exception $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }
}
