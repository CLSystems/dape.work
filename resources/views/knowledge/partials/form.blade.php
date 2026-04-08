<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title') }}</label>
            <input type="text" name="title" id="title" value="{{ old('title', $entry->title) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
            @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Slug -->
        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Slug') }}</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $entry->slug) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
            @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- System -->
        <div>
            <label for="system" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('System') }}</label>
            <select name="system" id="system" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                <option value="elasticsearch" {{ old('system', $entry->system) === 'elasticsearch' ? 'selected' : '' }}>Elasticsearch</option>
                <option value="kibana" {{ old('system', $entry->system) === 'kibana' ? 'selected' : '' }}>Kibana</option>
                <option value="logstash" {{ old('system', $entry->system) === 'logstash' ? 'selected' : '' }}>Logstash</option>
                <option value="zabbix" {{ old('system', $entry->system) === 'zabbix' ? 'selected' : '' }}>Zabbix</option>
                <option value="docker" {{ old('system', $entry->system) === 'docker' ? 'selected' : '' }}>Docker</option>
            </select>
            @error('system') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Category -->
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Category') }}</label>
            <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                <option value="error" {{ old('category', $entry->category) === 'error' ? 'selected' : '' }}>Error</option>
                <option value="alert" {{ old('category', $entry->category) === 'alert' ? 'selected' : '' }}>Alert</option>
                <option value="performance" {{ old('category', $entry->category) === 'performance' ? 'selected' : '' }}>Performance</option>
            </select>
            @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                <option value="draft" {{ old('status', $entry->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="reviewed" {{ old('status', $entry->status) === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                <option value="published" {{ old('status', $entry->status) === 'published' ? 'selected' : '' }}>Published</option>
            </select>
            @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Version -->
        <div>
            <label for="version" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Version') }}</label>
            <input type="text" name="version" id="version" value="{{ old('version', $entry->version ?? '1.0') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
            @error('version') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Last Verified At -->
        <div>
            <label for="last_verified_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Last Verified At') }}</label>
            <input type="datetime-local" name="last_verified_at" id="last_verified_at" value="{{ old('last_verified_at', $entry->last_verified_at?->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            @error('last_verified_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Description -->
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
        <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>{{ old('description', $entry->structured_payload['description'] ?? '') }}</textarea>
        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Steps -->
    <div x-data="{ steps: {{ json_encode(old('steps', $entry->structured_payload['steps'] ?? [''])) }} }">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Steps') }}</label>
        <template x-for="(step, index) in steps" :key="index">
            <div class="flex mt-2">
                <input type="text" :name="'steps[' + index + ']'" x-model="steps[index]" class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                <button type="button" @click="steps.splice(index, 1)" class="ml-2 text-red-600 hover:text-red-900" x-show="steps.length > 1">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </template>
        <button type="button" @click="steps.push('')" class="mt-2 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ __('Add Step') }}
        </button>
        @error('steps.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Tags -->
    <div x-data="{ tags: {{ json_encode(old('tags', $entry->structured_payload['tags'] ?? [''])) }} }">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tags') }}</label>
        <div class="flex flex-wrap gap-2 mt-2">
            <template x-for="(tag, index) in tags" :key="index">
                <div class="flex items-center">
                    <input type="text" :name="'tags[' + index + ']'" x-model="tags[index]" class="block w-24 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <button type="button" @click="tags.splice(index, 1)" class="ml-1 text-red-600 hover:text-red-900">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>
            <button type="button" @click="tags.push('')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Add Tag') }}
            </button>
        </div>
        @error('tags.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
