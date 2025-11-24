<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Notice Details') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ $notice->title }}</x-slot>

    {{-- Animated Background --}}
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-blue-50 to-cyan-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-sky-300 dark:bg-sky-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-blue-300 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Back Button --}}
            <div class="mb-6">
                <a href="{{ route('manager.notices.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:-translate-y-0.5 transition-all duration-200 shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Notice Board
                </a>
            </div>

            {{-- Notice Content --}}
            <x-ui.glass-card>
                @php
                    $priorityColors = [
                        'urgent' => 'from-red-500 to-pink-500',
                        'high' => 'from-orange-500 to-amber-500',
                        'important' => 'from-purple-500 to-indigo-500',
                        'normal' => 'from-green-500 to-emerald-500',
                        'general' => 'from-blue-500 to-cyan-500',
                        'low' => 'from-gray-500 to-slate-500',
                        'reminder' => 'from-yellow-500 to-orange-400',
                    ];
                    $priorityBg = $priorityColors[$notice->priority] ?? 'from-gray-500 to-slate-500';
                @endphp

                {{-- Header --}}
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white">
                            @if($notice->priority === 'urgent')
                                <span class="text-red-600 dark:text-red-400">=4 URGENT:</span>
                            @elseif($notice->priority === 'high' || $notice->priority === 'important')
                                <span class="text-orange-600 dark:text-orange-400"> </span>
                            @endif
                            {{ $notice->title }}
                        </h3>
                        <div class="flex items-center gap-2">
                            @if($notice->status === 'draft')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    Draft
                                </span>
                            @elseif($notice->status === 'published')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300">
                                    Published
                                </span>
                            @elseif($notice->status === 'archived')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300">
                                    Archived
                                </span>
                            @endif
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r {{ $priorityBg }} text-white">
                                {{ ucfirst($notice->priority) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Posted by: <strong class="ml-1">{{ $notice->author->name ?? 'Unknown' }}</strong>
                        </span>
                        <span>"</span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $notice->created_at->format('M j, Y g:i A') }}
                        </span>
                        @if($notice->published_at)
                        <span>"</span>
                        <span>Published: {{ $notice->published_at->format('M j, Y g:i A') }}</span>
                        @endif
                    </div>
                </div>

                {{-- Content --}}
                <div class="prose prose-lg dark:prose-invert max-w-none mb-6">
                    <div class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $notice->content }}</div>
                </div>

                {{-- Visible To --}}
                @if($notice->visible_to && is_array($notice->visible_to))
                <div class="mb-6 p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Visible to:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($notice->visible_to as $role)
                            <span class="px-3 py-1 rounded-lg text-xs font-medium bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300">
                                {{ ucfirst($role) }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    @if($notice->posted_by === auth()->id())
                    <a href="{{ route('manager.notices.edit', $notice) }}" class="px-5 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-xl hover:-translate-y-1 transition-all duration-300 shadow-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Notice
                    </a>

                    <form method="POST" action="{{ route('manager.notices.destroy', $notice) }}" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this notice?')" class="px-5 py-3 bg-gradient-to-r from-red-500 to-pink-500 text-white font-semibold rounded-xl hover:-translate-y-1 transition-all duration-300 shadow-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Notice
                        </button>
                    </form>
                    @endif
                </div>
            </x-ui.glass-card>

        </div>
    </div>
</x-app-layout>
