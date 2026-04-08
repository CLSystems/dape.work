<x-layouts.app>
    <x-slot:title>
        Top {{ count($workflows) }}
        {{ ucfirst($slug) }}
        {{ ucfirst($dimension) }} Automation Workflows | DAPE.work
    </x-slot:title>
    <x-slot:meta>
        <meta name="description" content="The highest-impact DevOps automation workflows for
            {{ $slug }}, ranked by estimated time saved.">
        @if(!empty($schemaItemListJson))
            <script type="application/ld+json">
                {!! $schemaItemListJson !!}
            </script>
        @endif
        @if(!empty($schemaFaqJson))
            <script type="application/ld+json">
                {!! $schemaFaqJson !!}
            </script>
        @endif
    </x-slot:meta>

    <div class="max-w-4xl mx-auto">
        <section>
            <h1 class="text-3xl font-bold mb-4">
                Top {{ count($workflows) }}
                {{ ucfirst($slug) }}
                {{ ucfirst($dimension) }} Workflows
            </h1>

            <p class="text-lg mb-6 text-gray-600 dark:text-gray-400">
                The highest-impact DevOps automation workflows for
                <strong>{{ $slug }}</strong>, ranked by estimated time saved.<br/>
                Total expected time saved across workflows:
                <strong>{{ $totalTimeSaved }} hours per week</strong>.
            </p>


            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <ol class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($workflows as $item)
                        @php $workflow = $item['_source']; @endphp
                        <li class="block px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                            <a href="/workflows/{{ $workflow['slug'] }}" class="text-blue-600 dark:text-blue-400 font-medium">
                                {{ $workflow['title'] }}
                            </a>
                            - {{ $workflow['expected_time_saved'] }} saved
                            - {{ implode(', ', $workflow['tool']) }}
                            - <a href="/complexity/{{ strtolower($workflow['complexity']) }}" class="text-blue-600 dark:text-blue-400 font-medium">
                                {{ $workflow['complexity'] }}
                            </a>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    </div>
</x-layouts.app>
