<?php

namespace App\Crawlers;

use App\Models\CrawlJob;
use Illuminate\Support\Collection;

abstract class WebsiteCrawler
{
    public function __construct(protected CrawlJob $crawlJob)
    {
    }

    abstract public function getStartUrls(): array;

    abstract public function shouldCrawl(string $url): bool;

    abstract public function extractLinks(string $content, string $pageUrl): Collection;

    abstract public function parseData(string $content, string $url): array;

    public function getMaxDepth(): int
    {
        return $this->crawlJob->website->max_depth;
    }

    public function getDelayMs(): int
    {
        return $this->crawlJob->website->delay_ms;
    }

    public function shouldUseFlaresolverr(): bool
    {
        return $this->crawlJob->website->use_flaresolverr;
    }
}
