<?php

namespace App\AI\Prompts;

use App\AI\Schemas\ElasticsearchErrorSchema;

class ElasticsearchErrorPrompt
{
    public static function build(string $scenario): string
    {
        $schema = json_encode(ElasticsearchErrorSchema::schema(), JSON_PRETTY_PRINT);

        return <<<PROMPT
You are a senior Elasticsearch SRE.

Generate a response for the following Elasticsearch operational issue:

SCENARIO:
{$scenario}

OUTPUT REQUIREMENTS (STRICT):
- Output valid JSON only
- Do NOT include explanations or markdown
- Do NOT include keys not defined in the schema
- Do NOT nest or rename keys
- Arrays must contain at least one item
- Commands must be realistic and production-safe
- Use concise, precise technical language

JSON SCHEMA:
{$schema}
PROMPT;
    }
}
