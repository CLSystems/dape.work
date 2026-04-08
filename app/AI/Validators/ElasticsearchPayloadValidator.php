<?php

namespace App\AI\Validators;

use App\AI\Schemas\ElasticsearchErrorSchema;
use RuntimeException;

class ElasticsearchPayloadValidator
{
    public static function validate(array $payload): void
    {
        $schema = ElasticsearchErrorSchema::schema();

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
