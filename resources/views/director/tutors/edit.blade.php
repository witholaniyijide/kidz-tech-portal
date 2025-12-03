<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Edit Tutor') }}</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-8 shadow-lg">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Edit Tutor: {{ $tutor->first_name }} {{ $tutor->last_name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Tutor edit form will be implemented here.</p>
                <a href="{{ route('director.tutors.show', $tutor) }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition-all">Cancel</a>
            </div>
        </div>
    </div>
</x-app-layout>
