<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DiscoveredUrl extends Model
{
    protected $fillable = [
        'website_id',
        'url',
        'url_hash',
        'status',
        'discovered_at',
        'last_fetched_at',
        'fail_count',
        'last_error',
    ];

    protected $casts = [
        'discovered_at' => 'datetime',
        'last_fetched_at' => 'datetime',
    ];

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeFetched(Builder $query): Builder
    {
        return $query->where('status', 'fetched');
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', 'failed');
    }

    public function scopeForWebsite(Builder $query, string $websiteId): Builder
    {
        return $query->where('website_id', $websiteId);
    }

    public static function urlExists(string $websiteId, string $url): bool
    {
        return self::query()
            ->where('website_id', $websiteId)
            ->where('url_hash', md5($url))
            ->exists();
    }

    public static function createFromUrl(string $websiteId, string $url): ?self
    {
        $urlHash = md5($url);

        $existing = self::query()
            ->where('website_id', $websiteId)
            ->where('url_hash', $urlHash)
            ->first();

        if ($existing) {
            return null;
        }

        return self::query()->create([
            'website_id' => $websiteId,
            'url' => $url,
            'url_hash' => $urlHash,
            'discovered_at' => now(),
            'status' => 'pending',
        ]);
    }

    public function markAsFetched(): void
    {
        $this->update([
            'status' => 'fetched',
            'last_fetched_at' => now(),
        ]);
    }

    public function markAsFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'fail_count' => $this->fail_count + 1,
            'last_error' => $error,
            'last_fetched_at' => now(),
        ]);
    }
}
