<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KnowledgeEntry;

class BackfillMonetizationKey extends Command
{
    protected $signature = 'backfill:monetization {--dry-run}';

    protected $description = 'Ensure all structured_payloads contain monetization key';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $this->info('Starting monetization backfill' . ($dryRun ? ' (dry-run)' : ''));

        $updated = 0;

        KnowledgeEntry::chunk(100, function ($entries) use (&$updated, $dryRun) {
            foreach ($entries as $entry) {

                $payload = $entry->structured_payload;

                if (!array_key_exists('monetization', $payload)) {
                    $payload['monetization'] = null;

                    if (!$dryRun) {
                        $entry->structured_payload = $payload;
                        $entry->save();
                    }

                    $updated++;
                    $this->line("Patched: {$entry->slug}");
                }
            }
        });

        $this->info("Completed. Entries updated: {$updated}");

        return Command::SUCCESS;
    }
}
