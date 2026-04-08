<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Knowledge Entry') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Update the details of your knowledge entry') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <form action="{{ route('knowledge.update', $entry) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            @include('knowledge.partials.form')

            <div class="mt-8 flex items-center justify-end">
                <a href="{{ route('knowledge.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 mr-4">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Update Entry') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
