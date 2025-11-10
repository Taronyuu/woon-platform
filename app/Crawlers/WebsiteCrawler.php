<?php

namespace App\Crawlers;

use Illuminate\Support\Collection;

class WebsiteCrawler
{
    protected array $config = [];
    protected ?string $websiteId = null;

    public function __construct(?string $websiteId = null)
    {
        if ($websiteId !== null) {
            $this->websiteId = $websiteId;
            $this->config = config("websites.{$websiteId}");

            if (!$this->config) {
                throw new \InvalidArgumentException("Website configuration not found: {$websiteId}");
            }
        }
    }

    public function getWebsiteId(): string
    {
        return $this->websiteId;
    }

    public function getName(): string
    {
        return $this->config['name'];
    }

    public function getBaseUrl(): string
    {
        return $this->config['base_url'];
    }

    public function getStartUrls(): array
    {
        return $this->config['start_urls'] ?? [];
    }

    public function shouldCrawl(string $url): bool
    {
        $callback = $this->config['should_crawl'];
        return $callback($url);
    }

    public function isLeafPage(string $url): bool
    {
        if (!isset($this->config['is_leaf_page'])) {
            return false;
        }

        $callback = $this->config['is_leaf_page'];
        return $callback($url);
    }

    public function extractLinks(string $content, string $pageUrl): Collection
    {
        $callback = $this->config['extract_links'];
        $links = $callback($content, $pageUrl);
        return collect($links);
    }

    public function parseData(string $content, string $url): array
    {
        $parseDataConfig = $this->config['parse_data'];

        if (is_callable($parseDataConfig)) {
            return $parseDataConfig($content, $url);
        }

        if (is_string($parseDataConfig) && str_contains($parseDataConfig, '@')) {
            [$class, $method] = explode('@', $parseDataConfig);
            $instance = app($class);
            return $instance->$method($content, $url);
        }

        return [];
    }

    public function getMaxDepth(): int
    {
        return $this->config['max_depth'] ?? 3;
    }

    public function getDelayMs(): int
    {
        return $this->config['delay_ms'] ?? 1000;
    }

    public function shouldUseFlaresolverr(): bool
    {
        return $this->config['use_flaresolverr'] ?? false;
    }

    public function isActive(): bool
    {
        return $this->config['is_active'] ?? true;
    }
}
