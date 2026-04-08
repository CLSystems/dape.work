<?php

namespace App\Http\Controllers;

use App\Services\ElasticsearchService;

class ComplexityController extends Controller
{
    private array $allowed = ['beginner', 'intermediate', 'advanced'];

    /**
     * Display workflows based on the specified complexity level.
     *
     * @param string               $level The complexity level to filter the workflows.
     * @param ElasticsearchService $es    The Elasticsearch service instance used to retrieve workflows.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException If the provided level is not allowed or if no workflows are found.
     * @return \Illuminate\View\View The view displaying the workflows for the specified complexity level.
     *
     */
    public function show(string $level, ElasticsearchService $es)
    {
        abort_unless(in_array($level, $this->allowed), 404);

        $workflows = $es->getWorkflowsByComplexity($level);
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

        return view('components.complexity.show', [
            'level'     => $level,
            'workflows' => $workflows,
            'totalTimeSaved' => $totalTimeSaved,
            'schemaItemListJson' => $schemaItemListJson
        ]);
    }
}
