@props([
    'notices' => []
])

<x-ui.glass-card>
    <div class="flex items-center justify-between mb-6">
        <x-ui.section-title>Notice Board</x-ui.section-title>
        <x-ui.gradient-button
            href="{{ route('noticeboard.create') }}"
            gradient="bg-gradient-manager"
            aria-label="Post New Notice"
            class="text-sm px-4 py-2"
        >
            <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Post New Notice
        </x-ui.gradient-button>
    </div>

    @if(count($notices) > 0)
        <div class="space-y-3 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
            @foreach($notices as $notice)
            <div class="p-4 rounded-xl bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border-l-4 {{ $notice['priority'] ?? 'normal' === 'high' ? 'border-amber-500' : 'border-sky-500' }} hover:bg-white/30 dark:hover:bg-gray-900/40 transition-all duration-200">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <h4 class="font-semibold text-gray-900 dark:text-white flex-1">{{ $notice['title'] ?? 'Notice' }}</h4>
                    @if(isset($notice['priority']) && $notice['priority'] === 'high')
                        <span class="px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-600 dark:text-amber-400 text-xs font-medium">
                            Urgent
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-2">{{ $notice['content'] ?? 'Notice content' }}</p>
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-500">
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ $notice['author'] ?? 'Admin' }}
                    </span>
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $notice['date'] ?? 'Today' }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 pt-4 border-t border-white/10 dark:border-gray-700/10">
            <a
                href="{{ route('noticeboard.index') }}"
                class="text-sm text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 font-medium inline-flex items-center gap-1 transition-colors"
                aria-label="View all notices"
            >
                View All Notices
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    @else
        <div class="py-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <p class="text-gray-600 dark:text-gray-400 mb-4">No notices available at the moment.</p>
        </div>
    @endif
</x-ui.glass-card>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #0ea5e9, #38bdf8);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #0284c7, #0ea5e9);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
