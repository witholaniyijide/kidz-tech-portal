<x-parent-layout>
    <x-slot name="title">{{ $student->full_name }}</x-slot>
    <x-slot name="subtitle">Student Profile & Progress</x-slot>

    <div class="space-y-6">
        <!-- Back Button -->
        <div>
            <a href="{{ route('parent.children.index') }}"
               class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-sky-600 dark:hover:text-sky-400">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to My Children
            </a>
        </div>

        <!-- Profile Header -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex flex-col md:flex-row md:items-center gap-6">
                <!-- Avatar -->
                <div class="w-24 h-24 rounded-2xl bg-sky-100 dark:bg-sky-900/30 flex items-center justify-center shadow-xl overflow-hidden">
                    @if($student->profile_photo)
                        <img src="{{ asset('storage/' . $student->profile_photo) }}"
                             alt="{{ $student->full_name }}"
                             class="w-full h-full object-cover">
                    @else
                        <span class="text-4xl font-heading font-bold text-gray-900 dark:text-white">{{ substr($student->first_name, 0, 1) }}</span>
                    @endif
                </div>

                <!-- Info -->
                <div class="flex-1">
                    <h2 class="text-2xl font-heading font-bold text-gray-800 dark:text-white mb-2">
                        {{ $student->full_name }}
                    </h2>
                    <div class="flex flex-wrap gap-4 text-sm">
                        @if($student->date_of_birth)
                            <span class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $student->date_of_birth->format('M d, Y') }} ({{ $student->date_of_birth->age }} years old)
                            </span>
                        @endif
                        @if($student->gender)
                            <span class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ ucfirst($student->gender) }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Progress Circle & Performance Link -->
                <div class="text-center">
                    <div class="relative w-24 h-24">
                        <svg class="w-24 h-24 transform -rotate-90">
                            <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="none"
                                    class="text-gray-200 dark:text-gray-700"/>
                            <circle cx="48" cy="48" r="40" stroke="url(#progress-gradient)" stroke-width="8" fill="none"
                                    stroke-linecap="round"
                                    stroke-dasharray="{{ 251.2 * $progressPercentage / 100 }} 251.2"/>
                            <defs>
                                <linearGradient id="progress-gradient">
                                    <stop offset="0%" stop-color="#0ea5e9"/>
                                    <stop offset="100%" stop-color="#22d3ee"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-xl font-heading font-bold text-gray-800 dark:text-white">{{ $progressPercentage }}%</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Overall Progress</p>
                    <a href="{{ route('parent.performance.index', ['student_id' => $student->id]) }}"
                       class="inline-flex items-center mt-3 px-4 py-2 text-sm font-medium text-white bg-sky-500 hover:bg-sky-600 rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        View Performance
                    </a>
                </div>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Tutor Info -->
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">Tutor Information</h3>
                @if($student->tutor)
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <span class="text-lg font-bold text-purple-600 dark:text-purple-400">
                                {{ substr($student->tutor->first_name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-white">
                                {{ $student->tutor->first_name }} {{ $student->tutor->last_name }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Assigned Tutor</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No tutor assigned</p>
                @endif
            </div>

            <!-- Class Schedule -->
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">Class Schedule <span class="text-xs font-normal">(NG Time)</span></h3>
                @if($classSchedule && count($classSchedule) > 0)
                    <div class="space-y-2">
                        @foreach($classSchedule as $schedule)
                            <div class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>
                                    @if(isset($schedule['day']))
                                        {{ $schedule['day'] }} {{ isset($schedule['time']) ? \Carbon\Carbon::parse($schedule['time'])->format('g:i A') : '' }}
                                    @elseif(isset($schedule['schedule']))
                                        {{ $schedule['schedule'] }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No schedule set</p>
                @endif
            </div>

            <!-- Class Links -->
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">Class Links</h3>
                <div class="space-y-3">
                    @if($student->class_link)
                        <a href="{{ $student->class_link }}" target="_blank" rel="noopener noreferrer"
                           class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors">
                            <span class="text-sm font-medium text-blue-700 dark:text-blue-400">Class Link</span>
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    @endif
                    @if($student->google_classroom_link)
                        <a href="{{ $student->google_classroom_link }}" target="_blank" rel="noopener noreferrer"
                           class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/30 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors">
                            <span class="text-sm font-medium text-green-700 dark:text-green-400">Google Classroom</span>
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    @endif
                    @if(!$student->class_link && !$student->google_classroom_link)
                        <p class="text-gray-500 dark:text-gray-400">No class links available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Enrollment Info -->
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">Enrollment Details</h3>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @if($student->enrollment_date)
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Enrolled Since</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $student->enrollment_date->format('M d, Y') }}</p>
                    </div>
                @endif
                @if($student->coding_experience)
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Coding Experience</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $student->coding_experience }}</p>
                    </div>
                @endif
                @if($student->career_interest)
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Career Interest</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $student->career_interest }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Current Stage</p>
                    <p class="font-medium text-gray-800 dark:text-white">Stage {{ $currentStage ?? 1 }} of 12</p>
                </div>
            </div>
        </div>

        <!-- Curriculum Roadmap -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-heading font-bold text-gray-800 dark:text-white">Curriculum Roadmap</h3>
                <button onclick="openRequestCourseModal({{ $student->id }}, '{{ $student->first_name }} {{ $student->last_name }}')"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-sky-700 dark:text-sky-400 bg-sky-100 dark:bg-sky-900/30 rounded-lg hover:bg-sky-200 dark:hover:bg-sky-900/50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Request New Course
                </button>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($curriculumRoadmap as $course)
                    <div class="relative">
                        @if($course['status'] === 'completed' || $course['status'] === 'current')
                        <button onclick="openCourseLearningModal({{ $student->id }}, {{ $course['id'] }}, '{{ addslashes($course['title']) }}', '{{ $course['status'] }}')"
                                class="w-full text-left p-4 rounded-xl border-2 transition-all duration-200 hover:shadow-lg hover:scale-105 cursor-pointer
                                    {{ $course['status'] === 'completed' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/30' :
                                       'border-sky-500 bg-sky-50 dark:bg-sky-900/20 hover:bg-sky-100 dark:hover:bg-sky-900/30' }}">
                            <div class="w-10 h-10 mx-auto mb-2 rounded-lg flex items-center justify-center
                                        {{ $course['status'] === 'completed' ? 'bg-emerald-500 text-white' : 'bg-sky-500 text-white' }}">
                                @include('parent.partials.course-icon', ['icon' => $course['icon']])
                            </div>
                            <p class="text-xs text-center font-medium text-gray-700 dark:text-gray-300 line-clamp-2">
                                {{ $course['title'] }}
                            </p>
                            @if($course['status'] === 'completed')
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            @else
                                <div class="mt-2">
                                    <div class="h-1 bg-gray-200 dark:bg-gray-700 rounded-full">
                                        <div class="h-1 bg-sky-500 rounded-full" style="width: {{ $course['progress'] }}%"></div>
                                    </div>
                                </div>
                            @endif
                            <p class="text-[10px] text-center mt-2 text-gray-500 dark:text-gray-400">View topics</p>
                        </button>
                        @else
                        <div class="p-4 rounded-xl border-2 transition-all duration-200 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 opacity-60">
                            <div class="w-10 h-10 mx-auto mb-2 rounded-lg flex items-center justify-center bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400">
                                @include('parent.partials.course-icon', ['icon' => $course['icon']])
                            </div>
                            <p class="text-xs text-center font-medium text-gray-700 dark:text-gray-300 line-clamp-2">
                                {{ $course['title'] }}
                            </p>
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Reports & Certifications -->
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Recent Reports -->
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-heading font-bold text-gray-800 dark:text-white">Reports</h3>
                    <a href="{{ route('parent.reports.index') }}?student_id={{ $student->id }}"
                       class="text-sm text-sky-600 dark:text-sky-400 hover:underline">View All</a>
                </div>
                @if($reports->count() > 0)
                    <div class="space-y-3">
                        @foreach($reports as $report)
                            <a href="{{ route('parent.reports.show', ['student' => $student->id, 'report' => $report->id]) }}"
                               class="block p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white text-sm">
                                            {{ $report->month }} {{ $report->year }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $report->tutor ? $report->tutor->first_name : 'Unknown' }} Tutor
                                        </p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 dark:text-gray-400 py-4">No reports yet</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Course Learning Modal -->
    <div id="courseLearningModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="course-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCourseLearningModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div id="courseLearningIcon" class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-sky-100 dark:bg-sky-900/30">
                                <svg class="h-6 w-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="course-modal-title">
                                    <span id="courseLearningTitle">Course Details</span>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    <span id="courseLearningStatus" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"></span>
                                </p>
                            </div>
                        </div>
                        <button onclick="closeCourseLearningModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Loading State -->
                    <div id="courseLearningLoading" class="py-8 text-center">
                        <svg class="animate-spin h-8 w-8 text-sky-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">Loading topics...</p>
                    </div>

                    <!-- Content -->
                    <div id="courseLearningContent" class="hidden">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Topics your child has learned in this course:</p>
                        <div id="topicsList" class="flex flex-wrap gap-2 max-h-64 overflow-y-auto">
                            <!-- Topics will be populated here -->
                        </div>
                        <p id="noTopicsMessage" class="hidden text-gray-500 dark:text-gray-400 text-sm text-center py-4">No topics have been recorded for this course yet.</p>
                    </div>

                    <!-- Error State -->
                    <div id="courseLearningError" class="hidden py-8 text-center">
                        <svg class="w-12 h-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-red-600 dark:text-red-400" id="courseLearningErrorMessage">Failed to load topics</p>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 sm:px-6 flex justify-end">
                    <button type="button" onclick="closeCourseLearningModal()"
                            class="inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Request New Course Modal -->
    <div id="requestCourseModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeRequestCourseModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-sky-100 dark:bg-sky-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Request New Course
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                For: <span id="requestCourseStudentName" class="font-medium"></span>
                            </p>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="requestCourseName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Course Name
                                    </label>
                                    <input type="text" id="requestCourseName"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-sky-500 focus:border-sky-500"
                                           placeholder="e.g., Advanced Python, Data Science, etc.">
                                </div>
                                <div>
                                    <label for="requestCourseMessage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Additional Message (Optional)
                                    </label>
                                    <textarea id="requestCourseMessage" rows="3"
                                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-sky-500 focus:border-sky-500"
                                              placeholder="Why are you interested in this course?"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="button" onclick="submitCourseRequest()"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-sky-600 text-base font-medium text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Send Request
                    </button>
                    <button type="button" onclick="closeRequestCourseModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="requestCourseStudentId" value="">

    @push('scripts')
    <script>
        // Course Learning Modal Functions
        function openCourseLearningModal(studentId, courseId, courseTitle, status) {
            // Show modal
            document.getElementById('courseLearningModal').classList.remove('hidden');

            // Set title and status badge
            document.getElementById('courseLearningTitle').textContent = courseTitle;
            const statusBadge = document.getElementById('courseLearningStatus');
            if (status === 'completed') {
                statusBadge.textContent = 'Completed';
                statusBadge.className = 'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400';
                document.getElementById('courseLearningIcon').className = 'flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30';
            } else {
                statusBadge.textContent = 'In Progress';
                statusBadge.className = 'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-sky-100 text-sky-800 dark:bg-sky-900/30 dark:text-sky-400';
                document.getElementById('courseLearningIcon').className = 'flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-sky-100 dark:bg-sky-900/30';
            }

            // Show loading, hide content and error
            document.getElementById('courseLearningLoading').classList.remove('hidden');
            document.getElementById('courseLearningContent').classList.add('hidden');
            document.getElementById('courseLearningError').classList.add('hidden');

            // Fetch data
            fetch(`/parent/children/${studentId}/course-learning?course_id=${courseId}&course_title=${encodeURIComponent(courseTitle)}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('courseLearningLoading').classList.add('hidden');

                if (data.success) {
                    document.getElementById('courseLearningContent').classList.remove('hidden');

                    const topicsList = document.getElementById('topicsList');
                    const noTopicsMsg = document.getElementById('noTopicsMessage');
                    topicsList.innerHTML = '';

                    if (data.data.topics && data.data.topics.length > 0) {
                        noTopicsMsg.classList.add('hidden');
                        data.data.topics.forEach(topic => {
                            const badge = document.createElement('span');
                            badge.className = 'inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-sky-50 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300 border border-sky-200 dark:border-sky-800';
                            badge.textContent = topic;
                            topicsList.appendChild(badge);
                        });
                    } else {
                        noTopicsMsg.classList.remove('hidden');
                    }
                } else {
                    document.getElementById('courseLearningError').classList.remove('hidden');
                    document.getElementById('courseLearningErrorMessage').textContent = data.error || 'Failed to load topics';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('courseLearningLoading').classList.add('hidden');
                document.getElementById('courseLearningError').classList.remove('hidden');
                document.getElementById('courseLearningErrorMessage').textContent = 'An error occurred while loading topics';
            });
        }

        function closeCourseLearningModal() {
            document.getElementById('courseLearningModal').classList.add('hidden');
        }

        function openRequestCourseModal(studentId, studentName) {
            document.getElementById('requestCourseStudentId').value = studentId;
            document.getElementById('requestCourseStudentName').textContent = studentName;
            document.getElementById('requestCourseName').value = '';
            document.getElementById('requestCourseMessage').value = '';
            document.getElementById('requestCourseModal').classList.remove('hidden');
        }

        function closeRequestCourseModal() {
            document.getElementById('requestCourseModal').classList.add('hidden');
        }

        function submitCourseRequest() {
            const studentId = document.getElementById('requestCourseStudentId').value;
            const courseName = document.getElementById('requestCourseName').value.trim();
            const message = document.getElementById('requestCourseMessage').value.trim();

            if (!courseName) {
                alert('Please enter a course name.');
                return;
            }

            fetch('{{ route("parent.children.request-course") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    student_id: studentId,
                    course_name: courseName,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeRequestCourseModal();
                } else {
                    alert(data.error || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
    @endpush
</x-parent-layout>
