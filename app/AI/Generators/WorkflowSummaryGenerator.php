<?php

namespace App\AI\Generators;

use App\AI\AIClient;
use App\AI\Prompts\WorkflowSummaryPrompt;
use App\AI\Validators\ElasticsearchPayloadValidator;
use App\AI\Validators\MonetizationValidator;
use App\AI\Normalizers\SummaryPayloadNormalizer;
use App\Monetization\MonetizationCatalog;

class WorkflowSummaryGenerator
{
    public function __construct(
        private AIClient $client,
        private SummaryPayloadNormalizer $normalizer
    ) {}

    public function generate(): array
    {
        $prompt = WorkflowSummaryPrompt::build();

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
