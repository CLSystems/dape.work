<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\IndexKnowledgeEntries;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('knowledge:index {--entry_id=}', function () {
    $this->call(IndexKnowledgeEntries::class, [
        '--entry_id' => $this->option('entry_id'),
    ]);
})->purpose('Index published knowledge entries into Elasticsearch');
