<x-manager-layout title="Pending Attendance">
    {{-- Back Link --}}
    <a href="{{ route('manager.attendance.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-[#C15F3C] dark:hover:text-[#DA7756] mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Attendance
    </a>

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pending Attendance</h1>
            <p class="text-gray-600 dark:text-gray-400">Review attendance records awaiting admin approval</p>
        </div>
        <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30 px-4 py-2 rounded-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-semibold">{{ $pendingRecords->total() }} Pending</span>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/30 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="text-sm text-blue-800 dark:text-blue-300 font-medium">Manager View Only</p>
                <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                    These attendance records require Admin approval. As a Manager, you can view and monitor pending submissions but cannot approve or reject them.
                </p>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-6 mb-6 shadow-sm">
        <form action="{{ route('manager.attendance.pending') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Date --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                <input type="date" name="date" value="{{ request('date') }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#C15F3C] focus:ring-[#C15F3C]">
            </div>

            {{-- Tutor Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tutor</label>
                <select name="tutor_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#C15F3C] focus:ring-[#C15F3C]">
                    <option value="">All Tutors</option>
                    @foreach($tutors ?? [] as $tutor)
                        <option value="{{ $tutor->id }}" {{ request('tutor_id') == $tutor->id ? 'selected' : '' }}>
                            {{ $tutor->first_name }} {{ $tutor->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Student Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student</label>
                <select name="student_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#C15F3C] focus:ring-[#C15F3C]">
                    <option value="">All Students</option>
                    @foreach($students ?? [] as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->first_name }} {{ $student->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Submit Button --}}
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white font-semibold rounded-lg hover:from-[#A34E30] hover:to-[#C15F3C] transition-all shadow-lg shadow-orange-500/25">
                    Filter
                </button>
                <a href="{{ route('manager.attendance.pending') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Pending Attendance List --}}
    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-amber-50/50 to-orange-50/50 dark:from-amber-900/10 dark:to-orange-900/10">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-semibold text-gray-900 dark:text-white">Awaiting Admin Approval</span>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($pendingRecords as $record)
                    <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl p-5 border border-amber-200/50 dark:border-amber-700/30 hover:shadow-lg transition-all">
                        {{-- Header --}}
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-amber-500 to-orange-500 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($record->student->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($record->student->last_name ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $record->student->first_name ?? 'N/A' }} {{ $record->student->last_name ?? '' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $record->student->student_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                Pending
                            </span>
                        </div>

                        {{-- Details --}}
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $record->class_date ? \Carbon\Carbon::parse($record->class_date)->format('M d, Y') : 'N/A' }}
                            </div>
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $record->class_time ?? 'N/A' }} ({{ $record->duration ?? 60 }} min)
                            </div>
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $record->tutor->first_name ?? 'N/A' }} {{ $record->tutor->last_name ?? '' }}
                            </div>
                            @if($record->topic)
                                <div class="flex items-start text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="line-clamp-2">{{ $record->topic }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Late Badge --}}
                        @if($record->is_late_submission)
                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                <span class="inline-flex items-center text-xs text-red-600 dark:text-red-400">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Late Submission
                                </span>
                            </div>
                        @endif

                        {{-- Submitted Time --}}
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Submitted {{ $record->created_at ? $record->created_at->diffForHumans() : 'N/A' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-emerald-300 dark:text-emerald-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">All caught up!</p>
                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">No pending attendance records to review</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pagination --}}
        @if($pendingRecords->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $pendingRecords->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-manager-layout>
