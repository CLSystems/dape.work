<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Static\CategoryIndexRenderer;
use App\Models\KnowledgeEntry;
use App\Static\KnowledgeEntryRenderer;

class BuildStaticKnowledge extends Command
{
    protected $signature = 'static:build {--entry_id=}';
    protected $description = 'Build static knowledge pages';

    public function handle(
        KnowledgeEntryRenderer $entryRenderer,
        CategoryIndexRenderer $categoryRenderer
    ) {
        // Render individual pages
        KnowledgeEntry::where('status', 'published')->each(function ($entry) use ($entryRenderer) {
            $entryRenderer->render($entry);
            $this->info("Rendered: {$entry->slug}");
        });

        // Render category indexes
        $categoryRenderer->render('alerts');
        $categoryRenderer->render('errors');
        $categoryRenderer->render('performance');
    }
}
