<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Notice Board') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Notice Board') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Announcements & Notices</h1>
                    <p class="text-gray-500 dark:text-gray-400">Internal communications for tutors and staff</p>
                </div>
                <a href="{{ route('manager.notices.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Post Announcement
                </a>
            </div>

            {{-- Quick Stats --}}
            <div class="grid grid-cols-4 gap-4 mb-8">
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 backdrop-blur-xl border border-red-200 dark:border-red-800/30 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['important'] }}</p>
                    <p class="text-xs text-red-600 dark:text-red-400">Important</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 backdrop-blur-xl border border-blue-200 dark:border-blue-800/30 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['general'] }}</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400">General</p>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/20 backdrop-blur-xl border border-amber-200 dark:border-amber-800/30 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['reminder'] }}</p>
                    <p class="text-xs text-amber-600 dark:text-amber-400">Reminders</p>
                </div>
            </div>

            {{-- Filter --}}
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-xl p-4 mb-6">
                <form action="{{ route('manager.notices.index') }}" method="GET" class="flex flex-wrap gap-4 items-center">
                    <div class="flex-1 min-w-[150px]">
                        <select name="priority" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                            <option value="">All Priorities</option>
                            <option value="important" {{ request('priority') == 'important' ? 'selected' : '' }}>🔴 Important</option>
                            <option value="general" {{ request('priority') == 'general' ? 'selected' : '' }}>🔵 General</option>
                            <option value="reminder" {{ request('priority') == 'reminder' ? 'selected' : '' }}>🟡 Reminder</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                            <option value="">All Status</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 font-medium rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900/50 transition-all text-sm">
                        Filter
                    </button>
                    <a href="{{ route('manager.notices.index') }}" class="px-4 py-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 text-sm">
                        Reset
                    </a>
                </form>
            </div>

            {{-- Forum/Chat Style Notices --}}
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden">
                <div class="p-4 border-b border-white/10 bg-gradient-to-r from-emerald-500/10 to-teal-500/10">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        <span class="font-semibold text-gray-900 dark:text-white">Announcement Feed</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">For Directors, Admins, Managers & Tutors</span>
                    </div>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($notices as $notice)
                        <div class="p-6 hover:bg-white/30 dark:hover:bg-gray-800/20 transition-colors">
                            <div class="flex gap-4">
                                {{-- Author Avatar --}}
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r 
                                        @if($notice->priority === 'important') from-red-500 to-rose-500
                                        @elseif($notice->priority === 'reminder') from-amber-500 to-orange-500
                                        @else from-blue-500 to-indigo-500
                                        @endif 
                                        flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($notice->author->name ?? 'A', 0, 1)) }}
                                    </div>
                                </div>

                                {{-- Notice Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $notice->author->name ?? 'Unknown' }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
                                                @if($notice->author && $notice->author->hasRole('director'))
                                                    Director
                                                @elseif($notice->author && $notice->author->hasRole('admin'))
                                                    Admin
                                                @elseif($notice->author && $notice->author->hasRole('manager'))
                                                    Manager
                                                @endif
                                            </span>
                                            <span class="text-xs text-gray-400 dark:text-gray-500 ml-2">
                                                {{ $notice->published_at ? $notice->published_at->diffForHumans() : 'Draft' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            {{-- Priority Badge --}}
                                            <span class="px-2 py-0.5 text-xs rounded-full font-medium
                                                @if($notice->priority === 'important') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                                @elseif($notice->priority === 'reminder') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                                                @else bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                                @endif">
                                                {{ ucfirst($notice->priority) }}
                                            </span>
                                            {{-- Status Badge --}}
                                            @if($notice->status === 'draft')
                                                <span class="px-2 py-0.5 text-xs rounded-full font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                                    Draft
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Title --}}
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $notice->title }}</h3>

                                    {{-- Content Preview --}}
                                    <div class="text-gray-600 dark:text-gray-300 text-sm mb-3 prose dark:prose-invert max-w-none">
                                        {!! Str::limit(strip_tags($notice->content), 300) !!}
                                    </div>

                                    {{-- Visible To Tags --}}
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="text-xs text-gray-400 dark:text-gray-500">Visible to:</span>
                                        @foreach($notice->visible_to ?? [] as $role)
                                            <span class="px-2 py-0.5 text-xs rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                                {{ ucfirst($role) }}
                                            </span>
                                        @endforeach
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-4">
                                        <a href="{{ route('manager.notices.show', $notice) }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium">
                                            Read More →
                                        </a>
                                        @if($notice->posted_by === auth()->id())
                                            <a href="{{ route('manager.notices.edit', $notice) }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                Edit
                                            </a>
                                            <form action="{{ route('manager.notices.destroy', $notice) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this notice?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-500 hover:text-red-700">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">No announcements yet</p>
                            <a href="{{ route('manager.notices.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-medium rounded-lg hover:from-emerald-600 hover:to-teal-600 transition-all">
                                Post First Announcement
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($notices->hasPages())
                    <div class="px-6 py-4 border-t border-white/10">
                        {{ $notices->withQueryString()->links() }}
                    </div>
                @endif
            </div>

            {{-- Info Box --}}
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/30 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm text-blue-800 dark:text-blue-300 font-medium">About Notice Board</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                            This notice board is for internal communications. Directors, Admins, and Managers can post announcements. 
                            Tutors can view but cannot create announcements. Parents and students do not have access to this board.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <x-ui.flash-message type="success" :message="session('success')" />
    @endif

    @if(session('error'))
        <x-ui.flash-message type="error" :message="session('error')" />
    @endif
</x-app-layout>
