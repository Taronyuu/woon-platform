# 🕷️ Web Crawler System - Architecture Documentation

## Overview
Laravel-native web crawler using Firecrawl's `/scrape` endpoint with **database queues only** (no Redis required). Designed for simplicity and extensibility.

---

## ✅ Key Design Decisions

1. **Database Queues** - No Redis dependency, use Laravel's database queue driver
2. **PropertyUnit Model** - Real estate-focused naming
3. **URL Deduplication** - Database-based visited tracking per crawl job
4. **Comprehensive Schema** - 50+ real estate attributes based on existing platform patterns
5. **Property Features ENUM** - Standardized outdoor spaces, parking, and storage

---

## 📊 Complete Database Schema

### Relationships
- **websites** ↔ **property_units**: Many-to-Many (a property can be listed on multiple websites)
- **websites** → **crawl_jobs**: One-to-Many
- **crawl_jobs** → **crawled_pages**: One-to-Many

### websites
```php
Schema::create('websites', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('base_url');
    $table->string('crawler_class');
    $table->tinyInteger('max_depth')->default(3);
    $table->integer('delay_ms')->default(1000);
    $table->boolean('use_flaresolverr')->default(true);
    $table->json('start_urls');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

---

### crawl_jobs
```php
Schema::create('crawl_jobs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('website_id')->constrained()->cascadeOnDelete();
    $table->enum('status', ['pending', 'running', 'completed', 'failed', 'cancelled'])->default('pending');
    $table->integer('pages_crawled')->default(0);
    $table->integer('pages_failed')->default(0);
    $table->integer('links_found')->default(0);
    $table->integer('properties_extracted')->default(0);
    $table->integer('total_requests')->default(0);
    $table->integer('avg_response_time_ms')->nullable();
    $table->timestamp('started_at')->nullable();
    $table->timestamp('completed_at')->nullable();
    $table->timestamps();

    $table->index(['website_id', 'status']);
    $table->index('created_at');
});
```

---

### property_unit_website (Pivot Table)
```php
Schema::create('property_unit_website', function (Blueprint $table) {
    $table->id();
    $table->foreignId('property_unit_id')->constrained()->cascadeOnDelete();
    $table->foreignId('website_id')->constrained()->cascadeOnDelete();
    $table->string('external_id')->nullable();
    $table->text('source_url');
    $table->timestamp('first_seen_at');
    $table->timestamp('last_seen_at');
    $table->timestamps();

    $table->unique(['property_unit_id', 'website_id']);
    $table->index(['website_id', 'external_id']);
});
```

---

### crawled_pages
```php
Schema::create('crawled_pages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('crawl_job_id')->constrained()->cascadeOnDelete();
    $table->text('url');
    $table->string('url_hash', 64)->index();
    $table->longText('content')->nullable();
    $table->longText('raw_html')->nullable();
    $table->json('links')->nullable();
    $table->json('metadata')->nullable();
    $table->integer('status_code')->nullable();
    $table->string('content_hash', 64)->nullable();
    $table->string('mime_type')->default('text/html');
    $table->timestamp('scraped_at')->nullable();
    $table->timestamps();

    $table->unique(['crawl_job_id', 'url_hash']);
    $table->index(['crawl_job_id', 'status_code']);
    $table->index('content_hash');
});
```

**Metadata JSON structure:**
```json
{
  "title": "Huis te koop: Hoofdstraat 123",
  "description": "Prachtige eengezinswoning...",
  "language": "nl",
  "sourceURL": "https://www.funda.nl/koop/...",
  "statusCode": 200,
  "scrapeDuration": 4500
}
```

---

### property_units (Full Schema)
```php
Schema::create('property_units', function (Blueprint $table) {
    $table->id();

    // Core Identity (no direct website relationship - use pivot table)

    // Basic Information
    $table->string('name')->nullable();
    $table->string('title');
    $table->longText('description')->nullable();

    $table->enum('property_type', [
        'house', 'apartment', 'villa', 'townhouse',
        'farm', 'commercial', 'land', 'parking', 'other'
    ]);

    $table->enum('transaction_type', ['sale', 'rent', 'auction']);

    $table->enum('status', [
        'available', 'sold', 'rented', 'pending', 'withdrawn'
    ])->default('available');

    $table->enum('living_type', [
        'woonhuis', 'appartement', 'studio', 'penthouse',
        'bovenwoning', 'benedenwoning', 'maisonnette', 'villa',
        'herenhuis', 'drive_in_woning', 'flat', 'galerij_flat'
    ])->nullable();

    // Financial Details
    $table->decimal('buyprice', 12, 2)->nullable();
    $table->string('buyprice_label')->nullable();
    $table->decimal('buyprice_range_from', 12, 2)->nullable();
    $table->decimal('buyprice_range_to', 12, 2)->nullable();
    $table->decimal('land_costs', 12, 2)->nullable();
    $table->decimal('contract_price', 12, 2)->nullable();
    $table->decimal('ground_lease', 12, 2)->nullable();
    $table->decimal('canon', 12, 2)->nullable();
    $table->text('canon_comment')->nullable();

    $table->decimal('rentprice_month', 10, 2)->nullable();
    $table->decimal('service_fee_month', 10, 2)->nullable();
    $table->decimal('total_rent_month', 10, 2)->nullable();

    $table->string('price_currency', 3)->default('EUR');
    $table->decimal('property_tax', 10, 2)->nullable();
    $table->decimal('hoa_fees', 10, 2)->nullable();

    // Location Details
    $table->string('address_street')->nullable();
    $table->string('address_number')->nullable();
    $table->string('address_addition')->nullable();
    $table->string('address_city')->nullable();
    $table->string('address_postal_code')->nullable();
    $table->string('address_province')->nullable();
    $table->string('address_country', 2)->default('NL');
    $table->decimal('latitude', 10, 8)->nullable();
    $table->decimal('longitude', 11, 8)->nullable();
    $table->string('neighborhood')->nullable();
    $table->string('municipality')->nullable();

    // Physical Attributes
    $table->integer('surface')->nullable();
    $table->integer('lotsize')->nullable();
    $table->integer('volume')->nullable();
    $table->integer('outdoor_surface')->nullable();
    $table->string('planarea')->nullable();

    $table->tinyInteger('bedrooms')->nullable();
    $table->tinyInteger('sleepingrooms')->nullable();
    $table->tinyInteger('bathrooms')->nullable();
    $table->tinyInteger('floors')->nullable();

    $table->smallInteger('construction_year')->nullable();
    $table->smallInteger('renovation_year')->nullable();

    // Energy & Sustainability
    $table->enum('energy_label', [
        'A++++', 'A+++', 'A++', 'A+', 'A',
        'B', 'C', 'D', 'E', 'F', 'G'
    ])->nullable();
    $table->decimal('energy_index', 5, 2)->nullable();

    // Layout & Orientation
    $table->string('floor')->nullable();
    $table->string('orientation')->nullable();

    // Features (Boolean Flags)
    $table->boolean('berth')->default(false);
    $table->boolean('garage')->default(false);
    $table->boolean('has_parking')->default(false);
    $table->boolean('has_elevator')->default(false);
    $table->boolean('has_ac')->default(false);
    $table->boolean('has_alarm')->default(false);

    // Structured Features (JSON)
    $table->json('parking_lots_data')->nullable();
    $table->json('storages_data')->nullable();
    $table->json('outdoor_spaces_data')->nullable();

    // Media & Files
    $table->json('images')->nullable();
    $table->json('videos')->nullable();
    $table->json('floor_plans')->nullable();
    $table->string('virtual_tour_url')->nullable();
    $table->string('brochure_url')->nullable();

    // Agent Information
    $table->string('agent_name')->nullable();
    $table->string('agent_company')->nullable();
    $table->string('agent_phone')->nullable();
    $table->string('agent_email')->nullable();
    $table->string('agent_logo_url')->nullable();

    // Additional Data
    $table->json('features')->nullable();
    $table->json('amenities')->nullable();
    $table->json('data')->nullable();

    // Dates
    $table->date('listing_date')->nullable();
    $table->string('acceptance_date')->nullable();
    $table->dateTime('viewing_date')->nullable();

    $table->timestamp('first_seen_at');
    $table->timestamp('last_seen_at');
    $table->timestamp('last_changed_at')->nullable();
    $table->timestamps();

    // Indexes
    $table->index(['transaction_type', 'status']);
    $table->index(['address_city']);
    $table->index(['address_postal_code']);
    $table->index(['buyprice']);
    $table->index(['rentprice_month']);
    $table->index(['surface']);
    $table->index(['listing_date']);
    $table->index(['first_seen_at']);
    $table->index(['last_seen_at']);
});
```

---

## 🎯 Property Features Enums

### OutdoorSpaceType
```php
namespace App\Enums;

enum OutdoorSpaceType: string
{
    case GARDEN = 'tuin';
    case BALCONY = 'balkon';
    case ROOF_TERRACE = 'dakterras';
    case TERRACE = 'terras';
    case PATIO = 'patio';
    case COURTYARD = 'binnenplaats';
    case VERANDA = 'veranda';
    case FRONT_GARDEN = 'voortuin';
    case BACK_GARDEN = 'achtertuin';

    public function label(): string
    {
        return match($this) {
            self::GARDEN => 'Tuin',
            self::BALCONY => 'Balkon',
            self::ROOF_TERRACE => 'Dakterras',
            self::TERRACE => 'Terras',
            self::PATIO => 'Patio',
            self::COURTYARD => 'Binnenplaats',
            self::VERANDA => 'Veranda',
            self::FRONT_GARDEN => 'Voortuin',
            self::BACK_GARDEN => 'Achtertuin',
        };
    }
}
```

### ParkingType
```php
namespace App\Enums;

enum ParkingType: string
{
    case GARAGE = 'garage';
    case CARPORT = 'carport';
    case DRIVEWAY = 'oprit';
    case PARKING_SPOT = 'parkeerplaats';
    case UNDERGROUND = 'ondergronds';
    case COVERED = 'overdekt';
    case STREET = 'straat';

    public function label(): string
    {
        return match($this) {
            self::GARAGE => 'Garage',
            self::CARPORT => 'Carport',
            self::DRIVEWAY => 'Oprit',
            self::PARKING_SPOT => 'Parkeerplaats',
            self::UNDERGROUND => 'Ondergrondse parking',
            self::COVERED => 'Overdekte parking',
            self::STREET => 'Straatparkeren',
        };
    }
}
```

### StorageType
```php
namespace App\Enums;

enum StorageType: string
{
    case BASEMENT = 'kelder';
    case ATTIC = 'zolder';
    case SHED = 'schuur';
    case BOX_ROOM = 'berging';
    case EXTERNAL = 'externe_berging';

    public function label(): string
    {
        return match($this) {
            self::BASEMENT => 'Kelder',
            self::ATTIC => 'Zolder',
            self::SHED => 'Schuur',
            self::BOX_ROOM => 'Berging',
            self::EXTERNAL => 'Externe berging',
        };
    }
}
```

---

## 🔄 Crawl Flow (Database Queues)

### Phase 1: Initiation
```
User clicks "Start Crawl" in Filament
    ↓
InitiateCrawlJob dispatched
    ↓
- Creates CrawlJob record (status: running)
- Gets start URLs from WebsiteCrawler->getStartUrls()
- For each start URL:
    - Creates hash: md5($url)
    - Creates CrawledPage record (status: pending, url_hash)
    - Dispatches ScrapePageJob
```

### Phase 2: Scraping
```
ScrapePageJob (queue: "crawl-{website_id}")
    ↓
- Checks if URL already scraped:
  CrawledPage::where('crawl_job_id', $id)
    ->where('url_hash', $hash)
    ->exists()
- If already exists: skip
- Calls FirecrawlService->scrape($url)
- Updates CrawledPage record with content, metadata
- Dispatches ExtractLinksJob
- Dispatches ParseDataJob
- Updates CrawlJob stats
```

### Phase 3: Link Discovery
```
ExtractLinksJob
    ↓
- Gets crawler: $crawler = new {$website->crawler_class}($crawlJob)
- Extracts links: $crawler->extractLinks($content, $pageUrl)
- Filters: $crawler->shouldCrawl($url)
- For each valid link:
    - Creates hash: md5($url)
    - Checks if exists:
      CrawledPage::where('crawl_job_id', $id)
        ->where('url_hash', $hash)
        ->exists()
    - If NOT exists:
        - Creates new CrawledPage record (status: pending)
        - Dispatches new ScrapePageJob
```

### Phase 4: Data Parsing
```
ParseDataJob
    ↓
- Gets crawler instance
- Parses data: $data = $crawler->parseData($content, $url)
- Creates/updates PropertyUnit using upsert:
  PropertyUnit::updateOrCreate(
    ['website_id' => $id, 'external_id' => $externalId],
    [...$data]
  )
- Updates CrawlJob stats
```

---

## 🏗️ Core Components

### 0. Model Relationships

#### Website Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Website extends Model
{
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
```

#### PropertyUnit Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PropertyUnit extends Model
{
    public function websites(): BelongsToMany
    {
        return $this->belongsToMany(Website::class)
            ->withPivot('external_id', 'source_url', 'first_seen_at', 'last_seen_at')
            ->withTimestamps();
    }
}
```

---

### 1. Abstract Crawler Contract

```php
namespace App\Crawlers;

use App\Models\CrawlJob;
use Illuminate\Support\Collection;

abstract class WebsiteCrawler
{
    public function __construct(protected CrawlJob $crawlJob)
    {
    }

    abstract public function getStartUrls(): array;

    abstract public function shouldCrawl(string $url): bool;

    abstract public function extractLinks(string $content, string $pageUrl): Collection;

    abstract public function parseData(string $content, string $url): array;

    public function getMaxDepth(): int
    {
        return $this->crawlJob->website->max_depth;
    }

    public function getDelayMs(): int
    {
        return $this->crawlJob->website->delay_ms;
    }

    public function shouldUseFlaresolverr(): bool
    {
        return $this->crawlJob->website->use_flaresolverr;
    }
}
```

---

### 2. Concrete Crawler Example: FundaCrawler

```php
namespace App\Crawlers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FundaCrawler extends WebsiteCrawler
{
    public function getStartUrls(): array
    {
        return [
            'https://www.funda.nl/koop/heel-nederland/',
            'https://www.funda.nl/huur/heel-nederland/',
        ];
    }

    public function shouldCrawl(string $url): bool
    {
        if (!Str::startsWith($url, 'https://www.funda.nl')) {
            return false;
        }

        if (Str::contains($url, ['/hypotheek', '/nieuwbouw-service', '/mijn-funda'])) {
            return false;
        }

        return Str::contains($url, ['/koop/', '/huur/', '/detail/']);
    }

    public function extractLinks(string $content, string $pageUrl): Collection
    {
        preg_match_all('/<a[^>]+href="([^"]+)"/', $content, $matches);

        return collect($matches[1])
            ->map(fn($url) => $this->normalizeUrl($url, $pageUrl))
            ->filter(fn($url) => $this->shouldCrawl($url))
            ->unique()
            ->values();
    }

    public function parseData(string $content, string $url): array
    {
        if (!Str::contains($url, '/detail/')) {
            return [];
        }

        return [
            'external_id' => $this->extractFundaId($url),
            'title' => $this->extractTitle($content),
            'description' => $this->extractDescription($content),
            'property_type' => $this->extractPropertyType($content),
            'transaction_type' => Str::contains($url, '/koop/') ? 'sale' : 'rent',
            'buyprice' => $this->extractPrice($content),
            'address_street' => $this->extractStreet($content),
            'address_city' => $this->extractCity($content),
            'address_postal_code' => $this->extractPostalCode($content),
            'surface' => $this->extractLivingArea($content),
            'lotsize' => $this->extractPlotArea($content),
            'bedrooms' => $this->extractRooms($content),
            'sleepingrooms' => $this->extractBedrooms($content),
            'bathrooms' => $this->extractBathrooms($content),
            'construction_year' => $this->extractYear($content),
            'energy_label' => $this->extractEnergyLabel($content),
            'images' => $this->extractImages($content),
            'features' => $this->extractFeatures($content),
            'agent_name' => $this->extractAgentName($content),
            'first_seen_at' => now(),
            'last_seen_at' => now(),
        ];
    }

    private function normalizeUrl(string $url, string $baseUrl): string
    {
    }

    private function extractFundaId(string $url): ?string
    {
        if (preg_match('/\/(\d+)-/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function extractPrice(string $content): ?float
    {
        if (preg_match('/€\s*([\d.]+)/', $content, $matches)) {
            return (float) str_replace('.', '', $matches[1]);
        }
        return null;
    }
}
```

---

### 3. FirecrawlService

```php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirecrawlService
{
    private string $baseUrl;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->baseUrl = config('services.firecrawl.base_url');
        $this->username = config('services.firecrawl.username');
        $this->password = config('services.firecrawl.password');
    }

    public function scrape(
        string $url,
        array $formats = ['markdown', 'links'],
        bool $onlyMainContent = true,
        bool $useFlaresolverr = true,
        int $timeout = 60000
    ): array {
        $response = Http::withBasicAuth($this->username, $this->password)
            ->timeout(120)
            ->post("{$this->baseUrl}/scrape", [
                'url' => $url,
                'formats' => $formats,
                'onlyMainContent' => $onlyMainContent,
                'useFlaresolverr' => $useFlaresolverr,
                'timeout' => $timeout,
            ]);

        if (!$response->successful()) {
            Log::error('Firecrawl API error', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \Exception("Firecrawl API error: {$response->status()}");
        }

        $data = $response->json();

        if (!$data['success']) {
            throw new \Exception("Firecrawl scrape failed: " . ($data['error'] ?? 'Unknown error'));
        }

        return $data['data'];
    }
}
```

---

### 4. Job Classes

#### InitiateCrawlJob
```php
namespace App\Jobs;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InitiateCrawlJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $crawlJobId)
    {
    }

    public function handle(): void
    {
        $crawlJob = CrawlJob::findOrFail($this->crawlJobId);
        $crawlerClass = $crawlJob->website->crawler_class;
        $crawler = new $crawlerClass($crawlJob);

        $crawlJob->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        foreach ($crawler->getStartUrls() as $url) {
            $urlHash = md5($url);

            $crawledPage = CrawledPage::create([
                'crawl_job_id' => $crawlJob->id,
                'url' => $url,
                'url_hash' => $urlHash,
            ]);

            ScrapePageJob::dispatch($crawlJob->id, $crawledPage->id, $url);
        }
    }
}
```

#### ScrapePageJob
```php
namespace App\Jobs;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use App\Services\FirecrawlService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScrapePageJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $maxExceptions = 2;
    public $timeout = 300;

    public function __construct(
        public int $crawlJobId,
        public int $crawledPageId,
        public string $url
    ) {
        $crawlJob = CrawlJob::find($crawlJobId);
        $this->onQueue('crawl-' . $crawlJob->website_id);
    }

    public function handle(FirecrawlService $firecrawl): void
    {
        $crawledPage = CrawledPage::findOrFail($this->crawledPageId);
        $crawlJob = CrawlJob::findOrFail($this->crawlJobId);

        if ($crawledPage->scraped_at) {
            return;
        }

        $crawlerClass = $crawlJob->website->crawler_class;
        $crawler = new $crawlerClass($crawlJob);

        try {
            $data = $firecrawl->scrape(
                $this->url,
                ['markdown', 'links'],
                true,
                $crawler->shouldUseFlaresolverr()
            );

            $crawledPage->update([
                'content' => $data['markdown'] ?? null,
                'raw_html' => $data['html'] ?? null,
                'links' => $data['links'] ?? [],
                'metadata' => $data['metadata'] ?? [],
                'status_code' => $data['metadata']['statusCode'] ?? 200,
                'scraped_at' => now(),
            ]);

            ExtractLinksJob::dispatch($crawlJob->id, $crawledPage->id);
            ParseDataJob::dispatch($crawlJob->id, $crawledPage->id);

            $crawlJob->increment('pages_crawled');
            $crawlJob->increment('total_requests');

        } catch (\Exception $e) {
            $crawlJob->increment('pages_failed');

            throw $e;
        }
    }
}
```

#### ExtractLinksJob
```php
namespace App\Jobs;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExtractLinksJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

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

        $links = $crawler->extractLinks(
            $crawledPage->content ?? '',
            $crawledPage->url
        );

        $newLinksCount = 0;

        foreach ($links as $link) {
            $urlHash = md5($link);

            $exists = CrawledPage::where('crawl_job_id', $crawlJob->id)
                ->where('url_hash', $urlHash)
                ->exists();

            if (!$exists) {
                $newPage = CrawledPage::create([
                    'crawl_job_id' => $crawlJob->id,
                    'url' => $link,
                    'url_hash' => $urlHash,
                ]);

                ScrapePageJob::dispatch($crawlJob->id, $newPage->id, $link);
                $newLinksCount++;
            }
        }

        $crawlJob->increment('links_found', $newLinksCount);
    }
}
```

#### ParseDataJob
```php
namespace App\Jobs;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use App\Models\PropertyUnit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParseDataJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

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
            $crawledPage->content ?? '',
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
```

---

## ⚙️ Configuration

### config/services.php
```php
return [
    'firecrawl' => [
        'base_url' => env('FIRECRAWL_BASE_URL', 'https://crawl.meerdevelopment.nl/firecrawl/v1'),
        'username' => env('FIRECRAWL_USERNAME', 'admin'),
        'password' => env('FIRECRAWL_PASSWORD'),
    ],
];
```

### .env
```env
FIRECRAWL_BASE_URL=https://crawl.meerdevelopment.nl/firecrawl/v1
FIRECRAWL_USERNAME=admin
FIRECRAWL_PASSWORD=mounted-fascism-outsell-equivocal-spokesman-scarf

QUEUE_CONNECTION=database
```

### config/queue.php
```php
'connections' => [
    'database' => [
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 300,
        'after_commit' => false,
    ],
],
```

---

## 📂 JSON Data Structure Examples

### outdoor_spaces_data
```json
[
    {"name": "Tuin", "type": "tuin", "m2": 50, "orientation": "zuid"},
    {"name": "Balkon", "type": "balkon", "m2": 12, "orientation": "west"},
    {"name": "Dakterras", "type": "dakterras", "m2": 25, "orientation": "zuid"}
]
```

### parking_lots_data
```json
[
    {"name": "Parkeerplaats 1", "type": "garage", "price": 25000},
    {"name": "Parkeerplaats 2", "type": "garage", "price": 25000}
]
```

### storages_data
```json
[
    {"name": "Berging 1", "type": "berging", "price": 5000, "m2": 6},
    {"name": "Kelder", "type": "kelder", "price": 0, "m2": 15}
]
```

### features (general property features)
```json
[
    "Luxe afwerking",
    "Open keuken",
    "Vloerverwarming",
    "Airconditioning",
    "Zonnepanelen",
    "Dakramen",
    "Hardhouten kozijnen",
    "Dubbele beglazing"
]
```

### amenities (nearby amenities)
```json
[
    "Scholen in de buurt",
    "Openbaar vervoer",
    "Winkels op loopafstand",
    "Park in de buurt",
    "Sportfaciliteiten"
]
```

### data (catch-all for extra attributes)
```json
{
    "funda_id": "12345678",
    "publication_status": "beschikbaar",
    "view_count": 2500,
    "favorites_count": 45,
    "cadastral_data": {
        "section": "A",
        "number": "1234"
    }
}
```

---

## 📁 Directory Structure

```
app/
├── Crawlers/
│   ├── WebsiteCrawler.php (abstract)
│   └── FundaCrawler.php
├── Enums/
│   ├── OutdoorSpaceType.php
│   ├── ParkingType.php
│   └── StorageType.php
├── Filament/
│   ├── Pages/
│   │   └── CrawlDashboard.php
│   └── Resources/
│       ├── CrawlJobResource.php
│       ├── PropertyUnitResource.php
│       └── WebsiteResource.php
├── Jobs/
│   ├── ExtractLinksJob.php
│   ├── InitiateCrawlJob.php
│   ├── ParseDataJob.php
│   └── ScrapePageJob.php
├── Models/
│   ├── CrawledPage.php
│   ├── CrawlJob.php
│   ├── PropertyUnit.php
│   └── Website.php
└── Services/
    └── FirecrawlService.php

database/
└── migrations/
    ├── xxxx_create_websites_table.php
    ├── xxxx_create_crawl_jobs_table.php
    ├── xxxx_create_crawled_pages_table.php
    └── xxxx_create_property_units_table.php

config/
├── queue.php
└── services.php
```

---

## 🚀 Implementation Order

1. **Enums** (OutdoorSpaceType, ParkingType, StorageType)
2. **Migrations** (websites, crawl_jobs, crawled_pages, property_units, property_unit_website)
3. **Models** (Website, CrawlJob, CrawledPage, PropertyUnit with relationships)
4. **Services** (FirecrawlService)
5. **Abstract WebsiteCrawler** + FundaCrawler
6. **Jobs** (InitiateCrawlJob, ScrapePageJob, ExtractLinksJob, ParseDataJob)
7. **Filament Resources** (WebsiteResource, CrawlJobResource, PropertyUnitResource)
8. **Filament Dashboard** (CrawlDashboard with stats and controls)

---

## 🧪 Testing Strategy

### Unit Tests
- WebsiteCrawler implementations (shouldCrawl, extractLinks, parseData)
- FirecrawlService API integration
- Enum label methods

### Feature Tests
- Complete crawl flow from initiation to property extraction
- URL deduplication logic
- Job retry behavior
- Data persistence and updates

### Manual Testing Checklist
1. Start a crawl from Filament
2. Monitor queue processing
3. Verify CrawledPage records created
4. Verify PropertyUnit records created
5. Test with Cloudflare-protected site (useFlaresolverr: true)
6. Test pagination crawling
7. Test duplicate URL handling

---

## 📊 Monitoring & Observability

### Key Metrics to Track
- Pages crawled per minute
- Success/failure rate
- Average scrape duration
- Queue depth
- PropertyUnit extraction rate

### Filament Dashboard Widgets
1. **Active Crawls** - Real-time status of running crawls
2. **Crawl Statistics** - Success rates, pages processed
3. **Recent Property Units** - Latest extracted properties
4. **Queue Status** - Pending jobs per website
5. **Error Log** - Failed scrapes with retry info

---

## 🔧 Troubleshooting

### Common Issues

**Queue not processing:**
```bash
php artisan queue:work
```

**Failed jobs:**
```bash
php artisan queue:failed
php artisan queue:retry {id}
```

**Clear failed jobs:**
```bash
php artisan queue:flush
```

**Check crawl status:**
```sql
SELECT status, COUNT(*)
FROM crawl_jobs
GROUP BY status;
```

**Find duplicate URLs:**
```sql
SELECT url_hash, COUNT(*)
FROM crawled_pages
WHERE crawl_job_id = 1
GROUP BY url_hash
HAVING COUNT(*) > 1;
```

---

## 🎯 Future Enhancements

### Phase 2 Features
- [ ] Schedule automatic re-crawls
- [ ] Email notifications on crawl completion
- [ ] CSV/JSON export of PropertyUnits
- [ ] Webhook support for new properties
- [ ] Multi-language support
- [ ] Image download and storage
- [ ] Price change tracking
- [ ] Property comparison tools

### Scalability
- [ ] Add Redis for visited URLs (when needed)
- [ ] Implement Horizon for advanced queue monitoring
- [ ] Add horizontal scaling support
- [ ] Implement rate limiting per IP
- [ ] Add proxy rotation support

---

## 📚 References

- [Firecrawl API Documentation](/Users/zander/Downloads/FIRECRAWL_API_DOCS_CUSTOM_FLARESOLVER.md)
- [Laravel Queues Documentation](https://laravel.com/docs/12.x/queues)
- [Filament Documentation](https://filamentphp.com/docs)
- [PHP Enums](https://www.php.net/manual/en/language.enumerations.php)

---

**Document Version:** 1.0
**Last Updated:** 2025-01-08
**Status:** Ready for Implementation ✅
