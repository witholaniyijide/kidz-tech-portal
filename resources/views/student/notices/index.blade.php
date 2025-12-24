<x-student-layout title="Notices">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notices</h1>
                <p class="text-gray-600 dark:text-gray-400">Important announcements and updates</p>
            </div>

            @if($types->count() > 0)
                <form method="GET" action="{{ route('student.notices.index') }}">
                    <select name="type" onchange="this.form.submit()"
                            class="rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-[#F5A623] focus:border-[#F5A623]">
                        <option value="">All Types</option>
                        @foreach($types as $t)
                            <option value="{{ $t }}" {{ $type === $t ? 'selected' : '' }}>
                                {{ ucfirst($t) }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>

        <!-- Notices List -->
        @if($notices->count() > 0)
            <div class="space-y-4">
                @foreach($notices as $notice)
                    <a href="{{ route('student.notices.show', $notice) }}"
                       class="block glass-card rounded-2xl p-5 hover-lift transition-all {{ $notice->is_pinned ? 'border-l-4 border-[#F5A623]' : '' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 rounded-xl {{ $notice->type === 'urgent' ? 'bg-red-100 dark:bg-red-900/30' : ($notice->type === 'warning' ? 'bg-yellow-100 dark:bg-yellow-900/30' : 'bg-gradient-student') }} flex items-center justify-center flex-shrink-0">
                                    @if($notice->type === 'urgent')
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    @elseif($notice->type === 'warning')
                                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $notice->title }}</h3>
                                        @if($notice->is_pinned)
                                            <span class="px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 rounded-full">
                                                Pinned
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                        {{ Str::limit(strip_tags($notice->content), 150) }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                        {{ $notice->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $notices->withQueryString()->links() }}
            </div>
        @else
            <div class="glass-card rounded-2xl p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No notices</h3>
                <p class="text-gray-500 dark:text-gray-400">There are no announcements at this time.</p>
            </div>
        @endif
    </div>
</x-student-layout>
