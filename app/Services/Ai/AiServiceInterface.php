<?php

namespace App\Services\Ai;

/**
 * Contract that every AI provider must implement.
 */
interface AiServiceInterface
{
    /**
     * Send a chat message and return the AI text response.
     *
     * @param  array  $messages  [{role: 'user'|'assistant', content: string}, ...]
     * @param  string $systemPrompt
     * @param  array  $options   ['max_tokens', 'temperature', ...]
     * @return string
     */
    public function chat(array $messages, string $systemPrompt = '', array $options = []): string;

    /**
     * Return the provider slug identifier.
     */
    public function getProvider(): string;

    /**
     * Return the active model slug.
     */
    public function getModel(): string;

    /**
     * Verify connectivity (API key is valid & reachable).
     */
    public function testConnection(): array; // ['ok' => bool, 'message' => string]
}
