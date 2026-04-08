<?php

namespace App\AI\Validators;

class MonetizationValidator
{
    public static function validate(?array $block): void
    {
        if ($block === null) {
            return;
        }

        foreach (['type','title','cta','url','placement'] as $key) {
            if (!array_key_exists($key, $block)) {
                throw new \RuntimeException(
                    "Invalid monetization block: missing {$key}"
                );
            }
        }
    }
}
