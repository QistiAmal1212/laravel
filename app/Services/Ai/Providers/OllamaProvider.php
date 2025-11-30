<?php

namespace App\Services\Ai\Providers;

use Illuminate\Support\Facades\Http;

class OllamaProvider implements AiProvider
{
    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(
        protected array $config,
    ) {
    }

    public function ask(string $prompt, array $options = []): string
    {
        $response = Http::timeout(300)->post(
            rtrim((string) $this->config['host'], '/').'/api/chat',
            array_merge([
                'model' => $this->config['model'],
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'stream' => false,
            ], $options),
        )->throw()->json();

        return (string) ($response['message']['content'] ?? '');
    }
}
