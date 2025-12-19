<x-manager-layout title="Tutor Performance">
    {{-- Back Link --}}
    <a href="{{ route('manager.tutors.show', $tutor) }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-[#C15F3C] dark:hover:text-[#DA7756] mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Tutor Details
    </a>

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-r from-[#C15F3C] to-[#DA7756] flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                {{ strtoupper(substr($tutor->first_name, 0, 1)) }}{{ strtoupper(substr($tutor->last_name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tutor->first_name }} {{ $tutor->last_name }}</h1>
                <p class="text-gray-500 dark:text-gray-400">Performance Overview</p>
            </div>
        </div>
        <div class="flex gap-2">
            <select id="period-filter" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C15F3C] focus:ring-[#C15F3C]">
                <option value="all">All Time</option>
                <option value="3">Last 3 Months</option>
                <option value="6">Last 6 Months</option>
                <option value="12">Last 12 Months</option>
            </select>
        </div>
    </div>

    {{-- Performance Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @php
            $avgScore = $tutor->tutorAssessments()->avg('performance_score') ?? 0;
            $totalClasses = $tutor->attendanceRecords()->count();
            $approvedReports = $tutor->monthlyReports()->where('status', 'approved-by-director')->count();
            $assessmentCount = $tutor->tutorAssessments()->count();
        @endphp
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-5 shadow-sm">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Average Score</p>
                <p class="text-4xl font-bold text-[#C15F3C]">{{ round($avgScore) }}%</p>
            </div>
        </div>
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-5 shadow-sm">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Classes</p>
                <p class="text-4xl font-bold text-blue-600">{{ $totalClasses }}</p>
            </div>
        </div>
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-5 shadow-sm">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Approved Reports</p>
                <p class="text-4xl font-bold text-emerald-600">{{ $approvedReports }}</p>
            </div>
        </div>
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-5 shadow-sm">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Assessments</p>
                <p class="text-4xl font-bold text-amber-600">{{ $assessmentCount }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Assessment History --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assessment History</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50/80 dark:bg-gray-800/80">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Period</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Score</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($tutor->tutorAssessments()->latest()->take(10)->get() as $assessment)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $assessment->assessment_month }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#C15F3C] to-[#DA7756] h-2 rounded-full" style="width: {{ $assessment->performance_score ?? 0 }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $assessment->performance_score ?? 0 }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full font-medium
                                            @if($assessment->status === 'approved-by-director') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                                            @elseif($assessment->status === 'approved-by-manager') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                            @else bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                                            @endif">
                                            {{ ucfirst(str_replace('-', ' ', $assessment->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $assessment->created_at ? $assessment->created_at->format('M j, Y') : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        No assessments found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Monthly Report Summary --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Monthly Reports Summary</h3>
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="text-center p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl">
                        <p class="text-2xl font-bold text-emerald-600">{{ $tutor->monthlyReports()->where('status', 'approved-by-director')->count() }}</p>
                        <p class="text-sm text-emerald-600">Approved</p>
                    </div>
                    <div class="text-center p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                        <p class="text-2xl font-bold text-amber-600">{{ $tutor->monthlyReports()->where('status', 'submitted')->count() }}</p>
                        <p class="text-sm text-amber-600">Pending</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <p class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $tutor->monthlyReports()->where('status', 'draft')->count() }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Draft</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Performance Rating --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Performance Rating</h3>
                <div class="text-center">
                    <div class="relative w-32 h-32 mx-auto mb-4">
                        <svg class="w-32 h-32 transform -rotate-90">
                            <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="12" fill="none" class="text-gray-200 dark:text-gray-700"></circle>
                            <circle cx="64" cy="64" r="56" stroke="url(#perfGradient)" stroke-width="12" fill="none" stroke-linecap="round" stroke-dasharray="351.86" stroke-dashoffset="{{ 351.86 - (351.86 * $avgScore / 100) }}"></circle>
                            <defs>
                                <linearGradient id="perfGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#C15F3C" />
                                    <stop offset="100%" stop-color="#DA7756" />
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ round($avgScore) }}%</span>
                        </div>
                    </div>
                    @if($avgScore >= 90)
                        <span class="px-4 py-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-full font-medium">Excellent</span>
                    @elseif($avgScore >= 75)
                        <span class="px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full font-medium">Good</span>
                    @elseif($avgScore >= 60)
                        <span class="px-4 py-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-full font-medium">Average</span>
                    @else
                        <span class="px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-full font-medium">Needs Improvement</span>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('manager.assessments.index') }}?tutor={{ $tutor->id }}" class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white font-medium rounded-xl hover:shadow-lg transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        New Assessment
                    </a>
                    <a href="{{ route('manager.tutor-reports.index') }}?tutor_id={{ $tutor->id }}" class="flex items-center justify-center w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-manager-layout>
