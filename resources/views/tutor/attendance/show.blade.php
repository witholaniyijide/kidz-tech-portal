<x-tutor-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
            Attendance Details
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            View attendance record details
        </p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-green-800 dark:text-green-300">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    @endif

    <!-- Attendance Details Card -->
    <div class="max-w-3xl">
        <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-lg">
            <!-- Status Badge -->
            <div class="mb-6">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    @if($attendance->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                    @elseif($attendance->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                    @endif">
                    @if($attendance->status === 'approved')
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif($attendance->status === 'rejected')
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @else
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                    {{ ucfirst($attendance->status) }}
                </span>
            </div>

            <!-- Details Grid -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Student</label>
                    <p class="text-lg text-gray-900 dark:text-white">{{ $attendance->student->fullName() }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Class Date</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $attendance->class_date->format('F d, Y') }}</p>
                    </div>

                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Class Time</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $attendance->class_time }}</p>
                    </div>
                </div>

                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Duration</label>
                    <p class="text-lg text-gray-900 dark:text-white">{{ $attendance->duration_minutes }} minutes</p>
                </div>

                @if($attendance->topic)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Topic</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $attendance->topic }}</p>
                    </div>
                @endif

                @if($attendance->notes)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Notes</label>
                        <p class="text-gray-900 dark:text-white">{{ $attendance->notes }}</p>
                    </div>
                @endif

                @if($attendance->approved_by)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Approved By</label>
                        <p class="text-gray-900 dark:text-white">{{ $attendance->approver->name ?? 'Unknown' }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $attendance->approved_at?->format('F d, Y g:i A') }}</p>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Submitted</label>
                    <p class="text-gray-900 dark:text-white">{{ $attendance->created_at->format('F d, Y g:i A') }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('tutor.dashboard') }}"
                    class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</x-tutor-layout>
