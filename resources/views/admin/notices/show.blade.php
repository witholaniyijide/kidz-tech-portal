<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('View Notice') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - View Notice') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-3">
                    {{-- Priority Badge --}}
                    @if($notice->priority === 'high')
                        <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                            🔴 High Priority
                        </span>
                    @endif
                    
                    {{-- Status Badge --}}
                    @if($notice->status === 'published')
                        <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                            ✅ Published
                        </span>
                    @elseif($notice->status === 'draft')
                        <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                            📝 Draft
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                            📦 Archived
                        </span>
                    @endif
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.notices.edit', $notice) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('admin.notices.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </a>
                </div>
            </div>

            {{-- Notice Content --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                <div class="p-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $notice->title }}</h1>
                    
                    {{-- Meta Info --}}
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>{{ $notice->author->name ?? 'Admin' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $notice->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>
                                @if($notice->audience === 'all') Everyone
                                @elseif($notice->audience === 'tutors') Tutors Only
                                @elseif($notice->audience === 'parents') Parents Only
                                @else {{ ucfirst($notice->audience ?? 'All') }}
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="prose prose-lg dark:prose-invert max-w-none">
                        {!! nl2br(e($notice->content)) !!}
                    </div>
                </div>
            </div>

            {{-- Additional Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Status Details --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow p-6">
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-4">Status Details</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($notice->status) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Priority</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($notice->priority ?? 'Normal') }}</span>
                        </div>
                        @if($notice->published_at)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Published</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $notice->published_at->format('M j, Y g:i A') }}</span>
                            </div>
                        @endif
                        @if($notice->expires_at)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Expires</span>
                                <span class="font-medium {{ $notice->expires_at->isPast() ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                                    {{ $notice->expires_at->format('M j, Y') }}
                                    @if($notice->expires_at->isPast()) (Expired) @endif
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Timestamps --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow p-6">
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-4">Timeline</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Created</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $notice->created_at->format('M j, Y g:i A') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Last Updated</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $notice->updated_at->format('M j, Y g:i A') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Age</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $notice->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-8 flex justify-between items-center">
                <form action="{{ route('admin.notices.destroy', $notice) }}" method="POST" onsubmit="return confirm('Delete this notice?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Notice
                    </button>
                </form>

                @if($notice->status === 'draft')
                    <form action="{{ route('admin.notices.update', $notice) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="title" value="{{ $notice->title }}">
                        <input type="hidden" name="content" value="{{ $notice->content }}">
                        <input type="hidden" name="priority" value="{{ $notice->priority }}">
                        <input type="hidden" name="audience" value="{{ $notice->audience }}">
                        <input type="hidden" name="status" value="published">
                        <button type="submit" class="inline-flex items-center px-5 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Publish Now
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
