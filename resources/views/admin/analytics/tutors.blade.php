<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Tutors Analytics') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - Tutors Analytics') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Tutors Analytics</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Workload and performance insights</p>
                </div>
                <a href="{{ route('admin.analytics.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Analytics
                </a>
            </div>

            {{-- Overview Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Tutors</div>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-emerald-600">{{ $stats['active'] ?? 0 }}</div>
                    <div class="text-sm text-emerald-700 dark:text-emerald-400">Active</div>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-amber-600">{{ $stats['on_leave'] ?? 0 }}</div>
                    <div class="text-sm text-amber-700 dark:text-amber-400">On Leave</div>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($stats['avg_students'] ?? 0, 1) }}</div>
                    <div class="text-sm text-blue-700 dark:text-blue-400">Avg Students/Tutor</div>
                </div>
            </div>

            {{-- Tutor Workload Distribution --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tutor Workload Distribution</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($tutorWorkloads as $tutor)
                            @php
                                $maxStudents = $tutorWorkloads->max('students_count') ?: 1;
                                $percentage = ($tutor->students_count / $maxStudents) * 100;
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-xs">
                                            {{ strtoupper(substr($tutor->first_name, 0, 1)) }}
                                        </div>
                                        <span class="text-gray-700 dark:text-gray-300">{{ $tutor->first_name }} {{ $tutor->last_name }}</span>
                                    </div>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $tutor->students_count }} students</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-3 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-4">No tutor data available</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                {{-- Classes This Month --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Classes Completed This Month</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($tutorClassesThisMonth as $tutor)
                            <div class="px-6 py-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($tutor->first_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $tutor->first_name }} {{ $tutor->last_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $tutor->students_count ?? 0 }} students assigned</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-2xl font-bold text-blue-600">{{ $tutor->attendances_count }}</span>
                                    <p class="text-xs text-gray-500">classes</p>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-gray-500">No data available</div>
                        @endforelse
                    </div>
                </div>

                {{-- Tutors by Status --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tutors by Status</h3>
                    </div>
                    <div class="p-6">
                        @php
                            $total = max(($stats['total'] ?? 1), 1);
                            $statuses = [
                                ['label' => 'Active', 'count' => $stats['active'] ?? 0, 'color' => 'bg-emerald-500', 'icon' => '✅'],
                                ['label' => 'Inactive', 'count' => $stats['inactive'] ?? 0, 'color' => 'bg-gray-400', 'icon' => '⏸️'],
                                ['label' => 'On Leave', 'count' => $stats['on_leave'] ?? 0, 'color' => 'bg-amber-500', 'icon' => '🏖️'],
                                ['label' => 'Resigned', 'count' => $stats['resigned'] ?? 0, 'color' => 'bg-red-500', 'icon' => '👋'],
                            ];
                        @endphp
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($statuses as $status)
                                <div class="text-center p-4 rounded-xl {{ $status['count'] > 0 ? 'bg-gray-50 dark:bg-gray-700/50' : 'bg-gray-50/50 dark:bg-gray-700/20' }}">
                                    <div class="text-2xl mb-1">{{ $status['icon'] }}</div>
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $status['count'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $status['label'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Full Tutor Performance Table --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Tutors Performance Overview</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Tutor</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Students</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Classes (Month)</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Classes (Total)</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Late Submissions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($allTutors as $tutor)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-9 h-9 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-xs mr-3">
                                                {{ strtoupper(substr($tutor->first_name, 0, 1)) }}{{ strtoupper(substr($tutor->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $tutor->first_name }} {{ $tutor->last_name }}</p>
                                                <p class="text-xs text-gray-500">{{ $tutor->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($tutor->status === 'active') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                                            @elseif($tutor->status === 'on_leave') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                                            @elseif($tutor->status === 'resigned') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                            @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $tutor->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-semibold text-gray-900 dark:text-white">
                                        {{ $tutor->students_count ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-semibold text-blue-600">
                                        {{ $tutor->classes_this_month ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                                        {{ $tutor->total_classes ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if(($tutor->late_submissions ?? 0) > 0)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                                {{ $tutor->late_submissions }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">0</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">No tutors found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
