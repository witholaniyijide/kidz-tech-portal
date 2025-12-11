<x-app-layout>
    <x-slot name="header">
        {{ __('Notice Board') }}
    </x-slot>
    <x-slot name="title">{{ __('Notices') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filter Section -->
            <x-ui.glass-card class="mb-8">
                <!-- Action Button -->
                <div class="flex justify-end mb-4">
                    <a href="{{ route('director.notices.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Create Notice
                    </a>
                </div>
                <form method="GET" action="{{ route('director.notices.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Title or content..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="w-36">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                        <select name="priority" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                    <div class="w-36">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all">Filter</button>
                        <a href="{{ route('director.notices.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-all">Reset</a>
                    </div>
                </form>
            </x-ui.glass-card>

            <!-- Notices List -->
            <div class="space-y-4">
                @forelse($notices as $notice)
                <x-ui.glass-card>
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $notice->title }}</h3>
                                @php
                                    $priorityColors = [
                                        'low' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                        'normal' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'high' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'urgent' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                    ];
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                        'published' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'archived' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$notice->priority] ?? $priorityColors['normal'] }}">
                                    {{ ucfirst($notice->priority) }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$notice->status] ?? $statusColors['draft'] }}">
                                    {{ ucfirst($notice->status) }}
                                </span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 line-clamp-2">{{ Str::limit(strip_tags($notice->content), 200) }}</p>
                            <div class="flex items-center gap-4 mt-3 text-sm text-gray-500 dark:text-gray-400">
                                <span>By {{ $notice->author->name ?? 'Unknown' }}</span>
                                <span>{{ $notice->created_at->format('M d, Y') }}</span>
                                @if($notice->visible_to)
                                    @php $visibleTo = is_array($notice->visible_to) ? $notice->visible_to : json_decode($notice->visible_to, true); @endphp
                                    <span>Visible to: {{ is_array($visibleTo) ? implode(', ', array_map('ucfirst', $visibleTo)) : ucfirst($visibleTo) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <a href="{{ route('director.notices.show', $notice) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400" title="View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('director.notices.edit', $notice) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <button 
                                type="button"
                                @click="$dispatch('open-delete-modal', { action: '{{ route('director.notices.destroy', $notice) }}', name: '{{ addslashes($notice->title) }}' })"
                                class="text-red-600 hover:text-red-900 dark:text-red-400" 
                                title="Delete"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </x-ui.glass-card>
                @empty
                <x-ui.glass-card>
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">No notices found</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Create your first notice to get started.</p>
                    </div>
                </x-ui.glass-card>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">{{ $notices->withQueryString()->links() }}</div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-ui.delete-modal 
        title="Delete Notice" 
        message="Are you sure you want to delete this notice? This action cannot be undone."
    />
</x-app-layout>
