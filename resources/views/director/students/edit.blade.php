<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Edit Student') }}</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-400 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-purple-400 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-8 shadow-lg">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Edit Student: {{ $student->first_name }} {{ $student->last_name }}</h1>

                <p class="text-gray-600 dark:text-gray-400 mb-4">Student edit form will be implemented here.</p>

                <div class="flex gap-3">
                    <a href="{{ route('director.students.show', $student) }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition-all">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
