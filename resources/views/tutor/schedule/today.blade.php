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
            <svg class="w-6 h-6 inline-block mr-2 text-[#4B49AC]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Today's Classes - {{ now()->format('l, F d, Y') }}
        </h2>

        <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
            @forelse($todayClasses as $class)
                <div class="mb-4 last:mb-0 p-4 bg-white/30 dark:bg-gray-800/30 rounded-xl hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="font-bold text-lg text-gray-900 dark:text-white">
                                {{ $class['student']->fullName() ?? 'Unknown Student' }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center mt-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @php
                                    try {
                                        $formattedTime = \Carbon\Carbon::parse($class['time'])->format('g:i A');
                                    } catch (\Exception $e) {
                                        $formattedTime = $class['time'] ?? 'TBD';
                                    }
                                @endphp
                                {{ $formattedTime }}
                                @if(isset($class['duration']))
                                    <span class="mx-1">•</span>
                                    {{ $class['duration'] }}
                                @endif
                            </p>
                            <!-- Class Links -->
                            <div class="flex flex-wrap gap-2 mt-3">
                                @if($class['student']->class_link)
                                    <a href="{{ $class['student']->class_link }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        Join Class
                                    </a>
                                @endif
                                @if($class['student']->google_classroom_link)
                                    <a href="{{ $class['student']->google_classroom_link }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        Classroom
                                    </a>
                                @endif
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-gradient-to-r from-[#4B49AC] to-[#7978E9] text-white text-xs font-semibold rounded-full">
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
            <svg class="w-6 h-6 inline-block mr-2 text-[#4B49AC]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            This Week's Classes
        </h2>

        <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
            @forelse($weekClasses as $class)
                <div class="mb-4 last:mb-0 p-4 bg-white/30 dark:bg-gray-800/30 rounded-xl hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
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
                                @php
                                    try {
                                        $formattedTime = \Carbon\Carbon::parse($class['time'])->format('g:i A');
                                    } catch (\Exception $e) {
                                        $formattedTime = $class['time'] ?? 'TBD';
                                    }
                                @endphp
                                {{ $formattedTime }}
                                @if(isset($class['duration']))
                                    <span class="mx-1">•</span>
                                    {{ $class['duration'] }}
                                @endif
                            </p>
                            <!-- Class Links -->
                            <div class="flex flex-wrap gap-2 mt-3">
                                @if($class['student']->class_link)
                                    <a href="{{ $class['student']->class_link }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        Join Class
                                    </a>
                                @endif
                                @if($class['student']->google_classroom_link)
                                    <a href="{{ $class['student']->google_classroom_link }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        Classroom
                                    </a>
                                @endif
                            </div>
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
