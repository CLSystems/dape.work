<?php

namespace App\Services;

use App\AI\Prompts\WorkflowSummaryPrompt;
use App\Models\AiSummary;
use App\AI\AIClient;

class AiSummaryService
{
    public function get(string $type, string $id, array $stats): string
    {
        $summary = AiSummary::query()->where( ['page_type' => $type, 'identifier' => $id])->first();

        if (!$summary) {
            $summary = AiSummary::firstOrCreate(
                ['page_type' => $type, 'identifier' => $id],
                ['summary' => $this->generate($type, $id, $stats)]
            );
        }

        return $summary->summary;
    }

    private function generate(string $type, string $id, array $stats): string
    {
        // Call OpenAI / local LLM here
        return json_encode(app(AIClient::class)->generate($this->prompt($type, $id, $stats)));
    }

    private function prompt(string $type, string $id, array $stats): string
    {
        $instructions = WorkflowSummaryPrompt::build();
        return view('ai.prompts.summary', compact('type', 'id', 'stats', 'instructions'))->render();
    }
}
