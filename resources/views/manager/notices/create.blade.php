<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Post Announcement') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Post Announcement') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Back Link --}}
            <a href="{{ route('manager.notices.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Notice Board
            </a>

            {{-- Form Card --}}
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Create New Announcement</h3>

                <form action="{{ route('manager.notices.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Title *
                        </label>
                        <input type="text" name="title" id="title" required
                               value="{{ old('title') }}"
                               placeholder="Enter announcement title..."
                               class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Content --}}
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Content *
                        </label>
                        <textarea name="content" id="content" rows="8" required
                                  placeholder="Write your announcement here..."
                                  class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Priority & Status --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Priority *
                            </label>
                            <select name="priority" id="priority" required
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="general" {{ old('priority') == 'general' ? 'selected' : '' }}>🔵 General</option>
                                <option value="important" {{ old('priority') == 'important' ? 'selected' : '' }}>🔴 Important</option>
                                <option value="reminder" {{ old('priority') == 'reminder' ? 'selected' : '' }}>🟡 Reminder</option>
                            </select>
                            @error('priority')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status *
                            </label>
                            <select name="status" id="status" required
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Publish Now</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Save as Draft</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Visible To --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Visible To *
                        </label>
                        <div class="bg-white/30 dark:bg-gray-800/30 rounded-xl p-4 space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="visible_to[]" value="tutor" 
                                       {{ in_array('tutor', old('visible_to', ['tutor'])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <span class="ml-3 text-gray-700 dark:text-gray-300">Tutors</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="visible_to[]" value="manager"
                                       {{ in_array('manager', old('visible_to', ['manager'])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <span class="ml-3 text-gray-700 dark:text-gray-300">Managers</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="visible_to[]" value="admin"
                                       {{ in_array('admin', old('visible_to', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <span class="ml-3 text-gray-700 dark:text-gray-300">Admins</span>
                            </label>
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Note: Parents and students cannot see this notice board.
                                </p>
                            </div>
                        </div>
                        @error('visible_to')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex justify-end gap-4 pt-4">
                        <a href="{{ route('manager.notices.index') }}" 
                           class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all shadow-lg">
                            Post Announcement
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('error'))
        <x-ui.flash-message type="error" :message="session('error')" />
    @endif
</x-app-layout>
