<x-manager-layout title="Edit Notice">
    {{-- Back Link --}}
    <a href="{{ route('manager.notices.show', $notice) }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-[#C15F3C] dark:hover:text-[#DA7756] mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Notice
    </a>

    {{-- Form Card --}}
    <div class="max-w-3xl">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-6 shadow-sm">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Edit Announcement</h3>

            <form action="{{ route('manager.notices.update', $notice) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Title *
                    </label>
                    <input type="text" name="title" id="title" required
                           value="{{ old('title', $notice->title) }}"
                           placeholder="Enter announcement title..."
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#C15F3C] focus:ring-[#C15F3C]">
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
                              class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#C15F3C] focus:ring-[#C15F3C]">{{ old('content', $notice->content) }}</textarea>
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
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#C15F3C] focus:ring-[#C15F3C]">
                            <option value="general" {{ old('priority', $notice->priority) == 'general' ? 'selected' : '' }}>General</option>
                            <option value="important" {{ old('priority', $notice->priority) == 'important' ? 'selected' : '' }}>Important</option>
                            <option value="reminder" {{ old('priority', $notice->priority) == 'reminder' ? 'selected' : '' }}>Reminder</option>
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
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#C15F3C] focus:ring-[#C15F3C]">
                            <option value="published" {{ old('status', $notice->status) == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status', $notice->status) == 'draft' ? 'selected' : '' }}>Draft</option>
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
                    <div class="bg-gray-50/50 dark:bg-gray-800/50 rounded-xl p-4 space-y-3">
                        @php
                            $visibleTo = old('visible_to', $notice->visible_to ?? []);
                        @endphp
                        <label class="flex items-center">
                            <input type="checkbox" name="visible_to[]" value="tutor"
                                   {{ in_array('tutor', $visibleTo) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#C15F3C] shadow-sm focus:border-[#C15F3C] focus:ring-[#C15F3C]">
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Tutors</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="visible_to[]" value="manager"
                                   {{ in_array('manager', $visibleTo) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#C15F3C] shadow-sm focus:border-[#C15F3C] focus:ring-[#C15F3C]">
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Managers</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="visible_to[]" value="admin"
                                   {{ in_array('admin', $visibleTo) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#C15F3C] shadow-sm focus:border-[#C15F3C] focus:ring-[#C15F3C]">
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

                {{-- Meta Info --}}
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Notice Information</h4>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Created</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $notice->created_at->format('M d, Y \a\t h:i A') }}</dd>
                        </div>
                        @if($notice->published_at)
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Published</dt>
                                <dd class="text-gray-900 dark:text-white">{{ $notice->published_at->format('M d, Y \a\t h:i A') }}</dd>
                            </div>
                        @endif
                        @if($notice->updated_at && $notice->updated_at->ne($notice->created_at))
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Last Updated</dt>
                                <dd class="text-gray-900 dark:text-white">{{ $notice->updated_at->format('M d, Y \a\t h:i A') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex justify-end gap-4 pt-4">
                    <a href="{{ route('manager.notices.show', $notice) }}"
                       class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white font-semibold rounded-xl hover:from-[#A34E30] hover:to-[#C15F3C] transition-all shadow-lg shadow-orange-500/25">
                        Update Notice
                    </button>
                </div>
            </form>
        </div>

        {{-- Danger Zone --}}
        <div class="mt-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 rounded-xl p-6">
            <h4 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-2">Danger Zone</h4>
            <p class="text-sm text-red-600 dark:text-red-400 mb-4">
                Once you delete a notice, there is no going back. Please be certain.
            </p>
            <form action="{{ route('manager.notices.destroy', $notice) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this notice? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all">
                    Delete This Notice
                </button>
            </form>
        </div>
    </div>
</x-manager-layout>
