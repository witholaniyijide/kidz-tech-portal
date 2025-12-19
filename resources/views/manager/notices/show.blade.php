<x-manager-layout title="View Notice">
    {{-- Back Link --}}
    <a href="{{ route('manager.notices.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-[#C15F3C] dark:hover:text-[#DA7756] mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Notice Board
    </a>

    <div class="max-w-4xl">
        {{-- Notice Card --}}
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl overflow-hidden shadow-sm">
            {{-- Header --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700
                @if($notice->priority === 'important') bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20
                @elseif($notice->priority === 'reminder') bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20
                @else bg-gradient-to-r from-[#C15F3C]/5 to-[#DA7756]/5 dark:from-[#C15F3C]/10 dark:to-[#DA7756]/10
                @endif">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4">
                        {{-- Author Avatar --}}
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r
                                @if($notice->priority === 'important') from-red-500 to-rose-500
                                @elseif($notice->priority === 'reminder') from-amber-500 to-orange-500
                                @else from-[#C15F3C] to-[#DA7756]
                                @endif
                                flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($notice->author->name ?? 'A', 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $notice->title }}</h1>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-gray-600 dark:text-gray-300 font-medium">{{ $notice->author->name ?? 'Unknown' }}</span>
                                <span class="text-gray-400">•</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    @if($notice->author && $notice->author->hasRole('director'))
                                        Director
                                    @elseif($notice->author && $notice->author->hasRole('admin'))
                                        Admin
                                    @elseif($notice->author && $notice->author->hasRole('manager'))
                                        Manager
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        {{-- Priority Badge --}}
                        <span class="px-3 py-1 text-sm rounded-full font-semibold
                            @if($notice->priority === 'important') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                            @elseif($notice->priority === 'reminder') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                            @else bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                            @endif">
                            {{ ucfirst($notice->priority) }}
                        </span>
                        {{-- Status Badge --}}
                        @if($notice->status === 'draft')
                            <span class="px-3 py-1 text-sm rounded-full font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                Draft
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-6">
                {{-- Meta Info --}}
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $notice->published_at ? $notice->published_at->format('F j, Y \a\t g:i A') : 'Not published' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span>Visible to:</span>
                        @foreach($notice->visible_to ?? [] as $role)
                            <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                {{ ucfirst($role) }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Notice Content --}}
                <div class="prose dark:prose-invert max-w-none">
                    {!! nl2br(e($notice->content)) !!}
                </div>
            </div>

            {{-- Actions Footer (if author) --}}
            @if($notice->posted_by === auth()->id())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('manager.notices.edit', $notice) }}"
                           class="inline-flex items-center px-4 py-2 bg-[#C15F3C]/10 dark:bg-[#C15F3C]/20 text-[#C15F3C] dark:text-[#DA7756] font-semibold rounded-xl hover:bg-[#C15F3C]/20 dark:hover:bg-[#C15F3C]/30 transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Notice
                        </a>
                        <form action="{{ route('manager.notices.destroy', $notice) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this notice? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 font-semibold rounded-xl hover:bg-red-200 dark:hover:bg-red-900/50 transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- Navigation --}}
        <div class="flex justify-between mt-6">
            @if($previousNotice ?? null)
                <a href="{{ route('manager.notices.show', $previousNotice) }}"
                   class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-[#C15F3C] dark:hover:text-[#DA7756]">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Previous Notice
                </a>
            @else
                <div></div>
            @endif
            @if($nextNotice ?? null)
                <a href="{{ route('manager.notices.show', $nextNotice) }}"
                   class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-[#C15F3C] dark:hover:text-[#DA7756]">
                    Next Notice
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @endif
        </div>
    </div>
</x-manager-layout>
