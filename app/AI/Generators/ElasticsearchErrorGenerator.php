<?php

namespace App\AI\Generators;

use App\AI\AIClient;
use App\AI\Prompts\ElasticsearchErrorPrompt;
use App\AI\Validators\ElasticsearchPayloadValidator;
use App\AI\Validators\MonetizationValidator;
use App\AI\Normalizers\KnowledgePayloadNormalizer;
use App\Monetization\MonetizationCatalog;

class ElasticsearchErrorGenerator
{
    public function __construct(
        private AIClient $client,
        private KnowledgePayloadNormalizer $normalizer
    ) {}

    public function generate(string $scenario): array
    {
        $prompt = ElasticsearchErrorPrompt::build($scenario);

        // 1. Raw AI output (may be messy)
        $rawPayload = $this->client->generate($prompt);

        // 2. Normalize (coerce + clean)
        $normalizedPayload = $this->normalizer->normalize($rawPayload);
        $normalizedPayload['monetization'] ??= null;


        // 3. Validate (strict, throws on failure)
        ElasticsearchPayloadValidator::validate($normalizedPayload);

        if (isset($normalizedPayload['system']) &&
            $normalizedPayload['system'] === 'elasticsearch' &&
            $normalizedPayload['category'] === 'errors' &&
            $normalizedPayload['severity'] === 'critical'
        ) {
            $normalizedPayload['monetization'] = MonetizationCatalog::for('elasticsearch.audit');
            MonetizationValidator::validate($normalizedPayload['monetization']);
        }

        // 4. Return canonical payload
        return $normalizedPayload;
    }
}
