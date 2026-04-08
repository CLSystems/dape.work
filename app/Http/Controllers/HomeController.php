<?php

namespace App\Http\Controllers;

use App\Services\ElasticsearchService;

class HomeController extends Controller
{
    public function index(ElasticsearchService $es)
    {
        $categories = $es->getAllCategories();
        $tools = $es->getAllTools();
        $complexities = $es->getAllComplexities();
        $totalWorkflows = $es->getTotalWorkflowsCount();

        return view('home', compact('categories', 'tools', 'complexities', 'totalWorkflows'));
    }
}
