<?php

namespace App\Services;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([config('services.elasticsearch.host')])
            ->setBasicAuthentication(config('services.elasticsearch.user'), config('services.elasticsearch.password'))
            ->setSSLVerification(false)
            ->build();
    }

    public function getWorkflowBySlug(string $slug): ?array
    {
        $response = $this->client->search([
            'index' => 'devops_workflows_normalized',
            'body' => [
                'size' => 1,
                'query' => [
                    'term' => [
                        'slug' => $slug
                    ]
                ]
            ]
        ]);

        return $response['hits']['hits'][0]['_source'] ?? null;
    }

    public function getAllWorkflowSlugs(): array
    {
        $response = $this->client->search([
            'index' => 'devops_workflows_normalized',
            'body' => [
                'size' => 1000,
                '_source' => ['slug']
            ]
        ]);

        return array_map(fn ($h) => $h['_source']['slug'], $response['hits']['hits'] ?? []);
    }

    public function getWorkflowsByCategorySlug(string $category_slug): array
    {
        return $this->client->search([
            'index' => 'devops_workflows_normalized',
            'body' => [
                'size' => 100,
                'query' => [
                    'term' => [
                        'category_slug' => $category_slug
                    ]
                ],
                'sort' => [
                    'expected_time_saved_numeric' => 'desc'
                ]
            ]
        ])['hits']['hits'];
    }

    public function getWorkflowById(string $id): ?array
    {
        $response = $this->client->search([
            'index' => 'devops_workflows_normalized',
            'body' => [
                'size' => 1,
                'query' => [
                    'term' => [
                        'workflow_id' => $id
                    ]
                ]
            ]
        ]);

        return $response['hits']['hits'][0]['_source'] ?? null;
    }

    public function getWorkflowsByTool(string $tool): array
    {
        return $this->client->search([
            'index' => 'devops_workflows_normalized',
            'body' => [
                'size' => 100,
                'query' => [
                    'term' => [
                        'tool' => $tool
                    ]
                ],
                'sort' => [
                    'expected_time_saved_numeric' => ['order' => 'desc']
                ]
            ]
        ])['hits']['hits'];
    }

    public function getWorkflowsByComplexity(string $complexity): array
    {
        return $this->client->search([
            'index' => 'devops_workflows_normalized',
            'body' => [
                'size' => 100,
                'query' => [
                    'term' => [
                        'complexity' => ucfirst($complexity)
                    ]
                ],
                'sort' => [
                    'expected_time_saved_numeric' => ['order' => 'desc']
                ]
            ]
        ])['hits']['hits'];
    }

    public function getWorkflowsByTag(string $tag): array
    {
        return $this->client->search([
            'index' => 'devops_workflows_normalized',
            'body' => [
                'size' => 100,
                'query' => [
                    'term' => [
                        'tags' => $tag
                    ]
                ],
                'sort' => [
                    'expected_time_saved_numeric' => ['order' => 'desc']
                ]
            ]
        ])['hits']['hits'];
    }

    /**
     *
     */
    public function getTopWorkflows(
        string $field,
        string $value,
        int $limit = 10
    ): array {
        return $this->client->search([
            'index' => 'devops_workflows_normalized',
            'body' => [
                'size' => $limit,
                'query' => [
                    'term' => [
                        $field => $value
                    ]
                ],
                'sort' => [
                    'expected_time_saved_numeric' => ['order' => 'desc']
                ]
            ]
        ])['hits']['hits'];
    }

    public function getCategoryBySlug(string $category_slug): array
    {
        return $this->client->search([
            'index' => 'devops_workflows_normalized',
            'body' => [
                'size' => 1,
                'query' => [
                    'term' => [
                        'category_slug' => $category_slug
                    ]
                ],
                'sort' => [
                    'expected_time_saved_numeric' => ['order' => 'desc']
                ]
            ]
        ])['hits']['hits'][0]['_source'];
    }

    /**
     *
     */
    public function getAllCategories(): array
    {
        $response = $this->client->search([
            'index' => 'devops_workflows_normalized',
            'body' => [
                'size' => 0,
                'aggs' => [
                    'categories' => [
                        'terms' => [
                            'field' => 'category.keyword',
                            'size' => 50
                        ]
                    ]
                ]
            ]
        ]);

        return $response['aggregations']['categories']['buckets'] ?? [];
    }


    public function getAllTools(): array
    {
        $response = $this->client->search([
            'index' => 'devops_workflows_normalized',
            'body' => [
                'size' => 0,
                'aggs' => [
                    'tools' => [
                        'terms' => [
                            'field' => 'tool',
                            'size' => 50
                        ]
                    ]
                ]
            ]
        ]);

        return $response['aggregations']['tools']['buckets'] ?? [];
    }

    public function getAllComplexities(): array
    {
        $response = $this->client->search([
            'index' => 'devops_workflows_normalized',
            'body' => [
                'size' => 0,
                'aggs' => [
                    'complexities' => [
                        'terms' => [
                            'field' => 'complexity',
                            'size' => 10
                        ]
                    ]
                ]
            ]
        ]);

        return $response['aggregations']['complexities']['buckets'] ?? [];
    }

    public function getAllTags(): array
    {
        return $this->client->search([
            'index' => 'devops_workflows_normalized',
            'body' => [
                'size' => 0,
                'aggs' => [
                    'tags' => [
                        'terms' => [
                            'field' => 'tags',
                            'size' => 100
                        ]
                    ]
                ]
            ]
        ])['aggregations']['tags']['buckets'] ?? [];
    }

    public function getTotalWorkflowsCount(): int
    {
        $response = $this->client->count([
            'index' => 'devops_workflows_normalized'
        ]);

        return $response['count'] ?? 0;
    }

    public function getToolStats(string $tool): array
    {
        $response = $this->client->search([
            'index' => 'devops_workflows_normalized',
            'body' => [
                'size' => 0,
                'query' => [
                    'bool' => [
                        'filter' => [
                            [
                                'term' => [
                                    'tool' => $tool
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' => [
                    'complexity_dist' => [
                        'terms' => [
                            'field' => 'complexity.keyword',
                            'size' => 10
                        ]
                    ],
                    'avg_time_saved' => [
                        'avg' => [
                            'field' => 'expected_time_saved_numeric'
                        ]
                    ],
                    'top_categories' => [
                        'terms' => [
                            'field' => 'category.keyword',
                            'size' => 3
                        ]
                    ],
                    'top_tags' => [
                        'terms' => [
                            'field' => 'tags.keyword',
                            'size' => 5
                        ]
                    ]
                ]
            ]
        ]);

        // dd($tool, $response['hits'], $response['aggregations']);

        return [
            'workflow_count' => $response['hits']['total']['value'] ?? 0,

            'complexity_distribution' => collect(
                $response['aggregations']['complexity_dist']['buckets'] ?? []
            )->mapWithKeys(fn ($b) => [
                $b['key'] => $b['doc_count']
            ])->toArray(),

            'avg_time_saved_hours' => round(
                $response['aggregations']['avg_time_saved']['value'] ?? 0,
                1
            ),

            'top_categories' => collect(
                $response['aggregations']['top_categories']['buckets'] ?? []
            )->pluck('key')->toArray(),

            'top_tags' => collect(
                $response['aggregations']['top_tags']['buckets'] ?? []
            )->pluck('key')->toArray(),
        ];
    }

    /**
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     * @throws \Elastic\Elasticsearch\Exception\MissingParameterException
     */
    public function index(array $array)
    {
        $this->client->index($array);
    }

    public function delete(array $array)
    {
        $this->client->delete($array);
    }

}
