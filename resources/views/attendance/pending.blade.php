<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Pending Attendance Approvals
            </h2>
            <a href="{{ route('attendance.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to All Attendance
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-cyan-50 to-teal-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 px-4 sm:px-6 lg:px-8" x-data="attendanceApproval()">
        <div class="max-w-7xl mx-auto space-y-6">

            @if(session('success'))
                <div class="relative overflow-hidden rounded-2xl bg-green-500/90 backdrop-blur-xl p-4 shadow-xl border border-white/20">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-white font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="relative overflow-hidden rounded-2xl bg-red-500/90 backdrop-blur-xl p-4 shadow-xl border border-white/20">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="text-white font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Filters Section --}}
            <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-6 border border-white/20">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter Attendance
                </h3>
                <form method="GET" action="{{ route('attendance.pending') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="tutor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Filter by Tutor
                        </label>
                        <select name="tutor_id" id="tutor_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            <option value="">All Tutors</option>
                            @foreach(\App\Models\Tutor::where('status', 'active')->orderBy('first_name')->get() as $tutor)
                                <option value="{{ $tutor->id }}" {{ request('tutor_id') == $tutor->id ? 'selected' : '' }}>
                                    {{ $tutor->first_name }} {{ $tutor->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Filter by Student
                        </label>
                        <select name="student_id" id="student_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            <option value="">All Students</option>
                            @foreach(\App\Models\Student::where('status', 'active')->orderBy('first_name')->get() as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            From Date
                        </label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    </div>

                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            To Date
                        </label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    </div>

                    <div class="md:col-span-4 flex gap-3">
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-teal-500 to-cyan-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-teal-600 hover:to-cyan-600 active:from-teal-700 active:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Apply Filters
                        </button>
                        <a href="{{ route('attendance.pending') }}" class="inline-flex items-center px-6 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            {{-- Bulk Actions --}}
            <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-4 border border-white/20">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" @change="toggleSelectAll" x-model="selectAll" class="rounded border-gray-300 dark:border-gray-600 text-teal-600 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Select All</span>
                        </label>
                        <span class="text-sm text-gray-500 dark:text-gray-400" x-text="selectedItems.length + ' selected'"></span>
                    </div>
                    <div class="flex gap-3">
                        <button @click="bulkApprove" x-show="selectedItems.length > 0" type="button"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-green-600 hover:to-emerald-600 active:from-green-700 active:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Approve Selected
                        </button>
                        <button @click="bulkReject" x-show="selectedItems.length > 0" type="button"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-red-600 hover:to-pink-600 active:from-red-700 active:to-pink-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject Selected
                        </button>
                    </div>
                </div>
            </div>

            {{-- Pending Attendance Cards --}}
            <div class="space-y-4">
                @forelse($attendances as $attendance)
                    <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl hover:shadow-2xl border border-white/20 transform hover:-translate-y-1 transition-all duration-300">
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                {{-- Checkbox --}}
                                <div class="pt-1">
                                    <input type="checkbox"
                                        :value="{{ $attendance->id }}"
                                        x-model="selectedItems"
                                        class="rounded border-gray-300 dark:border-gray-600 text-teal-600 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                                </div>

                                {{-- Content --}}
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                                <svg class="w-6 h-6 mr-2 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('l, F j, Y') }}
                                            </h3>
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                                            Pending
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Student</p>
                                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ $attendance->student->first_name }} {{ $attendance->student->last_name }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Tutor</p>
                                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ $attendance->tutor->first_name }} {{ $attendance->tutor->last_name }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Time</p>
                                            <p class="text-base font-medium text-gray-800 dark:text-gray-200">
                                                {{ \Carbon\Carbon::parse($attendance->start_time)->format('g:i A') }} -
                                                {{ \Carbon\Carbon::parse($attendance->end_time)->format('g:i A') }}
                                                ({{ $attendance->duration_minutes }} mins)
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Progress</p>
                                            <p class="text-base font-medium text-gray-800 dark:text-gray-200">
                                                {{ $attendance->student->completed_periods }}/{{ $attendance->student->total_periods }} periods completed
                                            </p>
                                        </div>
                                    </div>

                                    @if($attendance->topic_covered)
                                        <div class="mb-4">
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Topic</p>
                                            <p class="text-base font-medium text-gray-800 dark:text-gray-200">
                                                {{ $attendance->topic_covered }}
                                            </p>
                                        </div>
                                    @endif

                                    @if($attendance->notes)
                                        <div class="mb-4">
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Notes</p>
                                            <p class="text-base text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/50 rounded-lg p-3">
                                                {{ $attendance->notes }}
                                            </p>
                                        </div>
                                    @endif

                                    {{-- Action Buttons --}}
                                    <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <form method="POST" action="{{ route('attendance.approve', $attendance->id) }}" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-green-600 hover:to-emerald-600 active:from-green-700 active:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-lg">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('attendance.reject', $attendance->id) }}" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" onclick="return confirm('Are you sure you want to reject this attendance record?')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-red-600 hover:to-pink-600 active:from-red-700 active:to-pink-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-lg">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Reject
                                            </button>
                                        </form>

                                        <a href="{{ route('students.show', $attendance->student_id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-500 to-cyan-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-teal-600 hover:to-cyan-600 active:from-teal-700 active:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-lg">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            View Student
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-12 border border-white/20 text-center">
                        <svg class="w-24 h-24 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-400 mb-2">No Pending Attendance</h3>
                        <p class="text-gray-500 dark:text-gray-500">All attendance records have been reviewed.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($attendances->hasPages())
                <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-4 border border-white/20">
                    {{ $attendances->links() }}
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('attendanceApproval', () => ({
                selectedItems: [],
                selectAll: false,

                toggleSelectAll() {
                    if (this.selectAll) {
                        this.selectedItems = [
                            @foreach($attendances as $attendance)
                                {{ $attendance->id }},
                            @endforeach
                        ];
                    } else {
                        this.selectedItems = [];
                    }
                },

                bulkApprove() {
                    if (this.selectedItems.length === 0) {
                        alert('Please select at least one attendance record');
                        return;
                    }

                    if (!confirm(`Are you sure you want to approve ${this.selectedItems.length} attendance record(s)?`)) {
                        return;
                    }

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('attendance.bulk-approve') }}';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    this.selectedItems.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'attendance_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                },

                bulkReject() {
                    if (this.selectedItems.length === 0) {
                        alert('Please select at least one attendance record');
                        return;
                    }

                    if (!confirm(`Are you sure you want to reject ${this.selectedItems.length} attendance record(s)?`)) {
                        return;
                    }

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('attendance.bulk-reject') }}';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    this.selectedItems.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'attendance_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                }
            }))
        })
    </script>
    @endpush
</x-app-layout>
