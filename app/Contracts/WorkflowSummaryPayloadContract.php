<?php

namespace App\Contracts;

final class WorkflowSummaryPayloadContract
{
    public const REQUIRED_KEYS = [
        'page_type',
        'identifier',
        'workflow_count',
        'complexity_distribution',
        'avg_time_saved_hours',
        'top_categories',
        'top_tags',
    ];

    public const COMPLEXITY_KEYS = [
        'Beginner',
        'Intermediate',
        'Advanced',
    ];
}
