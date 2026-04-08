<x-layouts.app>
    <x-slot:title>{{ $workflow['title'] }} | DAPE.work</x-slot:title>
    <x-slot:meta>
        <meta name="description" content="{{ $workflow['ai_summary'] }}">

        @if($schemaHowTo)
            <script type="application/ld+json">
                {!! $schemaHowTo !!}
            </script>
        @endif
    </x-slot:meta>

    <h1>{{ $workflow['title'] }}</h1>

    <p>{{ $workflow['description'] }}</p>

    <p>
        <strong>Expected time saved:</strong>
        {{ $workflow['expected_time_saved'] }}
    </p>

    <p>
        <strong>Complexity:</strong>
        <a href="/complexity/{{ strtolower($workflow['complexity']) }}" class="px-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out text-blue-600 dark:text-blue-400 font-medium">
            {{ $workflow['complexity'] }}
        </a>
    </p>

    <p>
        <strong>Steps:</strong>
        <ol>
        @foreach($workflow['steps'] as $key => $step)
            <li>{{ ($key+1) }} - {{$step}}</li>
        @endforeach
        </ol>
    </p>

    <p>
        <strong>Tools:</strong><br/>
        @foreach ($workflow['tool'] as $tool)
            <a href="/tools/{{ strtolower($tool) }}" class="px-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out text-blue-600 dark:text-blue-400 font-medium">{{ ucfirst($tool) }}</a><br/>
        @endforeach
    </p>

    @if (!empty($workflow['example_code_snippets']))
        <h2>Example</h2>
        @foreach($workflow['example_code_snippets'] as $snippet)
            <pre>{{ $snippet }}</pre>
        @endforeach
    @endif

    @if (!empty($workflow['tags']))
        <h3>Tags</h3>
        @foreach ($workflow['tags'] as $tag)
            <a href="/tags/{{ $tag }}" class="px-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out text-blue-600 dark:text-blue-400 font-medium">{{ $tag }}</a>
        @endforeach
    @endif



</x-layouts.app>
