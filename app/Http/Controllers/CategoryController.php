<?php

namespace App\Http\Controllers;

use App\Services\ElasticsearchService;
use Illuminate\Support\Str;

class CategoryController extends Controller
{

    /**
     * Display the categories index view with data retrieved from Elasticsearch.
     * Abort with a 404 error if no categories are found.
     *
     * @param ElasticsearchService $es Service to interact with Elasticsearch.
     *
     * @return \Illuminate\View\View Rendered view for the categories index.
     */
    public function index(ElasticsearchService $es)
    {
        $categories = $es->getAllCategories();
        // abort_if(!count($categories), 404);

        return view('components.categories.index', ['categories' => $categories]);
    }

    /**
     * Handles the display of workflows for a specific category.
     *
     * @param string               $category_slug The category of workflows to fetch, provided in slug format.
     * @param ElasticsearchService $es            The service used to query workflows from Elasticsearch.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Thrown with a 404 status if no workflows are found for the given category.
     * @return \Illuminate\View\View The view rendered with workflows data and metadata about the category.
     *
     */

    public function show(string $category_slug, ElasticsearchService $es)
    {
        $normalized = strtolower(str_replace(['/', ' '], '-', $category_slug));

        $category = $es->getCategoryBySlug($normalized);

        $workflows = $es->getWorkflowsByCategorySlug($normalized);

        abort_if(empty($workflows), 404);

//        dd(array_first($workflows));

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

        return view('components.categories.show', [
            'category'       => $category['category'],
            'slug'           => $category_slug,
            'workflows'      => $workflows,
            'totalTimeSaved' => $totalTimeSaved,
            'schemaItemListJson' => $schemaItemListJson
        ]);
    }
}
