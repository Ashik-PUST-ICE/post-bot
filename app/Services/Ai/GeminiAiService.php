<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;

/**
 * Google Gemini provider.
 * Docs: https://ai.google.dev/gemini-api/docs/text-generation
 */
class GeminiAiService implements AiServiceInterface
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct(string $apiKey, string $model = AI_MODEL_GEMINI_25_FLASH)
    {
        $this->apiKey = $apiKey;
        $this->model  = $model;
    }

    public function getProvider(): string { return AI_PROVIDER_GEMINI; }
    public function getModel(): string    { return $this->model; }

    public function chat(array $messages, string $systemPrompt = '', array $options = []): string
    {
        // Build Gemini contents format
        $contents = [];
        foreach ($messages as $m) {
            $contents[] = [
                'role'  => $m['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $m['content']]],
            ];
        }

        $payload = ['contents' => $contents];

        if (!empty($systemPrompt)) {
            $payload['systemInstruction'] = [
                'parts' => [['text' => $systemPrompt]],
            ];
        }

        if (isset($options['max_tokens'])) {
            $payload['generationConfig'] = ['maxOutputTokens' => $options['max_tokens']];
        }

        $url = "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}";

        $response = Http::post($url, $payload);

        if ($response->failed()) {
            throw new \RuntimeException('Gemini API error: ' . $response->body());
        }

        return $response->json('candidates.0.content.parts.0.text', '');
    }

    public function testConnection(): array
    {
        try {
            $this->chat([['role' => 'user', 'content' => 'Hello']], '', ['max_tokens' => 10]);
            return ['ok' => true, 'message' => __('Connected to Gemini successfully.')];
        } catch (\Exception $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }
}
