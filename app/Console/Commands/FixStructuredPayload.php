<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KnowledgeEntry;

class FixStructuredPayload extends Command
{
    protected $signature = 'payload:fix';
    protected $description = 'Normalize structured_payload into proper contract shape';

    public function handle()
    {
        KnowledgeEntry::each(function ($entry) {

            $payload = $entry->structured_payload;

            if (!isset($payload['description'])) {
                return;
            }

            $decoded = json_decode($payload['description'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error("Entry {$entry->id}: invalid embedded JSON");
                return;
            }

            $entry->structured_payload = $decoded;
            $entry->save();

            $this->info("Fixed entry {$entry->id}");
        });
    }
}
