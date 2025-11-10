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

        'start_urls' => [
            'https://www.funda.nl/zoeken/koop/',
            'https://www.funda.nl/zoeken/huur/',
        ],

        'should_crawl' => function (string $url): bool {
            if (!Str::startsWith($url, 'https://www.funda.nl')) {
                return false;
            }

            \Log::info('should_crawl check', ['url' => $url]);

            if (Str::contains($url, ['/koop/', '/huur/', '/zoeken/koop', '/zoeken/huur'])) {
                if (preg_match('#/detail/[^/]+/[^/]+/[^/]+/\d+/.+#', $url)) {
                    \Log::info('should_crawl: Rejecting detail sub-page', ['url' => $url]);
                    return false;
                }
                \Log::info('should_crawl: Accepting', ['url' => $url]);
                return true;
            }

            \Log::info('should_crawl: Rejecting (no match)', ['url' => $url]);
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
