@extends('layouts.student')

@section('content')
<div class="space-y-6">
    <!-- Student Switcher (Parents Only) -->
    @if(auth()->user()->role === 'parent' && isset($students) && count($students) > 1)
        <x-student.student-switcher :students="$students" :currentStudent="$student" />
    @endif

    <!-- Welcome Header -->
    <x-ui.glass-card>
        <div class="bg-gradient-to-r from-sky-500 to-cyan-400 text-white rounded-2xl p-6 md:p-8 -m-6 mb-0">
            <div class="flex items-center justify-between">
                <div>
                    @if(auth()->user()->role === 'parent')
                        <h1 class="text-2xl md:text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h1>
                        <p class="text-sky-100 text-sm md:text-base">
                            Here's {{ $student->first_name }}'s learning progress
                        </p>
                    @else
                        <h1 class="text-2xl md:text-3xl font-bold mb-2">Welcome back, {{ $student->first_name }}!</h1>
                        <p class="text-sky-100 text-sm md:text-base">
                            Keep up the great work on your coding journey
                        </p>
                    @endif
                </div>
                <div class="hidden md:block">
                    <svg class="w-20 h-20 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
        </div>
    </x-ui.glass-card>

    <!-- Main Stats Cards -->
    <div class="grid gap-4 md:gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
        <!-- Progress Level Card -->
        <x-ui.glass-card padding="p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center text-sky-600 dark:text-sky-400 mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                        <span class="text-xs font-medium uppercase tracking-wider">Progress</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $student->progressPercentage() }}%</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Overall Progress</p>
                </div>
                <div class="ml-4">
                    <x-ui.progress-circle :percentage="$student->progressPercentage()" :size="80" :strokeWidth="6" />
                </div>
            </div>
        </x-ui.glass-card>

        <!-- Milestones Completed Card -->
        <x-ui.glass-card padding="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Milestones</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $completedMilestones }}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Completed</p>
                </div>
            </div>
        </x-ui.glass-card>

        <!-- Last Report Card -->
        <x-ui.glass-card padding="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Last Report</p>
                    @if($lastReport)
                        <p class="text-base font-bold text-gray-900 dark:text-white mt-1">{{ $lastReport->month }}</p>
                        <x-ui.status-badge :status="$lastReport->status" class="mt-1" />
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">No reports yet</p>
                    @endif
                </div>
            </div>
        </x-ui.glass-card>

        <!-- Next Milestone Card -->
        <x-ui.glass-card padding="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Next Up</p>
                    @if($student->roadmap_next_milestone)
                        <p class="text-sm font-bold text-gray-900 dark:text-white mt-1 leading-tight">{{ Str::limit($student->roadmap_next_milestone, 30) }}</p>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Keep going!</p>
                    @endif
                </div>
            </div>
        </x-ui.glass-card>
    </div>

    <!-- Progress Roadmap -->
    <x-student.roadmap-tracker
        :currentStage="$student->roadmap_stage ?? 'Intro to CS'"
        :progress="$student->progressPercentage()" />

    <!-- Recent Activity & Reports Grid -->
    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Recent Progress -->
        <x-ui.glass-card>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Progress</h3>
                <a href="{{ route('student.progress.index') }}" class="text-sm font-medium text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 transition-colors">
                    View All →
                </a>
            </div>

            @if($recentProgress->count() > 0)
                <div class="space-y-3">
                    @foreach($recentProgress as $progress)
                        <div class="flex items-start space-x-3 p-3 rounded-xl {{ $progress->completed ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700' }}">
                            <div class="flex-shrink-0 mt-1">
                                @if($progress->completed)
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $progress->title }}</p>
                                @if($progress->description)
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($progress->description, 80) }}</p>
                                @endif
                                @if($progress->completed && $progress->completed_at)
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                        Completed {{ $progress->completed_at->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                            @if($progress->points)
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-amber-700 dark:text-amber-300 bg-amber-100 dark:bg-amber-900/30 rounded-full">
                                        +{{ $progress->points }} pts
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <x-ui.empty-state
                    title="No progress yet"
                    description="Your learning milestones will appear here as you complete them"
                    icon="chart" />
            @endif
        </x-ui.glass-card>

        <!-- Recent Reports -->
        <x-ui.glass-card>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Reports</h3>
                <a href="{{ route('student.reports.index') }}" class="text-sm font-medium text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 transition-colors">
                    View All →
                </a>
            </div>

            @if($recentReports->count() > 0)
                <div class="space-y-3">
                    @foreach($recentReports as $report)
                        <a href="{{ route('student.reports.show', $report->id) }}" class="block p-4 rounded-xl bg-gradient-to-r from-sky-50 to-cyan-50 dark:from-sky-900/20 dark:to-cyan-900/20 border border-sky-200 dark:border-sky-800 hover:shadow-lg transition-all duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $report->month }}</h4>
                                        <x-ui.status-badge :status="$report->status" />
                                    </div>
                                    @if($report->summary)
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">{{ $report->summary }}</p>
                                    @endif
                                    @if($report->tutor)
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                            Tutor: {{ $report->tutor->full_name }}
                                        </p>
                                    @endif
                                </div>
                                <svg class="w-5 h-5 text-gray-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <x-ui.empty-state
                    title="No reports available"
                    description="Monthly reports from your tutor will appear here"
                    icon="document" />
            @endif
        </x-ui.glass-card>
    </div>

    <!-- Notifications Preview (if any recent notifications) -->
    @if(isset($recentNotifications) && $recentNotifications->count() > 0)
        <x-ui.glass-card>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Notifications</h3>
                <a href="{{ route('student.notifications.index') }}" class="text-sm font-medium text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 transition-colors">
                    View All →
                </a>
            </div>

            <div class="space-y-2">
                @foreach($recentNotifications->take(3) as $notification)
                    <div class="flex items-start space-x-3 p-3 rounded-xl {{ $notification->read_at ? 'bg-gray-50 dark:bg-gray-800/50' : 'bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-800' }}">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-2 h-2 rounded-full {{ $notification->read_at ? 'bg-gray-400' : 'bg-sky-500' }}"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $notification->data['title'] ?? 'Notification' }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-ui.glass-card>
    @endif
</div>
@endsection
