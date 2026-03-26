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
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $monthStats['totalScheduled'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Scheduled</div>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/30 rounded-xl p-4 shadow-sm border border-emerald-200/50 dark:border-emerald-700/50">
                    <div class="text-2xl font-bold text-emerald-600">{{ $monthStats['completed'] }}</div>
                    <div class="text-sm text-emerald-700 dark:text-emerald-400">Completed</div>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/30 rounded-xl p-4 shadow-sm border border-amber-200/50 dark:border-amber-700/50">
                    <div class="text-2xl font-bold text-amber-600">{{ $monthStats['pending'] }}</div>
                    <div class="text-sm text-amber-700 dark:text-amber-400">Pending</div>
                </div>
                <div class="bg-red-50 dark:bg-red-900/30 rounded-xl p-4 shadow-sm border border-red-200/50 dark:border-red-700/50">
                    <div class="text-2xl font-bold text-red-600">{{ $monthStats['notTaken'] }}</div>
                    <div class="text-sm text-red-700 dark:text-red-400">Not Taken</div>
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

                    <div class="w-36">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">All</option>
                            <option value="approved" {{ $statusFilter === 'approved' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="not_taken" {{ $statusFilter === 'not_taken' ? 'selected' : '' }}>Not Taken</option>
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
                                    $hasClasses = count($day['scheduledClasses']) > 0;
                                    $isSelected = $selectedDate === $day['dateStr'];
                                    $totalClasses = $day['completedCount'] + $day['pendingCount'] + $day['notTakenCount'];
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
                                        @elseif($day['isCurrentMonth'] && $totalClasses > 0)
                                            <span class="px-1.5 py-0.5 text-xs bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded font-medium">{{ $totalClasses }}</span>
                                        @endif
                                    </div>

                                    @if($day['isCurrentMonth'] && $hasClasses)
                                        <div class="space-y-0.5">
                                            @if($day['completedCount'] > 0)
                                                <div class="flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400">
                                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                                    {{ $day['completedCount'] }} completed
                                                </div>
                                            @endif
                                            @if($day['pendingCount'] > 0)
                                                <div class="flex items-center gap-1 text-xs text-amber-600 dark:text-amber-400">
                                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                                    {{ $day['pendingCount'] }} pending
                                                </div>
                                            @endif
                                            @if($day['notTakenCount'] > 0)
                                                <div class="flex items-center gap-1 text-xs text-red-600 dark:text-red-400">
                                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                                    {{ $day['notTakenCount'] }} not taken
                                                </div>
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
                            <span class="text-gray-600 dark:text-gray-400">Completed (attendance approved)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-amber-500 rounded-full"></span>
                            <span class="text-gray-600 dark:text-gray-400">Pending (attendance awaiting approval)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                            <span class="text-gray-600 dark:text-gray-400">Not Taken (no attendance submitted)</span>
                        </div>
                    </div>
                </div>

                <!-- Selected Date Details -->
                <div class="lg:col-span-1" x-data="{
                    showModal: false,
                    modalData: null,
                    openModal(data) {
                        this.modalData = data;
                        this.showModal = true;
                    }
                }">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 sticky top-4">
                        @if($selectedDateData)
                            @php
                                $scheduledClasses = $selectedDateData['scheduledClasses'];
                                $totalClasses = count($scheduledClasses);
                                $completedCount = count(array_filter($scheduledClasses, fn($c) => $c['status'] === 'completed'));
                                $pendingCount = count(array_filter($scheduledClasses, fn($c) => $c['status'] === 'pending'));
                                $notTakenCount = count(array_filter($scheduledClasses, fn($c) => $c['status'] === 'not_taken'));
                            @endphp
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
                                {{-- Summary stats --}}
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        {{ $totalClasses }} scheduled
                                    </span>
                                    @if($completedCount > 0)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            {{ $completedCount }} completed
                                        </span>
                                    @endif
                                    @if($pendingCount > 0)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                            {{ $pendingCount }} pending
                                        </span>
                                    @endif
                                    @if($notTakenCount > 0)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                            {{ $notTakenCount }} not taken
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="p-4 max-h-[500px] overflow-y-auto">
                                @if($totalClasses === 0)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No classes scheduled for this date.</p>
                                @else
                                    <div class="space-y-2">
                                        @foreach($scheduledClasses as $class)
                                            @if($class['status'] === 'completed' || $class['status'] === 'pending')
                                                {{-- Class with attendance record - clickable for details --}}
                                                @php $record = $class['attendance']; @endphp
                                                <button type="button"
                                                    @click="openModal({
                                                        id: {{ $record->id }},
                                                        student: '{{ addslashes(($class['student']->first_name ?? 'Unknown') . ' ' . ($class['student']->last_name ?? '')) }}',
                                                        tutor: '{{ addslashes(($class['tutor']->first_name ?? 'Unknown') . ' ' . ($class['tutor']->last_name ?? '')) }}',
                                                        status: '{{ $record->status }}',
                                                        time: '{{ $record->class_time ? \Carbon\Carbon::parse($record->class_time)->format('g:i A') : ($class['time'] ?? 'N/A') }}',
                                                        duration: '{{ $record->duration_minutes ?? 'N/A' }}',
                                                        topic: '{{ addslashes($record->topic ?? '') }}',
                                                        courses: {{ json_encode($record->courses_covered ?? []) }},
                                                        notes: '{{ addslashes($record->notes ?? '') }}',
                                                        submittedAt: '{{ $record->created_at->format('M j, Y g:i A') }}',
                                                        isLate: {{ $record->is_late ? 'true' : 'false' }},
                                                        type: 'attendance'
                                                    })"
                                                    class="w-full text-left p-3 rounded-lg transition-colors
                                                        {{ $class['status'] === 'completed'
                                                            ? 'bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200/50 dark:border-emerald-800/50 hover:bg-emerald-100 dark:hover:bg-emerald-900/30'
                                                            : 'bg-amber-50 dark:bg-amber-900/20 border border-amber-200/50 dark:border-amber-800/50 hover:bg-amber-100 dark:hover:bg-amber-900/30' }}">
                                                    <div class="flex items-start justify-between">
                                                        <div>
                                                            <div class="font-medium text-gray-900 dark:text-white text-sm">
                                                                {{ $class['student']->first_name ?? 'Unknown' }} {{ $class['student']->last_name ?? '' }}
                                                            </div>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                {{ $class['tutor']->first_name ?? 'Unassigned' }} {{ $class['tutor']->last_name ?? '' }}
                                                                @if($class['time'])
                                                                    <span class="ml-1">@ {{ $class['time'] }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center gap-1">
                                                            @if($record->is_late)
                                                                <span class="px-1.5 py-0.5 text-xs font-medium rounded bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Late</span>
                                                            @endif
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                                {{ $class['status'] === 'completed' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                                                {{ $class['status'] === 'completed' ? 'Completed' : 'Pending' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </button>
                                            @else
                                                {{-- Not Taken - no attendance record --}}
                                                <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200/50 dark:border-red-800/50">
                                                    <div class="flex items-start justify-between">
                                                        <div>
                                                            <div class="font-medium text-gray-900 dark:text-white text-sm">
                                                                {{ $class['student']->first_name }} {{ $class['student']->last_name }}
                                                            </div>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                {{ $class['tutor']->first_name ?? 'Unassigned' }} {{ $class['tutor']->last_name ?? '' }}
                                                                @if($class['time'])
                                                                    <span class="ml-1">@ {{ $class['time'] }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                                            Not Taken
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
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

                    {{-- Attendance Detail Modal --}}
                    <div x-show="showModal" x-cloak
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                         @keydown.escape.window="showModal = false">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto"
                             @click.outside="showModal = false">
                            {{-- Modal Header --}}
                            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Attendance Details</h3>
                                <button @click="showModal = false" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Modal Content --}}
                            <div class="p-4 space-y-4" x-show="modalData">
                                {{-- Status Badge --}}
                                <div class="flex items-center gap-2">
                                    <template x-if="modalData?.status === 'approved'">
                                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            Completed
                                        </span>
                                    </template>
                                    <template x-if="modalData?.status === 'pending'">
                                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                            Pending Approval
                                        </span>
                                    </template>
                                    <template x-if="modalData?.isLate">
                                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                            Late Submission
                                        </span>
                                    </template>
                                </div>

                                {{-- Student & Tutor Info --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Student</label>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="modalData?.student"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tutor</label>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="modalData?.tutor"></p>
                                    </div>
                                </div>

                                {{-- Time & Duration --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Class Time</label>
                                        <p class="text-sm text-gray-900 dark:text-white" x-text="modalData?.time"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Duration</label>
                                        <p class="text-sm text-gray-900 dark:text-white" x-text="modalData?.duration + ' mins'"></p>
                                    </div>
                                </div>

                                {{-- Topic --}}
                                <div x-show="modalData?.topic">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Topic</label>
                                    <p class="text-sm text-gray-900 dark:text-white" x-text="modalData?.topic"></p>
                                </div>

                                {{-- Courses Covered --}}
                                <div x-show="modalData?.courses?.length > 0">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Courses Covered</label>
                                    <div class="flex flex-wrap gap-1">
                                        <template x-for="course in modalData?.courses" :key="course">
                                            <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 rounded" x-text="course"></span>
                                        </template>
                                    </div>
                                </div>

                                {{-- Notes --}}
                                <div x-show="modalData?.notes">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Notes</label>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3" x-text="modalData?.notes"></p>
                                </div>

                                {{-- Submitted At --}}
                                <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Submitted: <span x-text="modalData?.submittedAt"></span>
                                    </p>
                                </div>
                            </div>

                            {{-- Modal Footer --}}
                            <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 rounded-b-2xl">
                                <button @click="showModal = false" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    Close
                                </button>
                                <a :href="'{{ route('director.attendance.index') }}/' + modalData?.id"
                                   class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                                    View Full Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        [x-cloak] { display: none !important; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    @endpush
</x-app-layout>
