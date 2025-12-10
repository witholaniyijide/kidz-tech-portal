@extends('layouts.student')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">My Progress</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Track your learning milestones and achievements</p>
        </div>
    </div>

    <!-- Progress Overview Card -->
    <x-ui.glass-card>
        <div class="grid gap-6 md:grid-cols-3">
            <div class="text-center">
                <x-ui.progress-circle :percentage="$student->progressPercentage()" :size="120" :strokeWidth="8" />
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mt-3">Overall Progress</p>
            </div>
            <div class="md:col-span-2 flex flex-col justify-center">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800">
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $completedCount }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Completed</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gradient-to-br from-blue-50 to-sky-50 dark:from-blue-900/20 dark:to-sky-900/20 border border-blue-200 dark:border-blue-800">
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $inProgressCount }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">In Progress</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200 dark:border-amber-800">
                        <p class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $totalPoints }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Points Earned</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border border-purple-200 dark:border-purple-800">
                        <p class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $student->roadmap_stage ?? 'Getting Started' }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Current Stage</p>
                    </div>
                </div>
            </div>
        </div>
    </x-ui.glass-card>

    <!-- Learning Roadmap -->
    <x-student.roadmap-tracker
        :currentStage="$student->roadmap_stage ?? 'Intro to CS'"
        :progress="$student->progressPercentage()" />

    <!-- Progress Timeline -->
    <x-ui.glass-card>
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Progress Timeline</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">All your learning milestones and achievements</p>
        </div>

        @if($progressItems->count() > 0)
            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-sky-500 via-cyan-400 to-blue-500"></div>

                <!-- Timeline Items -->
                <div class="space-y-6">
                    @foreach($progressItems as $progress)
                        <div class="relative flex items-start space-x-6 group">
                            <!-- Timeline Dot -->
                            <div class="relative flex-shrink-0 z-10">
                                <div class="w-8 h-8 rounded-full border-4 {{ $progress->completed ? 'bg-gradient-to-br from-green-500 to-emerald-500 border-white dark:border-gray-900' : 'bg-gradient-to-br from-blue-500 to-sky-500 border-white dark:border-gray-900' }} flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-200">
                                    @if($progress->completed)
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>

                            <!-- Progress Card -->
                            <div class="flex-1 rounded-xl p-5 {{ $progress->completed ? 'bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800' : 'bg-gradient-to-r from-blue-50 to-sky-50 dark:from-blue-900/20 dark:to-sky-900/20 border border-blue-200 dark:border-blue-800' }} hover:shadow-xl transition-shadow duration-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <h4 class="text-base font-bold text-gray-900 dark:text-white">{{ $progress->title }}</h4>
                                            @if($progress->completed)
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30 rounded-full">
                                                    ✓ Completed
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                                    In Progress
                                                </span>
                                            @endif
                                        </div>

                                        @if($progress->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $progress->description }}</p>
                                        @endif

                                        @if($progress->milestone_code)
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2 font-mono">{{ $progress->milestone_code }}</p>
                                        @endif

                                        <div class="flex items-center space-x-4 mt-3">
                                            @if($progress->completed && $progress->completed_at)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    Completed {{ $progress->completed_at->format('M d, Y') }}
                                                </span>
                                            @endif

                                            @if($progress->points)
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-bold text-amber-700 dark:text-amber-300 bg-amber-100 dark:bg-amber-900/30 rounded-full">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    +{{ $progress->points }} Points
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            @if($progressItems->hasPages())
                <div class="mt-6">
                    {{ $progressItems->links() }}
                </div>
            @endif
        @else
            <x-ui.empty-state
                title="No progress items yet"
                description="Your learning milestones and achievements will appear here as you make progress"
                icon="chart" />
        @endif
    </x-ui.glass-card>
</div>
@endsection
