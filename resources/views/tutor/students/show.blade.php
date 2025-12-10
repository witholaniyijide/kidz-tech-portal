<x-tutor-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ $student->fullName() }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Student ID: {{ $student->student_id }}
                </p>
            </div>
            <a href="{{ route('tutor.reports.create', ['student_id' => $student->id]) }}"
                class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                Create Report
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Student Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Student Details -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Student Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Email:</span>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $student->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Status:</span>
                        <p class="font-medium text-gray-900 dark:text-white capitalize">{{ $student->status }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Enrollment Date:</span>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ $student->enrollment_date ? $student->enrollment_date->format('M d, Y') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Attendance Rate:</span>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $attendanceRate }}%</p>
                    </div>
                </div>
            </div>

            <!-- Recent Reports -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Recent Reports</h2>

                @forelse($student->tutorReports as $report)
                    <div class="mb-3 p-4 bg-white/30 dark:bg-gray-800/30 rounded-xl">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $report->month }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 capitalize">Status: {{ $report->status }}</p>
                            </div>
                            <a href="{{ route('tutor.reports.show', $report) }}"
                                class="text-purple-600 dark:text-purple-400 hover:underline text-sm">
                                View
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 dark:text-gray-400">No reports yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Right Column: Quick Stats -->
        <div class="space-y-6">
            <!-- Stats Card -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Quick Stats</h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Reports</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $student->tutorReports->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Attendance Records</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $student->attendanceRecords->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tutor-layout>
