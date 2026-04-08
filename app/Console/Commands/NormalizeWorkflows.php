<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Elastic\Elasticsearch\ClientBuilder;
use stdClass;

class NormalizeWorkflows extends Command
{
    protected $signature = 'workflows:normalize';
    protected $description = 'Normalize all workflows, generate slugs, and reindex into a normalized Elasticsearch index';

    protected $es;
    protected $sourceIndex = 'devops_workflows';
    protected $targetIndex = 'devops_workflows_normalized';

    public function __construct()
    {
        parent::__construct();

        $this->es = ClientBuilder::create()
            ->setHosts([config('services.elasticsearch.host')])
            ->setBasicAuthentication(config('services.elasticsearch.user'), config('services.elasticsearch.password'))
            ->setSSLVerification(false)
            ->build();
    }

    public function handle()
    {
        $this->info("Starting normalization of workflows...");

        // Step 1: Delete target index if exists
        if ($this->es->indices()->exists(['index' => $this->targetIndex])) {
            $this->es->indices()->delete(['index' => $this->targetIndex]);
            $this->info("Deleted existing target index: {$this->targetIndex}");
        }

        // Step 2: Create a normalized index with mappings
        $this->es->indices()->create([
            'index' => $this->targetIndex,
            'body' => [
                'mappings' => [
                    'properties' => [
                        'workflow_id' => ['type' => 'keyword'],
                        'slug' => ['type' => 'keyword'],
                        'title' => ['type' => 'text', 'fields' => ['keyword' => ['type' => 'keyword']]],
                        'description' => ['type' => 'text'],
                        'tool' => ['type' => 'keyword'],
                        'tool_slug' => ['type' => 'keyword'],
                        'category' => ['type' => 'text', 'fields' => ['keyword' => ['type' => 'keyword']]],
                        'category_slug' => ['type' => 'keyword'],
                        'complexity' => ['type' => 'keyword'],
                        'steps' => ['type' => 'text'],
                        'prerequisites' => ['type' => 'text'],
                        'dependencies' => ['type' => 'text'],
                        'expected_time_saved' => ['type' => 'text'],
                        'expected_time_saved_numeric' => ['type' => 'integer'],
                        'tags' => ['type' => 'keyword'],
                        'tag_slug' => ['type' => 'keyword'],
                        'source_url' => ['type' => 'keyword'],
                        'ai_summary' => ['type' => 'text'],
                        'example_code_snippets' => ['type' => 'text']
                    ]
                ]
            ]
        ]);
        $this->info("Created normalized index: {$this->targetIndex}");

        // Step 3: Scroll through source index
        $scroll = '1m';
        $response = $this->es->search([
            'index' => $this->sourceIndex,
            'scroll' => $scroll,
            'size' => 50,
            'body' => ['query' => ['match_all' => new stdClass()]]
        ]);

        $scroll_id = $response['_scroll_id'];
        $total = $response['hits']['total']['value'] ?? count($response['hits']['hits']);
        $this->info("Found {$total} documents to normalize.");

        $count = 0;
        do {
            $documents = $response['hits']['hits'];
            if (empty($documents)) {
                break;
            }

            $bulk = [];
            foreach ($documents as $doc) {
                $source = $doc['_source'];

                // Generate slugs
                $workflowSlug = $this->generateSlug($source['title']);
                $categorySlug = $this->generateSlug($source['category']);

                // Prepare normalized document
                $normalized = array_merge($source, [
                    'slug' => $workflowSlug,
                    'category_slug' => $categorySlug
                ]);

                $bulk[] = ['index' => ['_index' => $this->targetIndex, '_id' => $source['workflow_id']]];
                $bulk[] = $normalized;

                $count++;
            }

            // Bulk insert
            $this->es->bulk(['body' => $bulk]);

            // Scroll for next batch
            $response = $this->es->scroll(['scroll_id' => $scroll_id, 'scroll' => $scroll]);
            $scroll_id = $response['_scroll_id'] ?? null;
        } while (!empty($response['hits']['hits']));

        $this->info("Normalization complete. Total workflows processed: {$count}");
    }

    private function generateSlug(string $text): string
    {
        $slug = strtolower($text);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug); // remove special chars
        $slug = preg_replace('/[\s\/]+/', '-', $slug);          // spaces → hyphens
        return $slug;
    }
}
