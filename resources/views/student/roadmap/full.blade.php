@extends('layouts.student')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Learning Roadmap</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Your journey through the KidzTech coding curriculum</p>
        </div>
        <a href="/curriculum.pdf" target="_blank" class="px-4 py-2 bg-gradient-to-r from-sky-500 to-cyan-400 text-white text-sm font-medium rounded-xl hover:shadow-lg transition-all duration-200 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span class="hidden sm:inline">View Full Curriculum</span>
        </a>
    </div>

    <!-- Progress Overview -->
    <x-ui.glass-card>
        <div class="grid gap-6 md:grid-cols-4">
            <div class="text-center">
                <x-ui.progress-circle :percentage="$student->progressPercentage() ?? 0" :size="100" :strokeWidth="6" />
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mt-3">Overall Progress</p>
            </div>
            <div class="flex flex-col justify-center items-center p-4 rounded-xl bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800">
                <p class="text-4xl font-bold text-green-600 dark:text-green-400">{{ $completedStages ?? 0 }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Stages Completed</p>
            </div>
            <div class="flex flex-col justify-center items-center p-4 rounded-xl bg-gradient-to-br from-blue-50 to-sky-50 dark:from-blue-900/20 dark:to-sky-900/20 border border-blue-200 dark:border-blue-800">
                <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $currentStage ?? 'Getting Started' }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Current Stage</p>
            </div>
            <div class="flex flex-col justify-center items-center p-4 rounded-xl bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border border-purple-200 dark:border-purple-800">
                <p class="text-4xl font-bold text-purple-600 dark:text-purple-400">{{ $remainingStages ?? 9 }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Stages Remaining</p>
            </div>
        </div>
    </x-ui.glass-card>

    <!-- Roadmap Tracker -->
    <x-student.roadmap-tracker
        :currentStage="$student->roadmap_stage ?? 'Intro to CS'"
        :progress="$student->progressPercentage() ?? 0" />

    <!-- Curriculum Stage Cards -->
    <div class="space-y-6">
        @php
            $stages = [
                [
                    'name' => 'Intro to CS',
                    'duration' => '4 weeks',
                    'prerequisites' => 'None',
                    'outcomes' => ['Understanding of basic computer concepts', 'Introduction to algorithms', 'Problem-solving basics'],
                    'color' => 'indigo'
                ],
                [
                    'name' => 'Scratch Beginner',
                    'duration' => '8 weeks',
                    'prerequisites' => 'Intro to CS',
                    'outcomes' => ['Block-based programming', 'Creating simple animations', 'Interactive storytelling'],
                    'color' => 'purple'
                ],
                [
                    'name' => 'Scratch Intermediate',
                    'duration' => '10 weeks',
                    'prerequisites' => 'Scratch Beginner',
                    'outcomes' => ['Advanced Scratch blocks', 'Game mechanics', 'Variables and lists'],
                    'color' => 'pink'
                ],
                [
                    'name' => 'Scratch Advanced',
                    'duration' => '12 weeks',
                    'prerequisites' => 'Scratch Intermediate',
                    'outcomes' => ['Complex game development', 'Broadcasting and cloning', 'Advanced algorithms'],
                    'color' => 'rose'
                ],
                [
                    'name' => 'Game Dev',
                    'duration' => '12 weeks',
                    'prerequisites' => 'Scratch Advanced',
                    'outcomes' => ['Game design principles', '2D game development', 'Physics and collision detection'],
                    'color' => 'orange'
                ],
                [
                    'name' => 'App Dev',
                    'duration' => '14 weeks',
                    'prerequisites' => 'Game Dev',
                    'outcomes' => ['Mobile app basics', 'UI/UX design', 'App deployment'],
                    'color' => 'amber'
                ],
                [
                    'name' => 'Web Dev',
                    'duration' => '16 weeks',
                    'prerequisites' => 'App Dev',
                    'outcomes' => ['HTML, CSS, JavaScript', 'Responsive design', 'Web hosting'],
                    'color' => 'emerald'
                ],
                [
                    'name' => 'Python',
                    'duration' => '16 weeks',
                    'prerequisites' => 'Web Dev',
                    'outcomes' => ['Text-based programming', 'Python syntax', 'Data structures and algorithms'],
                    'color' => 'cyan'
                ],
                [
                    'name' => 'Robotics',
                    'duration' => '12 weeks',
                    'prerequisites' => 'Python',
                    'outcomes' => ['Hardware programming', 'Sensors and actuators', 'Robotics projects'],
                    'color' => 'sky'
                ],
            ];

            $currentStageIndex = collect($stages)->search(fn($stage) => $stage['name'] === ($student->roadmap_stage ?? 'Intro to CS'));
            if ($currentStageIndex === false) {
                $currentStageIndex = 0;
            }
        @endphp

        @foreach($stages as $index => $stage)
            @php
                $isPast = $index < $currentStageIndex;
                $isCurrent = $index === $currentStageIndex;
                $isFuture = $index > $currentStageIndex;

                $colorClasses = [
                    'indigo' => 'from-indigo-500 to-indigo-600',
                    'purple' => 'from-purple-500 to-purple-600',
                    'pink' => 'from-pink-500 to-pink-600',
                    'rose' => 'from-rose-500 to-rose-600',
                    'orange' => 'from-orange-500 to-orange-600',
                    'amber' => 'from-amber-500 to-amber-600',
                    'emerald' => 'from-emerald-500 to-emerald-600',
                    'cyan' => 'from-cyan-500 to-cyan-600',
                    'sky' => 'from-sky-500 to-sky-600',
                ];
            @endphp

            <x-ui.glass-card padding="p-0">
                <div class="flex flex-col md:flex-row">
                    <!-- Stage Icon & Status -->
                    <div class="flex-shrink-0 p-6 bg-gradient-to-br {{ $colorClasses[$stage['color']] }} {{ $isFuture ? 'opacity-50' : '' }}">
                        <div class="flex flex-col items-center text-white">
                            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-3">
                                @if($isPast)
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @elseif($isCurrent)
                                    <svg class="w-8 h-8 animate-pulse-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                @else
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                @endif
                            </div>
                            <p class="text-xs font-semibold uppercase tracking-wider">
                                @if($isPast)
                                    Completed
                                @elseif($isCurrent)
                                    In Progress
                                @else
                                    Locked
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Stage Details -->
                    <div class="flex-1 p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $stage['name'] }}</h3>
                                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $stage['duration'] }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        Prerequisites: {{ $stage['prerequisites'] }}
                                    </span>
                                </div>
                            </div>

                            @if($isCurrent)
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-sky-700 dark:text-sky-300 bg-sky-100 dark:bg-sky-900/30 rounded-full">
                                    Current Stage
                                </span>
                            @elseif($isPast)
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30 rounded-full">
                                    ✓ Completed
                                </span>
                            @endif
                        </div>

                        <!-- Learning Outcomes -->
                        <div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Key Learning Outcomes:</p>
                            <ul class="space-y-1">
                                @foreach($stage['outcomes'] as $outcome)
                                    <li class="flex items-start text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-{{ $stage['color'] }}-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $outcome }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Milestone Progress -->
                        @if($isCurrent || $isPast)
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Milestones in this stage:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ $stageMilestones[$stage['name']]['completed'] ?? 0 }} / {{ $stageMilestones[$stage['name']]['total'] ?? 0 }}
                                    </span>
                                </div>
                                @if(isset($stageMilestones[$stage['name']]['total']) && $stageMilestones[$stage['name']]['total'] > 0)
                                    <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-gradient-to-r {{ $colorClasses[$stage['color']] }} h-2 rounded-full transition-all duration-500" style="width: {{ ($stageMilestones[$stage['name']]['completed'] / $stageMilestones[$stage['name']]['total']) * 100 }}%"></div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </x-ui.glass-card>
        @endforeach
    </div>
</div>
@endsection
