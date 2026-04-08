<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\KnowledgeEntry;
use App\Search\ElasticsearchIndexer;
use Elastic\Elasticsearch\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ElasticsearchKnowledgeContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_knowledge_entry_indexes_correctly_into_elasticsearch()
    {
        /** STEP 1: Create authoritative Laravel record */
        $entry = KnowledgeEntry::create([
            'slug'     => 'cluster-red-unassigned-shards',
            'system'   => 'elasticsearch',
            'category' => 'error',
            'title'    => 'Elasticsearch Cluster Red: Unassigned Shards',
            'status'   => KnowledgeEntry::STATUS_PUBLISHED,
            'version'  => '1.0',
            'structured_payload' => [
                'problem' => 'Cluster health is RED due to unassigned primary shards.',
                'root_cause' => 'Node failure or disk watermarks exceeded.',
                'detection' => [
                    'symptoms' => [
                        '_cluster/health returns red',
                        'Indices unavailable'
                    ],
                    'commands' => [
                        'GET _cluster/health',
                        'GET _cat/shards?v'
                    ]
                ],
                'remediation' => [
                    'steps' => [
                        'Identify unassigned shards',
                        'Add disk capacity or nodes',
                        'Reroute shards if necessary'
                    ]
                ],
                'prevention' => [
                    'Configure disk watermarks',
                    'Monitor node disk usage'
                ],
                'production_example' => [
                    'curl' => 'POST _cluster/reroute { ... }'
                ],
                'severity' => 'critical',
                'elasticsearch_version' => '8.x',
            ],
        ]);

        /** STEP 2: Index into Elasticsearch */
        $indexer = app(ElasticsearchIndexer::class);
        $indexer->index($entry);

        /** STEP 3: Fetch a document back from Elasticsearch */
        /** @var Client $client */
        $client = app(Client::class);

        $response = $client->get([
            'index' => 'knowledge-elasticsearch-v1',
            'id'    => $entry->slug,
        ]);

        $source = $response['_source'];

        /** STEP 4: Assert required fields exist */
        $this->assertEquals('cluster-red-unassigned-shards', $source['slug']);
        $this->assertEquals('elasticsearch', $source['system']);
        $this->assertEquals('error', $source['category']);
        $this->assertEquals('Elasticsearch Cluster Red: Unassigned Shards', $source['title']);

        /** STEP 5: Assert structured content integrity */
        $this->assertEquals(
            'Cluster health is RED due to unassigned primary shards.',
            $source['problem']
        );

        $this->assertEquals(
            'Node failure or disk watermarks exceeded.',
            $source['root_cause']
        );

        $this->assertIsArray($source['detection']['symptoms']);
        $this->assertContains(
            'GET _cluster/health',
            $source['detection']['commands']
        );

        $this->assertContains(
            'Identify unassigned shards',
            $source['remediation']['steps']
        );

        $this->assertEquals('critical', $source['severity']);
        $this->assertEquals('8.x', $source['elasticsearch_version']);

        /** STEP 6: Assert no schema drift (VERY IMPORTANT) */
        $allowedKeys = [
            'slug',
            'system',
            'category',
            'title',
            'problem',
            'root_cause',
            'detection',
            'remediation',
            'prevention',
            'production_example',
            'severity',
            'elasticsearch_version',
            'version',
            'last_verified_at',
            'published_at',
        ];

        foreach ($source as $key => $value) {
            $this->assertContains(
                $key,
                $allowedKeys,
                "Unexpected field found in Elasticsearch document: {$key}"
            );
        }
    }
}
