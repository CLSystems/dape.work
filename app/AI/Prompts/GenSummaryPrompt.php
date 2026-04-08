<?php

namespace App\AI\Prompts;

class GenSummaryPrompt
{
    public static function build(): string
    {

        return <<<PROMPT
You are writing an expert DevOps knowledge base.

Write a concise, authoritative summary for a {{page_type}} page identified as "{{identifier}}".

Use only the provided statistics.
Do not invent tools, workflows, or metrics.
Do not use future tense.
Do not use marketing language.

Structure:
1. What these workflows focus on
2. Typical automation use cases
3. Skill level distribution
4. Expected time savings (realistic)

Limit to 120–160 words.
Tone: technical, neutral, confident.
PROMPT;
    }
}
