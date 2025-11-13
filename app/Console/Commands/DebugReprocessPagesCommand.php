<?php

namespace App\Console\Commands;

use App\Jobs\ParseDataJob;
use App\Models\CrawledPage;
use Illuminate\Console\Command;

class DebugReprocessPagesCommand extends Command
{
    protected $signature = 'debug:reprocess-pages
                            {crawl_job_id? : The crawl job ID to reprocess}
                            {--url= : Process only pages matching this URL pattern}
                            {--limit=100 : Maximum number of pages to process}';

    protected $description = 'Reprocess all scraped pages and execute ParseDataJob for debugging';

    public function handle(): int
    {
        $crawlJobId = $this->argument('crawl_job_id');
        $urlPattern = $this->option('url');
        $limit = (int) $this->option('limit');

        $query = CrawledPage::query()
            ->whereNotNull('scraped_at')
            ->whereNotNull('raw_html');

        if ($crawlJobId) {
            $query->where('crawl_job_id', $crawlJobId);
        }

        if ($urlPattern) {
            $query->where('url', 'LIKE', "%{$urlPattern}%");
        }

        $pages = $query->limit($limit)->get();

        if ($pages->isEmpty()) {
            $this->warn('No scraped pages found matching criteria.');
            return self::FAILURE;
        }

        $this->info("Found {$pages->count()} scraped pages to reprocess");
        $this->line('');

        $bar = $this->output->createProgressBar($pages->count());
        $bar->start();

        $processed = 0;
        $propertiesCreated = 0;
        $errors = 0;

        foreach ($pages as $page) {
            try {
                $beforeCount = \App\Models\PropertyUnit::count();

                $job = new ParseDataJob($page->crawl_job_id, $page->id);
                $job->handle();

                $afterCount = \App\Models\PropertyUnit::count();
                if ($afterCount > $beforeCount) {
                    $propertiesCreated++;
                }

                $processed++;
            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("Error processing page #{$page->id}: {$e->getMessage()}");
                $this->line("URL: {$page->url}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Pages', $pages->count()],
                ['Successfully Processed', $processed],
                ['Properties Created/Updated', $propertiesCreated],
                ['Errors', $errors],
            ]
        );

        return self::SUCCESS;
    }
}
