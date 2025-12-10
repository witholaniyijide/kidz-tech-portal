<x-tutor-layout>
    <!-- Breadcrumbs -->
    <x-tutor.breadcrumbs :items="[
        ['label' => 'Today\'s Schedule']
    ]" />

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
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
            <svg class="w-6 h-6 inline-block mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Today's Classes - {{ now()->format('l, F d, Y') }}
        </h2>

        <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
            @forelse($todayClasses as $class)
                <div class="mb-4 last:mb-0 p-4 bg-white/30 dark:bg-gray-800/30 rounded-xl hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-lg text-gray-900 dark:text-white">
                                {{ $class['student']->fullName() ?? 'Unknown Student' }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center mt-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $class['time'] ?? 'TBD' }}
                            </p>
                        </div>
                        <span class="px-3 py-1 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs font-semibold rounded-full">
                            Today
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        No Classes Today
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        You don't have any classes scheduled for today. Enjoy your day!
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- This Week's Classes -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
            <svg class="w-6 h-6 inline-block mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            This Week's Classes
        </h2>

        <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
            @forelse($weekClasses as $class)
                <div class="mb-4 last:mb-0 p-4 bg-white/30 dark:bg-gray-800/30 rounded-xl hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-lg text-gray-900 dark:text-white">
                                {{ $class['student']->fullName() ?? 'Unknown Student' }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center mt-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($class['schedule_date'])->format('l, M d, Y') }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center mt-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $class['time'] ?? 'TBD' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        No Classes This Week
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        You don't have any classes scheduled for this week yet.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</x-tutor-layout>
