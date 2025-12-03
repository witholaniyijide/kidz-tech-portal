<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Tutor Details') }}</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-8 shadow-lg">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">{{ $tutor->first_name }} {{ $tutor->last_name }}</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Tutor ID</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $tutor->tutor_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $tutor->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ ucfirst($tutor->status) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Students</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $metrics['total_students'] }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('director.tutors.edit', $tutor) }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all">Edit Tutor</a>
                    <a href="{{ route('director.tutors.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition-all">Back to Tutors</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
