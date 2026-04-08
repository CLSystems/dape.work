<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKnowledgeEntryRequest;
use App\Http\Requests\UpdateKnowledgeEntryRequest;
use App\Models\KnowledgeEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KnowledgeEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $entries = KnowledgeEntry::latest()->paginate(10);

        return view('knowledge.index', compact('entries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('knowledge.create', [
            'entry' => new KnowledgeEntry(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKnowledgeEntryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $payload = [
            'description' => $data['description'],
            'steps' => $data['steps'],
            'tags' => $data['tags'] ?? [],
        ];

        unset($data['description'], $data['steps'], $data['tags']);

        $data['structured_payload'] = $payload;

        KnowledgeEntry::create($data);

        return to_route('knowledge.index')->with('status', __('Knowledge entry created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $knowledge = KnowledgeEntry::findOrFail($id);
        return view('knowledge.show', ['entry' => $knowledge]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $knowledge = KnowledgeEntry::findOrFail($id);
        return view('knowledge.edit', ['entry' => $knowledge]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKnowledgeEntryRequest $request, $id): RedirectResponse
    {
        $knowledge = KnowledgeEntry::findOrFail($id);
        $data = $request->validated();

        $payload = [
            'description' => $data['description'],
            'steps' => $data['steps'],
            'tags' => $data['tags'] ?? [],
        ];

        unset($data['description'], $data['steps'], $data['tags']);

        $data['structured_payload'] = $payload;

        $knowledge->update($data);

        return to_route('knowledge.index')->with('status', __('Knowledge entry updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $knowledge = KnowledgeEntry::findOrFail($id);
        $knowledge->delete();

        return to_route('knowledge.index')->with('status', __('Knowledge entry deleted successfully.'));
    }
}
