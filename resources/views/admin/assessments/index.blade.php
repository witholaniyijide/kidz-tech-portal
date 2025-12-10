<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Tutor Assessments') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - Assessments') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Tutor Assessments</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">View director-approved performance assessments</p>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm text-blue-700 dark:text-blue-400">Read-only access to approved assessments</span>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Assessments</div>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-emerald-600">{{ number_format($stats['avg_score'] ?? 0, 1) }}%</div>
                    <div class="text-sm text-emerald-700 dark:text-emerald-400">Average Score</div>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800/30 rounded-2xl p-5 shadow col-span-2 md:col-span-1">
                    <div class="text-3xl font-bold text-blue-600">{{ $tutors->count() }}</div>
                    <div class="text-sm text-blue-700 dark:text-blue-400">Active Tutors</div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow mb-6">
                <form method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="w-52">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tutor</label>
                        <select name="tutor_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="">All Tutors</option>
                            @foreach($tutors as $tutor)
                                <option value="{{ $tutor->id }}" {{ request('tutor_id') == $tutor->id ? 'selected' : '' }}>
                                    {{ $tutor->first_name }} {{ $tutor->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-44">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Month</label>
                        <select name="month" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="">All Months</option>
                            @foreach($months as $month)
                                <option value="{{ $month }}" {{ request('month') === $month ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['tutor_id', 'month']))
                        <a href="{{ route('admin.assessments.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- Assessments Table --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                @if($assessments->isEmpty())
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">📊</div>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Approved Assessments</h3>
                        <p class="text-gray-500 dark:text-gray-400">Director-approved tutor assessments will appear here.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tutor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Period</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Score</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Assessed By</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Approved On</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($assessments as $assessment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-9 h-9 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-xs mr-3">
                                                    {{ strtoupper(substr($assessment->tutor->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($assessment->tutor->last_name ?? '', 0, 1)) }}
                                                </div>
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    {{ $assessment->tutor->first_name ?? 'Unknown' }} {{ $assessment->tutor->last_name ?? '' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $assessment->assessment_month }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $score = $assessment->performance_score ?? 0;
                                                $colorClass = $score >= 80 ? 'bg-emerald-100 text-emerald-700' : ($score >= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700');
                                            @endphp
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $colorClass }}">
                                                {{ number_format($score, 0) }}%
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $assessment->manager->name ?? 'Manager' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $assessment->director_approved_at?->format('M j, Y') ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.assessments.show', $assessment) }}" class="p-2 text-teal-600 hover:bg-teal-50 dark:hover:bg-teal-900/20 rounded-lg transition-colors" title="View">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.assessments.print', $assessment) }}" target="_blank" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" title="Print">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($assessments->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $assessments->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
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
