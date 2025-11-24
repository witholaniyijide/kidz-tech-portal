<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Help Center') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Help Center') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-slate-50 to-gray-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-ui.glass-card padding="p-8">
                <div class="text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-400 dark:text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                        Help Center
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8">
                        This feature is coming soon. In the meantime, please contact support for assistance.
                    </p>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
            </x-ui.glass-card>
        </div>
    </div>
</x-app-layout>
