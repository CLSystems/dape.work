<?php

namespace App\Http\Controllers;

use App\Services\ElasticsearchService;

class TopController extends Controller
{
    /**
     * @var array|string[]
     */
    public static array $allowedDimensions = [
        'tool'       => 'tool',
        'category'   => 'category_slug',
        'complexity' => 'complexity',
        'tag'        => 'tags'
    ];

    public function show(
        string $dimension,
        string $slug,
        ElasticsearchService $es
    ) {
        abort_unless(isset(self::$allowedDimensions[$dimension]), 404);

        $field = self::$allowedDimensions[$dimension];
        $value = ($dimension != 'category' && $dimension != 'tag')
            ? ucfirst($slug)
            : $slug;

        $workflows = $es->getTopWorkflows($field, $value);

        abort_if(empty($workflows), 404);

        $totalTimeSaved = array_sum(array_map(
            fn ($w) => $w['_source']['expected_time_saved_numeric'],
            $workflows
        ));

        $schemaItemList = [];

        foreach ($workflows as $index => $item) {
            $workflow = $item['_source'];

            $schemaItemList[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $workflow['title'],
                'url' => url('/workflows/' . $workflow['slug'])
            ];
        }

        $schemaItemListJson = json_encode([
            "@context" => "https://schema.org",
            "@type" => "ItemList",
            "itemListElement" => $schemaItemList
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $faqData = $this->topFaq($dimension, $slug);
        $schemaFaqJson = json_encode([
            "@context" => "https://schema.org",
            "@type" => "FAQPage",
            "mainEntity" => collect($faqData)->map(fn($faq) => [
                "@type" => "Question",
                "name" => $faq['question'],
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => $faq['answer']
                ]
            ])->toArray()
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return view('components.top.show', [
            'dimension'          => $dimension,
            'slug'               => $slug,
            'workflows'          => $workflows,
            'totalTimeSaved'     => $totalTimeSaved,
            'schemaItemListJson' => $schemaItemListJson,
            'schemaFaqJson'      => $schemaFaqJson
        ]);
    }

    private function topFaq(string $dimension, string $slug): array
    {
        return [
            [
                'question' => "What are the best {$slug} {$dimension} workflows?",
                'answer' => "The best {$slug} {$dimension} workflows are ranked by estimated time saved and real-world automation impact."
            ],
            [
                'question' => "How are these workflows ranked?",
                'answer' => "Workflows are ranked by expected time saved per week, based on automation scope and implementation depth."
            ],
            [
                'question' => "Are these workflows production-ready?",
                'answer' => "Yes. All workflows are based on proven DevOps automation patterns used in real environments."
            ]
        ];
    }

}
