<x-app-layout>
    <x-slot name="header">{{ __('Attendance Management') }}</x-slot>
    <x-slot name="title">{{ __('Admin - Attendance') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-[#423A8E]/5 via-[#00CCCD]/5 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#423A8E]/30 dark:bg-[#423A8E]/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#00CCCD]/30 dark:bg-[#00CCCD]/40 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Attendance Management</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Review and approve tutor-submitted attendance records</p>
                </div>
                <div x-data="{ exportOpen: false }" class="relative">
                    <button @click="exportOpen = !exportOpen" type="button" class="inline-flex items-center px-4 py-2 bg-[#423A8E] hover:bg-[#423A8E] text-white rounded-lg shadow transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export CSV
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="exportOpen" @click.away="exportOpen = false" x-transition
                         class="absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Export Attendance Data</h4>
                            <div class="space-y-2">
                                <a href="{{ route('admin.attendance.export', ['period' => 'week']) }}"
                                   class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-[#423A8E]/5 dark:hover:bg-[#423A8E]/40/20 transition-colors">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white text-sm">This Week</div>
                                        <div class="text-xs text-gray-500">{{ now()->startOfWeek()->format('M j') }} - {{ now()->endOfWeek()->format('M j, Y') }}</div>
                                    </div>
                                    <svg class="w-5 h-5 text-[#423A8E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.attendance.export', ['period' => 'month']) }}"
                                   class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-[#423A8E]/5 dark:hover:bg-[#423A8E]/40/20 transition-colors">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white text-sm">This Month</div>
                                        <div class="text-xs text-gray-500">{{ now()->startOfMonth()->format('M j') }} - {{ now()->endOfMonth()->format('M j, Y') }}</div>
                                    </div>
                                    <svg class="w-5 h-5 text-[#423A8E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.attendance.export', ['start_date' => now()->subWeek()->startOfWeek()->format('Y-m-d'), 'end_date' => now()->subWeek()->endOfWeek()->format('Y-m-d')]) }}"
                                   class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-[#423A8E]/5 dark:hover:bg-[#423A8E]/40/20 transition-colors">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white text-sm">Last Week</div>
                                        <div class="text-xs text-gray-500">{{ now()->subWeek()->startOfWeek()->format('M j') }} - {{ now()->subWeek()->endOfWeek()->format('M j, Y') }}</div>
                                    </div>
                                    <svg class="w-5 h-5 text-[#423A8E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.attendance.export', ['start_date' => now()->subMonth()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->subMonth()->endOfMonth()->format('Y-m-d')]) }}"
                                   class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-[#423A8E]/5 dark:hover:bg-[#423A8E]/40/20 transition-colors">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white text-sm">Last Month</div>
                                        <div class="text-xs text-gray-500">{{ now()->subMonth()->startOfMonth()->format('M j') }} - {{ now()->subMonth()->endOfMonth()->format('M j, Y') }}</div>
                                    </div>
                                    <svg class="w-5 h-5 text-[#423A8E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <form action="{{ route('admin.attendance.export') }}" method="GET" class="space-y-3">
                                    <div class="text-xs font-medium text-gray-700 dark:text-gray-300">Custom Date Range</div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="date" name="start_date" class="px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded focus:ring-2 focus:ring-[#423A8E]">
                                        <input type="date" name="end_date" class="px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded focus:ring-2 focus:ring-[#423A8E]">
                                    </div>
                                    <button type="submit" class="w-full px-3 py-2 bg-[#423A8E] hover:bg-[#423A8E] text-white text-sm rounded-lg transition-colors">
                                        Export Custom Range
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Records</div>
                </div>
                <a href="{{ route('admin.attendance.index', ['status' => 'pending']) }}" class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/30 rounded-2xl p-5 shadow hover:shadow-lg transition-shadow">
                    <div class="text-3xl font-bold text-amber-600">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="text-sm text-amber-700 dark:text-amber-400">Pending Approval</div>
                </a>
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-emerald-600">{{ $stats['approved'] ?? 0 }}</div>
                    <div class="text-sm text-emerald-700 dark:text-emerald-400">Approved</div>
                </div>
                <a href="{{ route('admin.attendance.index', ['late_only' => 1]) }}" class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800/30 rounded-2xl p-5 shadow hover:shadow-lg transition-shadow">
                    <div class="text-3xl font-bold text-red-600">{{ $stats['late'] ?? 0 }}</div>
                    <div class="text-sm text-red-700 dark:text-red-400">Late Submissions</div>
                </a>
            </div>

            {{-- Filters --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow mb-6">
                <form method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="w-36">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                    </div>
                    <div class="w-40">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Month</label>
                        <select name="month" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                            <option value="">All Months</option>
                            @for($m = 1; $m <= 12; $m++)
                                @php $monthValue = now()->year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                                <option value="{{ $monthValue }}" {{ request('month') === $monthValue ? 'selected' : '' }}>
                                    {{ date('F Y', strtotime($monthValue . '-01')) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="w-40">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class Date</label>
                        <input type="date" name="date" value="{{ request('date') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                    </div>
                    <div class="w-44">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tutor</label>
                        <select name="tutor_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                            <option value="">All Tutors</option>
                            @foreach($tutors as $tutor)
                                <option value="{{ $tutor->id }}" {{ request('tutor_id') == $tutor->id ? 'selected' : '' }}>
                                    {{ $tutor->first_name }} {{ $tutor->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-44">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student</label>
                        <select name="student_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                            <option value="">All Students</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="late_only" id="late_only" value="1" {{ request('late_only') ? 'checked' : '' }}
                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <label for="late_only" class="text-sm text-gray-700 dark:text-gray-300">Late Only</label>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-[#423A8E] text-white rounded-lg hover:bg-[#423A8E] transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['status', 'month', 'date', 'tutor_id', 'student_id', 'late_only']))
                        <a href="{{ route('admin.attendance.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- Attendance Table --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden"
                 x-data="{
                    selectedIds: [],
                    selectAll: false,
                    pendingIds: {{ json_encode($attendances->where('status', 'pending')->pluck('id')->toArray()) }},
                    toggleAll() {
                        if (this.selectAll) {
                            this.selectedIds = [...this.pendingIds];
                        } else {
                            this.selectedIds = [];
                        }
                    },
                    toggleOne(id) {
                        if (this.selectedIds.includes(id)) {
                            this.selectedIds = this.selectedIds.filter(i => i !== id);
                        } else {
                            this.selectedIds.push(id);
                        }
                        this.selectAll = this.selectedIds.length === this.pendingIds.length && this.pendingIds.length > 0;
                    }
                 }">
                {{-- Bulk Actions Bar --}}
                <div x-show="selectedIds.length > 0" x-cloak
                     class="px-4 py-3 bg-[#423A8E]/10 dark:bg-[#423A8E]/20 border-b border-[#423A8E]/20 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-[#423A8E] dark:text-[#00CCCD]">
                            <span x-text="selectedIds.length"></span> record(s) selected
                        </span>
                    </div>
                    <form action="{{ route('admin.attendance.bulk-approve') }}" method="POST" class="inline">
                        @csrf
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="attendance_ids[]" :value="id">
                        </template>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Bulk Approve
                        </button>
                    </form>
                </div>

                @if($attendances->isEmpty())
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">📋</div>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Attendance Records Found</h3>
                        <p class="text-gray-500 dark:text-gray-400">Attendance records submitted by tutors will appear here for review.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-4 py-4 text-left">
                                        <input type="checkbox" x-model="selectAll" @change="toggleAll()"
                                               class="w-4 h-4 text-[#423A8E] border-gray-300 rounded focus:ring-[#423A8E]"
                                               :disabled="pendingIds.length === 0">
                                    </th>
                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tutor</th>
                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Class Date</th>
                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Topic</th>
                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Submitted</th>
                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($attendances as $attendance)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors {{ $attendance->status === 'pending' ? 'bg-amber-50/50 dark:bg-amber-900/10' : '' }} {{ ($attendance->is_late || $attendance->is_late_submission) && $attendance->status === 'pending' ? 'border-l-4 border-red-400' : '' }}">
                                        <td class="px-4 py-4">
                                            @if($attendance->status === 'pending')
                                                <input type="checkbox"
                                                       :checked="selectedIds.includes({{ $attendance->id }})"
                                                       @change="toggleOne({{ $attendance->id }})"
                                                       class="w-4 h-4 text-[#423A8E] border-gray-300 rounded focus:ring-[#423A8E]">
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-br from-[#00CCCD] to-[#00CCCD] rounded-full flex items-center justify-center text-white font-bold text-xs mr-2">
                                                    {{ strtoupper(substr($attendance->student->first_name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="font-medium text-gray-900 dark:text-white">{{ $attendance->student->first_name ?? 'Unknown' }} {{ $attendance->student->last_name ?? '' }}</span>
                                                    @if($attendance->is_stand_in)
                                                        <span class="ml-1 px-1.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded">Stand-in</span>
                                                    @endif
                                                    @if(isset($attendance->monthly_attended) && isset($attendance->monthly_total))
                                                        @if($attendance->is_stand_in)
                                                            <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                                                                Stand-in (not counted in main tutor's tally)
                                                            </p>
                                                        @else
                                                            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                                                                {{ $attendance->monthly_attended }}/{{ $attendance->monthly_total }} classes this month
                                                            </p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $attendance->tutor->first_name ?? 'Unknown' }} {{ $attendance->tutor->last_name ?? '' }}
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $attendance->class_date?->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-500">
                                                @if($attendance->class_time)
                                                    {{ $attendance->class_time->format('g:i A') }} • {{ $attendance->duration_minutes ?? 60 }} mins
                                                @else
                                                    {{ $attendance->duration_minutes ?? 60 }} mins
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            @if($attendance->courses_covered && count($attendance->courses_covered) > 0)
                                                <div class="text-xs text-[#423A8E] dark:text-[#00CCCD] font-medium truncate max-w-xs" title="{{ implode(', ', $attendance->courses_covered) }}">
                                                    {{ Str::limit(implode(', ', $attendance->courses_covered), 30) }}
                                                </div>
                                            @endif
                                            <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate">{{ $attendance->topic ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-4">
                                            {{-- Submission timestamp - key for determining if late --}}
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $attendance->created_at->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $attendance->created_at->format('g:i A') }}</div>
                                            <div class="text-xs text-gray-400">{{ $attendance->created_at->diffForHumans() }}</div>
                                            @if(($attendance->is_late || $attendance->is_late_submission) && $attendance->status === 'pending')
                                                <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Late Submission
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            @if($attendance->status === 'approved')
                                                @if($attendance->is_late || $attendance->is_late_submission)
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Late ✓
                                                    </span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                                        Approved ✓
                                                    </span>
                                                @endif
                                            @else
                                                @if($attendance->is_late || $attendance->is_late_submission)
                                                    <div class="flex flex-col gap-1">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 animate-pulse">
                                                            Pending
                                                        </span>
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                                            ⚠️ Late
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 animate-pulse">
                                                        Pending
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-center gap-1">
                                                {{-- View --}}
                                                <a href="{{ route('admin.attendance.show', $attendance) }}" class="p-1.5 text-gray-600 dark:text-gray-400 hover:text-[#423A8E] dark:hover:text-[#00CCCD] hover:bg-[#423A8E]/5 dark:hover:bg-[#423A8E]/40/20 rounded-lg transition-colors" title="View Details">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>

                                                @if($attendance->status === 'pending')
                                                    {{-- Approve --}}
                                                    <form action="{{ route('admin.attendance.approve', $attendance) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="p-1.5 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Approve">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        </button>
                                                    </form>

                                                    {{-- Mark Late --}}
                                                    <form action="{{ route('admin.attendance.late', $attendance) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="p-1.5 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="Mark as Late & Approve">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Delete (Request Resubmission) --}}
                                                <form action="{{ route('admin.attendance.destroy', $attendance) }}" method="POST" class="inline" 
                                                      onsubmit="return confirm('Delete this attendance record?\n\nThe tutor ({{ $attendance->tutor->first_name ?? 'Unknown' }}) will need to resubmit.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete (Request Resubmission)">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($attendances->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $attendances->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>

            {{-- Legend --}}
            <div class="mt-6 bg-white/40 dark:bg-gray-800/40 backdrop-blur-sm rounded-xl p-4">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Action Legend</h4>
                <div class="flex flex-wrap gap-4 text-xs text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-1">
                        <span class="w-5 h-5 bg-[#423A8E]/10 text-[#423A8E] rounded flex items-center justify-center">👁</span>
                        <span>View Details</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-5 h-5 bg-emerald-100 text-emerald-600 rounded flex items-center justify-center">✓</span>
                        <span>Approve</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-5 h-5 bg-amber-100 text-amber-600 rounded flex items-center justify-center">⏰</span>
                        <span>Mark Late & Approve</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-5 h-5 bg-red-100 text-red-600 rounded flex items-center justify-center">🗑</span>
                        <span>Delete (Request Resubmission)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        [x-cloak] { display: none !important; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
