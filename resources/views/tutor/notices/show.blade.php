<x-tutor-layout title="{{ $notice->title }}">
<div class="space-y-6">
    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('tutor.notices.index') }}" class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-[#4B51FF] transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Notices
        </a>

        <!-- Navigation -->
        <div class="flex items-center gap-2">
            @if($previousNotice)
                <a href="{{ route('tutor.notices.show', $previousNotice) }}"
                   class="p-2 text-slate-500 hover:text-[#4B51FF] hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                   title="Previous Notice">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @else
                <span class="p-2 text-slate-300 dark:text-slate-600 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            @endif

            @if($nextNotice)
                <a href="{{ route('tutor.notices.show', $nextNotice) }}"
                   class="p-2 text-slate-500 hover:text-[#4B51FF] hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                   title="Next Notice">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="p-2 text-slate-300 dark:text-slate-600 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </div>
    </div>

    <!-- Notice Card -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <!-- Header with Priority Color -->
        <div class="px-6 py-4
            @if($notice->priority === 'urgent') bg-gradient-to-r from-red-500 to-rose-600
            @elseif($notice->priority === 'high') bg-gradient-to-r from-amber-500 to-orange-500
            @else bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF]
            @endif">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 flex-wrap mb-2">
                        @if($notice->priority === 'urgent')
                            <span class="px-2 py-0.5 text-xs font-semibold bg-white/20 text-white rounded animate-pulse">Urgent</span>
                        @elseif($notice->priority === 'high')
                            <span class="px-2 py-0.5 text-xs font-semibold bg-white/20 text-white rounded">High Priority</span>
                        @elseif($notice->priority === 'normal')
                            <span class="px-2 py-0.5 text-xs font-semibold bg-white/20 text-white rounded">Normal</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-semibold bg-white/20 text-white rounded">Low Priority</span>
                        @endif
                    </div>
                    <h1 class="text-2xl font-bold text-white">{{ $notice->title }}</h1>
                </div>
                <div class="flex-shrink-0">
                    @if($notice->priority === 'urgent')
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                    @else
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Meta Information -->
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
            <div class="flex flex-wrap items-center gap-6 text-sm">
                <!-- Posted By -->
                @if($notice->author)
                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Posted by <strong class="text-slate-900 dark:text-white">{{ $notice->author->name ?? 'Admin' }}</strong></span>
                    </div>
                @endif

                <!-- Published Date -->
                <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ $notice->published_at ? $notice->published_at->format('F d, Y \a\t g:i A') : $notice->created_at->format('F d, Y \a\t g:i A') }}</span>
                </div>

                <!-- Time Ago -->
                <div class="flex items-center gap-2 text-slate-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $notice->published_at ? $notice->published_at->diffForHumans() : $notice->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="prose prose-slate dark:prose-invert max-w-none">
                {!! nl2br(e($notice->content)) !!}
            </div>
        </div>
    </div>

    <!-- Quick Navigation Cards -->
    @if($previousNotice || $nextNotice)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Previous Notice -->
            @if($previousNotice)
                <a href="{{ route('tutor.notices.show', $previousNotice) }}" class="glass-card rounded-xl p-4 hover:shadow-lg transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 p-2 bg-slate-100 dark:bg-slate-700 rounded-lg group-hover:bg-[#4B51FF]/10 transition-colors">
                            <svg class="w-5 h-5 text-slate-500 group-hover:text-[#4B51FF] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-slate-500 uppercase tracking-wider">Previous Notice</p>
                            <p class="font-medium text-slate-900 dark:text-white truncate group-hover:text-[#4B51FF] transition-colors">
                                {{ $previousNotice->title }}
                            </p>
                        </div>
                    </div>
                </a>
            @else
                <div></div>
            @endif

            <!-- Next Notice -->
            @if($nextNotice)
                <a href="{{ route('tutor.notices.show', $nextNotice) }}" class="glass-card rounded-xl p-4 hover:shadow-lg transition-all group text-right">
                    <div class="flex items-center gap-3 justify-end">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-slate-500 uppercase tracking-wider">Next Notice</p>
                            <p class="font-medium text-slate-900 dark:text-white truncate group-hover:text-[#4B51FF] transition-colors">
                                {{ $nextNotice->title }}
                            </p>
                        </div>
                        <div class="flex-shrink-0 p-2 bg-slate-100 dark:bg-slate-700 rounded-lg group-hover:bg-[#4B51FF]/10 transition-colors">
                            <svg class="w-5 h-5 text-slate-500 group-hover:text-[#4B51FF] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            @else
                <div></div>
            @endif
        </div>
    @endif
</div>
</x-tutor-layout>
