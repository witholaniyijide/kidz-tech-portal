<x-app-layout>
    <x-slot name="header">{{ __('Create Notice') }}</x-slot>
    <x-slot name="title">{{ __('Admin - Create Notice') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create Notice</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Share an announcement with your team</p>
                </div>
                <a href="{{ route('admin.notices.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-800 dark:text-red-400 px-6 py-4 rounded-xl">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.notices.store') }}" method="POST">
                @csrf

                {{-- Notice Content --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 text-white">
                        <h3 class="text-lg font-semibold">Notice Content</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required maxlength="255"
                                   placeholder="Enter notice title..."
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>

                        {{-- Content --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content <span class="text-red-500">*</span></label>
                            <textarea name="content" rows="8" required
                                      placeholder="Write your notice content here..."
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('content') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">You can use basic formatting. Keep it clear and concise.</p>
                        </div>
                    </div>
                </div>

                {{-- Settings --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                        <h3 class="text-lg font-semibold">Settings</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Priority --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                                <select name="priority" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="normal" {{ old('priority', 'normal') === 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Save as Draft</option>
                                    <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Publish Now</option>
                                </select>
                            </div>
                        </div>

                        {{-- Visible To (Audience) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Visible To <span class="text-red-500">*</span></label>
                            <p class="text-xs text-gray-500 mb-3">Select who can see this notice. Director always sees all notices.</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <label class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <input type="checkbox" name="visible_to[]" value="tutor"
                                           {{ in_array('tutor', old('visible_to', ['tutor', 'admin', 'manager'])) ? 'checked' : '' }}
                                           class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500 mr-2">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Tutors</span>
                                </label>
                                <label class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <input type="checkbox" name="visible_to[]" value="admin"
                                           {{ in_array('admin', old('visible_to', ['tutor', 'admin', 'manager'])) ? 'checked' : '' }}
                                           class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500 mr-2">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Admins</span>
                                </label>
                                <label class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <input type="checkbox" name="visible_to[]" value="manager"
                                           {{ in_array('manager', old('visible_to', ['tutor', 'admin', 'manager'])) ? 'checked' : '' }}
                                           class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500 mr-2">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Managers</span>
                                </label>
                                <label class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <input type="checkbox" name="visible_to[]" value="director"
                                           {{ in_array('director', old('visible_to', [])) ? 'checked' : '' }}
                                           class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500 mr-2">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Director</span>
                                </label>
                            </div>
                            @error('visible_to')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.notices.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium">
                        Create Notice
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
