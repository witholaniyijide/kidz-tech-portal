<x-parent-layout title="Attendance Details">
    <div class="space-y-6">
        <!-- Back Button -->
        <div>
            <a href="{{ route('parent.attendance.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Attendance
            </a>
        </div>

        <!-- Attendance Card -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($attendance->date)->format('l, F j, Y') }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Attendance Record</p>
                </div>
                @if($attendance->status === 'present')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Present
                    </span>
                @elseif($attendance->status === 'absent')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Absent
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                        {{ ucfirst($attendance->status) }}
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Student Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Student</h3>
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-parent flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($attendance->student->first_name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ $attendance->student->first_name ?? '' }} {{ $attendance->student->last_name ?? '' }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $attendance->student->email ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tutor Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tutor</h3>
                    @if($attendance->tutor)
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 rounded-2xl bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 text-2xl font-bold">
                                {{ substr($attendance->tutor->first_name ?? 'T', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $attendance->tutor->first_name ?? '' }} {{ $attendance->tutor->last_name ?? '' }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $attendance->tutor->email ?? '' }}
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No tutor assigned</p>
                    @endif
                </div>
            </div>

            @if($attendance->notes)
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Notes</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ $attendance->notes }}</p>
                </div>
            @endif

            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Recorded on {{ $attendance->created_at->format('M d, Y \a\t g:i A') }}
                </p>
            </div>
        </div>
    </div>
</x-parent-layout>
