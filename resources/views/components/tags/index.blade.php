<x-layouts.app>
    <x-slot:title>Automation Workflows by tag | DAPE.work</x-slot:title>
    <x-slot:meta>
        <meta name="description" content="Automation workflows by tag, including time saved, and automation maturity.">
    </x-slot:meta>


    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-4">Automation Workflows by Tag</h1>

        <section>
            <h2>Available Tags</h2>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($tags as $tag)
                        @php
                            $url = '/tags/' . strtolower(str_replace(['/', ' '], '-', $tag['key']));
                        @endphp

                        <li class="block px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                            <a href="{{ $url }}" class="text-blue-600 dark:text-blue-400 font-medium">
                                {{ $tag['key'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        <section>
            <h2>Common Categories Used</h2>
            <ul>
                @foreach (collect($tags)->pluck('categories')->flatten()->unique() as $category)
                    <li>
                        <a href="/categories/{{ str_replace('/', '-', strtolower($category)) }}">{{ $category }}</a>
                    </li>
                @endforeach
            </ul>
        </section>


    </div>

</x-layouts.app>
