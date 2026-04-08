<x-layouts.app>
    <x-slot:title>Automation Workflows by category | DAPE.work</x-slot:title>
    <x-slot:meta>
        <meta name="description" content="Automation workflows by category, including tools, time saved, and automation maturity.">
    </x-slot:meta>


    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-4">Automation Workflows by Category</h1>

        <section>
        <h2>Available Categories</h2>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($categories as $category)
                    @php
                        // $category = $item['_source'];
                        $normalized = strtolower(str_replace(' ', '-', $category['key']));
                        $normalized = strtolower(str_replace('/', '', $normalized));
                        $url = '/categories/' . $normalized;
                    @endphp

                    <li>
                        <a href="{{ $url }}" class="block px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out text-blue-600 dark:text-blue-400 font-medium">
                            {{ $category['key'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        </section>

    </div>

</x-layouts.app>
