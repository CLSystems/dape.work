<?php

namespace App\Contracts;

final class KnowledgePayloadContract
{
    public const REQUIRED_KEYS = [
        'problem',
        'root_cause',
        'detection',
        'remediation',
        'prevention',
        'severity',
        'elasticsearch_version',
    ];

    public const DETECTION_KEYS = [
        'symptoms',
        'commands',
    ];

    public const REMEDIATION_KEYS = [
        'steps',
    ];
}
