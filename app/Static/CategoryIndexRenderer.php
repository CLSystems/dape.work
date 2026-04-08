<?php

namespace App\Static;

use App\Models\KnowledgeEntry;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

class CategoryIndexRenderer
{
    public function render(string $category): void
    {
        $entries = KnowledgeEntry::where('status', 'published')
            ->where('category', $category)
            ->orderBy('title')
            ->get();

        $html = View::make('static.category_index', [
            'entries' => $entries,
        ])->render();

        $path = public_path("/elasticsearch/{$category}/index.html");

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $html);
    }
}
