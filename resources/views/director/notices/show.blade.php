<x-app-layout>
    <x-slot name="header">
        {{ __('View Notice') }}
    </x-slot>
    <x-slot name="title">{{ $notice->title }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mb-4">
                <a href="{{ route('director.notices.edit', $notice) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <a href="{{ route('director.notices.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back
                </a>
            </div>
            <x-ui.glass-card>
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-4">
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
                            {{ ucfirst($notice->priority) }} Priority
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$notice->status] ?? $statusColors['draft'] }}">
                            {{ ucfirst($notice->status) }}
                        </span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $notice->title }}</h1>
                </div>

                <div class="prose dark:prose-invert max-w-none mb-6">
                    {!! nl2br(e($notice->content)) !!}
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Author</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $notice->author->name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Created</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $notice->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Visible To</p>
                            @php $visibleTo = is_array($notice->visible_to) ? $notice->visible_to : json_decode($notice->visible_to, true); @endphp
                            <p class="font-medium text-gray-900 dark:text-white">{{ is_array($visibleTo) ? implode(', ', array_map('ucfirst', $visibleTo)) : ucfirst($visibleTo ?? 'All') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Last Updated</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $notice->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </x-ui.glass-card>
        </div>
    </div>
</x-app-layout>
