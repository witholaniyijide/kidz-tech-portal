<x-app-layout>
    <x-slot name="header">
        {{ __('Class Calendar') }}
    </x-slot>
    <x-slot name="title">{{ __('Calendar') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Class Calendar</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">View past classes and upcoming scheduled sessions</p>
                </div>
                <a href="{{ route('director.attendance.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    List View
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl p-4 shadow-sm border border-gray-200/50 dark:border-gray-700/50">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $monthStats['totalClasses'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Classes Held</div>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/30 rounded-xl p-4 shadow-sm border border-emerald-200/50 dark:border-emerald-700/50">
                    <div class="text-2xl font-bold text-emerald-600">{{ $monthStats['approved'] }}</div>
                    <div class="text-sm text-emerald-700 dark:text-emerald-400">Approved</div>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/30 rounded-xl p-4 shadow-sm border border-amber-200/50 dark:border-amber-700/50">
                    <div class="text-2xl font-bold text-amber-600">{{ $monthStats['pending'] }}</div>
                    <div class="text-sm text-amber-700 dark:text-amber-400">Pending</div>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-4 shadow-sm border border-blue-200/50 dark:border-blue-700/50">
                    <div class="text-2xl font-bold text-blue-600">{{ $monthStats['potentialTotal'] }}</div>
                    <div class="text-sm text-blue-700 dark:text-blue-400">Scheduled Classes</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl p-4 shadow-sm border border-gray-200/50 dark:border-gray-700/50 mb-6">
                <form method="GET" class="flex flex-wrap items-end gap-4">
                    <!-- Month/Year Navigation -->
                    <div class="flex items-center gap-2">
                        @php
                            $prevMonth = $month - 1;
                            $prevYear = $year;
                            if ($prevMonth < 1) {
                                $prevMonth = 12;
                                $prevYear--;
                            }
                            $nextMonth = $month + 1;
                            $nextYear = $year;
                            if ($nextMonth > 12) {
                                $nextMonth = 1;
                                $nextYear++;
                            }
                        @endphp
                        <a href="{{ route('director.calendar.index', array_merge(request()->except(['year', 'month', 'date']), ['year' => $prevYear, 'month' => $prevMonth])) }}"
                           class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white min-w-[140px] text-center">
                            {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
                        </span>
                        <a href="{{ route('director.calendar.index', array_merge(request()->except(['year', 'month', 'date']), ['year' => $nextYear, 'month' => $nextMonth])) }}"
                           class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    <div class="flex-1"></div>

                    <div class="w-44">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Tutor</label>
                        <select name="tutor_id" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">All Tutors</option>
                            @foreach($tutors as $tutor)
                                <option value="{{ $tutor->id }}" {{ $tutorFilter == $tutor->id ? 'selected' : '' }}>
                                    {{ $tutor->first_name }} {{ $tutor->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-44">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Student</label>
                        <select name="student_id" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">All Students</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ $studentFilter == $student->id ? 'selected' : '' }}>
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-32">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">All</option>
                            <option value="approved" {{ $statusFilter === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>

                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="month" value="{{ $month }}">

                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        Filter
                    </button>

                    @if($tutorFilter || $studentFilter || $statusFilter)
                        <a href="{{ route('director.calendar.index', ['year' => $year, 'month' => $month]) }}"
                           class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Calendar Grid -->
                <div class="lg:col-span-2">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                        <!-- Day Headers -->
                        <div class="grid grid-cols-7 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                                <div class="py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">
                                    {{ $dayName }}
                                </div>
                            @endforeach
                        </div>

                        <!-- Calendar Days -->
                        <div class="grid grid-cols-7">
                            @foreach($calendarDays as $day)
                                @php
                                    $hasClasses = $day['attendance']->count() > 0 || count($day['potential']) > 0;
                                    $isSelected = $selectedDate === $day['dateStr'];
                                @endphp
                                <a href="{{ route('director.calendar.index', array_merge(request()->all(), ['date' => $day['dateStr']])) }}"
                                   class="min-h-[100px] p-2 border-b border-r border-gray-200 dark:border-gray-700 transition-colors
                                          {{ $day['isCurrentMonth'] ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-800/50' }}
                                          {{ $isSelected ? 'ring-2 ring-indigo-500 ring-inset' : '' }}
                                          {{ $day['isToday'] ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}
                                          hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium {{ $day['isCurrentMonth'] ? ($day['isToday'] ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-900 dark:text-white') : 'text-gray-400 dark:text-gray-600' }}">
                                            {{ $day['day'] }}
                                        </span>
                                        @if($day['isToday'])
                                            <span class="px-1.5 py-0.5 text-xs bg-indigo-600 text-white rounded">Today</span>
                                        @endif
                                    </div>

                                    @if($day['isCurrentMonth'] && $hasClasses)
                                        <div class="space-y-1">
                                            @if($day['isPast'] || $day['isToday'])
                                                {{-- Past dates: show actual classes --}}
                                                @if($day['approvedCount'] > 0)
                                                    <div class="flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400">
                                                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                                        {{ $day['approvedCount'] }} approved
                                                    </div>
                                                @endif
                                                @if($day['pendingCount'] > 0)
                                                    <div class="flex items-center gap-1 text-xs text-amber-600 dark:text-amber-400">
                                                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                                        {{ $day['pendingCount'] }} pending
                                                    </div>
                                                @endif
                                            @else
                                                {{-- Future dates: show potential classes --}}
                                                @if($day['potentialCount'] > 0)
                                                    <div class="flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400">
                                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                                        {{ $day['potentialCount'] }} scheduled
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="mt-4 flex flex-wrap gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
                            <span class="text-gray-600 dark:text-gray-400">Approved attendance</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-amber-500 rounded-full"></span>
                            <span class="text-gray-600 dark:text-gray-400">Pending approval</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                            <span class="text-gray-600 dark:text-gray-400">Scheduled (future)</span>
                        </div>
                    </div>
                </div>

                <!-- Selected Date Details -->
                <div class="lg:col-span-1">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 sticky top-4">
                        @if($selectedDateData)
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $selectedDateData['date']->format('l, F j, Y') }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    @if($selectedDateData['isToday'])
                                        Today
                                    @elseif($selectedDateData['isPast'])
                                        {{ $selectedDateData['date']->diffForHumans() }}
                                    @else
                                        In {{ $selectedDateData['date']->diffForHumans() }}
                                    @endif
                                </p>
                            </div>

                            <div class="p-4 max-h-[500px] overflow-y-auto">
                                @if($selectedDateData['isPast'] || $selectedDateData['isToday'])
                                    {{-- Past/Today: Show actual attendance records --}}
                                    @if($selectedDateData['attendance']->isEmpty())
                                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No classes recorded for this date.</p>
                                    @else
                                        <div class="space-y-3">
                                            @foreach($selectedDateData['attendance'] as $record)
                                                <a href="{{ route('director.attendance.show', $record) }}"
                                                   class="block p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <div class="flex items-start justify-between">
                                                        <div>
                                                            <div class="font-medium text-gray-900 dark:text-white">
                                                                {{ $record->student->first_name ?? 'Unknown' }} {{ $record->student->last_name ?? '' }}
                                                            </div>
                                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                Tutor: {{ $record->tutor->first_name ?? 'Unknown' }} {{ $record->tutor->last_name ?? '' }}
                                                            </div>
                                                            @if($record->class_time)
                                                                <div class="text-xs text-gray-400 mt-1">
                                                                    {{ \Carbon\Carbon::parse($record->class_time)->format('g:i A') }}
                                                                    @if($record->duration_minutes)
                                                                        ({{ $record->duration_minutes }} mins)
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                                            {{ $record->status === 'approved' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                                            {{ ucfirst($record->status) }}
                                                        </span>
                                                    </div>
                                                    @if($record->topic)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 line-clamp-2">{{ $record->topic }}</p>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    {{-- Future: Show potential classes --}}
                                    @if(empty($selectedDateData['potential']))
                                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No classes scheduled for this date.</p>
                                    @else
                                        <div class="space-y-3">
                                            @foreach($selectedDateData['potential'] as $potential)
                                                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200/50 dark:border-blue-800/50">
                                                    <div class="flex items-start justify-between">
                                                        <div>
                                                            <div class="font-medium text-gray-900 dark:text-white">
                                                                {{ $potential['student']->first_name }} {{ $potential['student']->last_name }}
                                                            </div>
                                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                Tutor: {{ $potential['tutor']->first_name ?? 'Unassigned' }} {{ $potential['tutor']->last_name ?? '' }}
                                                            </div>
                                                            @if($potential['time'])
                                                                <div class="text-xs text-gray-400 mt-1">
                                                                    Scheduled: {{ $potential['time'] }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                            Scheduled
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @else
                            <div class="p-8 text-center">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Select a date to view details</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    @endpush
</x-app-layout>
