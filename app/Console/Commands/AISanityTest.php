<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AI\Generators\ElasticsearchErrorGenerator;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Throwable;

class AISanityTest extends Command
{
    protected $signature = 'ai:sanity-test';
    protected $description = 'Run a sanity test for the Elasticsearch AI generator';

    public function handle(ElasticsearchErrorGenerator $generator)
    {
        $scenario = 'Cluster health is RED due to unassigned primary shards after a node failure.';

        $this->info('Running AI sanity test...');
        $this->line('Scenario: ' . $scenario);
        $this->line('');

        try {
            $payload = $generator->generate($scenario);

            if (count($payload) !== 9) {
                throw new \RuntimeException('Payload key count mismatch');
            }

            $this->info('AI generation succeeded.');
            $this->line('');
            $this->line('Generated payload:');
            $this->line(json_encode($payload, JSON_PRETTY_PRINT));

            return CommandAlias::SUCCESS;

        } catch (Throwable $e) {
            $this->error('AI sanity test FAILED.');
            $this->error($e->getMessage());

            return CommandAlias::FAILURE;
        }
    }
}
