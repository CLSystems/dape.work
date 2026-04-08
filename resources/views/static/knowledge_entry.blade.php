<x-layouts.app>
    <x-slot:title>{{ $entry->title }}</x-slot:title>
    <x-slot:meta>
        <meta name="description"
              content="{{ \Illuminate\Support\Str::limit($payload['problem'], 155) }}">

        <link rel="canonical"
              href="{{ url("/{$entry->system}/{$entry->category}/{$entry->slug}") }}">

        {{-- OpenGraph --}}
        <meta property="og:title" content="{{ $entry->title }}">
        <meta property="og:description" content="{{ $payload['problem'] }}">
        <meta property="og:type" content="article">

        {{-- Structured Data --}}
        <script type="application/ld+json">
            {!! $techArticleJson !!}
        </script>

        @if($howToJson)
            <script type="application/ld+json">
                {!! $howToJson !!}
            </script>
        @endif
    </x-slot:meta>

    <div class="max-w-4xl mx-auto">
        <header class="mb-8">
            <h1 class="text-3xl font-bold mb-4">{{ $entry->title }}</h1>

            <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                <div>
                    <span class="font-semibold">Severity:</span>
                    <span class="px-2 py-0.5 rounded-full {{ $payload['severity'] === 'critical' ? 'bg-red-100 text-red-800' : ($payload['severity'] === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                        {{ ucfirst($payload['severity']) }}
                    </span>
                </div>
                <div>
                    <span class="font-semibold">Elasticsearch Version:</span>
                    {{ $payload['elasticsearch_version'] }}
                </div>
            </div>
        </header>

        <div class="space-y-8 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <section>
                <h2 class="text-xl font-bold mb-3 border-b pb-2 dark:border-gray-700">Problem</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $payload['problem'] }}</p>
            </section>

            <section>
                <h2 class="text-xl font-bold mb-3 border-b pb-2 dark:border-gray-700">Root Cause</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $payload['root_cause'] }}</p>
            </section>

            <section>
                <h2 class="text-xl font-bold mb-3 border-b pb-2 dark:border-gray-700">How to Detect</h2>

                <div class="mb-4">
                    <h3 class="text-lg font-semibold mb-2">Symptoms</h3>
                    <ul class="list-disc list-inside space-y-1 text-gray-700 dark:text-gray-300">
                        @foreach ($payload['detection']['symptoms'] as $symptom)
                            <li>{{ $symptom }}</li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-2">Commands</h3>
                    <div class="bg-gray-900 rounded-md p-4 overflow-x-auto">
                        <pre class="text-sm text-gray-100"><code>{{ implode("\n", $payload['detection']['commands']) }}</code></pre>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold mb-3 border-b pb-2 dark:border-gray-700">Remediation Steps</h2>
                <ol class="list-decimal list-inside space-y-2 text-gray-700 dark:text-gray-300">
                    @foreach ($payload['remediation']['steps'] as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ol>

                @if(($payload['monetization']['placement'] ?? '') === 'after_remediation')
                    <div class="mt-6">
                        @include('static.partials.monetization', ['block' => $payload['monetization']])
                    </div>
                @endif
            </section>

            <section>
                <h2 class="text-xl font-bold mb-3 border-b pb-2 dark:border-gray-700">Prevention</h2>
                <ul class="list-disc list-inside space-y-1 text-gray-700 dark:text-gray-300">
                    @foreach ($payload['prevention'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold mb-3 border-b pb-2 dark:border-gray-700">Production Example</h2>
                <div class="bg-gray-900 rounded-md p-4 overflow-x-auto">
                    <pre class="text-sm text-gray-100"><code>{{ $payload['production_example']['curl'] }}</code></pre>
                </div>
            </section>
        </div>

        <div class="mt-8 pb-6">
            <a href="{{ url('/elasticsearch/errors') }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                View all Elasticsearch errors
            </a>
        </div>
    </div>
</x-layouts.app>
