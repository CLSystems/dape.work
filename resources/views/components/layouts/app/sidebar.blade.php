            <aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
                class="bg-sidebar text-sidebar-foreground border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden">
                <!-- Sidebar Content -->
                <div class="h-full flex flex-col">
                    <!-- Sidebar Menu -->
                    <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">
                        <ul class="space-y-1 px-2">
                            <!-- Dashboard -->
                            @auth
                            <x-layouts.sidebar-link href="{{ route('dashboard') }}" icon='fas-house'
                                :active="request()->routeIs('dashboard*')">Dashboard</x-layouts.sidebar-link>

                            <!-- Knowledge Base -->
                            <x-layouts.sidebar-link href="{{ route('knowledge.index') }}" icon='fas-book'
                                :active="request()->routeIs('knowledge*')">Knowledge Items</x-layouts.sidebar-link>
                            @endauth

                            <x-layouts.sidebar-link href="{{ route('home') }}" icon='fas-house'
                                                    :active="request()->routeIs('home')">Home</x-layouts.sidebar-link>

                            <x-layouts.sidebar-link href="{{ route('categories.index') }}" icon='fas-folder'
                                                    :active="request()->routeIs('categories.*')">Categories</x-layouts.sidebar-link>

                            <x-layouts.sidebar-link href="{{ route('tags.index') }}" icon='fas-tags'
                                                    :active="request()->routeIs('tags.*')">Tags</x-layouts.sidebar-link>

                            <x-layouts.sidebar-link href="{{ route('tools.index') }}" icon='fas-tools'
                                                    :active="request()->routeIs('tools.*')">Tools</x-layouts.sidebar-link>

                            <x-layouts.sidebar-two-level-link-parent title="Complexity" icon="fas-chart-line"
                                                                    :active="request()->routeIs('complexity.*')">
                                <x-layouts.sidebar-two-level-link href="{{ route('complexity.show', 'beginner') }}" icon='fas-signal'
                                                                  :active="request()->fullUrlIs(route('complexity.show', 'beginner'))">Beginner</x-layouts.sidebar-two-level-link>
                                <x-layouts.sidebar-two-level-link href="{{ route('complexity.show', 'intermediate') }}" icon='fas-signal'
                                                                  :active="request()->fullUrlIs(route('complexity.show', 'intermediate'))">Intermediate</x-layouts.sidebar-two-level-link>
                                <x-layouts.sidebar-two-level-link href="{{ route('complexity.show', 'advanced') }}" icon='fas-signal'
                                                                  :active="request()->fullUrlIs(route('complexity.show', 'advanced'))">Advanced</x-layouts.sidebar-two-level-link>
                            </x-layouts.sidebar-two-level-link-parent>

                            <x-layouts.sidebar-two-level-link-parent title="Elasticsearch" icon="fas-house"
                                :active="request()->routeIs('es-errors*')">
                                <x-layouts.sidebar-two-level-link href="{{ route('es-errors') }}" icon='fas-house'
                                    :active="request()->routeIs('es-errors')">Errors</x-layouts.sidebar-two-level-link>
                            </x-layouts.sidebar-two-level-link-parent>
                        </ul>
                    </nav>
                </div>
            </aside>
