<x-layouts.app>
    <x-slot:title>
        {{ $tool }} Automation Workflows | DAPE.work
    </x-slot:title>
    <x-slot:meta>
        <meta name="description" content="Automation workflows for {{ $tool }} including tools, time saved, and automation maturity.">
        @if(!empty($schemaItemListJson))
            <script type="application/ld+json">
                {!! $schemaItemListJson !!}
            </script>
        @endif
    </x-slot:meta>


    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-4">{{ ucfirst($tool) }} Automation Workflows</h1>

        <p class="text-lg mb-6 text-gray-600 dark:text-gray-400">
            This page contains automation workflows built using
            <strong>{{ ucfirst($tool) }}</strong>.<br/>
            Total expected time saved across workflows:
            <strong>{{ $totalTimeSaved }} hours per week</strong>.
        </p>

        <section class="ai-summary">
            <p>{{ json_decode($summary)->summary }}</p>
            <p>&nbsp;</p>
        </section>

        <section>
            <h2>Available Workflows</h2>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($workflows as $item)
                        @php
                            $workflow = $item['_source'];
                            $url = '/workflows/' . ($workflow['slug']);
                        @endphp

                        <li class="block px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                            <a href="{{ $url }}" class="text-blue-600 dark:text-blue-400 font-medium">
                                {{ $workflow['title'] }}
                            </a>
                            - {{ $workflow['expected_time_saved'] }} saved
                            - <a href="/complexity/{{ strtolower($workflow['complexity']) }}" class="text-blue-600 dark:text-blue-400 font-medium">
                                {{ $workflow['complexity'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        <section>
            <h2>Common Categories Used</h2>
            <ul>
                @foreach (collect($workflows)->pluck('_source.categories')->flatten()->unique() as $category)
                    <li>
                        <a href="/categories/{{ str_replace('/', '-', strtolower($category)) }}">{{ $category }}</a>
                    </li>
                @endforeach
            </ul>
        </section>


    </div>

</x-layouts.app>
