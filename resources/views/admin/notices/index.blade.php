<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Notice Board') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - Notices') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Notice Board</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Manage announcements and notices</p>
                </div>
                <a href="{{ route('admin.notices.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-medium rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Notice
                </a>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Notices</div>
                </div>
                <a href="{{ route('admin.notices.index', ['status' => 'published']) }}" class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/30 rounded-2xl p-5 shadow hover:shadow-lg transition-shadow">
                    <div class="text-3xl font-bold text-emerald-600">{{ $stats['published'] ?? 0 }}</div>
                    <div class="text-sm text-emerald-700 dark:text-emerald-400">Published</div>
                </a>
                <a href="{{ route('admin.notices.index', ['status' => 'draft']) }}" class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/30 rounded-2xl p-5 shadow hover:shadow-lg transition-shadow">
                    <div class="text-3xl font-bold text-amber-600">{{ $stats['draft'] ?? 0 }}</div>
                    <div class="text-sm text-amber-700 dark:text-amber-400">Drafts</div>
                </a>
                <a href="{{ route('admin.notices.index', ['priority' => 'high']) }}" class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800/30 rounded-2xl p-5 shadow hover:shadow-lg transition-shadow">
                    <div class="text-3xl font-bold text-red-600">{{ $stats['high_priority'] ?? 0 }}</div>
                    <div class="text-sm text-red-700 dark:text-red-400">High Priority</div>
                </a>
            </div>

            {{-- Filters --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow mb-6">
                <form method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search notices..."
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div class="w-36">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="">All</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="w-36">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                        <select name="priority" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="">All</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="normal" {{ request('priority') === 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>
                    <div class="w-36">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Audience</label>
                        <select name="audience" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="">All</option>
                            <option value="all" {{ request('audience') === 'all' ? 'selected' : '' }}>Everyone</option>
                            <option value="tutors" {{ request('audience') === 'tutors' ? 'selected' : '' }}>Tutors</option>
                            <option value="parents" {{ request('audience') === 'parents' ? 'selected' : '' }}>Parents</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'priority', 'audience']))
                        <a href="{{ route('admin.notices.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- Notices List --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                @if($notices->isEmpty())
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">📢</div>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Notices Yet</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Create your first notice to share with the team.</p>
                        <a href="{{ route('admin.notices.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                            Create Notice
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($notices as $notice)
                            <div class="p-5 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors {{ $notice->status === 'draft' ? 'bg-amber-50/30 dark:bg-amber-900/10' : '' }}">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            {{-- Priority Badge --}}
                                            @if($notice->priority === 'high')
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                                    🔴 High
                                                </span>
                                            @elseif($notice->priority === 'low')
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                                    Low
                                                </span>
                                            @endif

                                            {{-- Status Badge --}}
                                            @if($notice->status === 'draft')
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                                    Draft
                                                </span>
                                            @elseif($notice->status === 'archived')
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                                    Archived
                                                </span>
                                            @endif

                                            {{-- Audience Badge --}}
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                @if($notice->audience === 'all') 👥 All
                                                @elseif($notice->audience === 'tutors') 👨‍🏫 Tutors
                                                @elseif($notice->audience === 'parents') 👪 Parents
                                                @else {{ ucfirst($notice->audience ?? 'All') }}
                                                @endif
                                            </span>
                                        </div>

                                        <a href="{{ route('admin.notices.show', $notice) }}" class="text-lg font-semibold text-gray-900 dark:text-white hover:text-teal-600 dark:hover:text-teal-400">
                                            {{ $notice->title }}
                                        </a>

                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                            {{ Str::limit(strip_tags($notice->content), 150) }}
                                        </p>

                                        <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                            <span>By {{ $notice->author->name ?? 'Admin' }}</span>
                                            <span>{{ $notice->created_at->format('M j, Y') }}</span>
                                            @if($notice->published_at)
                                                <span class="text-emerald-600">Published {{ $notice->published_at->diffForHumans() }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('admin.notices.show', $notice) }}" class="p-2 text-gray-500 hover:text-teal-600 hover:bg-teal-50 dark:hover:bg-teal-900/20 rounded-lg transition-colors" title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.notices.edit', $notice) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.notices.destroy', $notice) }}" method="POST" class="inline" onsubmit="return confirm('Delete this notice?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($notices->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $notices->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
    @endpush
</x-app-layout>
