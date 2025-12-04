<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MistralService
{
    private const API_ENDPOINT = 'https://api.mistral.ai/v1/chat/completions';
    private const API_KEY = 'PeBiaaVvUCCuHu8Seg1zLCOzj0maiZC3';
    private const MODEL = 'mistral-small-2506';

    public function sendPrompt(string $prompt, int $maxTokens = 8192): string
    {
        $response = Http::timeout(300)
            ->withHeaders([
                'Authorization' => 'Bearer ' . self::API_KEY,
                'Content-Type' => 'application/json',
            ])
            ->post(self::API_ENDPOINT, [
                'model' => self::MODEL,
                'max_tokens' => $maxTokens,
                'temperature' => 0.4,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

        if (!$response->successful()) {
            throw new \Exception('Mistral API request failed: ' . $response->body());
        }

        $data = $response->json();

        return $data['choices'][0]['message']['content'] ?? '';
    }
}
