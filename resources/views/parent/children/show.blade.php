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
                <div class="w-24 h-24 rounded-2xl bg-parent-gradient flex items-center justify-center text-white shadow-xl overflow-hidden">
                    @if($student->profile_photo)
                        <img src="{{ asset('storage/' . $student->profile_photo) }}"
                             alt="{{ $student->full_name }}"
                             class="w-full h-full object-cover">
                    @else
                        <span class="text-4xl font-heading font-bold">{{ substr($student->first_name, 0, 1) }}</span>
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

                <!-- Progress Circle -->
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
                            @if($student->tutor->email)
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->tutor->email }}</p>
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No tutor assigned</p>
                @endif
            </div>

            <!-- Class Schedule -->
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">Class Schedule</h3>
                @if($classSchedule && count($classSchedule) > 0)
                    <div class="space-y-2">
                        @foreach($classSchedule as $schedule)
                            <div class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>
                                    @if(isset($schedule['day']))
                                        {{ $schedule['day'] }} {{ $schedule['time'] ?? '' }}
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
                    <p class="font-medium text-gray-800 dark:text-white">Stage {{ $student->roadmap_stage ?? 1 }} of 12</p>
                </div>
            </div>
        </div>

        <!-- Curriculum Roadmap -->
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-lg font-heading font-bold text-gray-800 dark:text-white mb-6">Curriculum Roadmap</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($curriculumRoadmap as $course)
                    <div class="relative">
                        <div class="p-4 rounded-xl border-2 transition-all duration-200
                                    {{ $course['status'] === 'completed' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' :
                                       ($course['status'] === 'current' ? 'border-sky-500 bg-sky-50 dark:bg-sky-900/20' :
                                       'border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800') }}">
                            <div class="w-10 h-10 mx-auto mb-2 rounded-lg flex items-center justify-center
                                        {{ $course['status'] === 'completed' ? 'bg-emerald-500 text-white' :
                                           ($course['status'] === 'current' ? 'bg-sky-500 text-white' :
                                           'bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400') }}">
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
                            @elseif($course['status'] === 'current')
                                <div class="mt-2">
                                    <div class="h-1 bg-gray-200 dark:bg-gray-700 rounded-full">
                                        <div class="h-1 bg-sky-500 rounded-full" style="width: {{ $course['progress'] }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
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

            <!-- Certifications -->
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-heading font-bold text-gray-800 dark:text-white">Certifications</h3>
                    <a href="{{ route('parent.certifications.index') }}?student_id={{ $student->id }}"
                       class="text-sm text-sky-600 dark:text-sky-400 hover:underline">View All</a>
                </div>
                @if($certifications->count() > 0)
                    <div class="space-y-3">
                        @foreach($certifications as $cert)
                            <div class="p-3 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-800 dark:text-white text-sm truncate">{{ $cert->title }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $cert->issue_date->format('M d, Y') }}</p>
                                    </div>
                                    <a href="{{ route('parent.certifications.download', $cert) }}"
                                       class="text-amber-600 hover:text-amber-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 dark:text-gray-400 py-4">No certificates yet</p>
                @endif
            </div>
        </div>
    </div>
</x-parent-layout>
