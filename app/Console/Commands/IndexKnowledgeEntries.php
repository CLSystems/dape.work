<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KnowledgeEntry;
use App\Search\ElasticsearchIndexer;

class IndexKnowledgeEntries extends Command
{
    protected $signature = 'knowledge:index {--entry_id=}';
    protected $description = 'Index published knowledge entries into Elasticsearch';

    public function handle(ElasticsearchIndexer $indexer)
    {
        $query = KnowledgeEntry::where('status', 'published');

        if ($this->option('entry_id')) {
            $query->where('id', $this->option('entry_id'));
        }

        $query->each(function ($entry) use ($indexer) {
            $indexer->index($entry);
            $this->info("Indexed: {$entry->slug}");
        });
        $this->info("DONE.");
    }
}
