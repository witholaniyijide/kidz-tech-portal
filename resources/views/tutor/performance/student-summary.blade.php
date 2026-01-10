<x-tutor-layout title="Student Performance Summary">
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Student Performance Summary</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">Track your students' progress over time</p>
        </div>
        <a href="{{ route('tutor.performance.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to All Reports
        </a>
    </div>

    @if(empty($studentPerformance))
        <div class="glass-card rounded-2xl p-12 text-center">
            <div class="text-6xl mb-4">📊</div>
            <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-300 mb-2">No Performance Data Yet</h3>
            <p class="text-slate-500 dark:text-slate-400">Student performance summaries will appear here after assessments are approved by the Director.</p>
        </div>
    @else
        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white">
                <div class="text-sm opacity-90">Total Students Assessed</div>
                <div class="text-3xl font-bold">{{ count($studentPerformance) }}</div>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white">
                <div class="text-sm opacity-90">Highest Average Score</div>
                <div class="text-3xl font-bold">{{ number_format(max(array_column($studentPerformance, 'average_score')), 1) }}%</div>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white">
                <div class="text-sm opacity-90">Total Assessments</div>
                <div class="text-3xl font-bold">{{ array_sum(array_column($studentPerformance, 'total_assessments')) }}</div>
            </div>
        </div>

        <!-- Student Performance Cards -->
        <div class="space-y-6">
            @foreach($studentPerformance as $data)
                <div class="glass-card rounded-2xl p-6">
                    <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[#4B49AC] to-[#7978E9] flex items-center justify-center text-white text-xl font-bold">
                                {{ strtoupper(substr($data['student']->first_name, 0, 1)) }}{{ strtoupper(substr($data['student']->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900 dark:text-white">
                                    {{ $data['student']->first_name }} {{ $data['student']->last_name }}
                                </h3>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">
                                    {{ $data['total_assessments'] }} assessment{{ $data['total_assessments'] !== 1 ? 's' : '' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <!-- Average Score -->
                            <div class="text-center">
                                <div class="text-sm text-slate-500 dark:text-slate-400">Average</div>
                                <div class="text-2xl font-bold {{ $data['average_score'] >= 70 ? 'text-green-600 dark:text-green-400' : ($data['average_score'] >= 50 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                    {{ $data['average_score'] }}%
                                </div>
                            </div>
                            <!-- Latest Score -->
                            <div class="text-center">
                                <div class="text-sm text-slate-500 dark:text-slate-400">Latest</div>
                                <div class="text-2xl font-bold {{ $data['latest_score'] >= 70 ? 'text-green-600 dark:text-green-400' : ($data['latest_score'] >= 50 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                    {{ $data['latest_score'] }}%
                                </div>
                            </div>
                            <!-- Trend -->
                            <div class="text-center">
                                <div class="text-sm text-slate-500 dark:text-slate-400">Trend</div>
                                <div class="text-2xl font-bold flex items-center justify-center gap-1">
                                    @if($data['trend'] > 0)
                                        <span class="text-green-600 dark:text-green-400">+{{ number_format($data['trend'], 1) }}%</span>
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                        </svg>
                                    @elseif($data['trend'] < 0)
                                        <span class="text-red-600 dark:text-red-400">{{ number_format($data['trend'], 1) }}%</span>
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                        </svg>
                                    @else
                                        <span class="text-slate-600 dark:text-slate-400">0%</span>
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Chart -->
                    @if(count($data['chart_labels']) > 1)
                    <div class="h-48 mt-4">
                        <canvas id="chart-{{ $data['student']->id }}"></canvas>
                    </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center">
                        <div class="text-sm text-slate-500 dark:text-slate-400">
                            Score Range: {{ number_format(min($data['scores']->pluck('score')->toArray()), 1) }}% - {{ number_format(max($data['scores']->pluck('score')->toArray()), 1) }}%
                        </div>
                        <a href="{{ route('tutor.performance.index', ['student_id' => $data['student']->id]) }}"
                           class="px-4 py-2 bg-gradient-to-r from-[#4B49AC] to-[#7978E9] text-white text-sm font-medium rounded-lg hover:shadow-lg transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View All Reports
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($studentPerformance as $data)
            @if(count($data['chart_labels']) > 1)
            new Chart(document.getElementById('chart-{{ $data['student']->id }}'), {
                type: 'line',
                data: {
                    labels: @json($data['chart_labels']),
                    datasets: [{
                        label: 'Performance Score',
                        data: @json($data['chart_data']),
                        borderColor: 'rgb(79, 70, 229)',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(79, 70, 229)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.raw.toFixed(1) + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
            @endif
        @endforeach
    });
</script>
@endpush
</x-tutor-layout>
