<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScrapflyService
{
    private string $baseUrl = 'https://api.scrapfly.io/scrape';
    private string $apiKey;
    private int $cacheTtl = 86400;

    public function __construct()
    {
        $this->apiKey = config('services.scrapfly.api_key');
        $this->cacheTtl = config('services.scrapfly.cache_ttl', 86400);
    }

    public function scrape(
        string $url,
        string $country = 'nl',
        bool $asp = true,
        int $timeout = 75000,
        bool $retry = false,
        array $tags = ['project:default']
    ): array {
        $cacheKey = $this->getCacheKey($url, $country, $asp);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($url, $country, $asp, $timeout, $retry, $tags) {
            Log::info('ScrapflyService: Making API call (cache miss)', [
                'url' => $url,
                'country' => $country,
                'asp' => $asp,
            ]);

            $response = Http::timeout(120)
                ->get($this->baseUrl, [
                    'key' => $this->apiKey,
                    'url' => $url,
                    'country' => $country,
                    'asp' => $asp,
                    'timeout' => $timeout,
                    'retry' => $retry ? 'true' : 'false',
                    'tags' => implode(',', $tags),
                ]);

            if (!$response->successful()) {
                Log::error('Scrapfly API error', [
                    'url' => $url,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \Exception("Scrapfly API error: {$response->status()} - {$response->body()}");
            }

            $data = $response->json();

            if ($data === null) {
                Log::error('Scrapfly API returned invalid JSON', [
                    'url' => $url,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \Exception("Scrapfly API returned invalid JSON response");
            }

            if (!isset($data['result']['content'])) {
                throw new \Exception("Scrapfly scrape failed: Missing content in response");
            }

            return [
                'html' => $data['result']['content'],
                'url' => $data['result']['url'] ?? $url,
                'status_code' => $data['result']['status_code'] ?? 200,
                'duration' => $data['result']['duration'] ?? null,
            ];
        });
    }

    private function getCacheKey(string $url, string $country, bool $asp): string
    {
        return 'scrapfly:' . md5($url . $country . ($asp ? '1' : '0'));
    }
}
