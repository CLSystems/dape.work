<?php

namespace App\AI\Normalizers;

use App\Contracts\KnowledgePayloadContract;
use InvalidArgumentException;

class KnowledgePayloadNormalizer
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
        foreach (KnowledgePayloadContract::REQUIRED_KEYS as $key) {
            if (!array_key_exists($key, $raw)) {
                throw new InvalidArgumentException("Missing required key: {$key}");
            }
        }

        // Detection normalization
        $raw['detection'] = $this->normalizeSection(
            $raw['detection'],
            KnowledgePayloadContract::DETECTION_KEYS,
            'detection'
        );

        // Remediation normalization
        $raw['remediation'] = $this->normalizeSection(
            $raw['remediation'],
            KnowledgePayloadContract::REMEDIATION_KEYS,
            'remediation'
        );

        // Enforce arrays where expected
        $raw['detection']['symptoms'] = (array) $raw['detection']['symptoms'];
        $raw['detection']['commands'] = (array) $raw['detection']['commands'];
        $raw['remediation']['steps'] = (array) $raw['remediation']['steps'];
        $raw['prevention'] = (array) $raw['prevention'];

        // Trim noise
        foreach ($raw as $key => $value) {
            if (is_string($value)) {
                $raw[$key] = trim($value);
            }
        }

        $raw['monetization'] ??= null;

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
