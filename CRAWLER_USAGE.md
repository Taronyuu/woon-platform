# Web Crawler - Usage Guide

## Quick Start

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Seed Test Website

```bash
php artisan db:seed --class=WebsiteSeeder
```

This creates a Funda website configuration in your database.

### 3. Configure Environment

For local testing with synchronous execution (blocking mode):

```bash
QUEUE_CONNECTION=sync
```

For production with background queue processing:

```bash
QUEUE_CONNECTION=database
```

Add Firecrawl credentials to `.env`:

```env
FIRECRAWL_BASE_URL=https://crawl.meerdevelopment.nl/firecrawl/v1
FIRECRAWL_USERNAME=admin
FIRECRAWL_PASSWORD=mounted-fascism-outsell-equivocal-spokesman-scarf
```

## Artisan Command Usage

### List Available Websites

```bash
php artisan crawl:website --list
```

Output:
```
+----+-------+------------------------+---------------+--------+
| ID | Name  | Base URL               | Crawler       | Active |
+----+-------+------------------------+---------------+--------+
| 1  | Funda | https://www.funda.nl   | FundaCrawler  | ✓      |
+----+-------+------------------------+---------------+--------+
```

### Crawl a Specific Website by ID

```bash
php artisan crawl:website 1
```

### Crawl a Specific Website by Name

```bash
php artisan crawl:website Funda
```

### Crawl All Active Websites

```bash
php artisan crawl:website --all
```

## Testing Locally with QUEUE_CONNECTION=sync

When using `QUEUE_CONNECTION=sync`, all jobs will run synchronously (blocking). This is perfect for local testing:

```bash
QUEUE_CONNECTION=sync php artisan crawl:website 1
```

You'll see output like:

```
Starting crawl for: Funda
Base URL: https://www.funda.nl
Crawler: App\Crawlers\FundaCrawler

Created CrawlJob #1

Running in SYNC mode - this will block until complete

Crawl initiated. Monitoring progress...

Crawl completed!

+----------------------+----------------------+
| Metric               | Value                |
+----------------------+----------------------+
| Status               | completed            |
| Pages Crawled        | 15                   |
| Pages Failed         | 0                    |
| Links Found          | 45                   |
| Properties Extracted | 12                   |
| Total Requests       | 15                   |
| Started At           | 2025-01-08 14:30:00  |
| Completed At         | 2025-01-08 14:32:15  |
+----------------------+----------------------+
```

## Production Usage with Queue Workers

In production, use database queues with background workers:

### 1. Set Environment

```bash
QUEUE_CONNECTION=database
```

### 2. Start Queue Worker

```bash
php artisan queue:work
```

Or use the dev command (includes queue worker):

```bash
composer dev
```

### 3. Start Crawl

```bash
php artisan crawl:website 1
```

Output:
```
Starting crawl for: Funda
Base URL: https://www.funda.nl
Crawler: App\Crawlers\FundaCrawler

Created CrawlJob #1

Jobs dispatched to queue: database

Jobs are being processed by queue worker.
Monitor progress: php artisan queue:monitor crawl-1
View job: CrawlJob::find(1)
```

### 4. Monitor Progress

In Tinker:

```bash
php artisan tinker
```

```php
$job = CrawlJob::find(1);
$job->status;               // 'running', 'completed', 'failed'
$job->pages_crawled;        // Number of pages scraped
$job->properties_extracted; // Number of properties found
```

## Creating Custom Websites

### Via Tinker

```bash
php artisan tinker
```

```php
use App\Models\Website;

Website::create([
    'name' => 'Example Site',
    'base_url' => 'https://example.com',
    'crawler_class' => 'App\\Crawlers\\ExampleCrawler',
    'max_depth' => 3,
    'delay_ms' => 1000,
    'use_flaresolverr' => true,
    'start_urls' => [
        'https://example.com/properties',
    ],
    'is_active' => true,
]);
```

### Via Database Seeder

Create a new seeder or add to existing `WebsiteSeeder.php`.

## Viewing Results

### Check Crawl Jobs

```php
use App\Models\CrawlJob;

$jobs = CrawlJob::with('website')->latest()->get();
```

### Check Crawled Pages

```php
use App\Models\CrawledPage;

$pages = CrawledPage::where('crawl_job_id', 1)->get();
```

### Check Extracted Properties

```php
use App\Models\PropertyUnit;

$properties = PropertyUnit::with('websites')->latest()->get();
```

### Check Property-Website Relationships

```php
$property = PropertyUnit::first();
$property->websites; // All websites this property appears on
```

## Troubleshooting

### No websites found

Run the seeder:
```bash
php artisan db:seed --class=WebsiteSeeder
```

### Jobs not processing

If using database queues, ensure a worker is running:
```bash
php artisan queue:work
```

Or use sync mode for testing:
```bash
QUEUE_CONNECTION=sync php artisan crawl:website 1
```

### Firecrawl API errors

Check your `.env` credentials:
- `FIRECRAWL_BASE_URL`
- `FIRECRAWL_USERNAME`
- `FIRECRAWL_PASSWORD`

Test the connection:
```php
$service = app(\App\Services\FirecrawlService::class);
$result = $service->scrape('https://example.com');
```

### View Failed Jobs

```bash
php artisan queue:failed
```

Retry failed jobs:
```bash
php artisan queue:retry all
```

## Best Practices

1. **Start Small**: Test with `max_depth => 1` first
2. **Use Sync Mode Locally**: `QUEUE_CONNECTION=sync` for testing
3. **Monitor Jobs**: Check `crawl_jobs` table regularly
4. **Rate Limiting**: Adjust `delay_ms` to avoid overwhelming target sites
5. **Flaresolverr**: Enable for Cloudflare-protected sites
6. **URL Filtering**: Implement strict `shouldCrawl()` logic in your crawler
