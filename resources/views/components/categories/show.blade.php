<x-layouts.app>
    <x-slot:title>{{ $category }} Automation Workflows | DAPE.work</x-slot:title>
    <x-slot:meta>
        <meta name="description" content="Automation workflows for {{ $category }} including tools, time saved, and automation maturity.">
        @if(!empty($schemaItemListJson))
            <script type="application/ld+json">
                {!! $schemaItemListJson !!}
            </script>
        @endif
    </x-slot:meta>


    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-4">{{ $category }} Automation Workflows</h1>

        <p class="text-lg mb-6 text-gray-600 dark:text-gray-400">
            This category contains automation workflows focused on
            <strong>{{ $category }}</strong>.
            Total expected time saved across workflows:
            <strong>{{ $totalTimeSaved }} hours per week</strong>.
        </p>

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
            <h2>Common Tools Used</h2>
            <ul>
                @foreach (collect($workflows)->pluck('tools')->flatten()->unique() as $tool)
                    <li>
                        <a href="/tools/{{ strtolower($tool) }}">{{ $tool }}</a>
                    </li>
                @endforeach
            </ul>
        </section>


    </div>

</x-layouts.app>
