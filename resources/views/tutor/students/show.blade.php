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
                class="px-6 py-3 bg-gradient-to-r from-[#4B49AC] to-[#7978E9] text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
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
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Starting Level:</span>
                        <p class="font-medium text-gray-900 dark:text-white">Level {{ $student->starting_course_level ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Current Level:</span>
                        <p class="font-medium text-gray-900 dark:text-white">Level {{ $student->current_level ?? $student->starting_course_level ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Career Interest:</span>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $student->career_interest ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Classes Per Week:</span>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $student->classes_per_week ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Class Schedule & Links -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Class Schedule & Links</h2>

                @if($student->class_schedule && is_array($student->class_schedule) && count($student->class_schedule) > 0)
                    <div class="mb-4">
                        <span class="text-gray-600 dark:text-gray-400 block mb-2">Class Days & Times:</span>
                        <div class="space-y-2">
                            @foreach($student->class_schedule as $schedule)
                                <div class="flex items-center gap-2 p-2 bg-white/30 dark:bg-gray-800/30 rounded-lg">
                                    <svg class="w-4 h-4 text-[#4B49AC]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $schedule['day'] ?? 'N/A' }} at {{ $schedule['time'] ?? 'N/A' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-400 mb-4">No class schedule set</p>
                @endif

                <div class="space-y-3">
                    @if($student->live_classroom_link)
                        <div>
                            <span class="text-gray-600 dark:text-gray-400 block mb-1">Live Classroom Link:</span>
                            <a href="{{ $student->live_classroom_link }}" target="_blank"
                                class="inline-flex items-center gap-2 px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Join Live Class
                            </a>
                        </div>
                    @endif

                    @if($student->google_classroom_link)
                        <div>
                            <span class="text-gray-600 dark:text-gray-400 block mb-1">Google Classroom Link:</span>
                            <a href="{{ $student->google_classroom_link }}" target="_blank"
                                class="inline-flex items-center gap-2 px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                View Classroom
                            </a>
                        </div>
                    @endif

                    @if($student->class_link)
                        <div>
                            <span class="text-gray-600 dark:text-gray-400 block mb-1">Class Link:</span>
                            <a href="{{ $student->class_link }}" target="_blank"
                                class="inline-flex items-center gap-2 px-3 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                Open Class
                            </a>
                        </div>
                    @endif

                    @if(!$student->live_classroom_link && !$student->google_classroom_link && !$student->class_link)
                        <p class="text-gray-600 dark:text-gray-400">No class links available</p>
                    @endif
                </div>
            </div>

            <!-- Course Progression (Read-Only) -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Course Progression</h2>

                <div class="space-y-4">
                    {{-- Current Course --}}
                    <div>
                        <span class="text-gray-600 dark:text-gray-400 block mb-2">Current Course:</span>
                        @if($student->currentCourse)
                            <div class="flex items-center gap-2 p-3 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <span class="text-xl">▶️</span>
                                <span class="font-medium text-blue-800 dark:text-blue-200">{{ $student->currentCourse->full_name }}</span>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 italic">No current course set</p>
                        @endif
                    </div>

                    {{-- Completed Courses --}}
                    <div>
                        <span class="text-gray-600 dark:text-gray-400 block mb-2">Completed Courses:</span>
                        @php
                            $completedCourses = $student->completedCourses ?? collect();
                        @endphp
                        @if($completedCourses->count() > 0)
                            <div class="space-y-2">
                                @foreach($completedCourses as $course)
                                    <div class="flex items-center gap-2 p-2 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg">
                                        <span class="text-lg">✅</span>
                                        <span class="text-green-800 dark:text-green-200">{{ $course->full_name }}</span>
                                        @if($course->certificate_eligible)
                                            <span class="ml-auto text-xs bg-green-200 dark:bg-green-800 text-green-700 dark:text-green-300 px-2 py-0.5 rounded">Certificate</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 italic">No completed courses yet</p>
                        @endif
                    </div>

                    {{-- Progress Summary --}}
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Overall Progress:</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $student->getExplicitProgressPercentage() }}%</span>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                            <div class="bg-gradient-to-r from-[#4B49AC] to-[#7978E9] h-2.5 rounded-full" style="width: {{ $student->getExplicitProgressPercentage() }}%"></div>
                        </div>
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
                                class="text-[#4B49AC] dark:text-[#98BDFF] hover:underline text-sm">
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
