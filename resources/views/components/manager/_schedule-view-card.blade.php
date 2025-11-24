@props([
    'schedule' => []
])

<x-ui.glass-card class="h-full">
    <div class="flex items-center justify-between mb-6">
        <x-ui.section-title>Today's Schedule</x-ui.section-title>
        <span class="px-3 py-1 rounded-full bg-gradient-manager text-white text-xs font-semibold">
            {{ count($schedule) }} {{ count($schedule) === 1 ? 'Class' : 'Classes' }}
        </span>
    </div>

    @if(count($schedule) > 0)
        <div class="space-y-3 mb-6 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
            @foreach($schedule as $class)
            <div class="p-4 rounded-xl bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 dark:border-gray-700/10 hover:border-sky-400/30 transition-all duration-200">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $class['name'] ?? 'Class' }}</h4>
                            @if($class['status'] ?? false)
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $class['status'] === 'live' ? 'bg-red-500/20 text-red-600 dark:text-red-400' : 'bg-amber-500/20 text-amber-600 dark:text-amber-400' }}">
                                    {{ ucfirst($class['status']) }}
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $class['tutor'] ?? 'Tutor Name' }}
                            </span>
                        </p>
                        @if(isset($class['students']))
                        <p class="text-xs text-gray-500 dark:text-gray-500">
                            {{ $class['students'] }} {{ $class['students'] === 1 ? 'student' : 'students' }}
                        </p>
                        @endif
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-sm font-semibold text-sky-600 dark:text-sky-400">{{ $class['time'] ?? '10:00 AM' }}</p>
                        @if(isset($class['duration']))
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $class['duration'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="py-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-gray-600 dark:text-gray-400">No classes scheduled for today.</p>
        </div>
    @endif

    <div class="mt-6 pt-4 border-t border-white/10 dark:border-gray-700/10">
        <x-ui.gradient-button
            href="{{ route('schedule.weekly') }}"
            gradient="bg-gradient-manager"
            aria-label="View Full Schedule"
            class="w-full justify-center"
        >
            <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            View Full Schedule
        </x-ui.gradient-button>
    </div>
</x-ui.glass-card>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #0ea5e9, #38bdf8);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #0284c7, #0ea5e9);
}
</style>
