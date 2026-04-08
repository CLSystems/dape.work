<x-layouts.app>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $entry->title }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Knowledge Entry Details') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('knowledge.edit', $entry) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Edit Entry') }}
            </a>
            <a href="{{ route('knowledge.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Back to List') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Description') }}</h2>
                <div class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">
                    {{ $entry->structured_payload['description'] ?? __('No description provided.') }}
                </div>
            </div>

            <!-- Steps -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Steps') }}</h2>
                <ol class="list-decimal list-inside space-y-2 text-gray-600 dark:text-gray-400">
                    @forelse($entry->structured_payload['steps'] ?? [] as $step)
                        <li>{{ $step }}</li>
                    @empty
                        <li class="list-none text-gray-500">{{ __('No steps provided.') }}</li>
                    @endforelse
                </ol>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Meta Information -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Details') }}</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Slug') }}</dt>
                        <dd class="text-sm text-gray-800 dark:text-gray-200">{{ $entry->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('System') }}</dt>
                        <dd class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ ucfirst($entry->system) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Category') }}</dt>
                        <dd class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                {{ ucfirst($entry->category) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                        <dd class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $entry->status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                {{ $entry->status === 'reviewed' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                {{ $entry->status === 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}
                            ">
                                {{ ucfirst($entry->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Version') }}</dt>
                        <dd class="text-sm text-gray-800 dark:text-gray-200">{{ $entry->version }}</dd>
                    </div>
                    @if($entry->last_verified_at)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Last Verified At') }}</dt>
                        <dd class="text-sm text-gray-800 dark:text-gray-200">{{ $entry->last_verified_at->format('M d, Y H:i') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Tags -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Tags') }}</h2>
                <div class="flex flex-wrap gap-2">
                    @forelse($entry->structured_payload['tags'] ?? [] as $tag)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                            {{ $tag }}
                        </span>
                    @empty
                        <span class="text-sm text-gray-500">{{ __('No tags.') }}</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
