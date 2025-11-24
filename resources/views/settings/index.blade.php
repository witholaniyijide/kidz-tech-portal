<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Settings') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-slate-50 to-gray-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-ui.glass-card padding="p-8">
                <div class="text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-400 dark:text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                        Settings
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8">
                        This feature is coming soon. Use the Profile page to update your personal information.
                    </p>
                    <div class="flex justify-center gap-4">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Dashboard
                        </a>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-xl hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Go to Profile
                        </a>
                    </div>
                </div>
            </x-ui.glass-card>
        </div>
    </div>
</x-app-layout>
