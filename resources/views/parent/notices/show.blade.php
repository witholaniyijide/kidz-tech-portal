<x-parent-layout title="{{ $notice->title }}">
    <div class="space-y-6">
        <!-- Back Button -->
        <div>
            <a href="{{ route('parent.notices.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Notices
            </a>
        </div>

        <!-- Notice Card -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <!-- Header -->
            <div class="p-6 {{ $notice->type === 'urgent' ? 'bg-red-50 dark:bg-red-900/20 border-b-2 border-red-500' : ($notice->type === 'warning' ? 'bg-yellow-50 dark:bg-yellow-900/20 border-b-2 border-yellow-500' : 'bg-gradient-parent') }}">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap mb-2">
                            @if($notice->is_pinned)
                                <span class="px-2 py-0.5 text-xs font-medium bg-white/20 text-white rounded-full">
                                    Pinned
                                </span>
                            @endif
                            @if($notice->type)
                                <span class="px-2 py-0.5 text-xs font-medium bg-white/20 {{ $notice->type === 'urgent' ? 'text-red-800 dark:text-red-200' : ($notice->type === 'warning' ? 'text-yellow-800 dark:text-yellow-200' : 'text-white') }} rounded-full">
                                    {{ ucfirst($notice->type) }}
                                </span>
                            @endif
                        </div>
                        <h1 class="text-2xl font-bold {{ $notice->type === 'urgent' ? 'text-red-800 dark:text-red-200' : ($notice->type === 'warning' ? 'text-yellow-800 dark:text-yellow-200' : 'text-white') }}">
                            {{ $notice->title }}
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Meta Info -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center gap-6 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Posted: {{ $notice->created_at->format('F j, Y') }}
                    </div>
                    @if($notice->expires_at)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Expires: {{ $notice->expires_at->format('F j, Y') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="prose prose-gray dark:prose-invert max-w-none">
                    {!! nl2br(e($notice->content)) !!}
                </div>
            </div>

            @if($notice->attachments && count($notice->attachments) > 0)
                <div class="px-6 pb-6">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Attachments</h3>
                    <div class="space-y-2">
                        @foreach($notice->attachments as $attachment)
                            <a href="{{ asset('storage/' . $attachment) }}"
                               target="_blank"
                               class="flex items-center p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ basename($attachment) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-parent-layout>
