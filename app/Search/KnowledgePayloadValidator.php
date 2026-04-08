<?php

namespace App\Search;

class KnowledgePayloadValidator
{
    private const ALLOWED_KEYS = [
        'problem',
        'root_cause',
        'detection',
        'remediation',
        'prevention',
        'production_example',
        'severity',
        'elasticsearch_version',
        'monetization'
    ];

    public static function validate(array $payload): void
    {
        foreach ($payload as $key => $value) {
            if (!in_array($key, self::ALLOWED_KEYS, true)) {
                throw new \RuntimeException("Invalid payload key: {$key}");
            }
        }
    }
}
