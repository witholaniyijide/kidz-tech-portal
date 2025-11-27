@props(['student', 'showStats' => true])

<div class="rounded-2xl bg-white/20 dark:bg-gray-900/20 border border-white/10 dark:border-gray-700/10 shadow-lg backdrop-blur-xl p-4 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300">
    <div class="flex items-center space-x-4">
        <!-- Student Avatar -->
        <div class="flex-shrink-0">
            @if($student->profile_photo)
                <img src="{{ $student->profile_photo }}" alt="{{ $student->full_name }}" class="w-14 h-14 rounded-full object-cover border-2 border-sky-500">
            @else
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-sky-500 to-cyan-400 flex items-center justify-center text-white font-bold text-lg">
                    {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                </div>
            @endif
        </div>

        <!-- Student Info -->
        <div class="flex-1 min-w-0">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white truncate">
                {{ $student->full_name }}
            </h3>
            @if($showStats)
                <div class="flex items-center space-x-3 mt-1">
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        Grade {{ $student->age ?? 'N/A' }}
                    </span>
                    <span class="text-gray-400">•</span>
                    <span class="text-sm font-medium text-sky-600 dark:text-sky-400">
                        {{ $student->progressPercentage() }}% Progress
                    </span>
                </div>
            @else
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $student->roadmap_stage ?? 'Getting Started' }}
                </p>
            @endif
        </div>

        <!-- View Button -->
        <div class="flex-shrink-0">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
    </div>
</div>
