<?php

namespace App\Search;

use App\Models\KnowledgeEntry;
use Carbon\Carbon;

class ElasticsearchDocumentBuilder
{
    public static function fromKnowledgeEntry(KnowledgeEntry $entry): array
    {
        $payload = $entry->structured_payload ?? [];

        return [
            'slug'                   => $entry->slug,
            'system'                 => $entry->system,
            'category'               => $entry->category,
            'title'                  => $entry->title,

            // Structured content (flattened but controlled)
            'problem'                => $payload['problem'] ?? null,
            'root_cause'             => $payload['root_cause'] ?? null,

            'detection'              => [
                'symptoms' => $payload['detection']['symptoms'] ?? [],
                'commands' => $payload['detection']['commands'] ?? [],
            ],

            'remediation'            => [
                'steps' => $payload['remediation']['steps'] ?? [],
            ],

            'prevention'             => $payload['prevention'] ?? [],
            'production_example'     => [
                'curl' => $payload['production_example']['curl'] ?? null,
            ],

            'severity'               => $payload['severity'] ?? 'unknown',
            'elasticsearch_version'  => $payload['elasticsearch_version'] ?? 'unknown',

            'version'                => $entry->version,
            'last_verified_at'       => optional($entry->last_verified_at)->toIso8601String(),
            'published_at'           => Carbon::now()->toIso8601String(),
        ];
    }
}
