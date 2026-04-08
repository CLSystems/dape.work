<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Services\ElasticsearchService;

class SitemapController
{

    public function index()
    {
        $urls = [
            '/sitemap-workflows.xml',
            '/sitemap-categories.xml',
            '/sitemap-tools.xml',
            '/sitemap-tags.xml',
            '/sitemap-complexity.xml',
        ];

        $xml = view('components.sitemap.index', compact('urls'))->render();
        return $this->xmlResponse($xml);
    }

    public function workflows(ElasticsearchService $es)
    {
        $slugs = $es->getAllWorkflowSlugs();

        $xml = view('components.sitemap.urls', [
            'urls' => collect($slugs)->map(fn ($slug) => [
                'loc' => url('/workflows/' . $slug),
                'priority' => '0.9'
            ])
        ])->render();

        return $this->xmlResponse($xml);
    }

    public function categories(ElasticsearchService $es)
    {
        $slugs = $es->getAllCategories();

        $xml = view('components.sitemap.urls', [
            'urls' => collect($slugs)->map(fn ($slug) => [
                'loc' => url('/categories/' . strtolower(str_replace(['/', ' '], '-', $slug['key']))),
                'priority' => '0.9'
            ])
        ])->render();

        return $this->xmlResponse($xml);
    }

    public function tools(ElasticsearchService $es)
    {
        $tools = $es->getAllTools();

        $xml = view('components.sitemap.urls', [
            'urls' => collect($tools)->map(fn ($t) => [
                'loc' => url('/tools/' . strtolower($t['key'])),
                'priority' => '0.8'
            ])
        ])->render();

        return $this->xmlResponse($xml);
    }

    public function tags(ElasticsearchService $es)
    {
        $tools = $es->getAllTags();

        $xml = view('components.sitemap.urls', [
            'urls' => collect($tools)->map(fn ($t) => [
                'loc' => url('/tags/' . strtolower($t['key'])),
                'priority' => '0.8'
            ])
        ])->render();

        return $this->xmlResponse($xml);
    }

    public function complexity(ElasticsearchService $es)
    {
        $tools = $es->getAllComplexities();

        $xml = view('components.sitemap.urls', [
            'urls' => collect($tools)->map(fn ($t) => [
                'loc' => url('/complexity/' . strtolower($t['key'])),
                'priority' => '0.8'
            ])
        ])->render();

        return $this->xmlResponse($xml);
    }

    private function xmlResponse(string $xml): Response
    {
        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }
}
