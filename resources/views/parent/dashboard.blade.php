<x-parent-layout>
    <x-slot name="title">Welcome Back, {{ auth()->user()->name }}</x-slot>
    <x-slot name="subtitle">Track your children's coding journey</x-slot>

    <div class="space-y-6">
        <!-- Child Selector (if multiple children) -->
        @if($children->count() > 1)
            <div class="glass-card rounded-2xl p-4">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Viewing:</span>
                    <div class="flex items-center space-x-2 flex-wrap gap-2">
                        @foreach($children as $child)
                            <button onclick="switchChild({{ $child->id }})"
                                    class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-all duration-200
                                           {{ $selectedChild->id === $child->id
                                              ? 'bg-parent-gradient text-white shadow-lg'
                                              : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-semibold">
                                    {{ substr($child->first_name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium">{{ $child->first_name }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Hero Section -->
        <div class="glass-card rounded-2xl p-6 hover-lift">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <!-- Child Info -->
                <div class="flex items-center space-x-4">
                    <div class="w-20 h-20 rounded-2xl bg-parent-gradient flex items-center justify-center text-white shadow-xl overflow-hidden">
                        @if($selectedChild->profile_photo)
                            <img src="{{ asset('storage/' . $selectedChild->profile_photo) }}"
                                 alt="{{ $selectedChild->full_name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <span class="text-3xl font-heading font-bold">{{ substr($selectedChild->first_name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-2xl font-heading font-bold text-gray-800 dark:text-white">
                            {{ $selectedChild->full_name }}
                        </h2>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                            @if($selectedChild->tutor)
                                Tutor: {{ $selectedChild->tutor->first_name }} {{ $selectedChild->tutor->last_name }}
                            @else
                                No tutor assigned
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Class Schedule -->
                <div class="bg-sky-50 dark:bg-sky-900/30 rounded-xl p-4">
                    <h4 class="text-sm font-semibold text-sky-700 dark:text-sky-400 mb-2">Weekly Schedule</h4>
                    @if($selectedChild->class_schedule && is_array($selectedChild->class_schedule) && count($selectedChild->class_schedule) > 0)
                        <div class="space-y-1">
                            @foreach($selectedChild->class_schedule as $schedule)
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    @if(is_array($schedule))
                                        {{ $schedule['day'] ?? '' }} {{ $schedule['time'] ?? '' }}
                                    @else
                                        {{ $schedule }}
                                    @endif
                                </p>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">No schedule set</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Overall Progress -->
            <div class="glass-card rounded-2xl p-5 hover-lift">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-sky-100 dark:bg-sky-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-heading font-bold text-gray-800 dark:text-white">{{ $overallProgress }}%</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Overall Progress</p>
            </div>

            <!-- Milestones Completed -->
            <div class="glass-card rounded-2xl p-5 hover-lift">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-heading font-bold text-gray-800 dark:text-white">{{ $milestonesCompleted }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Milestones Completed</p>
            </div>

            <!-- Last Report -->
            <div class="glass-card rounded-2xl p-5 hover-lift">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xl font-heading font-bold text-gray-800 dark:text-white">
                    @if($lastReport)
                        {{ $lastReport->month }}
                    @else
                        None
                    @endif
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Last Monthly Report</p>
            </div>

            <!-- Next Milestone -->
            <div class="glass-card rounded-2xl p-5 hover-lift">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-heading font-bold text-gray-800 dark:text-white line-clamp-2">
                    @if($nextMilestone)
                        {{ $nextMilestone['title'] ?? 'Continue Learning' }}
                    @else
                        All caught up!
                    @endif
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Next Milestone</p>
            </div>
        </div>

        <!-- Curriculum Roadmap -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-heading font-bold text-gray-800 dark:text-white">Curriculum Roadmap</h3>
                <a href="{{ route('parent.performance.index') }}"
                   class="text-sm text-sky-600 dark:text-sky-400 hover:underline">
                    View Details
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($curriculumRoadmap as $course)
                    <div class="relative group">
                        <div class="p-4 rounded-xl border-2 transition-all duration-200
                                    {{ $course['status'] === 'completed' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' :
                                       ($course['status'] === 'current' ? 'border-sky-500 bg-sky-50 dark:bg-sky-900/20' :
                                       'border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800') }}">
                            <!-- Icon -->
                            <div class="w-10 h-10 mx-auto mb-2 rounded-lg flex items-center justify-center
                                        {{ $course['status'] === 'completed' ? 'bg-emerald-500 text-white' :
                                           ($course['status'] === 'current' ? 'bg-sky-500 text-white' :
                                           'bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400') }}">
                                @include('parent.partials.course-icon', ['icon' => $course['icon']])
                            </div>
                            <!-- Title -->
                            <p class="text-xs text-center font-medium text-gray-700 dark:text-gray-300 line-clamp-2">
                                {{ $course['title'] }}
                            </p>
                            <!-- Progress indicator -->
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
                                    <p class="text-xs text-center text-sky-600 dark:text-sky-400 mt-1">{{ $course['progress'] }}%</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Recent Reports -->
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-heading font-bold text-gray-800 dark:text-white">Recent Reports</h3>
                    <a href="{{ route('parent.reports.index') }}"
                       class="text-sm text-sky-600 dark:text-sky-400 hover:underline">View All</a>
                </div>

                @if($recentReports->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentReports as $report)
                            <a href="{{ route('parent.reports.show', ['student' => $report->student_id, 'report' => $report->id]) }}"
                               class="block p-4 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white">
                                            {{ $report->month }} {{ $report->year }} Report
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $report->student->first_name ?? 'Student' }} - {{ $report->tutor ? $report->tutor->first_name : 'Unknown' }}
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">No reports available yet</p>
                    </div>
                @endif
            </div>

            <!-- Certifications -->
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-heading font-bold text-gray-800 dark:text-white">Certifications</h3>
                    <a href="{{ route('parent.certifications.index') }}"
                       class="text-sm text-sky-600 dark:text-sky-400 hover:underline">View All</a>
                </div>

                @if($recentCertifications->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentCertifications as $cert)
                            <div class="p-4 rounded-xl bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200 dark:border-amber-800">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg bg-amber-500 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-800 dark:text-white truncate">{{ $cert->title }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $cert->student->first_name ?? 'Student' }} - {{ $cert->issue_date->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <a href="{{ route('parent.certifications.download', $cert) }}"
                                       class="p-2 text-amber-600 hover:bg-amber-100 dark:hover:bg-amber-900/30 rounded-lg transition-colors flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">No certificates earned yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function switchChild(studentId) {
            fetch('{{ route("parent.dashboard.switch-child") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ student_id: studentId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error switching child:', error);
            });
        }
    </script>
    @endpush
</x-parent-layout>
