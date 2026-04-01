<x-app-layout>
    <x-slot name="header">
        {{ __('Attendance Management') }}
    </x-slot>
    <x-slot name="title">{{ __('Attendance') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-ui.stat-card title="Total Attendance" value="{{ $totalAttendance }}"
                    icon='<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>'
                    gradient="bg-gradient-to-br from-blue-500 to-cyan-600" />
                <x-ui.stat-card title="Approved" value="{{ $approvedAttendance }}"
                    icon='<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                    gradient="bg-gradient-to-br from-green-500 to-emerald-600" />
                <x-ui.stat-card title="Pending" value="{{ $pendingAttendance }}"
                    icon='<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                    gradient="bg-gradient-to-br from-yellow-500 to-orange-600" />
                <x-ui.stat-card title="Late Submissions" value="{{ $lateSubmissions }}"
                    icon='<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>'
                    gradient="bg-gradient-to-br from-red-500 to-pink-600" />
            </div>

            <!-- Filter Section -->
            <x-ui.glass-card class="mb-8">
                <form method="GET" action="{{ route('director.attendance.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Range</label>
                            <select name="date_range" id="date_range_select" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                <option value="">All Time</option>
                                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                <option value="last_week" {{ request('date_range') == 'last_week' ? 'selected' : '' }}>Last Week</option>
                                <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                                <option value="custom" {{ request('date_range') == 'custom' || (request('start_date') || request('end_date')) ? 'selected' : '' }}>Custom Range</option>
                            </select>
                        </div>
                        <div id="start_date_container" class="{{ request('date_range') == 'custom' || request('start_date') || request('end_date') ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                        </div>
                        <div id="end_date_container" class="{{ request('date_range') == 'custom' || request('start_date') || request('end_date') ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                <option value="">All</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Late Submission</label>
                            <select name="late_submission" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                <option value="">All</option>
                                <option value="late_only" {{ request('late_submission') == 'late_only' ? 'selected' : '' }}>Late Only</option>
                                <option value="on_time" {{ request('late_submission') == 'on_time' ? 'selected' : '' }}>On Time Only</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tutor</label>
                            <select name="tutor_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                <option value="">All Tutors</option>
                                @foreach($tutors as $tutor)
                                    <option value="{{ $tutor->id }}" {{ request('tutor_id') == $tutor->id ? 'selected' : '' }}>{{ $tutor->first_name }} {{ $tutor->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student</label>
                            <select name="student_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                <option value="">All Students</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>{{ $student->first_name }} {{ $student->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 pt-2">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#4F46E5] to-[#818CF8] text-white rounded-lg hover:from-[#3730A3] hover:to-[#4F46E5] transition-all">Filter</button>
                        <a href="{{ route('director.attendance.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-all">Reset</a>
                    </div>
                </form>
            </x-ui.glass-card>

            <script>
                document.getElementById('date_range_select').addEventListener('change', function() {
                    const customContainers = ['start_date_container', 'end_date_container'];
                    const isCustom = this.value === 'custom';
                    customContainers.forEach(id => {
                        document.getElementById(id).classList.toggle('hidden', !isCustom);
                    });
                });
            </script>

            <!-- Today's Attendance -->
            @if($todayAttendance->count() > 0)
            <x-ui.glass-card class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Today's Attendance ({{ $todayAttendance->count() }})</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($todayAttendance as $record)
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $record->student->first_name ?? 'N/A' }} {{ $record->student->last_name ?? '' }}</span>
                            <x-ui.status-badge :status="$record->status" />
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tutor: {{ $record->tutor->first_name ?? 'N/A' }} {{ $record->tutor->last_name ?? '' }}</p>
                    </div>
                    @endforeach
                </div>
            </x-ui.glass-card>
            @endif

            <!-- Attendance List -->
            <x-ui.glass-card>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Attendance Records</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tutor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Topic</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($attendance as $record)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $record->class_date ? \Carbon\Carbon::parse($record->class_date)->format('M d, Y') : 'N/A' }}
                                    @if($record->is_late_submission)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Late
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    <div class="flex items-center gap-2">
                                        <span>{{ $record->student->first_name ?? 'N/A' }} {{ $record->student->last_name ?? '' }}</span>
                                        @if($record->is_stand_in)
                                            <span class="px-1.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded">Stand-in</span>
                                        @endif
                                    </div>
                                    @if(isset($record->monthly_attended) && isset($record->monthly_total))
                                        @if($record->is_stand_in)
                                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1 font-medium">
                                                Stand-in (not counted)
                                            </p>
                                        @else
                                            <div class="text-xs text-emerald-600 dark:text-emerald-400 mt-1 font-medium">
                                                {{ $record->monthly_attended }}/{{ $record->monthly_total }} this month
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $record->tutor->first_name ?? 'N/A' }} {{ $record->tutor->last_name ?? '' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($record->courses_covered && count($record->courses_covered) > 0)
                                        <div class="text-xs text-[#4F46E5] dark:text-[#818CF8] font-medium truncate max-w-xs" title="{{ implode(', ', $record->courses_covered) }}">
                                            {{ Str::limit(implode(', ', $record->courses_covered), 30) }}
                                        </div>
                                    @endif
                                    <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate">{{ $record->topic ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-ui.status-badge :status="$record->status" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('director.attendance.show', $record) }}" class="text-[#4F46E5] hover:text-blue-900 dark:text-blue-400">View</a>
                                        <a href="{{ route('director.attendance.edit', $record) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400">Edit</a>
                                        @if($record->status == 'pending')
                                        <form method="POST" action="{{ route('director.attendance.approve', $record) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400">Approve</button>
                                        </form>
                                        @endif
                                        <form method="POST" action="{{ route('director.attendance.destroy', $record) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this attendance record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">No attendance records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $attendance->withQueryString()->links() }}</div>
            </x-ui.glass-card>
        </div>
    </div>

</x-app-layout>
