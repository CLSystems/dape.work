<x-layouts.app>
    <x-slot:title>Elasticsearch Errors & Fixes</x-slot:title>
    <x-slot:meta>
        <meta name="description" content="Elasticsearch error explanations, root causes, and production-ready fixes.">
        <link rel="canonical" href="{{ url('/elasticsearch/errors') }}">
    </x-slot:meta>

    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-4">Elasticsearch Errors</h1>

        <p class="text-lg mb-6 text-gray-600 dark:text-gray-400">
            Production-grade explanations and remediation steps for common Elasticsearch errors.
        </p>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($entries as $entry)
                    <li>
                        <a href="{{ url("/{$entry->system}/{$entry->category}/{$entry->slug}.html") }}"
                           class="block px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out text-blue-600 dark:text-blue-400 font-medium">
                            {{ $entry->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-layouts.app>
