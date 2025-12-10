<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Edit Notice') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - Edit Notice') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Notice</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Update the notice details</p>
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

            {{-- Status Info --}}
            @if($notice->status === 'published')
                <div class="mb-6 p-4 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700 rounded-xl">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">✅</span>
                        <div>
                            <p class="font-semibold text-emerald-800 dark:text-emerald-400">Published</p>
                            <p class="text-sm text-emerald-700 dark:text-emerald-500">Published {{ $notice->published_at?->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @elseif($notice->status === 'draft')
                <div class="mb-6 p-4 bg-amber-100 dark:bg-amber-900/30 border border-amber-300 dark:border-amber-700 rounded-xl">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">📝</span>
                        <p class="font-semibold text-amber-800 dark:text-amber-400">Draft - Not yet published</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.notices.update', $notice) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Notice Content --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 text-white">
                        <h3 class="text-lg font-semibold">Notice Content</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $notice->title) }}" required maxlength="255"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>

                        {{-- Content --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content <span class="text-red-500">*</span></label>
                            <textarea name="content" rows="8" required
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('content', $notice->content) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Settings --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                        <h3 class="text-lg font-semibold">Settings</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Priority --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                                <select name="priority" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                                    <option value="normal" {{ old('priority', $notice->priority) === 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="high" {{ old('priority', $notice->priority) === 'high' ? 'selected' : '' }}>🔴 High</option>
                                    <option value="low" {{ old('priority', $notice->priority) === 'low' ? 'selected' : '' }}>Low</option>
                                </select>
                            </div>

                            {{-- Audience --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Audience</label>
                                <select name="audience" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                                    <option value="all" {{ old('audience', $notice->audience) === 'all' ? 'selected' : '' }}>👥 Everyone</option>
                                    <option value="tutors" {{ old('audience', $notice->audience) === 'tutors' ? 'selected' : '' }}>👨‍🏫 Tutors Only</option>
                                    <option value="parents" {{ old('audience', $notice->audience) === 'parents' ? 'selected' : '' }}>👪 Parents Only</option>
                                </select>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                                    <option value="draft" {{ old('status', $notice->status) === 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="published" {{ old('status', $notice->status) === 'published' ? 'selected' : '' }}>✅ Published</option>
                                    <option value="archived" {{ old('status', $notice->status) === 'archived' ? 'selected' : '' }}>📦 Archived</option>
                                </select>
                            </div>
                        </div>

                        {{-- Expiry Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expiry Date (Optional)</label>
                            <input type="date" name="expires_at" value="{{ old('expires_at', $notice->expires_at?->format('Y-m-d')) }}"
                                   class="w-full md:w-1/2 px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            <p class="text-xs text-gray-500 mt-1">Leave empty if the notice doesn't expire.</p>
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex justify-between">
                    <form action="{{ route('admin.notices.destroy', $notice) }}" method="POST" onsubmit="return confirm('Delete this notice?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                            Delete Notice
                        </button>
                    </form>

                    <div class="flex gap-4">
                        <a href="{{ route('admin.notices.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium">
                            Update Notice
                        </button>
                    </div>
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
