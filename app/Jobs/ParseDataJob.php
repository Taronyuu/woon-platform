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

        $crawlerClass = $crawlJob->website->crawler_class;
        $crawler = new $crawlerClass($crawlJob);

        $data = $crawler->parseData(
            $crawledPage->raw_html ?? $crawledPage->content ?? '',
            $crawledPage->url
        );

        if (empty($data)) {
            return;
        }

        $externalId = $data['external_id'] ?? null;
        unset($data['external_id']);

        $propertyUnit = PropertyUnit::firstOrCreate(
            [
                'address_postal_code' => $data['address_postal_code'] ?? null,
                'address_number' => $data['address_number'] ?? null,
                'address_addition' => $data['address_addition'] ?? null,
            ],
            $data
        );

        $propertyUnit->websites()->syncWithoutDetaching([
            $crawlJob->website_id => [
                'external_id' => $externalId,
                'source_url' => $crawledPage->url,
                'first_seen_at' => now(),
                'last_seen_at' => now(),
            ]
        ]);

        $crawlJob->increment('properties_extracted');
    }
}
