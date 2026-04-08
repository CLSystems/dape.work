<?php

namespace App\AI\Validators;

use App\AI\Schemas\WorkflowSummarySchema;
use RuntimeException;

class WorkflowSummaryPayloadValidator
{
    public static function validate(array $payload): void
    {
        $schema = WorkflowSummarySchema::schema();

        foreach ($schema as $key => $type) {
            if (!array_key_exists($key, $payload)) {
                throw new RuntimeException("Missing required key: {$key}");
            }
        }

        foreach ($payload as $key => $value) {
            if (!array_key_exists($key, $schema)) {
                throw new RuntimeException("Unexpected key in payload: {$key}");
            }
        }
    }
}
