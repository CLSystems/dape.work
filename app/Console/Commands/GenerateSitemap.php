<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KnowledgeEntry;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml';

    public function handle()
    {
        $urls = collect([
            url('/elasticsearch/errors'),
        ]);

        KnowledgeEntry::where('status', 'published')->each(function ($entry) use ($urls) {
            $urls->push(
                url("/elasticsearch/{$entry->category}/{$entry->slug}.html")
            );
        });

        $xml = view('static.sitemap', [
            'urls' => $urls,
        ])->render();

        file_put_contents(
            public_path('sitemap.xml'),
            $xml
        );

        $this->info('sitemap.xml generated');
    }
}
