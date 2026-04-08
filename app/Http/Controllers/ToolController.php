<?php

namespace App\Http\Controllers;

use App\Services\AiSummaryService;
use App\Services\ElasticsearchService;

class ToolController extends Controller
{

    /**
     *
     */
    public function index(ElasticsearchService $es)
    {
        $tools = $es->getAllTools();
        abort_if(!count($tools), 404);

        return view('components.tools.index', ['tools' => $tools]);
    }

    /**
     * Handles displaying the specified tool and its related workflows along with the total time saved.
     *
     * @param string               $tool_slug The slug representing the tool.
     * @param ElasticsearchService $es        Service for interacting with Elasticsearch.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If no workflows are found for the tool.
     * @return \Illuminate\View\View The view displaying the tool and its workflows.
     *
     */

    public function show(string $tool_slug, ElasticsearchService $es)
    {
        $tool = ucfirst($tool_slug);
        $normalized = str_replace('-', ' ', $tool);
        $workflows = $es->getWorkflowsByTool($normalized);

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

        $stats = $es->getToolStats($tool);

        $summary = app(AiSummaryService::class)
            ->get('tool', $tool, $stats);


        return view('components.tools.show', [
            'tool'      => $tool,
            'tool_slug' => $tool_slug,
            'summary'   => $summary,
            'workflows' => $workflows,
            'totalTimeSaved' => $totalTimeSaved,
            'schemaItemListJson' => $schemaItemListJson
        ]);
    }

}
