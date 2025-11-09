<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrawledPage extends Model
{
    protected $fillable = [
        'crawl_job_id',
        'url',
        'url_hash',
        'content',
        'raw_html',
        'links',
        'metadata',
        'status_code',
        'content_hash',
        'mime_type',
        'scraped_at',
    ];

    protected $casts = [
        'links' => 'array',
        'metadata' => 'array',
        'scraped_at' => 'datetime',
    ];

    public function crawlJob(): BelongsTo
    {
        return $this->belongsTo(CrawlJob::class);
    }
}
