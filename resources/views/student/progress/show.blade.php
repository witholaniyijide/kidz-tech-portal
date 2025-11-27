@extends('layouts.student')

@section('content')
<div class="space-y-6 max-w-4xl">
    <!-- Back Button & Header -->
    <div class="flex items-center space-x-4">
        <a href="{{ route('student.progress.index') }}" class="p-2 rounded-xl bg-white/50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 hover:bg-white/70 dark:hover:bg-gray-800/70 transition-all border border-gray-200 dark:border-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">{{ $progress->title }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Milestone Details</p>
        </div>
    </div>

    <!-- Milestone Status Card -->
    <x-ui.glass-card>
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <!-- Completion Badge -->
                <div class="flex-shrink-0">
                    @if($progress->completed)
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center text-white shadow-lg">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @else
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-sky-500 flex items-center justify-center text-white shadow-lg">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Status Info -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        @if($progress->completed)
                            Milestone Completed!
                        @else
                            In Progress
                        @endif
                    </h3>
                    @if($progress->completed && $progress->completed_at)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Completed on {{ $progress->completed_at->format('M d, Y') }}
                        </p>
                    @else
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Keep going! You're making great progress.
                        </p>
                    @endif
                </div>
            </div>

            <!-- Points Badge -->
            @if($progress->points)
                <div class="flex flex-col items-center">
                    <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl shadow-lg">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="font-bold text-xl">{{ $progress->points }}</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">XP Points</p>
                </div>
            @endif
        </div>
    </x-ui.glass-card>

    <!-- Description -->
    @if($progress->description)
        <x-ui.glass-card>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Description
            </h3>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $progress->description }}</p>
        </x-ui.glass-card>
    @endif

    <!-- Skills Gained & Curriculum Stage -->
    <div class="grid gap-6 md:grid-cols-2">
        <!-- Skills Gained -->
        <x-ui.glass-card>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                Skills Gained
            </h3>
            <ul class="space-y-2">
                <li class="flex items-start text-sm text-gray-700 dark:text-gray-300">
                    <svg class="w-5 h-5 mr-2 mt-0.5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Problem-solving
                </li>
                <li class="flex items-start text-sm text-gray-700 dark:text-gray-300">
                    <svg class="w-5 h-5 mr-2 mt-0.5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Logical thinking
                </li>
                <li class="flex items-start text-sm text-gray-700 dark:text-gray-300">
                    <svg class="w-5 h-5 mr-2 mt-0.5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Coding fundamentals
                </li>
            </ul>
        </x-ui.glass-card>

        <!-- Curriculum Stage -->
        @if($progress->milestone_code)
            <x-ui.glass-card>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Milestone Code
                </h3>
                <p class="font-mono text-sm text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 px-3 py-2 rounded-lg">
                    {{ $progress->milestone_code }}
                </p>
                @if($curriculumStage)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                        Part of: <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $curriculumStage }}</span>
                    </p>
                @endif
            </x-ui.glass-card>
        @endif
    </div>

    <!-- Related Activities & Next Steps -->
    <x-ui.glass-card>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
            What's Next?
        </h3>

        @if($nextMilestone)
            <div class="p-4 rounded-xl bg-gradient-to-r from-sky-50 to-cyan-50 dark:from-sky-900/20 dark:to-cyan-900/20 border border-sky-200 dark:border-sky-800">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Recommended Next Milestone:</p>
                <a href="{{ route('student.progress.show', $nextMilestone->id) }}" class="text-lg font-bold text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 transition-colors">
                    {{ $nextMilestone->title }} →
                </a>
                @if($nextMilestone->points)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Worth {{ $nextMilestone->points }} XP points</p>
                @endif
            </div>
        @else
            <p class="text-gray-700 dark:text-gray-300">
                Great work! Continue exploring your current curriculum stage and complete more milestones.
            </p>
        @endif
    </x-ui.glass-card>

    <!-- Completion Badge (if completed) -->
    @if($progress->completed)
        <x-ui.glass-card>
            <div class="text-center py-8">
                <div class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-gradient-to-br from-amber-500 via-orange-500 to-red-500 text-white shadow-2xl mb-4 animate-pulse-slow">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Achievement Unlocked!</h3>
                <p class="text-gray-600 dark:text-gray-400">You've successfully completed this milestone</p>
            </div>
        </x-ui.glass-card>
    @endif
</div>
@endsection
