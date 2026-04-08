<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KnowledgeEntry;

class NormalizeCategories extends Command
{
    protected $signature = 'category:normalize';
    protected $description = 'Normalize knowledge entry categories';

    public function handle()
    {
        KnowledgeEntry::where('category', 'errors')
            ->update(['category' => KnowledgeEntry::CATEGORY_ERROR]);

        KnowledgeEntry::where('category', 'alerts')
            ->update(['category' => KnowledgeEntry::CATEGORY_ALERT]);

        $this->info('Categories normalized to singular forms.');
    }
}
