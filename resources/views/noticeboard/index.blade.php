<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Notice Board') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Notice Board') }}</x-slot>

    {{-- Animated Background --}}
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-blue-50 to-cyan-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-sky-300 dark:bg-sky-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-blue-300 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-green-500/90 backdrop-blur-xl shadow-xl border border-white/20">
                    <div class="flex items-center text-white">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-red-500/90 backdrop-blur-xl shadow-xl border border-white/20">
                    <div class="flex items-center text-white">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{-- Header with Create Button --}}
            <x-ui.glass-card class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Notice Board</h3>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">View and manage notices</p>
                    </div>
                    <a href="{{ route('manager.notices.create') }}" class="px-5 py-3 bg-gradient-to-r from-sky-500 to-cyan-400 text-white font-semibold rounded-xl hover:-translate-y-1 transition-all duration-300 shadow-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Post New Notice
                    </a>
                </div>
            </x-ui.glass-card>

            {{-- Notice Cards --}}
            <div class="space-y-4">
                @forelse($notices as $notice)
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

                    <x-ui.glass-card>
                        <div class="flex gap-4">
                            {{-- Priority Indicator --}}
                            <div class="flex-shrink-0">
                                <div class="w-1 h-full bg-gradient-to-b {{ $priorityBg }} rounded-full"></div>
                            </div>

                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                            @if($notice->priority === 'urgent')
                                                <span class="text-red-600 dark:text-red-400">🔴 URGENT:</span>
                                            @elseif($notice->priority === 'high' || $notice->priority === 'important')
                                                <span class="text-orange-600 dark:text-orange-400">⚠️</span>
                                            @endif
                                            {{ $notice->title }}
                                        </h4>
                                        <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                            <span>Posted by: <strong>{{ $notice->author->name ?? 'Unknown' }}</strong></span>
                                            <span>•</span>
                                            <span>{{ $notice->created_at->format('M j, Y g:i A') }}</span>
                                        </div>
                                    </div>
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

                                {{-- Content Excerpt --}}
                                <div class="mb-4">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ Str::limit($notice->content, 250) }}
                                    </p>
                                </div>

                                {{-- Visible To --}}
                                @if($notice->visible_to && is_array($notice->visible_to))
                                <div class="mb-4">
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Visible to:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($notice->visible_to as $role)
                                            <span class="px-2 py-1 rounded-lg text-xs font-medium bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300">
                                                {{ ucfirst($role) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                {{-- Action Buttons --}}
                                <div class="flex gap-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('manager.notices.show', $notice) }}" class="px-4 py-2 bg-gradient-to-r from-sky-500 to-cyan-400 text-white font-semibold rounded-lg hover:-translate-y-0.5 transition-all duration-200 shadow-md flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </a>

                                    @if($notice->posted_by === auth()->id())
                                    <a href="{{ route('manager.notices.edit', $notice) }}" class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:-translate-y-0.5 transition-all duration-200 shadow-md flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('manager.notices.destroy', $notice) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this notice?')" class="px-4 py-2 bg-red-500 text-white font-semibold rounded-lg hover:-translate-y-0.5 transition-all duration-200 shadow-md flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </x-ui.glass-card>
                @empty
                    <x-ui.glass-card>
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Notices Found</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">There are no notices to display.</p>
                            <a href="{{ route('manager.notices.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-sky-500 to-cyan-400 text-white font-semibold rounded-xl hover:-translate-y-1 transition-all duration-300 shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Create Your First Notice
                            </a>
                        </div>
                    </x-ui.glass-card>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($notices->hasPages())
                <div class="mt-8">
                    {{ $notices->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
