<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('sitemap:generate')->daily();
Schedule::command('property:convert-descriptions --limit=50')->hourly();
Schedule::command('crawl:website funda --limit=40')->hourly();
