<x-manager-layout title="Student Details">
    {{-- Back Link --}}
    <a href="{{ route('manager.students.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-[#C15F3C] dark:hover:text-[#DA7756] mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Students
    </a>

    {{-- Student Header Card --}}
    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6 mb-6">
        <div class="flex flex-wrap items-start gap-6">
            {{-- Avatar --}}
            <div class="w-24 h-24 rounded-2xl bg-gradient-to-r from-[#C15F3C] to-[#DA7756] flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
            </div>

            {{-- Info --}}
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                    {{ $student->first_name }} {{ $student->last_name }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mb-3">Student ID: {{ $student->student_id ?? 'N/A' }}</p>

                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 text-sm rounded-full font-medium
                        @if($student->status === 'active') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                        @elseif($student->status === 'inactive') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                        @elseif($student->status === 'graduated') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                        @endif">
                        {{ ucfirst($student->status) }}
                    </span>
                    @if($student->age)
                        <span class="px-3 py-1 text-sm rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            Age {{ $student->age }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="flex gap-2">
                <a href="{{ route('manager.students.progress', $student) }}" class="inline-flex items-center px-4 py-2 bg-[#C15F3C]/10 dark:bg-[#C15F3C]/20 text-[#C15F3C] dark:text-[#DA7756] font-medium rounded-lg hover:bg-[#C15F3C]/20 dark:hover:bg-[#C15F3C]/30 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Progress
                </a>
                <a href="{{ route('manager.students.attendance', $student) }}" class="inline-flex items-center px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Attendance
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Contact & Personal Info --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Student Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
                        <p class="text-gray-900 dark:text-white">{{ $student->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Phone</label>
                        <p class="text-gray-900 dark:text-white">{{ $student->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Date of Birth</label>
                        <p class="text-gray-900 dark:text-white">{{ $student->date_of_birth ? $student->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Location</label>
                        <p class="text-gray-900 dark:text-white">{{ $student->location ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Enrollment Date</label>
                        <p class="text-gray-900 dark:text-white">{{ $student->enrollment_date ? $student->enrollment_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Current Period</label>
                        <p class="text-gray-900 dark:text-white">{{ $student->current_period ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Tutor Assignment --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assigned Tutor</h3>
                @if($student->tutor)
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-[#C15F3C] to-[#DA7756] flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($student->tutor->first_name, 0, 1)) }}{{ strtoupper(substr($student->tutor->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $student->tutor->first_name }} {{ $student->tutor->last_name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->tutor->email }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No tutor assigned</p>
                @endif
            </div>

            {{-- Recent Reports --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Reports</h3>
                    <a href="{{ route('manager.students.reports', $student) }}" class="text-sm text-[#C15F3C] hover:text-[#A34E30] font-medium">View All</a>
                </div>
                @forelse($student->monthlyReports()->latest()->take(3)->get() as $report)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $report->month }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">By {{ $report->tutor->first_name ?? 'Unknown' }}</p>
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
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No reports yet</p>
                @endforelse
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Progress Overview --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Progress Overview</h3>
                @php
                    $progress = $student->total_periods > 0 ? round(($student->completed_periods / $student->total_periods) * 100) : 0;
                @endphp
                <div class="text-center mb-4">
                    <div class="relative w-32 h-32 mx-auto">
                        <svg class="w-32 h-32 transform -rotate-90">
                            <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="12" fill="none" class="text-gray-200 dark:text-gray-700"></circle>
                            <circle cx="64" cy="64" r="56" stroke="url(#progressGradient)" stroke-width="12" fill="none" stroke-linecap="round" stroke-dasharray="{{ 351.86 }}" stroke-dashoffset="{{ 351.86 - (351.86 * $progress / 100) }}"></circle>
                            <defs>
                                <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#C15F3C" />
                                    <stop offset="100%" stop-color="#DA7756" />
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $progress }}%</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-bold text-[#C15F3C]">{{ $student->completed_periods ?? 0 }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Completed</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $student->total_periods ?? 0 }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Periods</p>
                    </div>
                </div>
            </div>

            {{-- Attendance Stats --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Attendance Rate</h3>
                @php
                    $attendanceRate = $student->attendanceRecords()->count() > 0
                        ? round(($student->attendanceRecords()->where('status', 'present')->count() / $student->attendanceRecords()->count()) * 100)
                        : 0;
                @endphp
                <div class="flex items-center gap-4">
                    <div class="text-4xl font-bold text-[#C15F3C]">{{ $attendanceRate }}%</div>
                    <div class="flex-1">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div class="bg-gradient-to-r from-[#C15F3C] to-[#DA7756] h-3 rounded-full" style="width: {{ $attendanceRate }}%"></div>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    {{ $student->attendanceRecords()->where('status', 'present')->count() }} of {{ $student->attendanceRecords()->count() }} classes attended
                </p>
            </div>

            {{-- Parent Info --}}
            @if($student->parent)
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Parent/Guardian</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Name</label>
                        <p class="text-gray-900 dark:text-white">{{ $student->parent->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                        <p class="text-gray-900 dark:text-white">{{ $student->parent->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Phone</label>
                        <p class="text-gray-900 dark:text-white">{{ $student->parent->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-manager-layout>
