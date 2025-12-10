<x-parent-layout>
    <x-slot name="title">Performance</x-slot>
    <x-slot name="subtitle">Track progress, skills, and achievements</x-slot>

    <div class="space-y-6">
        <!-- Child Selector -->
        @if($children->count() > 1)
            <div class="glass-card rounded-2xl p-4">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Select Child:</span>
                    <div class="flex items-center space-x-2 flex-wrap gap-2">
                        @foreach($children as $child)
                            <a href="{{ route('parent.performance.index', ['student_id' => $child->id]) }}"
                               class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-all duration-200
                                      {{ $selectedChild->id === $child->id
                                         ? 'bg-parent-gradient text-white shadow-lg'
                                         : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-semibold">
                                    {{ substr($child->first_name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium">{{ $child->first_name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Performance Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="glass-card rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-sky-100 dark:bg-sky-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-heading font-bold text-gray-800 dark:text-white">{{ $performanceData['overall_progress'] }}%</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Overall Progress</p>
            </div>

            <div class="glass-card rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-heading font-bold text-gray-800 dark:text-white">{{ number_format($performanceData['average_rating'], 1) }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Avg Rating (of 5)</p>
            </div>

            <div class="glass-card rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-heading font-bold text-gray-800 dark:text-white">{{ $performanceData['total_points'] }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">XP Points</p>
            </div>

            <div class="glass-card rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-heading font-bold text-gray-800 dark:text-white">{{ $performanceData['total_reports'] }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Reports</p>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Radar Chart -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-bold text-gray-800 dark:text-white mb-4">Performance Radar</h3>
                <div class="aspect-square max-w-xs mx-auto">
                    <canvas id="radarChart"></canvas>
                </div>
                <div class="grid grid-cols-2 gap-2 mt-4 text-sm">
                    @foreach($radarData['labels'] as $index => $label)
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full bg-sky-500"></div>
                            <span class="text-gray-600 dark:text-gray-400">{{ $label }}: {{ $radarData['data'][$index] }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Monthly Progress Trendline -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-bold text-gray-800 dark:text-white mb-4">Monthly Progress</h3>
                <div class="h-64">
                    <canvas id="trendlineChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Milestones & Next Steps -->
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Milestones Achieved -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-bold text-gray-800 dark:text-white mb-4">Milestones Achieved</h3>
                @if(count($milestones) > 0)
                    <div class="space-y-3 max-h-80 overflow-y-auto parent-scrollbar">
                        @foreach($milestones as $milestone)
                            <div class="flex items-start space-x-3 p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                                <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 dark:text-white text-sm">{{ $milestone['title'] }}</p>
                                    @if($milestone['description'])
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $milestone['description'] }}</p>
                                    @endif
                                    <div class="flex items-center space-x-3 mt-2">
                                        @if($milestone['points'])
                                            <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">+{{ $milestone['points'] }} XP</span>
                                        @endif
                                        @if($milestone['completed_at'])
                                            <span class="text-xs text-gray-400">{{ $milestone['completed_at'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">No milestones achieved yet</p>
                    </div>
                @endif
            </div>

            <!-- Next Recommended Milestone -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-bold text-gray-800 dark:text-white mb-4">Next Milestone</h3>
                @if($nextMilestone)
                    <div class="bg-sky-50 dark:bg-sky-900/20 rounded-xl p-6 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-sky-500 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-heading font-bold text-gray-800 dark:text-white mb-2">
                            {{ $nextMilestone['title'] }}
                        </h4>
                        @if(isset($nextMilestone['description']))
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $nextMilestone['description'] }}</p>
                        @endif
                        @if(isset($nextMilestone['points']))
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-sky-100 dark:bg-sky-900/50 text-sky-700 dark:text-sky-300 text-sm font-medium">
                                +{{ $nextMilestone['points'] }} XP
                            </span>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-heading font-bold text-gray-800 dark:text-white mb-2">All Caught Up!</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Great job! Continue the learning journey.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Radar Chart
        const radarCtx = document.getElementById('radarChart').getContext('2d');
        new Chart(radarCtx, {
            type: 'radar',
            data: {
                labels: {!! json_encode($radarData['labels']) !!},
                datasets: [{
                    label: 'Performance',
                    data: {!! json_encode($radarData['data']) !!},
                    backgroundColor: 'rgba(14, 165, 233, 0.2)',
                    borderColor: '#0ea5e9',
                    borderWidth: 2,
                    pointBackgroundColor: '#0ea5e9',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#0ea5e9'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 20
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Trendline Chart
        const trendlineCtx = document.getElementById('trendlineChart').getContext('2d');
        new Chart(trendlineCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($monthlyProgress, 'month')) !!},
                datasets: [{
                    label: 'Progress',
                    data: {!! json_encode(array_column($monthlyProgress, 'progress')) !!},
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#0ea5e9',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
    @endpush
</x-parent-layout>
