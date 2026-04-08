<?php

namespace App\Search;

use App\Models\KnowledgeEntry;
use Elastic\Elasticsearch\Client;

class KnowledgeSearchIndexer
{
    public function __construct(
        private Client $client
    ) {}

    public function index(KnowledgeEntry $entry): void
    {
        $payload = $entry->structured_payload;

        $this->client->index([
            'index' => 'knowledge_search_v1',
            'id'    => $entry->id,
            'body'  => [
                'id'        => $entry->id,
                'system'    => $entry->system,
                'category'  => $entry->category,
                'slug'      => $entry->slug,
                'title'     => $entry->title,
                'problem'   => $payload['problem'] ?? '',
                'tags'      => $payload['tags'] ?? [],
                'severity'  => $payload['severity'] ?? null,
                'url'       => $entry->canonical_url,
                'updated_at'=> $entry->updated_at->toIso8601String(),
            ],
        ]);
    }
}
