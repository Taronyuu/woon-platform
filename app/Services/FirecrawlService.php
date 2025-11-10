<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirecrawlService
{
    private string $baseUrl;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->baseUrl = config('services.firecrawl.base_url');
        $this->username = config('services.firecrawl.username');
        $this->password = config('services.firecrawl.password');
    }

    public function scrape(
        string $url,
        array $formats = ['markdown', 'links'],
        bool $onlyMainContent = true,
        bool $useFlaresolverr = true,
        int $timeout = 60000
    ): array {
        $response = Http::withBasicAuth($this->username, $this->password)
            ->timeout(120)
            ->post("{$this->baseUrl}/scrape", [
                'url' => $url,
                'formats' => $formats,
                'onlyMainContent' => $onlyMainContent,
                'useFlaresolverr' => $useFlaresolverr,
                'timeout' => $timeout,
            ]);

        if (!$response->successful()) {
            Log::error('Firecrawl API error', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \Exception("Firecrawl API error: {$response->status()}");
        }

        $data = $response->json();

        if ($data === null) {
            Log::error('Firecrawl API returned invalid JSON', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \Exception("Firecrawl API returned invalid JSON response");
        }

        if (!$data['success']) {
            throw new \Exception("Firecrawl scrape failed: " . ($data['error'] ?? 'Unknown error'));
        }

        return $data['data'];
    }
}
