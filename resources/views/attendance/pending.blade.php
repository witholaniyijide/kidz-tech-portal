<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Pending Attendance Approvals') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Pending Attendance') }}</x-slot>

    {{-- Animated Background --}}
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-blue-50 to-cyan-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-sky-300 dark:bg-sky-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-blue-300 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-green-500/90 backdrop-blur-xl shadow-xl border border-white/20">
                    <div class="flex items-center text-white">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-red-500/90 backdrop-blur-xl shadow-xl border border-white/20">
                    <div class="flex items-center text-white">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{-- Header --}}
            <x-ui.glass-card class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Attendance Approvals</h3>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">Review and approve pending attendance records</p>
                    </div>
                    <div class="bg-gradient-to-r from-sky-500 to-cyan-400 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                </div>

                {{-- Filters --}}
                <form method="GET" action="{{ route('manager.attendance.pending') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tutor</label>
                        <select name="tutor_id" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-sky-500 focus:ring-sky-500">
                            <option value="">All Tutors</option>
                            @foreach($tutors as $tutor)
                                <option value="{{ $tutor->id }}" {{ request('tutor_id') == $tutor->id ? 'selected' : '' }}>
                                    {{ $tutor->first_name }} {{ $tutor->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Student</label>
                        <select name="student_id" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-sky-500 focus:ring-sky-500">
                            <option value="">All Students</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date From</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-sky-500 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date To</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-sky-500 focus:ring-sky-500">
                    </div>

                    <div class="md:col-span-4 flex gap-3">
                        <button type="submit" class="px-5 py-3 bg-gradient-to-r from-sky-500 to-cyan-400 text-white font-semibold rounded-xl hover:-translate-y-1 transition-all duration-300 shadow-lg">
                            Apply Filters
                        </button>
                        <a href="{{ route('manager.attendance.pending') }}" class="px-5 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:-translate-y-1 transition-all duration-300">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </x-ui.glass-card>

            {{-- Bulk Actions --}}
            @if($records->count() > 0)
            <form id="bulkApproveForm" method="POST" action="{{ route('manager.attendance.bulkApprove') }}">
                @csrf
                <x-ui.glass-card class="mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 dark:border-gray-600 text-sky-500 focus:ring-sky-500">
                            <label for="selectAll" class="text-sm font-medium text-gray-700 dark:text-gray-300">Select All</label>
                            <span id="selectedCount" class="text-sm text-gray-500 dark:text-gray-400">0 selected</span>
                        </div>
                        <button type="submit" id="bulkApproveBtn" class="px-5 py-3 bg-gradient-to-r from-green-500 to-emerald-400 text-white font-semibold rounded-xl hover:-translate-y-1 transition-all duration-300 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            Approve Selected
                        </button>
                    </div>
                </x-ui.glass-card>

                {{-- Attendance Records --}}
                <div class="space-y-4">
                    @foreach($records as $record)
                    <x-ui.glass-card>
                        <div class="flex items-start gap-4">
                            <div class="pt-1">
                                <input type="checkbox" name="ids[]" value="{{ $record->id }}" class="attendance-checkbox rounded border-gray-300 dark:border-gray-600 text-sky-500 focus:ring-sky-500">
                            </div>

                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $record->student->first_name ?? 'Unknown' }} {{ $record->student->last_name ?? '' }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Tutor: {{ $record->tutor->first_name ?? 'Unknown' }} {{ $record->tutor->last_name ?? '' }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $record->class_date->format('D, M j, Y') }}
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($record->class_time)->format('g:i A') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Duration</span>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $record->duration_minutes }} minutes</p>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Topic</span>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $record->topic ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Progress</span>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $record->student->completed_periods ?? 0 }}/{{ $record->student->total_periods ?? 0 }} periods
                                        </p>
                                    </div>
                                </div>

                                @if($record->notes)
                                <div class="mb-4">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Notes</span>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ Str::limit($record->notes, 200) }}</p>
                                </div>
                                @endif

                                <div class="flex gap-3">
                                    <form method="POST" action="{{ route('manager.attendance.approve', $record) }}">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-400 text-white font-semibold rounded-lg hover:-translate-y-0.5 transition-all duration-200 shadow-md">
                                            ✓ Approve
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('manager.attendance.reject', $record) }}">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:-translate-y-0.5 transition-all duration-200 shadow-md">
                                            ✗ Reject
                                        </button>
                                    </form>

                                    <a href="{{ route('manager.students.show', $record->student_id) }}" class="px-4 py-2 bg-sky-500 text-white font-semibold rounded-lg hover:-translate-y-0.5 transition-all duration-200 shadow-md">
                                        View Student
                                    </a>
                                </div>
                            </div>
                        </div>
                    </x-ui.glass-card>
                    @endforeach
                </div>
            </form>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $records->links() }}
            </div>
            @else
            <x-ui.glass-card>
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Pending Attendance</h3>
                    <p class="text-gray-600 dark:text-gray-400">All attendance records have been processed.</p>
                </div>
            </x-ui.glass-card>
            @endif

        </div>
    </div>

    {{-- JavaScript for Bulk Select --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.attendance-checkbox');
            const selectedCount = document.getElementById('selectedCount');
            const bulkApproveBtn = document.getElementById('bulkApproveBtn');
            const bulkForm = document.getElementById('bulkApproveForm');

            function updateUI() {
                const checked = document.querySelectorAll('.attendance-checkbox:checked').length;
                selectedCount.textContent = `${checked} selected`;
                bulkApproveBtn.disabled = checked === 0;
            }

            selectAll?.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateUI();
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateUI);
            });

            bulkForm?.addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('.attendance-checkbox:checked').length;
                if (checked === 0) {
                    e.preventDefault();
                    alert('Please select at least one attendance record.');
                    return false;
                }
                return confirm(`Are you sure you want to approve ${checked} attendance record(s)?`);
            });
        });
    </script>
    @endpush
</x-app-layout>
