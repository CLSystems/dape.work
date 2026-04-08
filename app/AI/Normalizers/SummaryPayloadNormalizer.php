<?php

namespace App\AI\Normalizers;

use App\Contracts\WorkflowSummaryPayloadContract;
use InvalidArgumentException;

class SummaryPayloadNormalizer
{
    public function normalize(array $raw): array
    {
        // Flatten nested "description" JSON if AI wraps it
        if (isset($raw['description']) && is_string($raw['description'])) {
            $decoded = json_decode($raw['description'], true);
            if (is_array($decoded)) {
                $raw = array_merge($raw, $decoded);
                unset($raw['description']);
            }
        }

        // Ensure required top-level keys
        foreach (WorkflowSummaryPayloadContract::REQUIRED_KEYS as $key) {
            if (!array_key_exists($key, $raw)) {
                throw new InvalidArgumentException("Missing required key: {$key}");
            }
        }

        // Complexity normalization
        $raw['complexity_distribution'] = $this->normalizeSection(
            $raw['complexity_distribution'],
            WorkflowSummaryPayloadContract::COMPLEXITY_KEYS,
            'complexity_distribution'
        );

        // Enforce arrays where expected
        $raw['complexity_distribution'] = (array) $raw['complexity_distribution'];
        $raw['top_categories'] = (array) $raw['top_categories'];
        $raw['top_tags'] = (array) $raw['top_tags'];

        // Trim noise
        foreach ($raw as $key => $value) {
            if (is_string($value)) {
                $raw[$key] = trim($value);
            }
        }

        return $raw;
    }

    private function normalizeSection(array $section, array $keys, string $name): array
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $section)) {
                throw new InvalidArgumentException(
                    "Missing {$name}.{$key}"
                );
            }
        }

        return $section;
    }
}
