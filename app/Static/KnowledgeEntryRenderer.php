<?php

namespace App\Static;

use App\Models\KnowledgeEntry;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

class KnowledgeEntryRenderer
{
    public function render(KnowledgeEntry $entry): void
    {
        $payload = $entry->structured_payload;

        $techArticleJson = json_encode([
            "@context" => "https://schema.org",
            "@type" => "TechArticle",
            "headline" => $entry->title,
            "description" => $payload['problem'] ?? $entry->title,
            "author" => [
                "@type" => "Organization",
                "name" => "Dape",
                "url" => "https://dape.work"
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => "Dape",
                "url" => "https://dape.work"
            ],
            "datePublished" => $entry->created_at->toIso8601String(),
            "dateModified" => $entry->updated_at->toIso8601String(),
            "mainEntityOfPage" => $entry->getCanonicalUrlAttribute(),
            "keywords" => implode(", ", $payload["tags"] ?? [])
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $howToJson = null;
        if (!empty($payload['remediation']['steps'])) {
            $howToJson = json_encode([
                '@context' => 'https://schema.org',
                '@type'    => 'HowTo',
                'name'     => $entry->title,
                'description' => $payload['problem'] ?? $entry->title,
                'step' => collect($payload['remediation']['steps'])->map(fn ($step, $i) => [
                    '@type' => 'HowToStep',
                    'position' => $i + 1,
                    'text' => $step,
                ])->values()->all(),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        $html = View::make('static.knowledge_entry', [
            'entry'           => $entry,
            'payload'         => $payload,
            'techArticleJson' => $techArticleJson,
            'howToJson'       => $howToJson,
        ])->render();

        $path = public_path(
            "/{$entry->system}/{$entry->category}/{$entry->slug}.html"
        );

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $html);
    }
}
