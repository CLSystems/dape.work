<?php

namespace App\Http\Controllers;

use App\Services\ElasticsearchService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WorkflowController extends Controller
{
    public function show(string $slug, ElasticsearchService $es)
    {
        $workflow = $es->getWorkflowBySlug($slug);
        abort_if(!$workflow, 404);

        $schemaHowTo = null;

        if (!empty($workflow['steps']) && count($workflow['steps']) >= 2) {
            $schemaHowTo = [
                '@context' => 'https://schema.org',
                '@type' => 'HowTo',
                'name' => $workflow['title'],
                'description' => $workflow['description'],
                'totalTime' => 'PT' . max(15, $workflow['expected_time_saved_numeric'] * 10) . 'M',
                'step' => array_map(
                    fn ($step, $i) => [
                        '@type' => 'HowToStep',
                        'position' => $i + 1,
                        'name' => $step,
                        'text' => $step
                    ],
                    $workflow['steps'],
                    array_keys($workflow['steps'])
                )
            ];
        }

        return view('components.workflows.show', [
            'workflow'    => $workflow,
            'schemaHowTo' => json_encode($schemaHowTo)
        ]);
    }
}
