<?php

namespace App\Console\Commands;

use App\Models\AiSummary;
use Illuminate\Console\Command;
use App\Models\GenerationSeed;
use App\Models\KnowledgeEntry;
use App\AI\Generators\ElasticsearchErrorGenerator;
use Throwable;

class GenerateSummary extends Command
{
    protected $signature = 'generate:summary {--limit=3}';

    protected $description = 'Generate summaries for workflows';

    public function handle(ElasticsearchErrorGenerator $generator): int
    {
        $limit = (int) $this->option('limit');

        $seeds = GenerationSeed::where('status', 'pending')
            ->limit($limit)
            ->get();

        if ($seeds->isEmpty()) {
            $this->info('No pending seeds found.');
            return Command::SUCCESS;
        }

        foreach ($seeds as $seed) {
            $this->info("Generating: {$seed->slug}");

            $seed->update(['status' => 'processing']);
            $seed->update(['last_error' => NULL]);

            try {
                $payload = $generator->generate($seed->scenario);

                if (
                    !is_array($payload) ||
                    empty($payload['problem']) ||
                    empty($payload['remediation'])
                ) {
                    throw new \RuntimeException('Payload failed contract validation');
                }

                KnowledgeEntry::create([
                    'system' => $seed->system,
                    'category' => $seed->category,
                    'slug' => $seed->slug,
                    'title' => ucwords(str_replace('-', ' ', $seed->slug)),
                    'structured_payload' => $payload,
                    'status' => 'published',
                    'version' => '1.0',
                ]);

                $seed->update(['status' => 'done']);

            } catch (Throwable $e) {
                $seed->update([
                    'status' => 'failed',
                    'last_error' => $e->getMessage(),
                ]);

                $this->error("Failed: {$seed->slug}");
                $this->line($e->getMessage());
                $this->line($e->getTraceAsString());
            }
        }

        // Build static pages after generation
        $this->call('static:build');

        return Command::SUCCESS;
    }
}
