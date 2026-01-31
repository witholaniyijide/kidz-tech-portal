<x-student-layout title="My Schedule">
    <div class="space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Schedule</h1>
            <p class="text-gray-600 dark:text-gray-400">View your weekly class schedule</p>
        </div>

        <!-- Today's Classes -->
        @if(isset($todayClasses) && count($todayClasses) > 0)
            <div class="glass-card rounded-2xl p-6 border-l-4 border-[#F5A623]">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-student flex items-center justify-center text-white mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Today's Classes</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $today }}, {{ now()->format('F j, Y') }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach($todayClasses as $class)
                        <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $class['course'] }}</p>
                                    @if(isset($class['tutor']))
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            Tutor: {{ $class['tutor']->first_name ?? 'TBD' }} {{ $class['tutor']->last_name ?? '' }}
                                        </p>
                                    @endif
                                </div>
                                @php
                                    try {
                                        $formattedTime = \Carbon\Carbon::parse($class['time'])->format('g:i A');
                                    } catch (\Exception $e) {
                                        $formattedTime = $class['time'];
                                    }
                                @endphp
                                <span class="text-sm font-medium text-amber-600 dark:text-amber-400">{{ $formattedTime }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="glass-card rounded-2xl p-6 border-l-4 border-gray-300">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-xl bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Today - {{ $today ?? now()->format('l') }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No classes scheduled for today</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Weekly Schedule -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Weekly Schedule</h2>
            </div>

            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    $today = $today ?? now()->format('l');
                @endphp

                @foreach($days as $day)
                    <div class="p-4 {{ $day === $today ? 'bg-amber-50 dark:bg-amber-900/10' : '' }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $day }}</span>
                                @if($day === $today)
                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-amber-200 dark:bg-amber-800 text-amber-800 dark:text-amber-200 rounded-full">
                                        Today
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if(isset($weeklySchedule[$day]) && count($weeklySchedule[$day]) > 0)
                            <div class="space-y-2">
                                @foreach($weeklySchedule[$day] as $class)
                                    <div class="p-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $class['course'] }}</p>
                                                @if(isset($class['tutor']))
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        Tutor: {{ $class['tutor']->first_name ?? 'TBD' }} {{ $class['tutor']->last_name ?? '' }}
                                                    </p>
                                                @endif
                                            </div>
                                            @php
                                                try {
                                                    $formattedTime = \Carbon\Carbon::parse($class['time'])->format('g:i A');
                                                } catch (\Exception $e) {
                                                    $formattedTime = $class['time'];
                                                }
                                            @endphp
                                            <span class="text-sm font-semibold text-[#F5A623]">{{ $formattedTime }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">No classes scheduled</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tutor Info -->
        @if(isset($student) && $student && $student->tutor)
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">My Tutor</h3>
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-student flex items-center justify-center text-white text-2xl font-bold">
                        {{ substr($student->tutor->first_name ?? 'T', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ $student->tutor->first_name }} {{ $student->tutor->last_name }}
                        </p>
                        @if($student->tutor->email)
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->tutor->email }}</p>
                        @endif
                        @if($student->tutor->phone)
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->tutor->phone }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-student-layout>
