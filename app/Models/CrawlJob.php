<?php

namespace App\Models;

use App\Crawlers\WebsiteCrawler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrawlJob extends Model
{
    protected $fillable = [
        'website_id',
        'status',
        'pages_crawled',
        'pages_failed',
        'links_found',
        'properties_extracted',
        'total_requests',
        'avg_response_time_ms',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function getCrawler(): WebsiteCrawler
    {
        return new WebsiteCrawler($this->website_id);
    }

    public function getWebsiteConfig(): array
    {
        return config("websites.{$this->website_id}", []);
    }

    public function crawledPages(): HasMany
    {
        return $this->hasMany(CrawledPage::class);
    }
}
