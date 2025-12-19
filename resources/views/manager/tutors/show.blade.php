<x-manager-layout title="Tutor Details">
    {{-- Back Link --}}
    <a href="{{ route('manager.tutors.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-[#C15F3C] dark:hover:text-[#DA7756] mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Tutors
    </a>

    {{-- Tutor Header Card --}}
    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6 mb-6">
        <div class="flex flex-wrap items-start gap-6">
            {{-- Avatar --}}
            <div class="w-24 h-24 rounded-2xl bg-gradient-to-r from-[#C15F3C] to-[#DA7756] flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                {{ strtoupper(substr($tutor->first_name, 0, 1)) }}{{ strtoupper(substr($tutor->last_name, 0, 1)) }}
            </div>

            {{-- Info --}}
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                    {{ $tutor->first_name }} {{ $tutor->last_name }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mb-3">Tutor ID: {{ $tutor->tutor_id ?? 'N/A' }}</p>

                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 text-sm rounded-full font-medium
                        @if($tutor->status === 'active') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                        @elseif($tutor->status === 'inactive') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                        @elseif($tutor->status === 'on_leave') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $tutor->status)) }}
                    </span>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="flex gap-2">
                <a href="{{ route('manager.tutors.performance', $tutor) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white font-medium rounded-lg hover:from-[#A34E30] hover:to-[#C15F3C] transition-all shadow-lg shadow-orange-500/25">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    View Performance
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Contact Info --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tutor Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
                        <p class="text-gray-900 dark:text-white">{{ $tutor->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Phone</label>
                        <p class="text-gray-900 dark:text-white">{{ $tutor->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Location</label>
                        <p class="text-gray-900 dark:text-white">{{ $tutor->location ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Join Date</label>
                        <p class="text-gray-900 dark:text-white">{{ $tutor->created_at ? $tutor->created_at->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Assigned Students --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assigned Students ({{ $tutor->students->count() }})</h3>
                @if($tutor->students->count() > 0)
                    <div class="space-y-3">
                        @foreach($tutor->students as $student)
                            <div class="flex items-center justify-between p-3 bg-gray-50/50 dark:bg-gray-700/30 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-[#C15F3C] to-[#DA7756] flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->student_id ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full font-medium
                                    @if($student->status === 'active') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @endif">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No students assigned</p>
                @endif
            </div>

            {{-- Recent Reports --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Reports Submitted</h3>
                @forelse($tutor->monthlyReports()->latest()->take(5)->get() as $report)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $report->student->first_name ?? 'Unknown' }} {{ $report->student->last_name ?? '' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $report->month }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full font-medium
                            @if($report->status === 'approved-by-director') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                            @elseif($report->status === 'approved-by-manager') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                            @else bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                            @endif">
                            {{ ucfirst(str_replace('-', ' ', $report->status)) }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No reports submitted yet</p>
                @endforelse
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Quick Stats --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistics</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-[#C15F3C]/10 dark:bg-[#C15F3C]/20 rounded-lg">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Active Students</span>
                        <span class="text-xl font-bold text-[#C15F3C]">{{ $tutor->students->where('status', 'active')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Classes This Month</span>
                        <span class="text-xl font-bold text-blue-600">{{ $tutor->attendanceRecords()->whereMonth('class_date', now()->month)->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Reports Submitted</span>
                        <span class="text-xl font-bold text-emerald-600">{{ $tutor->monthlyReports->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- Latest Assessment --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Latest Assessment</h3>
                @php
                    $latestAssessment = $tutor->tutorAssessments()->latest()->first();
                @endphp
                @if($latestAssessment)
                    <div class="text-center">
                        <div class="text-4xl font-bold text-[#C15F3C] mb-2">{{ $latestAssessment->performance_score ?? 'N/A' }}%</div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $latestAssessment->assessment_month }}</p>
                        <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full font-medium
                            @if($latestAssessment->status === 'approved-by-director') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                            @else bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                            @endif">
                            {{ ucfirst(str_replace('-', ' ', $latestAssessment->status)) }}
                        </span>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No assessments yet</p>
                @endif
            </div>
        </div>
    </div>
</x-manager-layout>
