<?php

use Illuminate\Support\Str;

return [
    'funda' => [
        'id' => 'funda',
        'name' => 'Funda',
        'base_url' => 'https://www.funda.nl',
        'max_depth' => 3,
        'delay_ms' => 1000,
        'use_flaresolverr' => true,
        'is_active' => true,

        'status_mapping' => [
            'Beschikbaar' => 'available',
            'Verkocht' => 'unavailable',
            'Verhuurd' => 'unavailable',
            'Onder bod' => 'reserved',
            'Onder optie' => 'reserved',
        ],

        'start_urls' => array_merge(
            array_map(fn($page) => "https://www.funda.nl/zoeken/koop/?page={$page}", range(1, 700)),
            array_map(fn($page) => "https://www.funda.nl/zoeken/huur/?page={$page}", range(1, 300)),
        ),

        'should_crawl' => function (string $url): bool {
            if (!Str::startsWith($url, 'https://www.funda.nl')) {
                return false;
            }

            \Log::info('should_crawl check', ['url' => $url]);

            if (preg_match('#/detail/(koop|huur)/#', $url)) {
                if (preg_match('#/detail/[^/]+/[^/]+/[^/]+/\d+/$#', $url)) {
                    \Log::info('should_crawl: Accepting detail page', ['url' => $url]);
                    return true;
                }
                \Log::info('should_crawl: Rejecting detail sub-page', ['url' => $url]);
                return false;
            }

            \Log::info('should_crawl: Rejecting (not a detail page)', ['url' => $url]);
            return false;
        },

        'is_leaf_page' => function (string $url): bool {
            if (preg_match('#/detail/[^/]+/[^/]+/[^/]+/\d+#', $url)) {
                return true;
            }

            return false;
        },

        'extract_links' => function (string $content, string $pageUrl): array {
            return app(\App\Crawlers\FundaCrawler::class)->extractLinks($content, $pageUrl)->toArray();
        },

        'parse_data' => function (string $content, string $url) {
            return app(\App\Crawlers\FundaCrawler::class)->parseData($content, $url);
        },
    ],
];
