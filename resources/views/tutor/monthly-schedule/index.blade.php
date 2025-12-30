<x-tutor-layout title="Monthly Class Schedules">
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Monthly Class Schedules</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">Manage your students' class schedules for each month</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('tutor.schedule.today') }}" class="inline-flex items-center gap-2 px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Today's Schedule
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- Month Selector -->
    <div class="glass-card rounded-2xl shadow-lg p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <form method="GET" class="flex items-center gap-2">
                    <select name="month" class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9]">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create(null, $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                    <select name="year" class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9]">
                        @for($y = now()->year - 1; $y <= now()->year + 1; $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-[#4B49AC] to-[#7978E9] text-white rounded-xl hover:opacity-90 transition-all">
                        View
                    </button>
                </form>
            </div>

            <form action="{{ route('tutor.monthly-schedule.generate') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="month" value="{{ $month }}">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Auto-Generate All
                </button>
            </form>
        </div>
    </div>

    <!-- Schedule Cards -->
    <div class="glass-card rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-[#4B49AC] to-[#7978E9]">
            <h2 class="text-lg font-semibold text-white">{{ $monthName }} - Student Schedules</h2>
        </div>

        @if(count($scheduleData) === 0)
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-300 mb-2">No Students Assigned</h3>
                <p class="text-slate-500 dark:text-slate-400">You don't have any active students assigned to you.</p>
            </div>
        @else
            <div class="divide-y divide-slate-200 dark:divide-slate-700">
                @foreach($scheduleData as $data)
                    <div class="p-6 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <!-- Student Info -->
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-[#7978E9] to-[#98BDFF] rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($data['student']->first_name, 0, 1)) }}{{ strtoupper(substr($data['student']->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900 dark:text-white">
                                        {{ $data['student']->first_name }} {{ $data['student']->last_name }}
                                    </h3>
                                    <p class="text-sm text-slate-500">
                                        @if(!empty($data['class_days']))
                                            {{ implode(', ', $data['class_days']) }}
                                        @else
                                            No class days set
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Progress -->
                            <div class="flex items-center gap-6">
                                <div class="text-center">
                                    <div class="text-2xl font-bold {{ $data['total_classes'] > 0 ? 'text-[#7978E9]' : 'text-slate-400' }}">
                                        {{ $data['completed_classes'] }}/{{ $data['total_classes'] }}
                                    </div>
                                    <div class="text-xs text-slate-500">Classes</div>
                                </div>

                                @if($data['total_classes'] > 0)
                                    <div class="w-32">
                                        <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                            @php
                                                $percent = $data['total_classes'] > 0 ? ($data['completed_classes'] / $data['total_classes']) * 100 : 0;
                                            @endphp
                                            <div class="h-full bg-gradient-to-r from-[#7978E9] to-[#98BDFF] rounded-full" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <div class="text-xs text-center text-slate-500 mt-1">{{ round($percent) }}% Complete</div>
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="flex items-center gap-2">
                                    @if($data['has_schedule'])
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                            Not Set
                                        </span>
                                    @endif

                                    <a href="{{ route('tutor.monthly-schedule.edit', ['student' => $data['student']->id, 'year' => $year, 'month' => $month]) }}"
                                       class="p-2 text-slate-600 dark:text-slate-400 hover:text-[#7978E9] hover:bg-[#7978E9]/10 rounded-lg transition-colors"
                                       title="Edit Schedule">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-blue-800 dark:text-blue-300">
                <strong>How it works:</strong> The monthly schedule tracks how many classes each student should have in a month.
                When you submit attendance and it's approved, the "completed classes" count increases.
                Use "Auto-Generate All" to automatically calculate class counts based on each student's weekly schedule.
            </div>
        </div>
    </div>
</div>
</x-tutor-layout>
