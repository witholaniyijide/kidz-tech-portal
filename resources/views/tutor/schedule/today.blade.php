<x-tutor-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
            My Schedule
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            View your class schedule for today and this week
        </p>
    </div>

    <!-- Today's Classes -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Today's Classes</h2>

        <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
            @forelse($todayClasses as $class)
                <div class="mb-4 last:mb-0 p-4 bg-white/30 dark:bg-gray-800/30 rounded-xl">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white">
                                {{ $class['student']->fullName() ?? 'Unknown Student' }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Time: {{ $class['time'] ?? 'TBD' }}
                            </p>
                        </div>
                        <span class="px-3 py-1 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs rounded-full">
                            Today
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 dark:text-gray-400 text-center py-4">
                    No classes scheduled for today.
                </p>
            @endforelse
        </div>
    </div>

    <!-- This Week's Classes -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">This Week's Classes</h2>

        <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
            @forelse($weekClasses as $class)
                <div class="mb-4 last:mb-0 p-4 bg-white/30 dark:bg-gray-800/30 rounded-xl">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white">
                                {{ $class['student']->fullName() ?? 'Unknown Student' }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Date: {{ \Carbon\Carbon::parse($class['schedule_date'])->format('M d, Y') }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Time: {{ $class['time'] ?? 'TBD' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 dark:text-gray-400 text-center py-4">
                    No classes scheduled for this week.
                </p>
            @endforelse
        </div>
    </div>
</x-tutor-layout>
