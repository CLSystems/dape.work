<?php

namespace App\AI\Schemas;

class ElasticsearchErrorSchema
{
    public static function schema(): array
    {
        return [
            'problem' => 'string',
            'root_cause' => 'string',
            'detection' => [
                'symptoms' => 'string[]',
                'commands' => 'string[]',
            ],
            'remediation' => [
                'steps' => 'string[]',
            ],
            'prevention' => 'string[]',
            'production_example' => [
                'curl' => 'string',
            ],
            'severity' => 'critical|high|medium|low',
            'elasticsearch_version' => 'string',
            'monetization' => 'null',
        ];
    }
}
