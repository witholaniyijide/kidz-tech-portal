@props(['currentStage' => 'Intro to CS', 'progress' => 0])

@php
    $stages = [
        [
            'name' => 'Intro to CS',
            'icon' => 'academic-cap',
            'color' => 'indigo',
            'description' => 'Computer Science Basics'
        ],
        [
            'name' => 'Scratch Beginner',
            'icon' => 'puzzle',
            'color' => 'purple',
            'description' => 'Block-Based Programming'
        ],
        [
            'name' => 'Scratch Intermediate',
            'icon' => 'sparkles',
            'color' => 'pink',
            'description' => 'Advanced Scratch Projects'
        ],
        [
            'name' => 'Scratch Advanced',
            'icon' => 'star',
            'color' => 'rose',
            'description' => 'Complex Game Development'
        ],
        [
            'name' => 'Game Dev',
            'icon' => 'device-tablet',
            'color' => 'orange',
            'description' => 'Game Design & Development'
        ],
        [
            'name' => 'App Dev',
            'icon' => 'device-phone-mobile',
            'color' => 'amber',
            'description' => 'Mobile App Development'
        ],
        [
            'name' => 'Web Dev',
            'icon' => 'globe',
            'color' => 'emerald',
            'description' => 'Web Development'
        ],
        [
            'name' => 'Python',
            'icon' => 'code',
            'color' => 'cyan',
            'description' => 'Python Programming'
        ],
        [
            'name' => 'Robotics',
            'icon' => 'cpu-chip',
            'color' => 'sky',
            'description' => 'Robotics & Hardware'
        ],
    ];

    $currentStageIndex = collect($stages)->search(fn($stage) => $stage['name'] === $currentStage);
    if ($currentStageIndex === false) {
        $currentStageIndex = 0;
    }
@endphp

<x-ui.glass-card>
    <div class="mb-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Learning Roadmap</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">Track your progress through the KidzTech curriculum</p>
    </div>

    <!-- Desktop/Tablet: Horizontal Stepper -->
    <div class="hidden md:block">
        <div class="relative">
            <!-- Progress Line -->
            <div class="absolute top-8 left-0 right-0 h-1 bg-gray-200 dark:bg-gray-700 rounded-full">
                <div class="h-full bg-gradient-to-r from-sky-500 to-cyan-400 rounded-full transition-all duration-1000"
                     style="width: {{ $progress }}%"></div>
            </div>

            <!-- Stages -->
            <div class="relative flex justify-between">
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

                    <div class="flex flex-col items-center" style="width: {{ 100 / count($stages) }}%">
                        <!-- Stage Circle -->
                        <div class="relative z-10 flex items-center justify-center w-16 h-16 rounded-full border-4 transition-all duration-300 {{ $isPast || $isCurrent ? 'bg-gradient-to-br ' . $colorClasses[$stage['color']] . ' border-white dark:border-gray-900 shadow-lg' : 'bg-gray-200 dark:bg-gray-700 border-gray-300 dark:border-gray-600' }}">
                            @if($isPast)
                                <!-- Checkmark for completed stages -->
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($isCurrent)
                                <!-- Pulse for current stage -->
                                <div class="animate-pulse-slow">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                            @else
                                <!-- Lock for future stages -->
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            @endif
                        </div>

                        <!-- Stage Info -->
                        <div class="mt-3 text-center">
                            <p class="text-xs font-semibold {{ $isPast || $isCurrent ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-600' }}">
                                {{ $stage['name'] }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1 hidden lg:block">
                                {{ $stage['description'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Mobile: Vertical Stepper -->
    <div class="md:hidden space-y-4">
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

            <div class="flex items-start space-x-4">
                <!-- Stage Circle -->
                <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-full border-4 {{ $isPast || $isCurrent ? 'bg-gradient-to-br ' . $colorClasses[$stage['color']] . ' border-white dark:border-gray-900 shadow-lg' : 'bg-gray-200 dark:bg-gray-700 border-gray-300 dark:border-gray-600' }}">
                    @if($isPast)
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($isCurrent)
                        <div class="animate-pulse-slow">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    @else
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    @endif
                </div>

                <!-- Stage Info -->
                <div class="flex-1 pb-8 {{ !$isFuture ? 'border-l-2 border-sky-200 dark:border-sky-900 pl-4' : 'pl-4' }}">
                    <p class="text-sm font-semibold {{ $isPast || $isCurrent ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-600' }}">
                        {{ $stage['name'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                        {{ $stage['description'] }}
                    </p>
                    @if($isCurrent)
                        <span class="inline-flex items-center px-2 py-1 mt-2 text-xs font-medium text-sky-700 dark:text-sky-300 bg-sky-100 dark:bg-sky-900/30 rounded-full">
                            Current Stage
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-ui.glass-card>
