<x-manager-layout title="Student Details">
    {{-- Back Link --}}
    <a href="{{ route('manager.students.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-[#C15F3C] dark:hover:text-[#DA7756] mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Students
    </a>

    {{-- Student Header Card --}}
    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6 mb-6">
        <div class="flex flex-wrap items-start gap-6">
            {{-- Avatar --}}
            <div class="w-24 h-24 rounded-2xl bg-gradient-to-r from-[#C15F3C] to-[#DA7756] flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
            </div>

            {{-- Info --}}
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                    {{ $student->first_name }} {{ $student->last_name }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mb-3">Student ID: {{ $student->student_id ?? 'N/A' }}</p>

                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 text-sm rounded-full font-medium
                        @if($student->status === 'active') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                        @elseif($student->status === 'inactive') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                        @elseif($student->status === 'graduated') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                        @endif">
                        {{ ucfirst($student->status) }}
                    </span>
                    @if($student->age)
                        <span class="px-3 py-1 text-sm rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            Age {{ $student->age }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="flex gap-2">
                <a href="{{ route('manager.students.progress', $student) }}" class="inline-flex items-center px-4 py-2 bg-[#C15F3C]/10 dark:bg-[#C15F3C]/20 text-[#C15F3C] dark:text-[#DA7756] font-medium rounded-lg hover:bg-[#C15F3C]/20 dark:hover:bg-[#C15F3C]/30 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Progress
                </a>
                <a href="{{ route('manager.students.attendance', $student) }}" class="inline-flex items-center px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Attendance
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Contact & Personal Info --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Student Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
                        <p class="text-gray-900 dark:text-white">{{ $student->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Phone</label>
                        <p class="text-gray-900 dark:text-white">{{ $student->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Date of Birth</label>
                        <p class="text-gray-900 dark:text-white">{{ $student->date_of_birth ? $student->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Location</label>
                        <p class="text-gray-900 dark:text-white">{{ $student->location ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Enrollment Date</label>
                        <p class="text-gray-900 dark:text-white">{{ $student->enrollment_date ? $student->enrollment_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Current Period</label>
                        <p class="text-gray-900 dark:text-white">{{ $student->current_period ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Tutor Assignment --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assigned Tutor</h3>
                @if($student->tutor)
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-[#C15F3C] to-[#DA7756] flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($student->tutor->first_name, 0, 1)) }}{{ strtoupper(substr($student->tutor->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $student->tutor->first_name }} {{ $student->tutor->last_name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->tutor->email }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No tutor assigned</p>
                @endif
            </div>

            {{-- Recent Reports --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Reports</h3>
                    <a href="{{ route('manager.students.reports', $student) }}" class="text-sm text-[#C15F3C] hover:text-[#A34E30] font-medium">View All</a>
                </div>
                @forelse($student->monthlyReports()->latest()->take(3)->get() as $report)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $report->month }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">By {{ $report->tutor->first_name ?? 'Unknown' }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full font-medium
                            @if($report->status === 'approved-by-director') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                            @elseif($report->status === 'approved-by-manager') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                            @else bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                            @endif">
                            {{ ucfirst(str_replace('-', ' ', $report->status)) }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No reports yet</p>
                @endforelse
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Course Progression --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Course Progression</h3>

                {{-- Progress Circle --}}
                @php
                    $courseProgress = $student->usesExplicitProgression()
                        ? $student->getExplicitProgressPercentage()
                        : $student->progressPercentage();
                @endphp
                <div class="text-center mb-4">
                    <div class="relative w-28 h-28 mx-auto">
                        <svg class="w-28 h-28 transform -rotate-90">
                            <circle cx="56" cy="56" r="48" stroke="currentColor" stroke-width="10" fill="none" class="text-gray-200 dark:text-gray-700"></circle>
                            <circle cx="56" cy="56" r="48" stroke="url(#progressGradient)" stroke-width="10" fill="none" stroke-linecap="round" stroke-dasharray="{{ 301.59 }}" stroke-dashoffset="{{ 301.59 - (301.59 * $courseProgress / 100) }}"></circle>
                            <defs>
                                <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#C15F3C" />
                                    <stop offset="100%" stop-color="#DA7756" />
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $courseProgress }}%</span>
                        </div>
                    </div>
                </div>

                {{-- Current Course --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Current Course</label>
                    @if($student->currentCourse)
                        <div class="flex items-center gap-2 p-2 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg text-sm">
                            <span>▶️</span>
                            <span class="font-medium text-blue-800 dark:text-blue-200">{{ $student->currentCourse->full_name }}</span>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-sm italic">Not set</p>
                    @endif
                </div>

                {{-- Completed Courses --}}
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Completed ({{ $student->completedCourses->count() }}/12)</label>
                    @if($student->completedCourses->count() > 0)
                        <div class="space-y-1 max-h-40 overflow-y-auto">
                            @foreach($student->completedCourses as $course)
                                <div class="flex items-center gap-2 p-1.5 bg-green-50 dark:bg-green-900/30 rounded text-xs">
                                    <span>✅</span>
                                    <span class="text-green-800 dark:text-green-200">Level {{ $course->level }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-sm italic">None yet</p>
                    @endif
                </div>
            </div>

            {{-- Attendance Stats --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Classes Attended</h3>
                @php
                    // Count approved attendance records (classes confirmed attended)
                    $approvedClasses = $student->attendanceRecords()->where('status', 'approved')->count();
                    $pendingClasses = $student->attendanceRecords()->where('status', 'pending')->count();
                    $totalRecords = $student->attendanceRecords()->count();

                    // Calculate expected classes based on enrollment
                    $classesPerWeek = $student->classes_per_week ?? 2;
                    $enrollmentDate = $student->enrollment_date ?? $student->created_at;
                    $weeksEnrolled = $enrollmentDate ? max(1, ceil($enrollmentDate->diffInWeeks(now()))) : 1;
                    $expectedClasses = min($classesPerWeek * $weeksEnrolled, 100); // Cap at 100 for display

                    $attendanceRate = $expectedClasses > 0 ? min(100, round(($approvedClasses / $expectedClasses) * 100)) : 0;
                @endphp
                <div class="flex items-center gap-4">
                    <div class="text-4xl font-bold text-[#C15F3C]">{{ $approvedClasses }}</div>
                    <div class="flex-1">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div class="bg-gradient-to-r from-[#C15F3C] to-[#DA7756] h-3 rounded-full" style="width: {{ $attendanceRate }}%"></div>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    {{ $approvedClasses }} approved classes{{ $pendingClasses > 0 ? ", {$pendingClasses} pending" : '' }}
                </p>
            </div>

            {{-- Class Links --}}
            @if($student->class_link || $student->google_classroom_link)
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Class Links</h3>
                <div class="space-y-3">
                    @if($student->class_link)
                    <a href="{{ $student->class_link }}" target="_blank" class="flex items-center gap-3 p-3 bg-[#C15F3C]/10 dark:bg-[#C15F3C]/20 rounded-xl hover:bg-[#C15F3C]/20 dark:hover:bg-[#C15F3C]/30 transition-colors">
                        <div class="w-10 h-10 bg-[#C15F3C] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 dark:text-white">Class Link</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $student->class_link }}</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                    @endif
                    @if($student->google_classroom_link)
                    <a href="{{ $student->google_classroom_link }}" target="_blank" class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 0C5.372 0 0 5.373 0 12s5.372 12 12 12 12-5.373 12-12S18.628 0 12 0zm5.82 16.32a.75.75 0 01-1.06 0L12 11.56l-4.76 4.76a.75.75 0 01-1.06-1.06l4.76-4.76-4.76-4.76a.75.75 0 011.06-1.06L12 9.44l4.76-4.76a.75.75 0 011.06 1.06l-4.76 4.76 4.76 4.76a.75.75 0 010 1.06z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 dark:text-white">Google Classroom</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $student->google_classroom_link }}</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Class Schedule --}}
            @if($student->class_schedule && is_array($student->class_schedule) && count($student->class_schedule) > 0)
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Class Schedule <span class="text-sm font-normal text-gray-500">(NG Time)</span></h3>
                <div class="space-y-2">
                    @foreach($student->class_schedule as $schedule)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="w-8 h-8 bg-[#C15F3C]/10 dark:bg-[#C15F3C]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#C15F3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ is_array($schedule) ? ($schedule['day'] ?? $schedule['label'] ?? 'Class') : $schedule }}
                                </p>
                                @if(is_array($schedule) && isset($schedule['time']))
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $schedule['time'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-manager-layout>
