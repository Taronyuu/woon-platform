<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $crawl_job_id
 * @property string $url
 * @property string $url_hash
 * @property string|null $raw_html
 * @property int|null $status_code
 * @property string|null $content_hash
 * @property string $mime_type
 * @property \Illuminate\Support\Carbon|null $scraped_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CrawlJob $crawlJob
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawledPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawledPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawledPage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawledPage whereContentHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawledPage whereCrawlJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawledPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawledPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawledPage whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawledPage whereRawHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawledPage whereScrapedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawledPage whereStatusCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawledPage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawledPage whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CrawledPage whereUrlHash($value)
 * @mixin \Eloquent
 */
class CrawledPage extends Model
{
    protected $fillable = [
        'crawl_job_id',
        'url',
        'url_hash',
        'raw_html',
        'status_code',
        'content_hash',
        'mime_type',
        'scraped_at',
    ];

    protected $casts = [
        'scraped_at' => 'datetime',
    ];

    public function crawlJob(): BelongsTo
    {
        return $this->belongsTo(CrawlJob::class);
    }
}
