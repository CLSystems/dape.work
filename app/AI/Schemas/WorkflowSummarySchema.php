<?php

namespace App\AI\Schemas;

class WorkflowSummarySchema
{
    public static function schema(): array
    {
        return [
            'page_type' => 'string',
            'identifier' => 'string',
            'workflow_count' => 'integer',
            'complexity_distribution' => [
                'Beginner' => 'integer',
                'Intermediate' => 'integer',
                'Advanced' => 'integer',
            ],
            'avg_time_saved_hours' => 'float',
            'top_categories' => 'string[]',
            'top_tags' => 'string[]',
        ];
    }
}
