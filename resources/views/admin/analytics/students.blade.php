<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Students Analytics') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - Students Analytics') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Students Analytics</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Performance and attendance insights</p>
                </div>
                <a href="{{ route('admin.analytics.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Analytics
                </a>
            </div>

            {{-- Overview Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Students</div>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-emerald-600">{{ $stats['active'] ?? 0 }}</div>
                    <div class="text-sm text-emerald-700 dark:text-emerald-400">Active</div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-gray-600">{{ $stats['inactive'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Inactive</div>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['graduated'] ?? 0 }}</div>
                    <div class="text-sm text-blue-700 dark:text-blue-400">Graduated</div>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-amber-600">{{ $stats['withdrawn'] ?? 0 }}</div>
                    <div class="text-sm text-amber-700 dark:text-amber-400">Withdrawn</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                {{-- Students by Status Chart --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Students by Status</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @php
                                $total = max(($stats['total'] ?? 1), 1);
                                $statuses = [
                                    ['label' => 'Active', 'count' => $stats['active'] ?? 0, 'color' => 'bg-emerald-500'],
                                    ['label' => 'Inactive', 'count' => $stats['inactive'] ?? 0, 'color' => 'bg-gray-400'],
                                    ['label' => 'Graduated', 'count' => $stats['graduated'] ?? 0, 'color' => 'bg-blue-500'],
                                    ['label' => 'Withdrawn', 'count' => $stats['withdrawn'] ?? 0, 'color' => 'bg-amber-500'],
                                ];
                            @endphp
                            @foreach($statuses as $status)
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600 dark:text-gray-400">{{ $status['label'] }}</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $status['count'] }} ({{ number_format(($status['count'] / $total) * 100, 1) }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                        <div class="{{ $status['color'] }} h-3 rounded-full transition-all" style="width: {{ ($status['count'] / $total) * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Classes Distribution --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Classes Per Week Distribution</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-7 gap-2">
                            @for($i = 1; $i <= 7; $i++)
                                @php
                                    $count = $classesPerWeek[$i] ?? 0;
                                    $maxCount = max(array_values($classesPerWeek ?? [1]));
                                    $height = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                                @endphp
                                <div class="text-center">
                                    <div class="h-32 flex items-end justify-center mb-2">
                                        <div class="w-full max-w-[40px] bg-teal-500 rounded-t transition-all" style="height: {{ max($height, 5) }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $i }}x</div>
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $count }}</div>
                                </div>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-500 text-center mt-4">Number of students by classes per week</p>
                    </div>
                </div>
            </div>

            {{-- Top Performing Students --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Most Active Students (by attendance)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">#</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Tutor</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Classes Attended</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Progress</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($topStudents as $index => $student)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold text-xs mr-3">
                                                {{ strtoupper(substr($student->first_name, 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $student->tutor->first_name ?? 'Unassigned' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-semibold text-teal-600">{{ $student->attendances_count }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $progress = $student->total_periods > 0 ? ($student->completed_periods / $student->total_periods) * 100 : 0;
                                        @endphp
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                <div class="bg-teal-500 h-2 rounded-full" style="width: {{ min($progress, 100) }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ number_format($progress, 0) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Students Without Tutor --}}
            @if(isset($studentsWithoutTutor) && $studentsWithoutTutor->count() > 0)
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/30 rounded-2xl p-6">
                    <h4 class="font-semibold text-amber-800 dark:text-amber-400 mb-3 flex items-center">
                        <span class="mr-2">⚠️</span> Students Without Assigned Tutor ({{ $studentsWithoutTutor->count() }})
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($studentsWithoutTutor as $student)
                            <a href="{{ route('admin.students.edit', $student) }}" class="px-3 py-1 bg-white dark:bg-gray-800 text-amber-700 dark:text-amber-400 rounded-lg text-sm hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-colors">
                                {{ $student->first_name }} {{ $student->last_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
