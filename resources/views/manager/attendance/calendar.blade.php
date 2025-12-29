<x-manager-layout title="Attendance Calendar">
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Attendance Calendar</h1>
            <p class="text-gray-600 dark:text-gray-400">View attendance records in calendar format</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('manager.attendance.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                List View
            </a>
            <a href="{{ route('manager.attendance.pending') }}" class="inline-flex items-center px-4 py-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 font-semibold rounded-xl hover:bg-amber-200 dark:hover:bg-amber-900/50 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                View Pending
            </a>
        </div>
    </div>

    {{-- Month Navigation --}}
    @php
        $currentDate = \Carbon\Carbon::create($year, $month, 1);
        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();
        $startOfWeek = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
        $endOfWeek = $endOfMonth->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
    @endphp

    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl overflow-hidden shadow-sm">
        {{-- Month Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('manager.attendance.calendar', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
               class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ $currentDate->format('F Y') }}
            </h2>
            <a href="{{ route('manager.attendance.calendar', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
               class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        {{-- Day Headers --}}
        <div class="grid grid-cols-7 border-b border-gray-200 dark:border-gray-700">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="px-2 py-3 text-center text-sm font-medium text-gray-500 dark:text-gray-400">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        {{-- Calendar Grid --}}
        <div class="grid grid-cols-7">
            @php $date = $startOfWeek->copy(); @endphp
            @while($date <= $endOfWeek)
                @php
                    $dateKey = $date->format('Y-m-d');
                    $isCurrentMonth = $date->month === $month;
                    $isToday = $date->isToday();
                    $records = $attendanceRecords[$dateKey] ?? collect();
                @endphp
                <div class="min-h-[120px] border-r border-b border-gray-100 dark:border-gray-700 p-2 {{ !$isCurrentMonth ? 'bg-gray-50 dark:bg-gray-900/50' : '' }} {{ $isToday ? 'bg-amber-50 dark:bg-amber-900/20' : '' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium {{ $isToday ? 'text-amber-600 dark:text-amber-400' : ($isCurrentMonth ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-600') }}">
                            {{ $date->day }}
                        </span>
                        @if($records->count() > 0)
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full bg-[#C15F3C]/10 text-[#C15F3C]">
                                {{ $records->count() }}
                            </span>
                        @endif
                    </div>
                    @if($records->count() > 0)
                        <div class="space-y-1">
                            @foreach($records->take(3) as $record)
                                <div class="text-xs p-1 rounded truncate
                                    @if($record->status === 'approved') bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300
                                    @elseif($record->status === 'pending') bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300
                                    @else bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300
                                    @endif"
                                    title="{{ $record->student->first_name ?? 'Student' }} - {{ $record->tutor->first_name ?? 'Tutor' }}">
                                    {{ $record->student->first_name ?? 'N/A' }}
                                </div>
                            @endforeach
                            @if($records->count() > 3)
                                <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
                                    +{{ $records->count() - 3 }} more
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                @php $date->addDay(); @endphp
            @endwhile
        </div>
    </div>

    {{-- Legend --}}
    <div class="mt-6 flex flex-wrap items-center gap-6">
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700"></div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Approved</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-amber-100 dark:bg-amber-900/30 border border-amber-300 dark:border-amber-700"></div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Pending</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800"></div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Today</span>
        </div>
    </div>
</x-manager-layout>
