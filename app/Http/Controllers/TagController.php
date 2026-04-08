<?php

namespace App\Http\Controllers;

use App\Services\ElasticsearchService;

class TagController extends Controller
{

    /**
     *
     */
    public function index(ElasticsearchService $es)
    {
        $tags = $es->getAllTags();
        return view('components.tags.index', compact('tags'));
    }

    /**
     * Displays workflows associated with a specific tag.
     *
     * @param string               $tag The tag used to filter workflows.
     * @param ElasticsearchService $es  The Elasticsearch service instance for fetching workflows.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException If no workflows are found for the given tag.
     * @return \Illuminate\View\View The view displaying the tag and associated workflows.
     *
     */
    public function show(string $tag, ElasticsearchService $es)
    {
        $tag = strtolower($tag);

        $workflows = $es->getWorkflowsByTag($tag);
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

        return view('components.tags.show', [
            'tag' => $tag,
            'workflows' => $workflows,
            'totalTimeSaved' => $totalTimeSaved,
            'schemaItemListJson' => $schemaItemListJson
        ]);
    }
}
