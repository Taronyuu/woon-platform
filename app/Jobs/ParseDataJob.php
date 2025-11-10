<?php

namespace App\Jobs;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use App\Models\PropertyUnit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParseDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $crawlJobId,
        public int $crawledPageId
    ) {
    }

    public function handle(): void
    {
        $crawledPage = CrawledPage::findOrFail($this->crawledPageId);
        $crawlJob = CrawlJob::findOrFail($this->crawlJobId);

        $crawler = $crawlJob->getCrawler();

        if (!$crawler->isLeafPage($crawledPage->url)) {
            \Log::info("ParseDataJob: Skipping non-leaf page", [
                'url' => $crawledPage->url,
            ]);
            return;
        }

        $data = $crawler->parseData(
            $crawledPage->raw_html ?? $crawledPage->content ?? '',
            $crawledPage->url
        );

        if (empty($data)) {
            \Log::info("ParseDataJob: No data extracted from leaf page", [
                'url' => $crawledPage->url,
            ]);
            return;
        }

        $externalId = $data['external_id'] ?? null;
        unset($data['external_id']);

        $sourceUrl = $data['source_url'] ?? $crawledPage->url;
        unset($data['source_url']);

        $propertyUnit = PropertyUnit::firstOrCreate(
            [
                'address_postal_code' => $data['address_postal_code'] ?? null,
                'address_number' => $data['address_number'] ?? null,
                'address_addition' => $data['address_addition'] ?? null,
            ],
            $data
        );

        if (method_exists($propertyUnit, 'websites')) {
            $propertyUnit->websites()->syncWithoutDetaching([
                $crawlJob->website_id => [
                    'external_id' => $externalId,
                    'source_url' => $sourceUrl,
                    'first_seen_at' => now(),
                    'last_seen_at' => now(),
                ]
            ]);
        }

        $crawlJob->increment('properties_extracted');
    }
}
