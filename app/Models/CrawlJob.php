<?php

namespace App\Models;

use App\Crawlers\WebsiteCrawler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $website_id
 * @property string $status
 * @property int $pages_crawled
 * @property int $pages_failed
 * @property int $links_found
 * @property int $properties_extracted
 * @property int $total_requests
 * @property int|null $avg_response_time_ms
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CrawledPage> $crawledPages
 * @property-read int|null $crawled_pages_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob whereAvgResponseTimeMs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob whereLinksFound($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob wherePagesCrawled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob wherePagesFailed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob wherePropertiesExtracted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob whereTotalRequests($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawlJob whereWebsiteId($value)
 * @mixin \Eloquent
 */
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
