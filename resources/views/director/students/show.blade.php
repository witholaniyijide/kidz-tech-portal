<x-app-layout>
    <x-slot name="header">{{ __('Student Details') }}</x-slot>
    <x-slot name="title">{{ __('Director - Student Details') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-[#423A8E]/5 via-[#00CCCD]/5 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-4 sm:py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#423A8E]/30 dark:bg-[#423A8E]/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float hidden sm:block"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#00CCCD]/30 dark:bg-[#00CCCD]/40 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float hidden sm:block" style="animation-delay: 2s;"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-4 sm:px-6 py-4 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 sm:mb-8">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-[#00CCCD] to-[#00CCCD] rounded-xl sm:rounded-2xl flex items-center justify-center text-white font-bold text-lg sm:text-2xl shadow-lg">
                        {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $student->first_name }} {{ $student->other_name }} {{ $student->last_name }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">{{ $student->email ?? 'No email provided' }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('director.students.edit', $student) }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span class="hidden sm:inline">Edit</span>
                    </a>
                    <a href="{{ route('director.students.index') }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm">
                        <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline">Back</span>
                    </a>
                </div>
            </div>

            {{-- Status Badge --}}
            <div class="mb-6">
                <span class="inline-flex px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold rounded-full
                    @if($student->status === 'active') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                    @elseif($student->status === 'inactive') bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                    @elseif($student->status === 'graduated') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                    @else bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                    @endif">
                    {{ ucfirst($student->status) }}
                </span>
            </div>

            {{-- Student Info --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white">
                    <h3 class="text-base sm:text-lg font-semibold">Student Information</h3>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Full Name</label>
                            <p class="text-gray-900 dark:text-white font-medium text-sm sm:text-base">{{ $student->first_name }} {{ $student->other_name }} {{ $student->last_name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Email</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base break-all">{{ $student->email ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Date of Birth</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">{{ $student->date_of_birth?->format('M j, Y') ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Gender</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">{{ ucfirst($student->gender ?? '-') }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Coding Experience</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">{{ $student->coding_experience ?? '-' }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Career Interest</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">{{ $student->career_interest ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Class Info --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                    <h3 class="text-base sm:text-lg font-semibold">Class Information</h3>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Tutor Assigned</label>
                            <p class="text-gray-900 dark:text-white font-medium text-sm sm:text-base">
                                @if($student->tutor)
                                    {{ $student->tutor->first_name }} {{ $student->tutor->last_name }}
                                @else
                                    <span class="text-gray-400 italic">Unassigned</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Classes Per Week</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">{{ $student->classes_per_week ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Starting Course</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">
                                @if($student->startingCourse)
                                    Level {{ $student->startingCourse->level }} - {{ $student->startingCourse->name }}
                                @elseif($student->starting_course_level)
                                    Level {{ $student->starting_course_level }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Current Course</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">
                                @if($student->currentCourse)
                                    Level {{ $student->currentCourse->level }} - {{ $student->currentCourse->name }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Enrollment Date</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">{{ $student->enrollment_date?->format('M j, Y') ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Est. Classes per Month</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">{{ ($student->classes_per_week ?? 1) * 4 }} classes</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Class Link</label>
                            @if($student->class_link)
                                <a href="{{ $student->class_link }}" target="_blank" class="text-[#423A8E] dark:text-[#00CCCD] hover:underline truncate block text-sm sm:text-base">{{ $student->class_link }}</a>
                            @else
                                <p class="text-gray-400 text-sm sm:text-base">-</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Google Classroom</label>
                            @if($student->google_classroom_link)
                                <a href="{{ $student->google_classroom_link }}" target="_blank" class="text-[#423A8E] dark:text-[#00CCCD] hover:underline truncate block text-sm sm:text-base">{{ $student->google_classroom_link }}</a>
                            @else
                                <p class="text-gray-400 text-sm sm:text-base">-</p>
                            @endif
                        </div>
                    </div>

                    {{-- Completed Courses --}}
                    @if($student->completedCourses && $student->completedCourses->count() > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-3">Completed Courses</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($student->completedCourses as $course)
                                    <span class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-1.5 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg text-xs sm:text-sm">
                                        <svg class="w-3 sm:w-4 h-3 sm:h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Level {{ $course->level }} - {{ $course->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Class Schedule --}}
                    @php
                        $classSchedule = $student->class_schedule;
                        if (is_string($classSchedule)) {
                            $classSchedule = json_decode($classSchedule, true) ?? [];
                        }
                    @endphp
                    @if($classSchedule && is_array($classSchedule) && count($classSchedule) > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-3">Class Schedule <span class="text-xs font-normal text-gray-400">(NG Time)</span></label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($classSchedule as $schedule)
                                    @if(isset($schedule['day']) && isset($schedule['time']))
                                        <span class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg text-xs sm:text-sm">
                                            <span class="font-medium">{{ ucfirst($schedule['day']) }}</span>
                                            <span class="mx-1">-</span>
                                            <span>{{ \Carbon\Carbon::parse($schedule['time'])->format('g:i A') }}</span>
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Progress & Reports (Director View - Same as Parent Portal) --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white">
                    <h3 class="text-base sm:text-lg font-semibold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Progress & Reports (Parent Portal View)
                    </h3>
                    <p class="text-xs text-emerald-100 mt-1">This is what parents see on their portal</p>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        {{-- Overall Progress --}}
                        <div class="text-center p-4 bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-xl">
                            <div class="relative w-24 h-24 mx-auto mb-3">
                                <svg class="w-24 h-24 transform -rotate-90">
                                    <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="none"
                                            class="text-gray-200 dark:text-gray-700"/>
                                    <circle cx="48" cy="48" r="40" stroke="url(#director-progress-gradient)" stroke-width="8" fill="none"
                                            stroke-linecap="round"
                                            stroke-dasharray="{{ 251.2 * $progressPercentage / 100 }} 251.2"/>
                                    <defs>
                                        <linearGradient id="director-progress-gradient">
                                            <stop offset="0%" stop-color="#10b981"/>
                                            <stop offset="100%" stop-color="#14b8a6"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ $progressPercentage }}%</span>
                                </div>
                            </div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Overall Progress</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $student->completedCourses?->count() ?? 0 }} of 12 courses completed
                            </p>
                        </div>

                        {{-- Current Level --}}
                        <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl">
                            <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">
                                    @if($student->currentCourse)
                                        {{ $student->currentCourse->level }}
                                    @elseif($student->current_level)
                                        {{ $student->current_level }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Current Level</p>
                            @if($student->currentCourse)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $student->currentCourse->name }}</p>
                            @endif
                        </div>

                        {{-- Starting Level --}}
                        <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl">
                            <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">
                                    @if($student->startingCourse)
                                        {{ $student->startingCourse->level }}
                                    @elseif($student->starting_course_level)
                                        {{ $student->starting_course_level }}
                                    @else
                                        1
                                    @endif
                                </span>
                            </div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Starting Level</p>
                            @if($student->startingCourse)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $student->startingCourse->name }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Recent Reports --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Recent Reports
                        </h4>
                        @if($recentReports && $recentReports->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentReports as $report)
                                    <a href="{{ route('director.reports.show', $report) }}" class="block p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white text-sm">
                                                    {{ $report->month }} {{ $report->year }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    @if($report->courses_covered)
                                                        @php
                                                            $courses = is_array($report->courses_covered) ? $report->courses_covered : json_decode($report->courses_covered, true);
                                                        @endphp
                                                        Courses: {{ is_array($courses) ? implode(', ', array_slice($courses, 0, 3)) : $report->courses_covered }}
                                                        @if(is_array($courses) && count($courses) > 3)
                                                            +{{ count($courses) - 3 }} more
                                                        @endif
                                                    @else
                                                        No courses listed
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                @if($report->performance_rating)
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                        {{ $report->performance_rating }}/5
                                                    </span>
                                                @endif
                                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                                    @if($report->status === 'approved-by-director') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                                                    @elseif($report->status === 'approved-by-manager') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                                    @else bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                                                    @endif">
                                                    {{ ucwords(str_replace('-', ' ', $report->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('director.reports.index', ['student_id' => $student->id]) }}" class="text-sm text-[#423A8E] dark:text-[#00CCCD] hover:underline">
                                    View All Reports
                                </a>
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm">No reports submitted yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Parent Info --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-600 text-white">
                    <h3 class="text-base sm:text-lg font-semibold">Parent Information</h3>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                        {{-- Father --}}
                        <div>
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-4 flex items-center text-sm sm:text-base">
                                <span class="w-7 h-7 sm:w-8 sm:h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-2 text-blue-600 text-sm">👨</span>
                                Father
                            </h4>
                            <div class="space-y-2 sm:space-y-3 text-xs sm:text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Name</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->father_name ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Phone</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->father_phone ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Email</span>
                                    <span class="text-gray-900 dark:text-white text-right break-all">{{ $student->father_email ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Occupation</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->father_occupation ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Location</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->father_location ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        {{-- Mother --}}
                        <div>
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-4 flex items-center text-sm sm:text-base">
                                <span class="w-7 h-7 sm:w-8 sm:h-8 bg-pink-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center mr-2 text-pink-600 text-sm">👩</span>
                                Mother
                            </h4>
                            <div class="space-y-2 sm:space-y-3 text-xs sm:text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Name</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->mother_name ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Phone</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->mother_phone ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Email</span>
                                    <span class="text-gray-900 dark:text-white text-right break-all">{{ $student->mother_email ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Occupation</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->mother_occupation ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Location</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->mother_location ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Attendance --}}
            @if($student->attendances && $student->attendances->count() > 0)
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Recent Attendance</h3>
                        <a href="{{ route('director.attendance.index', ['student_id' => $student->id]) }}" class="text-xs sm:text-sm text-[#423A8E] dark:text-[#00CCCD] hover:underline">View All</a>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="space-y-3">
                            @foreach($student->attendances->take(5) as $attendance)
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white text-sm sm:text-base">{{ $attendance->topic ?? 'No topic' }}</div>
                                        <div class="text-xs sm:text-sm text-gray-500">{{ $attendance->class_date?->format('M j, Y') }} - {{ $attendance->duration }} mins</div>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full self-start sm:self-auto
                                        @if($attendance->status === 'approved') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                                        @elseif($attendance->status === 'pending') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                                        @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                        @endif">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
