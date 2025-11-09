<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Website extends Model
{
    protected $fillable = [
        'name',
        'base_url',
        'crawler_class',
        'max_depth',
        'delay_ms',
        'use_flaresolverr',
        'start_urls',
        'is_active',
    ];

    protected $casts = [
        'start_urls' => 'array',
        'use_flaresolverr' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function propertyUnits(): BelongsToMany
    {
        return $this->belongsToMany(PropertyUnit::class)
            ->withPivot('external_id', 'source_url', 'first_seen_at', 'last_seen_at')
            ->withTimestamps();
    }

    public function crawlJobs(): HasMany
    {
        return $this->hasMany(CrawlJob::class);
    }
}
