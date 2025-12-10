<x-tutor-layout title="Notice Board">
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Notice Board</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">Stay updated with important announcements</p>
        </div>
        @if($unreadCount > 0)
            <div class="flex items-center gap-2 px-4 py-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="font-medium">{{ $unreadCount }} unread {{ Str::plural('notice', $unreadCount) }}</span>
            </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-2xl p-4">
        <form method="GET" action="{{ route('tutor.notices.index') }}" class="flex flex-wrap items-center gap-4">
            <!-- Search -->
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search notices..."
                           class="w-full pl-10 pr-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                </div>
            </div>

            <!-- Priority Filter -->
            <div class="flex items-center gap-2">
                <label for="priority" class="text-sm font-medium text-slate-700 dark:text-slate-300">Priority:</label>
                <select id="priority" name="priority" onchange="this.form.submit()" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF]">
                    <option value="">All</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF] text-white rounded-xl hover:opacity-90 transition-opacity">
                Search
            </button>

            @if(request('search') || request('priority'))
                <a href="{{ route('tutor.notices.index') }}" class="text-sm text-slate-500 hover:text-[#4B51FF]">Clear filters</a>
            @endif
        </form>
    </div>

    <!-- Notices List -->
    @if($notices->isEmpty())
        <div class="glass-card rounded-2xl p-12 text-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">No Notices</h3>
            <p class="text-slate-500 dark:text-slate-400">
                @if(request('search') || request('priority'))
                    No notices match your search criteria.
                @else
                    There are no notices at this time.
                @endif
            </p>
        </div>
    @else
        <div class="space-y-4">
            @php
                $readNoticeIds = session('read_notices', []);
            @endphp
            @foreach($notices as $notice)
                @php
                    $isRead = in_array($notice->id, $readNoticeIds);
                @endphp
                <a href="{{ route('tutor.notices.show', $notice) }}"
                   class="block glass-card rounded-xl p-5 hover:shadow-lg transition-all group {{ !$isRead ? 'border-l-4 border-[#4B51FF]' : '' }}">
                    <div class="flex items-start gap-4">
                        <!-- Priority Indicator -->
                        <div class="flex-shrink-0">
                            @if($notice->priority === 'urgent')
                                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                            @elseif($notice->priority === 'high')
                                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @elseif($notice->priority === 'normal')
                                <div class="w-12 h-12 bg-gradient-to-br from-[#1D2A6D] to-[#4B51FF] rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-slate-400 to-slate-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Notice Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-2">
                                <!-- Priority Badge -->
                                @if($notice->priority === 'urgent')
                                    <span class="px-2 py-0.5 text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded animate-pulse">Urgent</span>
                                @elseif($notice->priority === 'high')
                                    <span class="px-2 py-0.5 text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 rounded">High Priority</span>
                                @endif

                                <!-- Unread Badge -->
                                @if(!$isRead)
                                    <span class="px-2 py-0.5 text-xs font-semibold bg-[#4B51FF]/10 text-[#4B51FF] dark:bg-[#4B51FF]/20 rounded">New</span>
                                @endif

                                <!-- Title -->
                                <h3 class="font-semibold text-slate-900 dark:text-white group-hover:text-[#4B51FF] transition-colors truncate">
                                    {{ $notice->title }}
                                </h3>
                            </div>

                            <!-- Excerpt -->
                            <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2 mb-3">
                                {{ Str::limit(strip_tags($notice->content), 150) }}
                            </p>

                            <!-- Meta Info -->
                            <div class="flex items-center gap-4 text-xs text-slate-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $notice->published_at ? $notice->published_at->format('M d, Y') : $notice->created_at->format('M d, Y') }}
                                </span>
                                @if($notice->author)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $notice->author->name ?? 'Admin' }}
                                    </span>
                                @endif
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $notice->published_at ? $notice->published_at->diffForHumans() : $notice->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <!-- Arrow -->
                        <div class="flex-shrink-0 self-center">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-[#4B51FF] group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $notices->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
</x-tutor-layout>
