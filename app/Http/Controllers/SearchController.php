<?php

namespace App\Http\Controllers;

use Elastic\Elasticsearch\Client;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        private Client $client
    ) {}

    public function query(Request $request)
    {
        $q = trim($request->query('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = $this->client->search([
            'index' => 'knowledge_search_v1',
            'size'  => 10,
            'body'  => [
                'query' => [
                    'multi_match' => [
                        'query'  => $q,
                        'fields' => [
                            'title^3',
                            'problem^2',
                            'tags'
                        ],
                    ],
                ],
            ],
        ]);

        return collect($results['hits']['hits'])->map(fn ($hit) => [
            'title' => $hit['_source']['title'],
            'url'   => $hit['_source']['url'],
            'score' => $hit['_score'],
        ]);
    }
}
