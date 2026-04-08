<?php

namespace App\Search;

use App\Models\KnowledgeEntry;
use App\Services\ElasticsearchService as Client;
// use Elastic\Elasticsearch\Client;

class ElasticsearchIndexer
{
    public function __construct(
        private Client $client
    ) {}

    public function index(KnowledgeEntry $entry): void
    {
        if ($entry->status !== KnowledgeEntry::STATUS_PUBLISHED) {
            throw new \RuntimeException('Only published entries can be indexed.');
        }

        KnowledgePayloadValidator::validate($entry->structured_payload);

        $document = ElasticsearchDocumentBuilder::fromKnowledgeEntry($entry);

        $this->client->index([
            'index'   => 'knowledge-elasticsearch-v1',
            'id'      => $entry->slug, // deterministic, idempotent
            'body'    => $document,
            'refresh' => false,
        ]);
    }

    public function delete(KnowledgeEntry $entry): void
    {
        $this->client->delete([
            'index' => 'knowledge-elasticsearch-v1',
            'id'    => $entry->slug,
        ]);
    }
}
