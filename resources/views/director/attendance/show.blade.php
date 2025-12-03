<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Attendance Details') }}</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-8 shadow-lg">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Attendance Record Details</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Class Date</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $attendance->class_date }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Student</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $attendance->student->first_name ?? 'N/A' }} {{ $attendance->student->last_name ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Tutor</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $attendance->tutor->first_name ?? 'N/A' }} {{ $attendance->tutor->last_name ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ ucfirst($attendance->status) }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    @if($attendance->status === 'pending')
                        <form action="{{ route('director.attendance.approve', $attendance) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl transition-all">Approve</button>
                        </form>
                    @endif
                    <a href="{{ route('director.attendance.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition-all">Back to Attendance</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
