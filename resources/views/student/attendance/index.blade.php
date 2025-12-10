@extends('layouts.student')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Attendance Summary</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Track your class attendance and participation</p>
        </div>
    </div>

    <!-- Attendance Statistics -->
    <div class="grid gap-6 md:grid-cols-4">
        <x-ui.glass-card padding="p-6">
            <div class="text-center">
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-3">Attendance Rate</p>
                <x-ui.progress-circle :percentage="$attendanceRate ?? 0" :size="100" :strokeWidth="6" />
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                    {{ $attendanceRate >= 90 ? 'Excellent!' : ($attendanceRate >= 75 ? 'Good' : 'Needs Improvement') }}
                </p>
            </div>
        </x-ui.glass-card>

        <x-ui.glass-card padding="p-6">
            <div class="text-center">
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Classes Completed</p>
                <p class="text-4xl font-bold text-green-600 dark:text-green-400">{{ $completedClasses ?? 0 }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Total Sessions</p>
            </div>
        </x-ui.glass-card>

        <x-ui.glass-card padding="p-6">
            <div class="text-center">
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Missed Classes</p>
                <p class="text-4xl font-bold text-red-600 dark:text-red-400">{{ $missedClasses ?? 0 }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Absences</p>
            </div>
        </x-ui.glass-card>

        <x-ui.glass-card padding="p-6">
            <div class="text-center">
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Current Streak</p>
                <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $currentStreak ?? 0 }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Consecutive Classes</p>
            </div>
        </x-ui.glass-card>
    </div>

    <!-- Monthly Attendance Chart -->
    <x-ui.glass-card>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Monthly Attendance Trend</h3>
        <div class="h-64">
            <canvas id="attendanceChart"></canvas>
        </div>
    </x-ui.glass-card>

    <!-- Attendance Timeline -->
    <x-ui.glass-card>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Recent Attendance</h3>

        @if(isset($attendanceRecords) && $attendanceRecords->count() > 0)
            <div class="space-y-3">
                @foreach($attendanceRecords as $record)
                    <div class="flex items-center justify-between p-4 rounded-xl {{ $record->status === 'present' ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' }}">
                        <div class="flex items-center space-x-4">
                            <!-- Status Icon -->
                            <div class="flex-shrink-0">
                                @if($record->status === 'present')
                                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center text-white">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $record->attendance_date ? \Carbon\Carbon::parse($record->attendance_date)->format('M d, Y') : 'N/A' }}
                                    </p>
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium {{ $record->status === 'present' ? 'text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30' : 'text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30' }} rounded-full capitalize">
                                        {{ $record->status }}
                                    </span>
                                </div>
                                @if($record->topic)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Topic: {{ $record->topic }}</p>
                                @endif
                                @if($record->tutor)
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Tutor: {{ $record->tutor->full_name ?? 'N/A' }}</p>
                                @endif
                            </div>

                            <!-- Duration -->
                            @if($record->duration)
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $record->duration }} min</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Duration</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($attendanceRecords->hasPages())
                <div class="mt-6">
                    {{ $attendanceRecords->links() }}
                </div>
            @endif
        @else
            <x-ui.empty-state
                title="No attendance records yet"
                description="Your class attendance records will appear here"
                icon="chart" />
        @endif
    </x-ui.glass-card>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const attendanceCtx = document.getElementById('attendanceChart');
    if (attendanceCtx) {
        const attendanceData = @json($monthlyAttendance ?? []);

        new Chart(attendanceCtx, {
            type: 'bar',
            data: {
                labels: attendanceData.map(item => item.date),
                datasets: [{
                    label: 'Present',
                    data: attendanceData.map(item => item.present ? 1 : 0),
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 2,
                    borderRadius: 8
                }, {
                    label: 'Absent',
                    data: attendanceData.map(item => item.present ? 0 : 1),
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 1,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return value === 1 ? 'Yes' : 'No';
                            },
                            color: getComputedStyle(document.documentElement).getPropertyValue('color-scheme') === 'dark' ? '#9ca3af' : '#6b7280'
                        },
                        grid: {
                            color: getComputedStyle(document.documentElement).getPropertyValue('color-scheme') === 'dark' ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: getComputedStyle(document.documentElement).getPropertyValue('color-scheme') === 'dark' ? '#9ca3af' : '#6b7280'
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: getComputedStyle(document.documentElement).getPropertyValue('color-scheme') === 'dark' ? '#d1d5db' : '#374151',
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection
