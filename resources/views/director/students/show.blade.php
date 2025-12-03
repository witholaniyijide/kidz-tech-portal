<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Student Details') }}</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-400 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-purple-400 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-8 shadow-lg">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">{{ $student->first_name }} {{ $student->last_name }}</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Student ID</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $student->student_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $student->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ ucfirst($student->status) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Completion Rate</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $metrics['completion_rate'] }}%</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('director.students.edit', $student) }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all">
                        Edit Student
                    </a>
                    <a href="{{ route('director.students.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition-all">
                        Back to Students
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
