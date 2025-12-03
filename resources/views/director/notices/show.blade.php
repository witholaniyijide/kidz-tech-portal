<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Notice Details') }}</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-8 shadow-lg">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $notice->title }}</h1>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Priority</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ ucfirst($notice->priority) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ ucfirst($notice->status) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Date</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $notice->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Content</p>
                    <div class="text-gray-900 dark:text-white bg-white/20 dark:bg-gray-800/20 rounded-xl p-4">
                        {!! nl2br(e($notice->content)) !!}
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('director.notices.edit', $notice) }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all">Edit Notice</a>
                    <a href="{{ route('director.notices.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition-all">Back to Notices</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
