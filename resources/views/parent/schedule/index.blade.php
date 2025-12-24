<x-parent-layout title="Class Schedule">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Class Schedule</h1>
                <p class="text-gray-600 dark:text-gray-400">View your children's weekly class schedules</p>
            </div>

            <!-- Child Filter -->
            @if($children->count() > 1)
                <form method="GET" action="{{ route('parent.schedule.index') }}">
                    <select name="child_id" onchange="this.form.submit()"
                            class="rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-[#F5A623] focus:border-[#F5A623]">
                        <option value="">All Children</option>
                        @foreach($children as $child)
                            <option value="{{ $child->id }}" {{ $selectedChildId == $child->id ? 'selected' : '' }}>
                                {{ $child->first_name }} {{ $child->last_name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>

        <!-- Today's Classes -->
        @if(count($todayClasses) > 0)
            <div class="glass-card rounded-2xl p-6 border-l-4 border-[#F5A623]">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-parent flex items-center justify-center text-white mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Today's Classes</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $today }}, {{ now()->format('F j, Y') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($todayClasses as $class)
                        <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $class['student']->first_name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $class['course'] }}</p>
                                </div>
                                <span class="text-sm font-medium text-amber-600 dark:text-amber-400">{{ $class['time'] }}</span>
                            </div>
                            @if($class['tutor'])
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    Tutor: {{ $class['tutor']->first_name ?? 'TBD' }} {{ $class['tutor']->last_name ?? '' }}
                                </p>
                            @endif
                        </div>
                    @endforeach
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
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($weeklySchedule[$day] as $class)
                                    <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-gradient-parent flex items-center justify-center text-white text-sm font-semibold mr-2">
                                                    {{ substr($class['student']->first_name, 0, 1) }}
                                                </div>
                                                <span class="font-medium text-gray-900 dark:text-white">{{ $class['student']->first_name }}</span>
                                            </div>
                                            <span class="text-sm font-semibold text-[#F5A623]">{{ $class['time'] }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $class['course'] }}</p>
                                        @if($class['tutor'])
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $class['tutor']->first_name ?? 'TBD' }} {{ $class['tutor']->last_name ?? '' }}
                                            </p>
                                        @endif
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

        <!-- Children Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($children as $child)
                <div class="glass-card rounded-2xl p-5 hover-lift">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-parent flex items-center justify-center text-white text-xl font-bold">
                            {{ substr($child->first_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $child->first_name }} {{ $child->last_name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $child->current_course ?? 'Coding Class' }}</p>
                        </div>
                    </div>

                    @if($child->tutor)
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Tutor: {{ $child->tutor->first_name }} {{ $child->tutor->last_name }}
                        </div>
                    @endif

                    @if($child->class_schedule && is_array($child->class_schedule) && count($child->class_schedule) > 0)
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ count($child->class_schedule) }} class{{ count($child->class_schedule) > 1 ? 'es' : '' }}/week
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">No schedule set</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-parent-layout>
